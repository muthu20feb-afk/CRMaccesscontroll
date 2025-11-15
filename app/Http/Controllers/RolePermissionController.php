<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
class RolePermissionController extends Controller
{
   public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('roles.index', compact('roles', 'permissions'));
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => strtolower(str_replace(' ', '-', $request->name))
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return back()->with('success', 'Role created successfully');
    }

    public function storePermission(Request $request)
    {
        $request->validate(['name' => 'required|unique:permissions']);
        Permission::create([
            'name' => $request->name,
            'slug' => strtolower(str_replace(' ', '-', $request->name))
        ]);
        return  redirect()->back()->with('success', 'Permission created successfully');
    }

    public function assignPermission(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);
        $role->permissions()->sync($request->permissions);
        return back()->with('success', 'Permissions updated');
    }

    public function RolesandPermissionData()
    {
        $roles = Role::with('permissions')->select('roles.*');

        return DataTables::of($roles)
            ->addColumn('permissions', function ($role) {
                $colors = [
                    ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'darkBg' => 'dark:bg-red-900', 'darkText' => 'dark:text-red-300'],
                    ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'darkBg' => 'dark:bg-green-900', 'darkText' => 'dark:text-green-300'],
                    ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'darkBg' => 'dark:bg-blue-900', 'darkText' => 'dark:text-blue-300'],
                    ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'darkBg' => 'dark:bg-yellow-900', 'darkText' => 'dark:text-yellow-300'],
                    ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'darkBg' => 'dark:bg-purple-900', 'darkText' => 'dark:text-purple-300'],
                ];

                return $role->permissions->map(function ($p) use ($colors) {
                    $color = $colors[$p->id % count($colors)];
                    return '<span class="' 
                        . $color['bg'] . ' ' 
                        . $color['text'] . ' text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm ' 
                        . $color['darkBg'] . ' ' 
                        . $color['darkText'] . '">' 
                        . e(ucfirst($p->name)) . '</span>';
                })->implode(' ');
            })

            ->addColumn('action', function ($role) {
                $permissions = $role->permissions->pluck('id');
                $permJsArray = $permissions->implode(',');

                return '
                    <div class="relative inline-block text-left">
                        <!-- Three dot button -->
                        <button onclick="document.getElementById(\'modal-'.$role->id.'\').classList.remove(\'hidden\')"
                            class="text-gray-600 hover:text-gray-900 focus:outline-none">
                            <p class="text-lg font-medium text-gray-900 dark:text-white">...</p>
                        </button>

                        <!-- Modal -->
                        <div id="modal-'.$role->id.'" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                            <div class="bg-white rounded-lg shadow-lg w-80 p-5">
                                <div class="flex justify-between items-center border-b pb-2 mb-4">
                                    <span class="font-medium text-gray-700">Actions for: '.e($role->name).'</span>
                                    <button onclick="document.getElementById(\'modal-'.$role->id.'\').classList.add(\'hidden\')" class="text-gray-600 hover:text-gray-900">&times;</button>
                                </div>

                                <div class="space-y-3">
                                    <button onclick="openAssignModal('.$role->id.', ['.$permJsArray.'])"
                                        class="w-full text-left text-blue-600 hover:underline">
                                        Assign Permission
                                    </button>

                                    <button onclick="openEditRoleModal('.$role->id.')"
                                        class="w-full text-left text-yellow-600 hover:underline">
                                        Edit Role
                                    </button>

                                    <button onclick="openDeleteModal(\'role\','.$role->id.', \''.e($role->name).'\')"
                                        class="w-full text-left text-red-600 hover:underline">
                                        Delete
                                    </button>
                                </div>

                                <div class="mt-4 flex justify-end">
                                    <button onclick="document.getElementById(\'modal-'.$role->id.'\').classList.add(\'hidden\')"
                                        class="px-4 py-2 bg-gray-200 text-sm rounded-lg">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
            })

            ->rawColumns(['permissions', 'action'])
            ->make(true);
    }

    public function edit(Role $role)
    {
        $role->load('permissions');
        $permissionIds = $role->permissions->pluck('id');
        return response()->json(['role' => $role, 'permissions' => $permissionIds]);
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate(['name' => 'required|string|max:255', 'permissions' => 'array']);
        $role->update(['name' => $data['name']]);
        $role->permissions()->sync($data['permissions'] ?? []);
        return response()->json(['message' => 'Role updated']);
    }

    public function destroy(Role $role)
    {
        $role->permissions()->detach();
        $role->delete();
        return response()->json(['message' => 'Role deleted']);
    }

        public function editpermission(Permission $permission)
    {
        return response()->json(['permission' => $permission]);
    }

    public function updatepermission(Request $request, Permission $permission)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        $permission->update(['name' => $data['name']]);
        return redirect()->back()->with('success', 'Permission updated successfully!');
    }

    public function destroypermission(Permission $permission)
    {
    
        $permission->roles()->detach();
        $permission->delete();
        return redirect()->back()->with('success', 'Permission deleted successfully!');
    }

}
