<?php

namespace App\Http\Controllers;



use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    
    public function assignRole(Request $request, $userId)
    {
        
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

       
        $user = User::findOrFail($userId);

       
        $role = Role::findByName($request->role);

        $user->assignRole($role);

        return response()->json([
            'message' => 'Role assigned successfully',
            'user' => $user,
            'role' => $role,
        ]);
    }
     
    
public function removeRole(Request $request, $userId)
{
   
    $request->validate([
        'role' => 'required|string|exists:roles,name',
    ]);
    $user = User::findOrFail($userId); 
    $user->removeRole($request->role);

    return response()->json([
        'message' => 'Role removed successfully',
        'user' => $user,
    ]);
}
}