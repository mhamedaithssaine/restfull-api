<?php 
namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    public function index()
    {
        $roles = Role::all();
        return response()->json(['roles' => $roles]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            // 'permissions' => 'nullable|array',
            // 'permissions.' => 'required',
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json(['message' => 'Role created successfully', 'role' => $role], 201);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|unique:roles,name,' . $id,
            // 'permissions' => 'nullable|array',
            // 'permissions.' => 'required',
        ]);

        $role = Role::findOrFail($id);

        if ($request->has('name')) {
            $role->name = $request->name;
            $role->save();
        }

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json(['message' => 'Role updated successfully', 'role' => $role]);
    }


    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }
}