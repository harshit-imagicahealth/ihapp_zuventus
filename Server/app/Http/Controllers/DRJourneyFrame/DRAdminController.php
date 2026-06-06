<?php

namespace App\Http\Controllers\DRJourneyFrame;

use App\Models\DRJourneyFrame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class DRAdminController extends Controller
{
    public function requestlist()
    {
        return view('Admin.dr_requests.list');
    }

    public function requestdata(Request $request)
    {
        $page = max(1, (int) $request->page);
        $perPage = 10;
        $search = $request->search ?? '';

        $query = DRJourneyFrame::query();

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('specialty', 'like', "%{$search}%")
                ->orWhere('mobile_number', 'like', "%{$search}%")
                ->orWhere('employee_code', 'like', "%{$search}%");
        }

        $total = $query->count();

        $data = $query->orderByDesc('id')
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get()
            ->map(function ($row) {
                return [
                    'id' => encrypt($row->id),
                    'raw_id' => $row->id,
                    'name' => $row->name,
                    'uin_id' => $row->uin_id,
                    'specialty' => $row->specialty,
                    'mobile_number' => $row->mobile_number,
                    'me_code' => $row->employee_code,
                    'created_at' => Carbon::parse($row->created_at)->format('d-m-Y h:i A'),
                    'delete_url' => route('admin.dr.journey.request.delete', encrypt($row->id))
                ];
            });

        return response()->json([
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => ceil($total / $perPage),
        ]);
    }

    // temparary removed not in used
    // public function requestedit($id)
    // {
    //     $data = DRJourneyFrame::findOrFail(decrypt($id));
    //     return view('Admin.dr_requests.edit', compact('data'));
    // }

    // public function editupdate(Request $request, $id)
    // {
    //     $request->validate([
    //         'status' => 'required|in:1,2',
    //     ]);

    //     $user = DRJourneyFrame::findOrFail(decrypt($id));

    //     if ($request->status == 1) {

    //         $posterName = 'arnicor/posters/' . uniqid() . '_' . time() . '.' . $request->poster->extension();

    //         Storage::disk('spaces')->put(
    //             $posterName,
    //             file_get_contents($request->poster),
    //             'public'
    //         );

    //         $videoName = 'arnicor/videos/' . uniqid() . '_' . time() . '.' . $request->video->extension();

    //         Storage::disk('spaces')->put(
    //             $videoName,
    //             file_get_contents($request->video),
    //             'public'
    //         );

    //         $user->poster = $posterName;
    //         $user->video = $videoName;
    //     }

    //     if ($request->status == 2) {
    //         $user->poster = null;
    //         $user->video = null;
    //     }

    //     $user->status = $request->status;
    //     $user->save();

    //     return redirect()
    //         ->route('admin.dr.journey.request.list')
    //         ->with('success', 'Request updated successfully');
    // }

    public function requestdelete(string $id)
    {
        $id = decrypt($id);

        $record = DRJourneyFrame::where('id', $id)->first();

        if (!$record) {
            return response()->json(['status' => 'error']);
        }

        if (!empty($record->photo)) {
            Storage::disk('spaces')->delete($record->photo);
        }

        $record->delete();

        return response()->json(['status' => 'success']);
    }


    public function requestcsv()
    {
        $fileName = 'Journey_Frame_Report_' . date('Y-m-d_H-i-s') . '.csv';

        $requests = DB::table('dr_journey_frame as requests')
            ->leftJoin('tbl_users as users', 'requests.employee_code', '=', 'users.employee_code')
            ->select(
                'requests.*',
                'users.name as user_name',
                'users.hq as user_hq',
                'users.region',
                'users.zone',
            )
            ->orderBy('requests.id', 'desc')
            ->get();

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function () use ($requests) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID',
                'Specialty',
                'Doctor Name',
                'Dr. Specialty',
                'collage_name',
                'pg_name',
                'Years Of Prectice',
                'Area Of Expertise',
                'Date Of Birth',
                'Dr. Mobile No',
                'Photo',
                'Photo Review Status',
                'QC Name',
                'Doctor Visiting Card Front Photo',
                'Doctor Visiting Card Back Photo',
                'Employee Code',
                'Employee Name',
                'Employee HQ',
                'Region',
                'Zone',
                'Created At',
                'Updated At',
            ]);

            foreach ($requests as $row) {

                $pstatus = "";

                if ($row->qc_status == 0) {
                    $pstatus = "Pending";
                } else if ($row->qc_status == 1) {
                    $pstatus = "Approved";
                } else if ($row->qc_status == 2) {
                    $pstatus = "Low Resolution";
                } else if ($row->qc_status == 3) {
                    $pstatus = "Not A Front Facing Photo";
                } else if ($row->qc_status == 4) {
                    $pstatus = "Face Missing";
                } else if ($row->qc_status == 5) {
                    $pstatus = "Multiple People in Photo";
                } else if ($row->qc_status == 6) {
                    $pstatus = "Reject - goggles";
                } else if ($row->qc_status == 7) {
                    $pstatus = "Not Compatible with AI — Upload Different Photo";
                }

                $expertise = $row->area_of_expertise;

                if (is_string($expertise)) {
                    $expertise = json_decode($expertise, true);
                }
                $expertise = is_array($expertise) ? implode(', ', $expertise) : '';

                fputcsv($file, [
                    $row->id,
                    $row->specialty,
                    $row->name,
                    $row->dr_specialty,
                    $row->collage_name,
                    $row->pg_name,
                    $row->year_of_practice,
                    $expertise,
                    $row->dob ? Carbon::parse($row->dob)->format('d-m-Y') : '',
                    $row->mobile_number,
                    $row->photo,
                    $pstatus,
                    $row->qcname,
                    $row->card_f_photo,
                    $row->card_b_photo,
                    $row->employee_code,
                    $row->user_name,
                    $row->user_hq,
                    $row->region,
                    $row->zone,
                    $row->created_at,
                    $row->updated_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
