<?php
namespace App\Interfaces;

interface EnrollmentRepositoryInterface
{
    public function enroll($courseId, $userId);
    public function getEnrollmentsByCourse($courseId);
    public function updateEnrollmentStatus($enrollmentId, $status);
    public function deleteEnrollment($enrollmentId);
}