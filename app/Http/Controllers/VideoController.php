<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Repositories\VideoRepository;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    protected $videoRepository;

    public function __construct(VideoRepository $videoRepository)
    {
        $this->videoRepository = $videoRepository;
        $this->middleware('role:mentor');
    }


    public function store(Request $request, $courseId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|string',
        ]);

        $data = $request->all();
        $data['course_id'] = $courseId;

        $video = $this->videoRepository->createVideo($data);
        return response()->json(['message' => 'Video created successfully', 'video' => $video], 201);
    }

 
    public function index($courseId)
    {
        $videos = $this->videoRepository->getVideosByCourse($courseId);
        return response()->json(['videos' => $videos]);
    }


    public function show($id)
    {
        $video = $this->videoRepository->getVideoById($id);
        return response()->json(['video' => $video]);
    }

 
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'url' => 'sometimes|string',
        ]);

        $video = $this->videoRepository->updateVideo($id, $request->all());
        return response()->json(['message' => 'Video updated successfully', 'video' => $video]);
    }

    public function destroy($id)
    {
        $this->videoRepository->deleteVideo($id);
        return response()->json(['message' => 'Video deleted successfully']);
    }
}