<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\EnrollmentRepository;

class EnrollmentController extends Controller
{
    protected $enrollmentRepository;

    public function __construct(EnrollmentRepository $enrollmentRepository)
    {
        $this->enrollmentRepository = $enrollmentRepository;
    }


    public function enroll(Request $request, $courseId)
    {
        $userId = auth()->id(); 
        $enrollment = $this->enrollmentRepository->enroll($courseId, $userId);

        if (!$enrollment) {
            return response()->json(['message' => 'User is already enrolled'], 400);
        }

        return response()->json(['message' => 'Enrollment successful', 'enrollment' => $enrollment], 201);
    }


    public function getEnrollmentsByCourse($courseId)
    {
        $enrollments = $this->enrollmentRepository->getEnrollmentsByCourse($courseId);
        return response()->json(['enrollments' => $enrollments]);
    }


    public function updateEnrollmentStatus(Request $request, $enrollmentId)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
        ]);

        $enrollment = $this->enrollmentRepository->updateEnrollmentStatus($enrollmentId, $request->status);
        return response()->json(['message' => 'Enrollment status updated', 'enrollment' => $enrollment]);
    }


    public function deleteEnrollment($enrollmentId)
    {
        $this->enrollmentRepository->deleteEnrollment($enrollmentId);
        return response()->json(['message' => 'Enrollment deleted']);
    }
}