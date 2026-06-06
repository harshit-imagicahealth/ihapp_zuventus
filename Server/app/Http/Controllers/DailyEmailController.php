<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DailyEmailController extends Controller
{
    public function dailymail()
    {
        $currentHour   = date('H');
        $currentMinute = date('i');

        // Check for scheduled time
        if (
            ($currentHour == '09' && $currentMinute == '00') ||
            ($currentHour == '15' && $currentMinute == '00') ||
            ($currentHour == '18' && $currentMinute == '00')
        ) {

            $users = DB::table('DailyMail')->where('status', 0)->get();

            if ($users->isEmpty()) {
                return response()->json(['message' => 'No users found']);
            }

            // Generate files once
            $rsmCsvPath = $this->generateRsmWiseCsv();
            $meCsvPath = $this->generateMeWiseCsv();
            $doctorTeamCsvPath = $this->generateDoctorTeamCsv();



            $today = date('Y_m_d');

            foreach ($users as $user) {
                $unsubscribeId  = encrypt($user->id);
                $unsubscribeUrl = route('unsubscribe', ['id' => $unsubscribeId]);

                $button = "<a href='{$unsubscribeUrl}' target='_blank' style='display:inline-block;padding:10px 20px;background:#dd3838;color:#fff;text-decoration:none;border-radius:5px;font-weight:bold'>Unsubscribe</a>";

                $payload = [
                    "mail_template_key" => "2518b.40f38d0712830fa4.k1.77b808d0-37f9-11f1-9334-ae9c7e0b6a9f.19d8bdce1dd",
                    "from" => [
                        "address" => "vanita@vinciohealth.in",
                        "name"    => "DRL : Redotil - Ai Video Portal"
                    ],
                    "to" => [
                        ["email_address" => ["address" => $user->email]]
                    ],
                    "merge_info" => [
                        "subject"      => "DRL : Redotil - Ai Video Enrollement Portal : " . date('d M Y'),
                        "greetings"    => "Greetings!",
                        "portal_name"  => "DRL – Redotil - Ai Video Enrollement Portal",
                        "link"         => $button
                    ],
                    "attachments" => [
                        [
                            "name"      => "RSM_Wise_Report_{$today}.csv",
                            "content"   => base64_encode(file_get_contents($rsmCsvPath)),
                            "mime_type" => "text/csv"
                        ],
                        [
                            "name"      => "Employee_Wise_Report_{$today}.csv",
                            "content"   => base64_encode(file_get_contents($meCsvPath)),
                            "mime_type" => "text/csv"
                        ],
                        [
                            "name"      => "Raw_Report_{$today}.csv",
                            "content"   => base64_encode(file_get_contents($doctorTeamCsvPath)),
                            "mime_type" => "text/csv"
                        ],
                    ]
                ];

                Http::withHeaders([
                    'accept'        => 'application/json',
                    'authorization' => 'Zoho-enczapikey PHtE6r0MQ+Dj3WR6oxFT56K4RZXyPdksrLlkLwFBs4lFD6NVSk0A/owqwTOwrB0sVqRDFKWSmtpgtbucsr3XcWjrNm9MVGqyqK3sx/VYSPOZsbq6x00asVkTfkLaV4LoddFi1CzTv9uX',
                    'content-type'  => 'application/json',
                ])->post('https://api.zeptomail.in/v1.1/email/template', $payload);
            }

            // Clean up
            @unlink($rsmCsvPath);
            @unlink($meCsvPath);
            @unlink($doctorTeamCsvPath);

            return response()->json(['message' => 'Daily mails sent successfully']);
        }

        return response()->json(['message' => 'Not the scheduled time.']);
    }

    private function generateRsmWiseCsv(): string
    {
        $filePath = $this->tmpPath("RSM_Wise_Report_" . date('Y_m_d') . ".csv");

        $handle = fopen($filePath, 'w');
        fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($handle, [
            'RSM Name',
            'RSM HQ',
            'Allocation',
            'Total Enrollment',
            'Balance',
            '% Execution',
        ]);

        $rows = DB::select("
        SELECT 
            rsm.name AS rsm_name,
            rsm.hq AS rsm_hq,
            COALESCE(SUM(tm.target_enrollment), 0) AS allocation,
            COALESCE(SUM(dr_counts.total_requests), 0) AS total_enrollment,
            (
                COALESCE(SUM(tm.target_enrollment), 0)
                - COALESCE(SUM(dr_counts.total_requests), 0)
            ) AS balance,
            CONCAT(
                ROUND(
                    (
                        COALESCE(SUM(dr_counts.total_requests), 0) * 100
                    ) / NULLIF(COALESCE(SUM(tm.target_enrollment), 0), 0),
                    0
                ),
                '%'
            ) AS execution
        FROM tbl_users rsm
        LEFT JOIN tbl_users asm
            ON asm.parent_employee_code = rsm.employee_code
            AND asm.employee_pos = 1
        LEFT JOIN tbl_users tm
            ON tm.parent_employee_code = asm.employee_code
            AND tm.employee_pos = 0
        LEFT JOIN (
            SELECT employee_code, COUNT(*) AS total_requests
            FROM dr_requests
            GROUP BY employee_code
        ) dr_counts
            ON dr_counts.employee_code = tm.employee_code
        WHERE rsm.employee_pos = 2
        GROUP BY rsm.employee_code, rsm.name, rsm.hq
        ORDER BY
        (
            COALESCE(SUM(dr_counts.total_requests), 0) * 100
        ) / NULLIF(COALESCE(SUM(tm.target_enrollment), 0), 0)
        DESC
        ");

        $grandAllocation = 0;
        $grandEnrollment = 0;
        $grandBalance = 0;

        foreach ($rows as $row) {

            $grandAllocation += $row->allocation;
            $grandEnrollment += $row->total_enrollment;
            $grandBalance += $row->balance;

            fputcsv($handle, [
                $row->rsm_name,
                $row->rsm_hq,
                $row->allocation,
                $row->total_enrollment,
                $row->balance,
                $row->execution,
            ]);
        }

        $grandExecution = $grandAllocation > 0
            ? round(($grandEnrollment * 100) / $grandAllocation) . '%'
            : '0%';

        fputcsv($handle, [
            'Grand Total',
            '',
            $grandAllocation,
            $grandEnrollment,
            $grandBalance,
            $grandExecution,
        ]);

        fclose($handle);

        return $filePath;
    }

    private function generateMeWiseCsv(): string
    {
        $filePath = $this->tmpPath("ME_Wise_Report_" . date('Y_m_d') . ".csv");

        $handle = fopen($filePath, 'w');

        // UTF-8 BOM
        fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($handle, [
            'ID',
            'Parent Employee Code',
            'Employee Code',
            'Employee Position',
            'Name',
            'Mobile No',
            'Designation',
            'HQ',
            'Region',
            'Zone',
            'Unique ID',
            'Target Enrollment',
            'Enrollment Received',
            'Balance',
            '% Enrollment',
        ]);

        $rows = DB::select("
        SELECT
            users.id,
            users.parent_employee_code,
            users.employee_code,
            users.employee_pos,
            users.name,
            users.mobile_no,
            users.designation,
            users.hq,
            users.region,
            users.zone,
            users.unique_id,
            users.target_enrollment,

            COALESCE(dr_counts.enrollment_received, 0) AS enrollment_received,

            (
                users.target_enrollment -
                COALESCE(dr_counts.enrollment_received, 0)
            ) AS balance,

            CASE
                WHEN users.target_enrollment > 0
                THEN CONCAT(
                    ROUND(
                        (
                            COALESCE(dr_counts.enrollment_received, 0) * 100
                        ) / users.target_enrollment,
                        0
                    ),
                    '%'
                )
                ELSE '0%'
            END AS percentage_enrollment

        FROM tbl_users users

        LEFT JOIN (
            SELECT
                employee_code,
                COUNT(*) AS enrollment_received
            FROM dr_requests
            GROUP BY employee_code
        ) dr_counts
            ON dr_counts.employee_code = users.employee_code

        WHERE users.employee_pos = 0
            AND users.region <> 'HO'

        ORDER BY
            users.region ASC,
            (
                COALESCE(dr_counts.enrollment_received, 0) * 100
            ) / NULLIF(users.target_enrollment, 0) DESC,
            users.name ASC
        ");

        foreach ($rows as $row) {

            fputcsv($handle, [
                $row->id,
                $row->parent_employee_code,
                $row->employee_code,
                'TM',
                $row->name,
                $row->mobile_no,
                $row->designation,
                $row->hq,
                $row->region,
                $row->zone,
                $row->unique_id,
                $row->target_enrollment,
                $row->enrollment_received,
                $row->balance,
                $row->percentage_enrollment,
            ]);
        }

        fclose($handle);

        return $filePath;
    }

    private function generateDoctorTeamCsv(): string
    {
        $filePath = $this->tmpPath("Doctor_Team_Report_" . date('Y_m_d') . ".csv");

        $handle = fopen($filePath, 'w');

        fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($handle, [
            'ID',
            'Doctor Name',
            'City',
            'MI Unnati/OCE ID',
            'Specialty',
            'Dr Mobile No',
            'Gender',
            'Language',
            'Photo',
            'Revise Photo',
            'Photo Review Status',
            'QC Name',
            'Video Status',
            'Download Ai Video Count',
            'Employee Code',
            'Employee Name',
            'Employee HQ',
            'Region',
            'Zone',
            'Created At',
            'Updated At',
        ]);

        $requests = DB::table('dr_requests as requests')
            ->leftJoin('tbl_users as users', 'requests.employee_code', '=', 'users.employee_code')
            ->select(
                'requests.*',
                'users.name as user_name',
                'users.hq as user_hq',
                'users.region',
                'users.zone'
            )
            ->orderBy('requests.id', 'desc')
            ->get();

        foreach ($requests as $row) {

            $pstatus = match ($row->qc_status) {
                0 => 'Pending',
                1 => 'Approved',
                2 => 'Low Resolution',
                3 => 'Not A Front Facing Photo',
                4 => 'Face Missing',
                5 => 'Multiple People in Photo',
                6 => 'Reject - goggles',
                7 => 'Not Compatible with AI — Upload Different Photo',
                default => '',
            };

            $vstatus = ($row->dm_videoStatus == 15) ? 'Done' : 'Pending';

            fputcsv($handle, [
                $row->id,
                $row->name,
                $row->city,
                $row->mi_oci_id,
                $row->speciality,
                $row->mobile,
                ucfirst($row->gender),
                $row->language,
                $row->photo ?? '',
                $row->revise_photograph
                    ? 'https://ihapp.blr1.cdn.digitaloceanspaces.com/' . $row->revise_photograph
                    : '',
                $pstatus,
                $row->qcname,
                $vstatus,
                $row->videoDownloadCount,
                $row->employee_code,
                $row->user_name,
                $row->user_hq,
                $row->region,
                $row->zone,
                $row->created_at,
                $row->updated_at,
            ]);
        }

        fclose($handle);

        return $filePath;
    }

    public function unsubscribed($id)
    {
        $decodedId = decrypt($id);

        if (!is_numeric($decodedId)) {
            abort(404);
        }

        DB::table('DailyMail')
            ->where('id', $decodedId)
            ->update(['status' => 1]);

        return view('unsubscribe_success', [
            'message' => 'You have been successfully unsubscribed.'
        ]);
    }

    private function tmpPath(string $fileName): string
    {
        $dir = storage_path('app/tmp');
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir . '/' . $fileName;
    }
}
