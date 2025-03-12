<?php

namespace App\Repositories;

use App\Models\Course;
use App\Interfaces\CourseRepositoryInterface;

class CourseRepository implements CourseRepositoryInterface
{
    public function index()
    {
        return Course::with(['category', 'tags'])->get();
    }

    public function getById($id)
    {
        return Course::with(['category', 'tags'])->find($id);
    }

    public function store(array $data)
    {
        $course = Course::create($data);
        
        if (isset($data['tags'])) {
            $course->tags()->attach($data['tags']);
        }
        
        return $course;
    }

    public function update(array $data, $id)
    {
        $course = Course::find($id);
        
        if (!$course) {
            return null;
        }

        $course->update($data);
        
        if (isset($data['tags'])) {
            $course->tags()->sync($data['tags']);
        }
        
        return $course;
    }

    public function delete($id)
    {
        $course = Course::find($id);
        
        if (!$course) {
            return null;
        }

        $course->tags()->detach();
        $course->delete();
        
        return $course;
    }

    public function getByCategory($categoryId)
    {
        return Course::where('category_id', $categoryId)
            ->with(['tags'])
            ->get();
    }
}