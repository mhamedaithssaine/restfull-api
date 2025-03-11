<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Interfaces\CategoryRepositoryInterface;

class CategoryController extends Controller
{

    private CategoryRepositoryInterface $categoryRepositoryInterface;

    public function __construct(CategoryRepositoryInterface $categoryRepositoryInterface)
    {
        $this->categoryRepositoryInterface = $categoryRepositoryInterface;
    }
    
    /**
     * Display a listing of the resource.
     */
    
    public function index()

    {
        $data = $this->categoryRepositoryInterface->index();
        return ApiResponseClass::sendResponse(CategoryResource::collection($data),'',200);
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
    public function store(StoreCategoryRequest $request)
    {
        $details =[
            'name' => $request->name,
            'description' => $request->description,
            'parent_id'=>$request->parent_id,
        ];
        DB::beginTransaction();

        try{
            $category = $this->categoryRepositoryInterface->store($details);
            DB::commit();
            return ApiResponseClass::sendResponse(new CategoryResource($category),'Category Create Successful',201);
        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = $this->categoryRepositoryInterface->getById($id);
        return ApiResponseClass::sendResponse(new CategoryResource($category),'',200);
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
    public function update(UpdateCategoryRequest $request, string $id)
    {
        $updateDetails =[
            'name' => $request->name,
            'description' => $request->description,
            'parent_id'=>$request->parent_id,
        ];
        DB::beginTransaction();
        try{
             $category = $this->categoryRepositoryInterface->update($updateDetails,$id);

             DB::commit();
             return ApiResponseClass::sendResponse('Product Update Successful','',201);

        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->categoryRepositoryInterface->delete($id);

        return ApiResponseClass::sendResponse('Product Delete Successful','',204);
    }

    /**
     * Get subcategories of a category
     */
    public function getSubCategories(string $id)
    {
        $subCategories = $this->categoryRepositoryInterface->getSubCategories($id);
        return ApiResponseClass::sendResponse(CategoryResource::collection($subCategories), '', 200);
    }
}
