<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Interfaces\TagRepositoryInterface;
use Illuminate\Support\Str;

class TagRepository implements TagRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        
    }

    public function index()
    {
        return Tag::all();
    }

    public function getById($id)
    {
        return Tag::find($id);
    }

    public function store(array $data)
    {
        $data['slug'] = Str::slug($data['name']);
        return Tag::create($data);
    }

    public function update(array $data, $id)
    {
        $tag = Tag::find($id);
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $tag->update($data);
        return $tag;
    }
    
    public function delete($id)
    {
        $tag = Tag::find($id);
        $tag->delete();
        return $tag;
    }
}