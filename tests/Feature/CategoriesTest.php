<?php


namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

describe('Category API Tests', function () {
    test('can see categories list', function () {
        Category::create(['name' => 'TestCategory1', 'description' => 'Test Description 1']);
        Category::create(['name' => 'TestCategory2', 'description' => 'Test Description 2']);
        
        $response = $this->get('/api/v1/categories');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['name', 'description']
            ]
        ]);
    });

    test('can create a category', function () {
        $categoryData = [
            'name' => 'Web Development',
            'description' => 'All about web development'
        ];

        $response = $this->post('/api/v1/categories', $categoryData);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', ['name' => 'Web Development']);
    });

    test('can retrieve a specific category', function () {
        $category = Category::create(['name' => 'Mobile Development', 'description' => 'All about mobile apps']);
        
        $response = $this->get('/api/v1/categories/' . $category->id);
        
        $response->assertStatus(200);
        $response->assertJson([
            'data' => ['name' => 'Mobile Development']
        ]);
    });

    test('can update a category', function () {
        $category = Category::create(['name' => 'Game Development', 'description' => 'Game dev courses']);
        
        $updatedData = [
            'name' => 'Game Design',
            'description' => 'Game design and development'
        ];
        
        $response = $this->put('/api/v1/categories/' . $category->id, $updatedData);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', ['name' => 'Game Design']);
    });

    test('can delete a category', function () {
        $category = Category::create(['name' => 'DevOps', 'description' => 'DevOps courses']);
        
        $response = $this->delete('/api/v1/categories/' . $category->id);
        
        $response->assertStatus(204);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    });

    test('can get subcategories', function () {
        $parentCategory = Category::create(['name' => 'Programming', 'description' => 'Programming courses']);
        
        Category::create([
            'name' => 'Python',
            'description' => 'Python courses',
            'parent_id' => $parentCategory->id
        ]);
        
        Category::create([
            'name' => 'JavaScript',
            'description' => 'JavaScript courses',
            'parent_id' => $parentCategory->id
        ]);
        
        $response = $this->get('/api/v1/categories/' . $parentCategory->id . '/subcategories');
        
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    });

    test('returns 404 for non-existent category', function () {
        $response = $this->get('/api/v1/categories/999');
        
        $response->assertStatus(404);
    });

    test('validates required fields when creating a category', function () {
        $response = $this->post('/api/v1/categories', []);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    });

    test('can create subcategory', function () {
        $parentCategory = Category::create(['name' => 'Programming', 'description' => 'Programming courses']);
        
        $categoryData = [
            'name' => 'Ruby',
            'description' => 'Ruby courses',
            'parent_id' => $parentCategory->id
        ];

        $response = $this->post('/api/v1/categories', $categoryData);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', [
            'name' => 'Ruby',
            'parent_id' => $parentCategory->id
        ]);
    });
});