<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseCollection;
use App\Http\Requests\StoreCourseRequest;
use App\Interfaces\CourseRepositoryInterface;

class CourseController extends Controller
{
    private CourseRepositoryInterface $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $courses = $this->courseRepository->index();
            return ApiResponseClass::sendResponse(new CourseCollection($courses),'Courses retrieved successfully');
        } catch (\Exception $e) {
            return ApiResponseClass::throw($e, 'Failed to retrieve courses');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {

        try {
            DB::beginTransaction();

            $course = $this->courseRepository->store($request->validated());

            DB::commit();
            ApiResponseClass::sendResponse(new CourseResource($course), 'Course created successfully', 201);
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e, 'Failed to create course');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $course = $this->courseRepository->getById($id);

            if (!$course) {
                return response()->json(['message' => 'Course not found'], 404);
            }


            return ApiResponseClass::sendResponse(
                new CourseResource($course),
                'Course retrieved successfully'
            );
        } catch (\Exception $e) {
            return ApiResponseClass::throw($e, 'Failed to retrieve course');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $course = $this->courseRepository->update($request->all(), $id);

            if (!$course) {
                return response()->json(['message' => 'Course not found'], 404);
            }

            DB::commit();
            return ApiResponseClass::sendResponse($course, 'Course updated successfully');
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e, 'Failed to update course');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $course = $this->courseRepository->delete($id);

            if (!$course) {
                return response()->json(['message' => 'Course not found'], 404);
            }

            DB::commit();
            return ApiResponseClass::sendResponse($course, 'Course deleted successfully');
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e, 'Failed to delete course');
        }
    }

    /**
     * Get courses by category.
     */
    public function getByCategory($categoryId)
    {
        try {
            $courses = $this->courseRepository->getByCategory($categoryId);
            return ApiResponseClass::sendResponse($courses, 'Courses retrieved successfully');
        } catch (\Exception $e) {
            return ApiResponseClass::throw($e, 'Failed to retrieve courses');
        }
    }
}
