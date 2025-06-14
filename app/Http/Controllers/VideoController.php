<?php

namespace App\Http\Controllers;

use App\Models\video;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Psy\Util\Str;

class VideoController extends Controller
{

    /**
     * عرض صفحة رفع الفيديوهات.
     */

    public function store(Request $request)
    {
        // dd($request);
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
            // 'video' => 'required|mimes:mp4,avi,mkv,wmv',
            // 'video' => 'mimetypes:mp4,video/avi,video/mpeg,video/quicktime'
            'video' => 'required|file|mimes:mp4,avi,mov,wmv,flv,mkv,webm,mpeg,3gp'

        ]);
        // dd($request);

        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            $videoPath = $request->file('video')->store('videos', 'public');
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No valid video file uploaded.'
            ], 400);
        }

        // Save data to the database
        $video = Video::create([
            'name' => $request->name,
            'desc' => $request->desc,
            'video' => $videoPath,
            'user_id' => auth()->id()
        ]);

        // Process video (e.g., convert to sign language)
        //  $this->processVideo($video);

        return response()->json([
            'status' => 'success',
            'data' => $video
        ], 200);
    }



    public function index()
    {
        $videos = Video::all();
        // dd($videos);

        $response = [
            'message' => 'all data retrieved success',
            'data' => $videos,
            'status' => 200
        ];
        return response($response, 200);
        //  return response()->json([
        //      'status' => 'success',
        //      'data' => $videos
        //  ], 200);

    }







    public function show(string $id)
    {


        $video = video::find($id);

        if (!empty($video)) {
            $response = [
                'message' => 'data retrieved success',
                'data' => $video,
                'status' => 200
            ];
        } else {
            $response = [
                'message' => 'no data to show',
                'status' => 401
            ];
        }

        return response($response, 200);

        // return response()->json([
        //     'status' => 'success',
        //     'data' => $video
        // ], 200);
    }






    public function update(Request $request, string $id)
    {
        $video = video::find($id);
        // dd($video);
        // if (empty($video)) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'الفيديو غير موجود.'
        //     ], 404);
        // }else {
        //     $request->validate([
        //         'name' => 'nullable|string|max:255',
        //         'desc' => 'nullable|string',
        //         'video' => 'nullable|mimes:mp4,avi,mkv|max:20480',
        //     ]);
        //     $video->desc = $request->desc;
        //     $video->name = $request->name;



        // }


        if(!empty($video)) {
            $request->validate([
                        'name' => 'required|string|max:255',
                        'desc' => 'required|string',
                        // 'video' => 'nullable|mimes:mp4,avi,mkv|max:20480',
                        'video' => 'nullable|mimetypes:video/avi,video/mpeg,video/quicktime'

                    ]);
                    $video->desc = $request->desc;
                    $video->name = $request->name;
                    $video->update();

                    $file = $request->file('video');

                    if($file) {
                        $videoPath = $request->file('video')->store('videos', 'public');
                        $video->video = $videoPath;
                        Storage::disk('public')->delete($video->video);
                    }
                    $video->save();

                    $response = [
                        'message' => 'data updated success',
                        'data' => $video,
                        'status' => 200
                    ];
        }else {
            $response =[
                'message'=> 'not updated',
                'status' => 200
            ];
        }

        return response($response , 200);



        // if ($request->hasFile('video') && $request->file('video')->isValid()) {
        //     if ($video->video && Storage::disk('public')->exists($video->video)) {

        //     }

        // }



        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'تم تحديث الفيديو بنجاح.',
        //     'data' => $video,
        // ], 200);
    }




    public function destroy(string $id)
    {
        $video = video::find($id);
        if (!empty($video)) {
            // تحقق مما إذا كانت القيمة في 'video_path' غير فارغة أو null
            if (!empty($video->video) && Storage::disk('public')->exists($video->video)) {
                // إذا كان المسار موجودًا، نقوم بحذفه
                Storage::disk('public')->delete($video->video);
            }

            $video->delete();

            $response = [
                'message' => 'data deleted success',
                'status' => 204
            ];
        } else {
            $response = [
                'message' => 'no data to deleted',
                'status' => 400
            ];
        }

        return response($response, 200);

    }


    //processing Video ========================>


    private function processVideo(Video $video)
    {

        $response = Http::post('api-to-convert-video-to-sign-language', [
            'video_path' => $video->video_path,
        ]);


        $video->update([
            'processed' => true,
        ]);
    }
}
