<?php

namespace App\Repositories;

use App\Models\Category;
use App\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        
    }

    public function index(){
        return Category::whereNull('parent_id')->with('children')->get();
    }

    public function getById($id){
       return Category::with('children')->find($id);
    }

    public function store(array $data){
       return Category::create($data);
    }

    public function update(array $data,$id){
        $category = Category::find($id);
        $category->update($data);
        return $category;
    }
    
    public function delete($id){
        $category = Category::find($id);
        $category->delete();
        return $category;
    }
    
    // pour recupere sous-categories
    public function getSubCategories($id)
    {
        return Category::where('parent_id', $id)->get();
    }
}
