<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use Spatie\Permission\Models\Role;

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

    
    /**
     * filtrage course by category et level content 
     */
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


    /**
     * Rechercher des mentors par nom ou domaine d'expertise.
     */
public function searchMentore(Request $request)
{
  
    $query = $request->query('search');
    
   
    $mentorRole = Role::where('name', 'mentor')->first();
    
    if (!$mentorRole) {
        return response()->json([
            'success' => false,
            'message' => 'Le rôle mentor n\'existe pas dans le système'
        ], 404);
    }
    
    $mentors = User::role($mentorRole)
                  ->where(function($q) use ($query) {
                      $q->where('name', 'like', '%'.$query.'%');
                        // ->orWhere('expertise', 'like', '%'.$query.'%')
                  })
                  ->with('roles:name')
                  ->get();
    
    return response()->json([
        'success' => true,
        'mentors' => $mentors
    ]);
}


}

