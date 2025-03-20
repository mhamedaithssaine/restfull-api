<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PermissionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::middleware('auth:api')->prefix('v1')->group(function () {
    // Course Routes
    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
    Route::get('/courses/category/{categoryId}', [CourseController::class, 'getByCategory']);

    // Tag Routes
    Route::get('/tags', [TagController::class, 'index']);
    Route::post('/tags', [TagController::class, 'store']);
    Route::post('/tags/mass', [TagController::class, 'massStore']);
    Route::get('/tags/{id}', [TagController::class, 'show']);
    Route::put('/tags/{id}', [TagController::class, 'update']);
    Route::delete('/tags/{id}', [TagController::class, 'destroy']);

    // Category Routes
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
    Route::get('/categories/{id}/subcategories', [CategoryController::class, 'getSubCategories']);

    // Role Routes
    Route::get('/roles', [RoleController::class, 'index']); 
    Route::post('/roles', [RoleController::class, 'store']); 
    Route::put('/roles/{id}', [RoleController::class, 'update']); 
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']); 

    // Permission Routes
     Route::get('/permissions', [PermissionController::class, 'index']);
     Route::post('/permissions', [PermissionController::class, 'store']);
     Route::put('/permissions/{id}', [PermissionController::class, 'update']);
     Route::delete('/permissions/{id}', [PermissionController::class, 'destroy']);

    // User  (Gestion des roles) Routes
    Route::post('/users/{userId}/assign-role', [UserController::class, 'assignRole'])->middleware('role:admin'); 
    Route::post('/users/{userId}/remove-role', [UserController::class, 'removeRole'])->middleware('role:admin');

    // Enrolement (inscriptions aux cours) Routes
    Route::post('/courses/{id}/enroll', [EnrollmentController::class, 'enroll'])->middleware('role:student');
    Route::get('/courses/{id}/enrollments', [EnrollmentController::class, 'listEnrollments'])->middleware('role:student');
    Route::put('/enrollments/{id}', [EnrollmentController::class, 'updateEnrollmentStatus'])->middleware('role:student');
    Route::delete('/enrollments/{id}', [EnrollmentController::class, 'deleteEnrollment'])->middleware('role:student');
    
    // Statistuque Routes
    Route::get('/stats/courses', [StatsController::class, 'getCourseStats'])->middleware('role:admin');
    Route::get('/stats/categories', [StatsController::class, 'getCategoryStats'])->middleware('role:admin');
    Route::get('/stats/tags', [StatsController::class, 'getTagStats'])->middleware('role:admin');

    // Video Routes
    Route::post('/courses/{id}/videos', [VideoController::class, 'store'])->middleware('role:mentor');
    Route::get('/courses/{id}/videos', [VideoController::class, 'index'])->middleware('role:mentor');;
    Route::get('/videos/{id}', [VideoController::class, 'show'])->middleware('role:mentor');;
    Route::put('/videos/{id}', [VideoController::class, 'update'])->middleware('role:mentor');;
    Route::delete('/videos/{id}', [VideoController::class, 'destroy'])->middleware('role:mentor');;
});





// authentification 
Route::group(['middleware' => 'api','prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
    Route::post('/update-profile', [AuthController::class, 'updateProfile'])->middleware('auth:api')->name('updateProfile');
});