<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
  
    public function index()
    {
        $permissions = Permission::all();
        return response()->json(['permissions' => $permissions]);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        $permission = Permission::create(['name' => $request->name]);

        return response()->json([
            'message' => 'Permission created successfully',
            'permission' => $permission,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $id,
        ]);

        $permission = Permission::findOrFail($id);
        $permission->name = $request->name;
        $permission->save();

        return response()->json([
            'message' => 'Permission updated successfully',
            'permission' => $permission,
        ]);
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully']);
    }
}