<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;

class SearchController extends Controller
{
    
    /**
     * Search course by titre or content 
     */


     public function searchCourse( Request $request){
     
        $searchQuery = $request->query('search');


       
        $query = Course::query();
        if( $searchQuery){
            $query->where('title','like','%'.$searchQuery.'%')
            ->orWhere('description','like','%'.$searchQuery.'%') ;
        }
       
        $courses=$query->get();
        return  ApiResponseClass::sendResponse($courses, 'Courses que vous avez chercher :');
    }

public function filterCoursesByCategoryLevel( Request $request){   
    $categoryId = $request->query('category');
    $level = $request->query('level');

    $query = Course::query();

    if ($categoryId) {
        $query->where('category_id', $categoryId);
    }

    if ($level) {
        $query->where('level', $level);
    }

    $courses = $query->get();

    return  ApiResponseClass::sendResponse($courses, 'Courses que vous avez chercher :');

}
}

