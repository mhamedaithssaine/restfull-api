<?php
namespace App\Repositories;

use App\Models\Enrollment;
use App\Interfaces\EnrollmentRepositoryInterface;

class EnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function enroll($courseId, $userId)
    {

        $existingEnrollment = Enrollment::where('course_id', $courseId)
            ->where('user_id', $userId)
            ->first();

        if ($existingEnrollment) {
            return null; 
        }


        return Enrollment::create([
            'course_id' => $courseId,
            'user_id' => $userId,
            'status' => 'pending', 

        ]);
    }

    public function getEnrollmentsByCourse($courseId)
    {
        return Enrollment::where('course_id', $courseId)->get();
    }

    public function updateEnrollmentStatus($enrollmentId, $status)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);
        $enrollment->status = $status;
        $enrollment->save();
        return $enrollment;
    }

    public function deleteEnrollment($enrollmentId)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);
        $enrollment->delete();
        return true;
    }
}