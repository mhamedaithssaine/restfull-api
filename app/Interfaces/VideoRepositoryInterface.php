<?php
namespace App\Interfaces;

interface VideoRepositoryInterface
{
    public function createVideo(array $data);
    public function getVideosByCourse($courseId);
    public function getVideoById($id);
    public function updateVideo($id, array $data);
    public function deleteVideo($id);
}