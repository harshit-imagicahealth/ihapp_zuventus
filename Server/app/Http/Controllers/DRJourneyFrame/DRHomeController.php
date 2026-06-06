<?php

namespace App\Http\Controllers\DRJourneyFrame;

use App\Models\DRJourneyFrame;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class DRHomeController extends Controller
{
    private function validationRules($edit = false, $id = null)
    {
        return [
            'name' => 'required|string|max:255|min:2',
            'specialty' => 'required|string|max:255|min:2',
            'dr_specialty' => 'required|string|max:255|min:2',
            'collage_name' => 'required|string|max:255|min:2',
            'pg_name' => 'required|string|max:255|min:2',
            'year_of_practice' => 'required|integer|min:0',
            'area_of_expertise' => 'required|array|min:1',
            'mobile_number' => 'required|digits:10',
            'photo_1' => ($edit ? 'nullable' : 'required') . '|image|mimes:jpg,jpeg,png|max:2048',
            'card_f_photo_1' => ($edit ? 'nullable' : 'required') . '|image|mimes:jpg,jpeg,png,webp|max:2048',
            'card_b_photo_1' => ($edit ? 'nullable' : 'required') . '|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    private function validationMessages()
    {
        return [
            'name.required' => 'Name Of Doctor Is Required.',
            'name.min' => 'Name Of Doctor Must Be At Least 2 Characters.',

            'specialty.required' => 'Specialty Is Required.',
            'specialty.min' => 'Specialty Must Be At Least 2 Characters.',

            'dr_specialty.required' => 'Doctor Specialty Is Required.',
            'dr_specialty.min' => 'Doctor Specialty Must Be At Least 2 Characters.',

            'collage_name.min' => 'College Name Must Be At Least 2 Characters.',

            'pg_name.min' => 'PG Name Must Be At Least 2 Characters.',

            'year_of_practice.integer' => 'Year Of Practice Must Be A Number.',
            'year_of_practice.min' => 'Year Of Practice Must Be Greater Than 0.',

            'area_of_expertise.required' => 'Area Of Expertise Is Required.',

            'mobile_number.digits' => 'Alternate Mobile Number Must Be Exactly 10 Digits.',

            // Image Messages
            'photo_1.required' => 'Photo Upload Is Required.',
            'photo_1.image' => 'Photo Must Be An Image File.',
            'photo_1.mimes' => 'Photo Must Be JPG, JPEG Or PNG.',
            'photo_1.max' => 'Photo Size Must Be Less Than 2MB.',

            'card_f_photo_1.image' => 'Doctor Visiting Card Front Image Must Be An Image File.',
            'card_b_photo_1.image' => 'Doctor Visiting Card Back Image Must Be An Image File.',
        ];
    }
    private function imagePath()
    {
        return "Zuventus/DoctorFrame/photos/";
    }
    private function cardImagePath()
    {
        return "Zuventus/DoctorFrame/cards/";
    }

    private function getData(Request $request, string $edit)
    {
        $data = [
            'name' => $request->name,
            'specialty' => $request->specialty,
            'dr_specialty' => $request->dr_specialty,
            'collage_name' => $request->collage_name,
            'pg_name' => $request->pg_name,
            'year_of_practice' => $request->year_of_practice,
            'area_of_expertise' => $request->area_of_expertise,
            'dob' => $request->dob,
            'mobile_number' => $request->mobile_number,
        ];
        if (!$edit) {
            $data['employee_code'] = Auth::user()->employee_code;
            $data['unique_id'] = Auth::user()->unique_id;
            $data['hq'] = Auth::user()->hq;
        }
        if ($request->hasFile('photo_1')) {
            $data['photo'] = $request->photo;
        }

        if ($request->hasFile('card_f_photo_1')) {
            $data['card_f_photo'] = $request->card_f_photo;
        }

        if ($request->hasFile('card_b_photo_1')) {
            $data['card_b_photo'] = $request->card_b_photo;
        }
        return $data;
    }
    private function getMEList(User $user)
    {
        if ($user->employee_pos == 0) {
            return User::where('employee_code', $user->employee_code)->get();
        }
        if ($user->employee_pos == 1) {
            return User::where('parent_employee_code', $user->employee_code)->where('id', '!=', $user->id)->get();
        }
        if ($user->employee_pos == 2) {
            $childCodes = User::where('parent_employee_code', $user->employee_code)->pluck('employee_code')->toArray();    // Get users reporting to those Level 1 codes (Level 2)
            return User::whereIn('parent_employee_code', $childCodes)->where('id', '!=', $user->id)->get();
        }
        if ($user->employee_pos == 3) {
            $level1Codes = User::where('parent_employee_code', $user->employee_code)->pluck('employee_code')->toArray();
            $level2Codes = User::whereIn('parent_employee_code', $level1Codes)->pluck('employee_code')->toArray();
            return User::whereIn('parent_employee_code', $level2Codes)->where('id', '!=', $user->id)->get();
        }

        return User::where('employee_pos', 0)->get();
    }

    // private function getChildEmployeeCodes($managerCode)
    // {
    //     $allCodes = [$managerCode];
    //     $fetchChildren = function ($parentCode) use (&$fetchChildren, &$allCodes) {

    //         $children = User::where('parent_employee_code', $parentCode)
    //             ->pluck('employee_code')
    //             ->toArray();
    //         foreach ($children as $child) {
    //             $allCodes[] = $child;
    //             $fetchChildren($child);
    //         }
    //     };

    //     $fetchChildren($managerCode);

    //     return $allCodes;
    // }



    public function index()
    {
        // $count = DRJourneyFrame::where('employee_code', Auth::user()->employee_code)->count();
        $count = 0;
        $requestCounts = DRJourneyFrame::where('employee_code', Auth::user()->employee_code)->requestsCount()->first();
        return view('DRJourneyFrame.dashboard', compact('count', 'requestCounts'));
    }

    public function meetingData(Request $request)
    {
        $page = max(1, (int) $request->page);
        $perPage = 10;
        $search = $request->search ?? '';
        $filters = $request->query('filters', null);
        $user = Auth::user();

        $employeeCodes = $this->getMEList($user)->pluck('unique_id')->toArray();

        $query = DRJourneyFrame::whereIn('unique_id', Auth::user()->employee_pos == 0 ? [$user->unique_id] : $employeeCodes);

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }
        if ($filters !== null) {
            $query->where(function ($q) use ($filters) {

                if (!empty($filters['dr_name'])) {
                    $q->where('name', 'like', '%' . $filters['dr_name'] . '%');
                }

                if (!empty($filters['specialty'])) {
                    $q->where('specialty', 'like', '%' . $filters['specialty'] . '%');
                }

                if (isset($filters['status']) && $filters['status'] !== '') {
                    $q->where('qc_status', $filters['status']);
                }
            });
        }

        $total = $query->count();

        $data = $query->orderByDesc('id')
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get()
            ->map(function ($row) {

                // PHOTO STATUS
                if ($row->qc_status == 0) {
                    $photoStatus = '<i class="fa-regular fa-clock text-warning fs-5"></i>';
                } elseif ($row->qc_status == 1) {
                    $photoStatus = '<i class="fa-solid fa-circle-check text-success fs-5"></i>';
                } else {
                    $reject_reason = null;
                    if ($row->qc_status == 2) {
                        $reject_reason = "Low Resolution";
                    } else if ($row->qc_status == 3) {
                        $reject_reason = "Not A Front Facing Photo";
                    } else if ($row->qc_status == 4) {
                        $reject_reason = "Face Missing";
                    } else if ($row->qc_status == 5) {
                        $reject_reason = "Multiple People in Photo";
                    } else if ($row->qc_status == 6) {
                        $reject_reason = "Reject - goggles";
                    } else if ($row->qc_status == 7) {
                        $reject_reason = "Not Compatible with AI — Upload Different Photo";
                    }
                    $photoStatus = '<i class="fa-solid fa-circle-xmark text-danger fs-5 review-photo"
                            data-id="' . encrypt($row->id) . '"
                            data-photo="https://ihapp.blr1.cdn.digitaloceanspaces.com/' . $row->photo . '"
                            data-reason="' . $reject_reason . '"
                        ></i> Edit';
                }

                return [
                    'id' => $row->encoded_id,
                    'original_id' => $row->id,
                    'employee_code' => $row->employee_code,
                    'name' => $row->name,
                    'specialty' => ucfirst($row->specialty) ?? '',
                    'status' => $photoStatus,
                    'edit_url' => route('dr.journey.edit', encrypt($row->id)),
                    'delete_url' => route('dr.journey.delete', encrypt($row->id)),
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

    public function create()
    {
        $requestCount = DRJourneyFrame::where('employee_code', Auth::user()->employee_code)->requestsCount()->first();
        if ($requestCount && $requestCount->total_requests >= 25) {
            Session()->flash('error', 'You have reached the maximum limit of 25 requests.');
            return redirect()->route('dr.journey.dashboard');
        }
        return view('DRJourneyFrame.create', compact('requestCount'));
    }

    public function store(Request $request)
    {
        $photo1 = null;
        $cardFrontPhoto = null;
        $cardBackPhoto = null;

        try {
            $validator = Validator::make($request->all(), $this->validationRules(false, null), $this->validationMessages());

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed'
                ], 422);
            }


            // Handle photo upload
            if ($request->hasFile('photo_1')) {
                $file = $request->file('photo_1');
                $photo1 = $this->imagePath() . '' . uniqid() . time() . '.png';


                Storage::disk('spaces')->put(
                    $photo1,
                    file_get_contents($file->getRealPath()),
                    'public'
                );
                // Double check that file exists
                if (!Storage::disk('spaces')->exists($photo1)) {
                    return response()->json([
                        'status' => false,
                        'message' => "Upload failed: file not found in Spaces after upload."
                    ], 500);
                }
                $photo1 = Storage::disk('spaces')->url($photo1);
                $request->merge(['photo' => $photo1]);
            }

            if ($request->hasFile('card_f_photo_1')) {
                $fileFront = $request->file('card_f_photo_1');
                $cardFrontPhoto = $this->cardImagePath() . '' . uniqid() . time() . '_front.png';

                Storage::disk('spaces')->put(
                    $cardFrontPhoto,
                    file_get_contents($fileFront->getRealPath()),
                    'public'
                );
                if (!Storage::disk('spaces')->exists($cardFrontPhoto)) {
                    return response()->json([
                        'status' => false,
                        'message' => "Upload failed: card front photo not found in Spaces after upload."
                    ], 500);
                }
                $cardFrontPhoto = Storage::disk('spaces')->url($cardFrontPhoto);
                $request->merge(['card_f_photo' => $cardFrontPhoto]);
            }

            if ($request->hasFile('card_b_photo_1')) {
                $fileBack = $request->file('card_b_photo_1');
                $cardBackPhoto = $this->cardImagePath() . '' . uniqid() . time() . '_back.png';

                Storage::disk('spaces')->put(
                    $cardBackPhoto,
                    file_get_contents($fileBack->getRealPath()),
                    'public'
                );
                if (!Storage::disk('spaces')->exists($cardBackPhoto)) {
                    return response()->json([
                        'status' => false,
                        'message' => "Upload failed: card back photo not found in Spaces after upload."
                    ], 500);
                }
                $cardBackPhoto = Storage::disk('spaces')->url($cardBackPhoto);
                $request->merge(['card_b_photo' => $cardBackPhoto]);
            }

            // Create the request record
            $data = DRJourneyFrame::create($this->getData($request, false));

            return response()->json([
                'status' => true,
                'url' => route('dr.journey.details', encrypt($data->id)),
                'data' => $data
            ], 201);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Store Error: ' . $th->getMessage());
            return response()->json([
                'status' => false,
                'url' => 'something went wrong!'
            ], 500);
        }
    }

    public function edit(string $id)
    {
        $data = DRJourneyFrame::where('id', decrypt($id))->where('employee_code', Auth::user()->employee_code)->first();
        if ($data) {
            return view('DRJourneyFrame.edit', compact('data'));
        }
        return abort(404);
    }

    public function update(Request $request, string $id)
    {
        try {
            // Decrypt the ID
            $decryptedId = decrypt($id);

            // Get the user's data
            $data = DRJourneyFrame::findOrFail($decryptedId);

            // Define validation rules
            $validator = Validator::make($request->all(), $this->validationRules(true, $decryptedId), $this->validationMessages());

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed'
                ], 422);
            }

            $photoFilename = null;

            // ============================================
            // HANDLE PHOTO UPDATE
            // ============================================
            if ($request->hasFile('photo_1')) {
                try {
                    // Delete old photo if exists
                    if ($data->photo && Storage::disk('spaces')->exists($data->photo)) {
                        Storage::disk('spaces')->delete($data->photo);
                    }

                    // Upload new photo
                    $photoFile = $request->file('photo_1');
                    $photoFilename = $this->imagePath() . '' . uniqid() . time() . '.' . $photoFile->getClientOriginalExtension();

                    // Upload file to DigitalOcean Spaces
                    Storage::disk('spaces')->put($photoFilename, file_get_contents($photoFile), 'public');

                    // Verify file exists
                    if (!Storage::disk('spaces')->exists($photoFilename)) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Photo upload failed. Please try again.'
                        ], 500);
                    }

                    $photoFilename = Storage::disk('spaces')->url($photoFilename);
                    $request->merge(['photo' => $photoFilename]);
                } catch (\Exception $e) {
                    Log::error('Photo Upload Error: ' . $e->getMessage());
                    return response()->json([
                        'status' => false,
                        'message' => 'Error uploading photo: ' . $e->getMessage()
                    ], 500);
                }
            }

            // ============================================
            // UPDATE DATABASE RECORD
            // ============================================
            $data->update($this->getData($request, true));

            return response()->json([
                'status' => true,
                'message' => 'Data updated successfully!',
                'url' => route('dr.journey.details', encrypt($data->id)) // Change this to your desired redirect route
            ], 200);
        } catch (\Exception $e) {
            Log::error('Employee Update Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id() ?? null
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete(string $id)
    {
        $id = decrypt($id);

        $record = DRJourneyFrame::where('id', $id)->first();

        if (!$record) {
            return response()->json(['status' => 'error']);
        }

        // Delete photo if exists
        if (!empty($record->photo) && Storage::disk('spaces')->exists($record->photo)) {
            Storage::disk('spaces')->delete($record->photo);
        }

        $record->delete();

        return response()->json(['status' => 'success']);
    }

    public function details(string $id)
    {
        $data = DRJourneyFrame::where('id', decrypt($id))->where('employee_code', Auth::user()->employee_code)->first();
        if ($data) {

            // $audioUrl = route('dr.journey.download-audio', encrypt($data->id)); // Local route instead
            $audioUrl = null;
            return view('DRJourneyFrame.details', compact('data', 'audioUrl'));
        }
        return abort(404);
    }

    public function photoReupload(Request $req)
    {
        if (!$req->hasFile('photo')) {
            return response()->json(['success' => false], 400);
        }

        $file = $req->file('photo');

        $id = decrypt($req->id);
        $record = DRJourneyFrame::findOrFail($id);

        $image = imagecreatefromstring(file_get_contents($file));
        if (!$image) {
            return response()->json(['success' => false], 400);
        }

        ob_start();
        imagepng($image, null, 9);
        $pngData = ob_get_clean();
        imagedestroy($image);

        $name = $this->imagePath() . '' . time() . uniqid() . '.png';

        Storage::disk('spaces')->put($name, $pngData, 'public');

        if ($record->photo) {
            Storage::disk('spaces')->delete($record->photo);
        }
        $name = Storage::disk('spaces')->url($name);

        $record->update([
            'photo' => $name,
            'qc_status' => 0
        ]);

        return response()->json(['success' => true]);
    }

    // poster functions
    public function downloadDP(Request $request)
    {
        // dd($request->all());
        $photo = $request->file('croped_dr_image');
        if (strtolower($request->specialty ?? null) == "physician") {
            $posterPath = base_path('public/images/physician-poster.png');
        } else if (strtolower($request->specialty ?? null) == "gyn") {
            $posterPath = base_path('public/images/gyn-poster.png');
        } else {
            throw new \Exception("Invalid specialty: $request->specialty");
        }

        $makePoster = $this->makePoster($photo, 'file', $posterPath, $request);
        if ($makePoster['status'] === 'error') {
            return response()->json([
                'status' => false,
                'message' => $makePoster['message']
            ], 500);
        }

        // Decode base64 image
        $base64Image = $makePoster['image_base64'];
        $imageData = base64_decode(str_replace('data:image/jpeg;base64,', '', $base64Image));

        $fileName = 'DP_' . $request->name . '_' . now()->format('YmdHis') . '.jpg';

        return response($imageData, 200, [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Content-Length' => strlen($imageData),
        ]);
    }
    private function makePoster($photo, string $photoType, string $posterPath, Request $rData)
    {
        try {
            if (!file_exists($posterPath)) {
                throw new \Exception("Poster does not exist: $posterPath");
            }

            $posterwidth = 1190;
            $posterheight = 840;
            $fileType = mime_content_type($posterPath);

            $originalposter = ($fileType === 'image/png')
                ? imagecreatefrompng($posterPath)
                : (($fileType === 'image/jpeg') ? imagecreatefromjpeg($posterPath) : null);

            if (! $originalposter) {
                throw new \Exception("Unsupported file type or failed to load the image.");
            }

            $destinationposter = imagecreatetruecolor($posterwidth, $posterheight);
            if ($fileType === 'image/png') {
                imagealphablending($destinationposter, false);
                imagesavealpha($destinationposter, true);
                $transparency = imagecolorallocatealpha($destinationposter, 0, 0, 0, 127);
                imagefill($destinationposter, 0, 0, $transparency);
            }

            if ($photoType == 'file') {

                $mimeType = $photo->getMimeType();
                $path = $photo->getRealPath();

                if ($mimeType === 'image/png') {
                    $photograph = imagecreatefrompng($path);
                } elseif ($mimeType === 'image/jpeg' || $mimeType === 'image/jpg') {
                    $photograph = imagecreatefromjpeg($path);
                } else {
                    throw new \Exception("Unsupported uploaded file type: $mimeType");
                }
            } else if ($photoType == 'url') {
                $imageInfo = @getimagesize($photo);
                if ($imageInfo === false) {
                    throw new \Exception("The file at the specified URL is not a valid image");
                }

                $mimeType = $imageInfo['mime'];
                $photograph = false;

                if ($mimeType === 'image/png') {
                    $photograph = @imagecreatefrompng($photo);
                } elseif ($mimeType === 'image/jpeg') {
                    $photograph = @imagecreatefromjpeg($photo);
                }

                if ($photograph === false) {
                    throw new \Exception("Failed to create an image from the URL.");
                }
            } else if ($photoType == 'base64') {
                $photograph = imagecreatefromstring(base64_decode(substr($photo, strpos($photo, ",") + 1)));
            } else {
                throw new \Exception("Invalid photo type specified: $photoType");
            }

            $photoWidth = 286;
            $photoHeight = 286;
            $radius = 40; // curve size
            $qrX = 61;
            $qrY = 81;

            $photograph = imagescale($photograph, $photoWidth, $photoHeight);

            // Create rounded image canvas
            $rounded = imagecreatetruecolor($photoWidth, $photoHeight);

            imagealphablending($destinationposter, true);
            imagesavealpha($destinationposter, true);

            $transparent = imagecolorallocatealpha($rounded, 0, 0, 0, 127);
            imagefill($rounded, 0, 0, $transparent);
            // Copy original image
            imagecopy($rounded, $photograph, 0, 0, 0, 0, $photoWidth, $photoHeight);


            // CUT BOTTOM-RIGHT CORNER
            for ($x = $photoWidth - $radius; $x < $photoWidth; $x++) {
                for ($y = $photoHeight - $radius; $y < $photoHeight; $y++) {

                    $dx = $x - ($photoWidth - $radius);
                    $dy = $y - ($photoHeight - $radius);

                    if (($dx * $dx + $dy * $dy) > ($radius * $radius)) {
                        imagesetpixel($rounded, $x, $y, $transparent);
                    }
                }
            }

            // imagecopy($destinationposter, $photograph, $qrX, $qrY, 0, 0, $photoWidth, $photoHeight);
            imagecopy($destinationposter, $rounded, $qrX, $qrY, 0, 0, $photoWidth, $photoHeight);

            imagecopy($destinationposter, $originalposter, 0, 0, 0, 0, $posterwidth, $posterheight);


            $namefont = base_path('public/fonts/Poppins/Poppins-SemiBold.ttf');
            $contentfont = base_path('public/fonts/Poppins/Poppins-Regular.ttf');
            if (! file_exists($namefont)) {
                throw new \Exception("Font file does not exist: $namefont");
            }

            $textColor = imagecolorallocate($destinationposter, 237, 38, 43);
            $textalign = 'left';

            $this->fitTextInBox(
                $destinationposter,
                "Dr. " . $rData->name,
                $namefont,
                395,      // boxX
                247,     // boxY
                585,      // boxWidth
                40,       // boxHeight
                130,       // max font size
                $textColor,
                $textalign,
            );
            $textColor = imagecolorallocate($destinationposter, 0, 0, 0);
            $textalign = 'center';
            $this->fitTextInBox(
                $destinationposter,
                $rData->dr_specialty,
                $contentfont,
                395,      // boxX
                285,     // boxY
                370,      // boxWidth
                40,       // boxHeight
                20,       // max font size
                $textColor,
                $textalign,
            );
            $textColor = imagecolorallocate($destinationposter, 0, 0, 0);
            $textalign = 'left';
            $this->fitTextInBox(
                $destinationposter,
                $rData->collage_name,
                $contentfont,
                38,      // boxX
                678,     // boxY
                325,      // boxWidth
                40,       // boxHeight
                20,       // max font size
                $textColor,
                $textalign,
            );
            $textColor = imagecolorallocate($destinationposter, 0, 0, 0);
            $textalign = 'left';
            $this->fitTextInBox(
                $destinationposter,
                $rData->pg_name,
                $contentfont,
                306,      // boxX
                586,     // boxY
                350,      // boxWidth
                40,       // boxHeight
                20,       // max font size
                $textColor,
                $textalign,
            );
            $textColor = imagecolorallocate($destinationposter, 0, 0, 0);
            $textalign = 'center';
            $this->fitTextInBox(
                $destinationposter,
                $rData->year_of_practice . " Years",
                $contentfont,
                644,      // boxX
                685,     // boxY
                154,      // boxWidth
                40,       // boxHeight
                20,       // max font size
                $textColor,
                $textalign,
            );

            $areaOfExpertise = $rData->area_of_expertise;

            // ensure it's array
            if (!is_array($areaOfExpertise)) {
                $areaOfExpertise = [$areaOfExpertise];
            }

            $count = count($areaOfExpertise);
            $boxY = 0;
            if ($count >= 3) {
                $boxY = 362;
            } elseif ($count == 2) {
                $boxY = 377;
            } else if ($count == 1) {
                $boxY = 388;
            } else {
                $boxY = 362;
            }

            // convert to multiline string
            $text = implode("\n", $areaOfExpertise);

            $textColor = imagecolorallocate($destinationposter, 0, 0, 0);
            $textalign = 'left';

            $this->fitTextInBox(
                $destinationposter,
                $text,
                $contentfont,
                878,      // boxX
                $boxY,     // boxY
                284,      // boxWidth
                190,       // boxHeight
                20,      // max font size
                $textColor,
                $textalign,
            );
            // Capture output buffer instead of saving to disk
            ob_start();
            imagejpeg($destinationposter, null, 80);
            $imageData = ob_get_clean();

            // Clean up
            imagedestroy($destinationposter);
            imagedestroy($originalposter);
            imagedestroy($photograph);

            $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageData);

            return [
                'status' => 'success',
                'image_base64' => $base64Image,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Exception occurred: ' . $e->getMessage(),
            ];
        }
    }

    private function fitTextInBox($image, string $text, string $font, string $boxX, string  $boxY, string $boxWidth, string $boxHeight, string $maxFontSize, string $color, string $textalign)
    {
        do {
            $bbox = imagettfbbox($maxFontSize, 0, $font, $text);
            $textWidth = abs($bbox[2] - $bbox[0]);
            $textHeight = abs($bbox[7] - $bbox[1]);
            $maxFontSize--;
        } while (($textWidth > $boxWidth || $textHeight > $boxHeight) && $maxFontSize > 5);


        if ($textalign == 'center') {
            $textX = (int) ($boxX + ($boxWidth - $textWidth) / 2); //Center vertically
        } else if ($textalign == 'left') {
            $textX = $boxX; // (Left aligned )
        } elseif ($textalign == 'right') {
            $textX = $boxX + $boxWidth - $textWidth; // (Right aligned )
        } else {
            echo "text align not define!";
            exit;
        }
        $textY = (int) ($boxY + ($boxHeight + $textHeight) / 2);

        imagettftext($image, $maxFontSize, 0, $textX, $textY, $color, $font, $text);
    }
}
