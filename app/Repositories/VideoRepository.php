<?php
namespace App\Repositories;

use App\Models\Video;
use App\Interfaces\VideoRepositoryInterface;

class VideoRepository implements VideoRepositoryInterface
{
    public function createVideo(array $data)
    {
        return Video::create($data);
    }

    public function getVideosByCourse($courseId)
    {
        return Video::where('course_id', $courseId)->get();
    }

    public function getVideoById($id)
    {
        return Video::findOrFail($id);
    }

    public function updateVideo($id, array $data)
    {
        $video = Video::findOrFail($id);
        $video->update($data);
        return $video;
    }

    public function deleteVideo($id)
    {
        $video = Video::findOrFail($id);
        $video->delete();
        return true;
    }
}