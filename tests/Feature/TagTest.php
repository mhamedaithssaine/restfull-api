<?php
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(RefreshDatabase::class);

describe('Tag API Tests', function () {
    test('can see tags list', function () {
        Tag::create(['name' => 'TestTag1']);
        Tag::create(['name' => 'TestTag2']);
        
        $response = $this->get('/api/v1/tags');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['name', 'slug']
            ]
        ]);
    });

    test('can create a tag', function () {
        $tagData = ['name' => 'Laravel'];

        $response = $this->post('/api/v1/tags', $tagData);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('tags', ['name' => 'Laravel']);
    });

    test('can retrieve a specific tag', function () {
        $tag = Tag::create(['name' => 'PHP']);
        
        $response = $this->get('/api/v1/tags/' . $tag->id);
        
        $response->assertStatus(200);
        $response->assertJson([
            'data' => ['name' => 'PHP']
        ]);
    });

    test('can update a tag', function () {
        $tag = Tag::create(['name' => 'JavaScript']);
        
        $updatedData = ['name' => 'TypeScript'];
        
        $response = $this->put('/api/v1/tags/' . $tag->id, $updatedData);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('tags', ['name' => 'TypeScript']);
    });

    test('can delete a tag', function () {
        $tag = Tag::create(['name' => 'Framework']);
        
        $response = $this->delete('/api/v1/tags/' . $tag->id);
        
        $response->assertStatus(200);
        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    });

    test('returns 404 for non-existent tag', function () {
        $response = $this->get('/api/v1/tags/999');
        
        $response->assertStatus(404);
    });

    test('can create multiple tags with massStore', function () {
        $tagsData = [
            'tags' => [
                ['name' => 'Laravel'],
                ['name' => 'PHP'],
                ['name' => 'Backend']
            ]
        ];
        
        $response = $this->post('/api/v1/tags/mass', $tagsData);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('tags', ['name' => 'Laravel']);
        $this->assertDatabaseHas('tags', ['name' => 'PHP']);
        $this->assertDatabaseHas('tags', ['name' => 'Backend']);
    });

    test('validates required fields when creating a tag', function () {
        $response = $this->post('/api/v1/tags', []);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    });
});
