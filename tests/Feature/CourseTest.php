<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

describe('Course API Tests', function () {
    test('can see courses list', function () {
        $category = Category::create(['name' => 'TestCategory', 'description' => 'Test Description']);
        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test Description',
            'content' => 'Test Content',
            'duration' => 60,
            'level' => 'beginner',
            'category_id' => $category->id
        ]);
        
        $response = $this->get('/api/v1/courses');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['title', 'description']
            ]
        ]);
    });

    test('can create a course', function () {
        $category = Category::create(['name' => 'TestCategory', 'description' => 'Test Description']);
        
        $courseData = [
            'title' => 'New Course',
            'description' => 'New Course Description',
            'content' => 'Course Content',
            'duration' => 120,
            'level' => 'intermediate',
            'category_id' => $category->id
        ];

        $response = $this->post('/api/v1/courses', $courseData);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('courses', ['title' => 'New Course']);
    });

    test('can retrieve a specific course', function () {
        $category = Category::create(['name' => 'TestCategory', 'description' => 'Test Description']);
        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test Description',
            'content' => 'Test Content',
            'duration' => 60,
            'level' => 'beginner',
            'category_id' => $category->id
        ]);
        
        $response = $this->get('/api/v1/courses/' . $course->id);
        
        $response->assertStatus(200);
        $response->assertJson([
            'data' => ['title' => 'Test Course']
        ]);
    });

    test('can update a course', function () {
        $category = Category::create(['name' => 'TestCategory', 'description' => 'Test Description']);
        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test Description',
            'content' => 'Test Content',
            'duration' => 60,
            'level' => 'beginner',
            'category_id' => $category->id
        ]);
        
        $updatedData = [
            'title' => 'Updated Course',
            'description' => 'Updated Description',
            'category_id' => $category->id
        ];
        
        $response = $this->put('/api/v1/courses/' . $course->id, $updatedData);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('courses', ['title' => 'Updated Course']);
    });

    test('can delete a course', function () {
        $category = Category::create(['name' => 'TestCategory', 'description' => 'Test Description']);
        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test Description',
            'content' => 'Test Content',
            'duration' => 60,
            'level' => 'beginner',
            'category_id' => $category->id
        ]);
        
        $response = $this->delete('/api/v1/courses/' . $course->id);
        
        $response->assertStatus(200);
        $this->assertSoftDeleted('courses', ['id' => $course->id]);
    });

    test('can get courses by category', function () {
        $category = Category::create(['name' => 'TestCategory', 'description' => 'Test Description']);
        Course::create([
            'title' => 'Test Course 1',
            'description' => 'Test Description 1',
            'content' => 'Test Content 1',
            'duration' => 60,
            'level' => 'beginner',
            'category_id' => $category->id
        ]);
        
        Course::create([
            'title' => 'Test Course 2',
            'description' => 'Test Description 2',
            'content' => 'Test Content 2',
            'duration' => 90,
            'level' => 'intermediate',
            'category_id' => $category->id
        ]);
        
        $response = $this->get('/api/v1/courses/category/' . $category->id);
        
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    });

    test('returns 404 for non-existent course', function () {
        $response = $this->get('/api/v1/courses/999');
        
        $response->assertStatus(404);
    });
});