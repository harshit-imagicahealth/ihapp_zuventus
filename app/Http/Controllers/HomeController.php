<?php

namespace App\Http\Controllers;

use App\Models\Requests;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{

public function downloadVideo($id, $videoType)
    {
        try {

            $id = decrypt($id);

            // dd($videoType);
            $record = Requests::findOrFail($id);


            if ($videoType == 'EurepaV_AIVideo') {
                $videoPath = base_path('video_output/' . $id . '.mp4');
                $record->increment('videoDownloadCount');
            } else if ($videoType == 'sembolic_ai_video') {
                $videoPath = base_path('second_video_output/' . $id . '.mp4');
                $record->increment('secondVideoDownloadCount');
            } else if($videoType == 'education_video') {
                $videoPath = public_path('videos/Sembolic Tab Patient Education Video.mp4');
                $record->increment('educationVideoDownloadCount');
            } else {
                return redirect()->back()->with('error', 'Invalid video type.');
            }

            // Check if file exists
            if (!File::exists($videoPath)) {
                return redirect()->back()->with('error', 'Video file not found.');
            }

            if($videoType == 'education_video') {
                return response()->download($videoPath, 'Sembolic Tab Patient Education Video.mp4');
            }

            // remove special characters
            $videoName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $record->name) . '.mp4';

            return response()->download($videoPath, $videoName);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {

            return redirect()->back()->with('error', 'Something went wrong while downloading the video.');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Something went wrong while downloading the video.');
        }
    }


    // torrent spectra me enrollment button functions
    // public function index()
    // {
    //     $count = Requests::where('employee_code',Auth::user()->employee_code)->count();
    //     return view('dashboard', compact('count'));
    // }

    // public function meetingData(Request $request)
    // {
    //     $page = max(1, (int) $request->page);
    //     $perPage = 10;
    //     $search = $request->search ?? '';

    //     $query = Requests::where('employee_code',Auth::user()->employee_code);

    //     if ($search !== '') {
    //         $query->where('name', 'like', "%{$search}%");
    //     }

    //     $total = $query->count();

    //     $data = $query->orderByDesc('id')
    //         ->offset(($page - 1) * $perPage)
    //         ->limit($perPage)
    //         ->get()
    //         ->map(function ($row) {

    //             // PHOTO STATUS
    //             if ($row->qc_status == 0) {
    //                 $photoStatus = '<i class="fa-regular fa-clock text-warning fs-5"></i>';
    //             } elseif ($row->qc_status == 1) {
    //                 $photoStatus = '<i class="fa-solid fa-circle-check text-success fs-5"></i>';
    //             } else {
    //                 if ($row->qc_status == 2) {
    //                     $reject_reason = "Low Resolution";
    //                 } else if ($row->qc_status == 3) {
    //                     $reject_reason = "Not A Front Facing Photo";
    //                 } else if ($row->qc_status == 4) {
    //                     $reject_reason = "Face Missing";
    //                 } else if ($row->qc_status == 5) {
    //                     $reject_reason = "Multiple People in Photo";
    //                 } else if ($row->qc_status == 6) {
    //                     $reject_reason = "Reject - goggles";
    //                 } else if ($row->qc_status == 7) {
    //                     $reject_reason = "Not Compatible with AI — Upload Different Photo";
    //                 }
    //                 $photoStatus = '<i class="fa-solid fa-circle-xmark text-danger fs-5 review-photo"
    //                         data-id="' . encrypt($row->id) . '"
    //                         data-photo="https://ihapp.blr1.cdn.digitaloceanspaces.com/' . $row->photo . '"
    //                         data-reason="' . $reject_reason . '"
    //                     ></i>';
    //             }

    //             if ($row->qc_audio_status == 0) {
    //                 $audioStatus = '<i class="fa-regular fa-clock text-warning fs-5"></i>';
    //             } elseif ($row->qc_audio_status == 1) {
    //                 $audioStatus = '<i class="fa-solid fa-circle-check text-success fs-5"></i>';
    //             } else {
    //                 $audioStatus = '<i class="fa-solid fa-circle-xmark text-danger fs-5 review-audio"
    //                         data-id="' . encrypt($row->id) . '"
    //                         data-audio="https://ihapp.blr1.cdn.digitaloceanspaces.com/' . $row->audio . '"
    //                     ></i>';
    //             }

    //             if ($row->dm_videoStatus == 15) {
    //                 $EurepaV_AIVideo = '<a href="' . route('download.video', ['id' => encrypt($row->id), 'type' => 'EurepaV_AIVideo']) . '" target="_blank"><i class="fa-solid fa-download text-primary fs-5"></i></a>';
    //             } else {
    //                 $EurepaV_AIVideo = '<i class="fa-regular fa-clock text-warning fs-5"></i>';
    //             }
    //             $education_video_download = '<a href="' . route('download.video', ['id' => encrypt($row->id), 'type' => 'education_video']) . '" target="_blank"><i class="fa-solid fa-download text-primary fs-5"></i></a>';



    //             if ($row->dm_secondVideoStatus == 15) {
    //                 $sembolic_ai_video = '<a href="' . route('download.video', ['id' => encrypt($row->id), 'type' => 'sembolic_ai_video']) . '" target="_blank"><i class="fa-solid fa-download text-primary fs-5"></i></a>';
    //                 // $sembolic_ai_video = '<i class="fa-regular fa-clock text-warning fs-5"></i>';
    //             } else {
    //                 $sembolic_ai_video = '<i class="fa-regular fa-clock text-warning fs-5"></i>';
    //             }

    //             return [
    //                 'id' => encrypt($row->id),
    //                 'name' => $row->name,
    //                 'designation' => $row->designation,
    //                 'status' => $photoStatus,
    //                 'audio_status' => $audioStatus,
    //                 'sembolic_ai_video' => $sembolic_ai_video,
    //                 'video_download' => $EurepaV_AIVideo,
    //                 'education_video_download' => $education_video_download,
    //                 'created_at' => Carbon::parse($row->created_at)->format('d-m-Y h:i A'),
    //             ];
    //         });

    //     return response()->json([
    //         'data' => $data,
    //         'total' => $total,
    //         'per_page' => $perPage,
    //         'current_page' => $page,
    //         'total_pages' => ceil($total / $perPage),
    //     ]);
    // }

    // public function create()
    // {
    //     $count = Requests::where('employee_code',Auth::user()->employee_code)->count();
    //     if ($count >= 1) {
    //         return redirect()->route('dashboard');
    //     }
    //     return view('create');
    // }

    // public function store(Request $request)
    // {
    //     $photo1 = null;
    //     $audio = null;

    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'name' => 'required|string|max:255|min:2',
    //             'designation' => 'required|string|max:255|min:2',
    //             'gender' => 'required|in:male,female',
    //             'mobile' => 'required',
    //             'hq' => 'required',
    //             'photo_1' => 'required',
    //             'audio' => 'required',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => false,
    //                 'errors' => $validator->errors(),
    //                 'message' => 'Validation failed'
    //             ], 422);
    //         }

    //         // Handle photo upload
    //         if ($request->hasFile('photo_1')) {
    //             $file = $request->file('photo_1');
    //             $photo1 = 'Torrent_Spectra/photos/' . uniqid() . time() . '.png';

    //             Storage::disk('spaces')->put(
    //                 $photo1,
    //                 file_get_contents($file->getRealPath()),
    //                 'public'
    //             );
    //             // Double check that file exists
    //             if (!Storage::disk('spaces')->exists($photo1)) {
    //                 return response()->json([
    //                     'status' => false,
    //                     'message' => "Upload failed: file not found in Spaces after upload."
    //                 ], 500);
    //             }
    //         }

    //         // Handle audio upload
    //         // if ($request->hasFile('audio')) {
    //         //     $audioFile = $request->file('audio');

    //         //     // Determine file extension based on MIME type
    //         //     $mimeType = $audioFile->getMimeType();
    //         //     $extension = $this->getAudioExtension($mimeType);

    //         //     $audio = 'Torrent_Spectra/audio/' . uniqid() . time() . '.' . $extension;

    //         //     Storage::disk('spaces')->put(
    //         //         $audio,
    //         //         file_get_contents($audioFile->getRealPath()),
    //         //         'public'
    //         //     );
    //         //     // Double check that file exists
    //         //     if (!Storage::disk('spaces')->exists($audio)) {
    //         //         return response()->json([
    //         //             'status' => false,
    //         //             'message' => "Upload failed: file not found in Spaces after upload."
    //         //         ], 500);
    //         //     }
    //         // }
    //         if ($request->hasFile('audio')) {

    //             $audioFile = $request->file('audio');

    //             // Temp paths
    //             $tempInput = storage_path('app/temp_' . uniqid() . '.' . $audioFile->getClientOriginalExtension());
    //             $tempOutput = storage_path('app/temp_' . uniqid() . '.mp3');

    //             // Move uploaded file to temp
    //             move_uploaded_file($audioFile->getRealPath(), $tempInput);

    //             // Convert to MP3 using FFmpeg
    //             $ffmpegCommand = "ffmpeg -i {$tempInput} -vn -ar 44100 -ac 2 -b:a 192k {$tempOutput} 2>&1";
    //             exec($ffmpegCommand, $output, $returnCode);

    //             if ($returnCode !== 0 || !file_exists($tempOutput)) {
    //                 return response()->json([
    //                     'status' => false,
    //                     'message' => 'Audio conversion failed'
    //                 ], 500);
    //             }

    //             // Save MP3 to Spaces
    //             $audio = 'Torrent_Spectra/audio/' . uniqid() . time() . '.mp3';

    //             Storage::disk('spaces')->put(
    //                 $audio,
    //                 file_get_contents($tempOutput),
    //                 'public'
    //             );

    //             // Cleanup temp files
    //             @unlink($tempInput);
    //             @unlink($tempOutput);
    //         }


    //         // Create the request record
    //         $data = Requests::create([
    //             'name' => $request->name,
    //             'hq' => $request->hq,
    //             'employee_code' =>Auth::user()->employee_code,
    //             'unique_id' =>Auth::user()->unique_id,
    //             'designation' =>Auth::user()->designation,
    //             'mobile' => $request->mobile,
    //             'gender' => $request->gender,
    //             'photo' => $photo1,
    //             'audio' => $audio,
    //         ]);

    //         return response()->json([
    //             'status' => true,
    //             'url' => route('details', encrypt($data->id))
    //         ], 201);
    //     } catch (\Throwable $th) {
    //         //throw $th;
    //         Log::error('Store Error: ' . $th->getMessage());
    //         return response()->json([
    //             'status' => false,
    //             'url' => 'something went wrong!'
    //         ], 500);
    //     }
    // }

    // public function edit($id)
    // {
    //     $data = Requests::where('id', decrypt($id))->where('employee_code',Auth::user()->employee_code)->first();
    //     if ($data) {
    //         return view('edit', compact('data'));
    //     }
    //     return abort(404);
    // }

    // public function update(Request $request, $id)
    // {
    //     try {
    //         // Decrypt the ID
    //         $decryptedId = decrypt($id);

    //         // Get the user's data
    //         $data = Requests::findOrFail($decryptedId);


    //         // Define validation rules
    //         $validator = Validator::make($request->all(), [
    //             'name' => 'required|string|max:255|min:2',
    //             'designation' => 'required|string|max:255|min:2',
    //             'gender' => 'required|in:male,female',
    //             'mobile' => 'required',
    //             'hq' => 'required',
    //             'photo_1' => 'nullable',
    //             'audio' => 'nullable',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => false,
    //                 'errors' => $validator->errors(),
    //                 'message' => 'Validation failed'
    //             ], 422);
    //         }

    //         // Prepare update data
    //         $updateData = [
    //             'name' => $request->name,
    //             'hq' => $request->hq,
    //             'designation' => $request->designation,
    //             'gender' => $request->gender,
    //             'mobile' => $request->mobile,
    //         ];

    //         // ============================================
    //         // HANDLE PHOTO UPDATE
    //         // ============================================
    //         if ($request->hasFile('photo_1')) {
    //             try {
    //                 // Delete old photo if exists
    //                 if ($data->photo && Storage::disk('spaces')->exists($data->photo)) {
    //                     Storage::disk('spaces')->delete($data->photo);
    //                 }

    //                 // Upload new photo
    //                 $photoFile = $request->file('photo_1');
    //                 $photoFilename = 'employees/photos/employee_' . uniqid() . time() . '.' . $photoFile->getClientOriginalExtension();

    //                 // Upload file to DigitalOcean Spaces
    //                 Storage::disk('spaces')->put($photoFilename, file_get_contents($photoFile), 'public');

    //                 // Verify file exists
    //                 if (!Storage::disk('spaces')->exists($photoFilename)) {
    //                     return response()->json([
    //                         'status' => false,
    //                         'message' => 'Photo upload failed. Please try again.'
    //                     ], 500);
    //                 }

    //                 $updateData['photo'] = $photoFilename;
    //             } catch (\Exception $e) {
    //                 Log::error('Photo Upload Error: ' . $e->getMessage());
    //                 return response()->json([
    //                     'status' => false,
    //                     'message' => 'Error uploading photo: ' . $e->getMessage()
    //                 ], 500);
    //             }
    //         }

    //         // ============================================
    //         // HANDLE AUDIO UPDATE
    //         // ============================================
    //         if ($request->hasFile('audio')) {
    //             try {
    //                 // Delete old audio if exists
    //                 if ($data->audio && Storage::disk('spaces')->exists($data->audio)) {
    //                     Storage::disk('spaces')->delete($data->audio);
    //                 }

    //                 // Upload new audio
    //                 $audioFile = $request->file('audio');
    //                 $audioFilename = 'employees/audio/employee_' . uniqid() . time() . '.' . $audioFile->getClientOriginalExtension();

    //                 // Upload file to DigitalOcean Spaces
    //                 Storage::disk('spaces')->put($audioFilename, file_get_contents($audioFile), 'public');

    //                 // Verify file exists
    //                 if (!Storage::disk('spaces')->exists($audioFilename)) {
    //                     return response()->json([
    //                         'status' => false,
    //                         'message' => 'Audio upload failed. Please try again.'
    //                     ], 500);
    //                 }

    //                 $updateData['audio'] = $audioFilename;
    //             } catch (\Exception $e) {
    //                 Log::error('Audio Upload Error: ' . $e->getMessage());
    //                 return response()->json([
    //                     'status' => false,
    //                     'message' => 'Error uploading audio: ' . $e->getMessage()
    //                 ], 500);
    //             }
    //         }

    //         // ============================================
    //         // UPDATE DATABASE RECORD
    //         // ============================================
    //         $data->update($updateData);

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Data updated successfully!',
    //             'url' => route('details', encrypt($data->id)) // Change this to your desired redirect route
    //         ], 200);
    //     } catch (\Exception $e) {
    //         Log::error('Employee Update Error: ' . $e->getMessage(), [
    //             'trace' => $e->getTraceAsString(),
    //             'user_id' => Auth::id() ?? null
    //         ]);

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'An unexpected error occurred: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    // public function delete($id)
    // {
    //     $id = decrypt($id);

    //     $record = Requests::where('id', $id)->first();

    //     if (!$record) {
    //         return response()->json(['status' => 'error']);
    //     }

    //     // Delete photo if exists
    //     if (!empty($record->photo) && Storage::disk('spaces')->exists($record->photo)) {
    //         Storage::disk('spaces')->delete($record->photo);
    //     }

    //     // Delete audio if exists
    //     if (!empty($record->audio) && Storage::disk('spaces')->exists($record->audio)) {
    //         Storage::disk('spaces')->delete($record->audio);
    //     }

    //     $record->delete();

    //     return response()->json(['status' => 'success']);
    // }

    // public function details($id)
    // {
    //     $data = Requests::where('id', decrypt($id))->where('employee_code',Auth::user()->employee_code)->first();
    //     if ($data) {

    //         $audioUrl = route('download-audio', encrypt($data->id)); // Local route instead
    //         return view('details', compact('data', 'audioUrl'));
    //     }
    //     return abort(404);
    // }

    // public function downloadAudio($id)
    // {
    //     $data = Requests::findOrFail(decrypt($id));

    //     // Get audio from DigitalOcean
    //     $audioUrl = 'https://ihapp.blr1.cdn.digitaloceanspaces.com/' . $data->audio;

    //     try {
    //         $audioContent = file_get_contents($audioUrl);

    //         return response($audioContent, 200)
    //             ->header('Content-Type', 'audio/wav')
    //             ->header('Content-Disposition', 'inline; filename="audio.wav"')
    //             ->header('Access-Control-Allow-Origin', '*');
    //     } catch (\Exception $e) {
    //         abort(404, 'Audio not found');
    //     }
    // }

    // /**
    //  * Get audio file extension based on MIME type
    //  */
    // private function getAudioExtension($mimeType)
    // {
    //     $mimeTypeMap = [
    //         'audio/mpeg' => 'mp3',
    //         'audio/wav' => 'wav',
    //         'audio/ogg' => 'ogg',
    //         'audio/webm' => 'webm',
    //         'audio/x-m4a' => 'm4a',
    //         'audio/mp4' => 'm4a',
    //         'audio/aac' => 'aac',
    //         'audio/flac' => 'flac',
    //         'application/ogg' => 'ogg',
    //     ];

    //     return $mimeTypeMap[$mimeType] ?? 'wav';
    // }


    // public function photoReupload(Request $req)
    // {
    //     if (!$req->hasFile('photo')) {
    //         return response()->json(['success' => false], 400);
    //     }

    //     $file = $req->file('photo');

    //     $id = decrypt($req->id);
    //     $record = Requests::findOrFail($id);

    //     $image = imagecreatefromstring(file_get_contents($file));
    //     if (!$image) {
    //         return response()->json(['success' => false], 400);
    //     }

    //     ob_start();
    //     imagepng($image, null, 9);
    //     $pngData = ob_get_clean();
    //     imagedestroy($image);

    //     $name = 'Torrent_Spectra/photos/' . time() . uniqid() . '.png';

    //     Storage::disk('spaces')->put($name, $pngData, 'public');

    //     if ($record->photo) {
    //         Storage::disk('spaces')->delete($record->photo);
    //     }

    //     $record->update([
    //         'photo' => $name,
    //         'qc_status' => 0
    //     ]);

    //     return response()->json(['success' => true]);
    // }

    // public function audioReupload(Request $req)
    // {
    //     if (!$req->hasFile('audio')) {
    //         return response()->json(['success' => false], 400);
    //     }

    //     $file = $req->file('audio');

    //     $id = decrypt($req->id);
    //     $record = Requests::findOrFail($id);

    //     if ($record->audio) {
    //         Storage::disk('spaces')->delete($record->audio);
    //     }

    //     $ext = strtolower($file->getClientOriginalExtension());

    //     $name = 'Torrent_Spectra/audio/' . time() . uniqid() . '.' . $ext;

    //     Storage::disk('spaces')->put($name, file_get_contents($file), 'public');

    //     $record->update([
    //         'audio' => $name,
    //         'qc_audio_status' => 0
    //     ]);

    //     return response()->json(['success' => true]);
    // }

    

}
