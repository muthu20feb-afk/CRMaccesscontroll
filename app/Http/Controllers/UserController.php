<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function userRoleIndex()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('roles.user_roles', compact('users', 'roles'));
    }
    public function assignUserRole(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $user->roles()->sync($request->roles ?? []);
        return back()->with('success', 'User roles updated successfully');
    }
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'roles' => 'required|array'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $user->roles()->sync($validated['roles']);

        return back()->with('success', 'User created successfully with roles.');
    }
    

    public function assignRoles(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'password' => 'nullable|min:6',
        'roles' => 'array',
        ]);
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }
        $user->save();
        $user->roles()->sync($request->roles ?? []);

        return redirect()->back()->with('success', 'User updated successfully!');
    }
    public function getUsers(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with('roles')->select('users.*');

            return DataTables::of($users)
                ->addIndexColumn()
                ->editColumn('name', function ($user) {
                    $initial = strtoupper(substr($user->name, 0, 1));
                    return '
                        <div class="flex items-center space-x-2">
                            <div class="h-8 w-8 rounded-full bg-indigo-500 text-white flex items-center justify-center font-semibold">
                                ' . $initial . '
                            </div>
                            <span class="font-medium text-gray-800">' . e(ucfirst($user->name)) . '</span>
                        </div>
                    ';
                })

                ->addColumn('roles', function ($user) {
                    if ($user->roles->isEmpty()) {
                        return '<span class="text-gray-400 italic text-xs">No Roles</span>';
                    }

                    $colors = [
                        'bg-red-100 text-red-700',
                        'bg-green-100 text-green-700',
                        'bg-blue-100 text-blue-700',
                        'bg-yellow-100 text-yellow-700',
                        'bg-purple-100 text-purple-700',
                    ];

                    $badges = '';
                    foreach ($user->roles as $role) {
                        $color = $colors[$role->id % count($colors)];

                        $badges .= '<span class="inline-block px-2 py-1 rounded-lg text-xs font-semibold mr-1 ' 
                                . $color . '">' . e(ucfirst($role->name)) . '</span>';
                    }

                    return $badges;
                })


                ->addColumn('action', function ($user) {
                    return '
                        <a href="javascript:void(0)" 
                        onclick="openEditModal(' . $user->id . ', \'' . e($user->name) . '\', \'' . e($user->email) . '\', ' . $user->roles->pluck('id') . ')" 
                        class="font-medium text-black-600 ">
                            Edit
                        </a>
                    ';
                })

                ->rawColumns(['name', 'roles', 'action'])
                ->make(true);
        }
    }
}
