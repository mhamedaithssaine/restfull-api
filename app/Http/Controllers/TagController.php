<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\StoreMassTagRequest;
use App\Interfaces\TagRepositoryInterface;

class TagController extends Controller
{
    private TagRepositoryInterface $tagRepositoryInterface;

     public function __construct(TagRepositoryInterface $tagRepositoryInterface)
     {
        $this->tagRepositoryInterface = $tagRepositoryInterface;
     }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $tags = $this->tagRepositoryInterface->index();
            return ApiResponseClass::sendResponse($tags, 'Tags retrieved successfully');
        } catch (\Exception $e) {
            ApiResponseClass::throw($e, 'Error retrieving tags');
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
    public function store(StoreTagRequest $request)
    {
        try {
            $validated = $request->validated();
            $tag = $this->tagRepositoryInterface->store($validated);
            return ApiResponseClass::sendResponse($tag, 'Tag created successfully', 201);
        } catch (\Exception $e) {
            ApiResponseClass::throw($e, 'Failed to create tag');
        }
    }


    /**
     * Store multiple tags at once.
     */
    public function massStore(StoreMassTagRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $tags = [];
            foreach ($request->tags as $tagData) {
                $tags[] = $this->tagRepositoryInterface->store($tagData);
            }
            
            DB::commit();
            return ApiResponseClass::sendResponse($tags, 'Tags created successfully', 201);
        } catch (\Exception $e) {
            ApiResponseClass::rollback($e, 'Failed to create tags');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        try {
            $tag = $this->tagRepositoryInterface->getById($id);
            
            if (!$tag) {
                return response()->json(['message' => 'Tag not found'], 404);
            }

            return ApiResponseClass::sendResponse($tag, 'Tag retrieved successfully');
        } catch (\Exception $e) {
            ApiResponseClass::throw($e, 'Failed to retrieve tag');
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
    public function update(StoreTagRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $tag = $this->tagRepositoryInterface->update($validated, $id);
            
            if (!$tag) {
                return response()->json(['message' => 'Tag not found'], 404);
            }

            return ApiResponseClass::sendResponse($tag, 'Tag updated successfully');
        } catch (\Exception $e) {
            ApiResponseClass::throw($e, 'Failed to update tag');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $tag = $this->tagRepositoryInterface->delete($id);
            
            if (!$tag) {
                return response()->json(['message' => 'Tag not found'], 404);
            }

            return ApiResponseClass::sendResponse($tag, 'Tag deleted successfully');
        } catch (\Exception $e) {
            ApiResponseClass::throw($e, 'Failed to delete tag');
        }
    }
}
