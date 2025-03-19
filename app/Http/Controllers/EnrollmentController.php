<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
  
    // Un élève s'inscrit à un cours
    public function enroll(Request $request, $courseId)
    {
        $user = Auth::user();

        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->first();

        if ($existingEnrollment) {
            return response()->json(['message' => 'You are already enrolled in this course'], 400);
        }

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $courseId,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Enrollment successful', 'enrollment' => $enrollment], 201);
    }

    public function listEnrollments($courseId)
    {
        $enrollments = Enrollment::where('course_id', $courseId)->with('user')->get();
        return response()->json(['enrollments' => $enrollments]);
    }

    public function updateEnrollmentStatus(Request $request, $enrollmentId)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
        ]);

        $enrollment = Enrollment::findOrFail($enrollmentId);
        $enrollment->status = $request->status;
        $enrollment->save();

        return response()->json(['message' => 'Enrollment status updated', 'enrollment' => $enrollment]);
    }

    public function deleteEnrollment($enrollmentId)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);
        $enrollment->delete();

        return response()->json(['message' => 'Enrollment deleted']);
    }
}
