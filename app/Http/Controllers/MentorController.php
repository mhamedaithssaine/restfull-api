<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class MentorController extends Controller
{
    public function getMentorCourses($id)
    {
        $courses = Course::where('user_id', $id)->get();
        return response()->json(['courses' => $courses]);
    }


    public function getMentorStudents($id)
    {

        $courses = Course::where('user_id', $id)->pluck('id');


        $enrollments = Enrollment::whereIn('course_id', $courses)->with('user')->get();


        $students = $enrollments->pluck('user')->unique();

        return response()->json(['students' => $students]);
    }


    public function getMentorPerformance($id)
    {

        $courses = Course::where('user_id', $id)->pluck('id');


        $totalCourses = $courses->count();
        $totalEnrollments = Enrollment::whereIn('course_id', $courses)->count();
        $totalStudents = Enrollment::whereIn('course_id', $courses)->distinct('user_id')->count('user_id');

        return response()->json([
            'total_courses' => $totalCourses,
            'total_enrollments' => $totalEnrollments,
            'total_students' => $totalStudents,
        ]);
    }
}
