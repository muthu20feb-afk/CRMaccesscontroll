@extends('layouts.app')
@section('title', 'Roles & Permissions')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="flex justify-between items-center mb-6 overflow-x-auto bg-white rounded-lg shadow p-6 ">
        <h2 class="text-2xl font-bold text-gray-800">Roles & Permissions</h2>
        <div class="space-x-3">
            <button onclick="openRoleModal()" 
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow">
                Add Role
            </button>
            <button onclick="openPermissionModal()" 
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
                Add Permission
            </button>
              <a href="{{ route('permissions.manage') }}" 
           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg shadow">
           Edit Permissions
        </a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow overflow-x-auto">
        <table id="roleTable" class="min-w-full border border-gray-200 text-gray-700">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Role</th>
                    <th class="px-4 py-3 text-left font-semibold">Permissions</th>
                    <th class="px-4 py-3 text-center font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

{{-- CREATE ROLE MODAL --}}
<div id="roleModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 relative">
        <!-- Close Button -->
        <button type="button" onclick="closeRoleModal()"
            class="absolute top-4 right-4 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg w-8 h-8 flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close modal</span>
        </button>

        <h3 class="text-lg font-semibold text-gray-800 mb-4">Create New Role</h3>

        <form id="createRoleForm">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Role Name</label>
                    <input type="text" name="name"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Role Name" required="">
            </div>

            <!-- Permissions Toggle -->
            <div x-data="{ open: false }" class="mb-4">
                <button type="button" @click="open = !open" 
                    class="w-full flex justify-between items-center px-4 py-2 bg-gray-100 rounded-lg focus:outline-none">
                    <span class="font-medium text-gray-700">Assign Permissions</span>
                    <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transform transition-transform"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" x-transition class="mt-2 max-h-48 overflow-y-auto border rounded-lg p-3">
                    @foreach($permissions as $perm)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="perm_{{ $perm->id }}">
                            <label for="perm_{{ $perm->id }}">{{ $perm->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeRoleModal()" 
                    class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Create</button>
            </div>
        </form>
    </div>
</div>



{{-- CREATE PERMISSION MODAL --}}
<div id="permissionModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 relative">
        <!-- Close Button -->
        <button type="button" onclick="closePermissionModal()"
            class="absolute top-4 right-4 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg w-8 h-8 flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close modal</span>
        </button>

        <h3 class="text-lg font-semibold text-gray-800 mb-4">Create New Permission</h3>
        <form id="createPermissionForm">
            @csrf
             <input type="text" name="name"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Permission" required="">
            <div class="flex justify-end space-x-3 mt-2">
                <button type="button" onclick="closePermissionModal()" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg">Create</button>
            </div>
        </form>
    </div>
</div>


{{-- ASSIGN PERMISSIONS MODAL --}}
<div id="assignModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Assign Permissions</h3>
        <form id="assignForm">
            @csrf
            <input type="hidden" name="role_id" id="assignRoleId">
            <div x-data="{ open: false }" class="mb-4">
                <button type="button" @click="open = !open" 
                    class="w-full flex justify-between items-center px-4 py-2 bg-gray-100 rounded-lg focus:outline-none">
                    <span class="font-medium text-gray-700">Assign Permissions</span>
                    <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transform transition-transform"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" x-transition class="mt-2 max-h-48 overflow-y-auto border rounded-lg p-3">
                    @foreach($permissions as $perm)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="perm_{{ $perm->id }}">
                            <label for="perm_{{ $perm->id }}">{{ $perm->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeAssignModal()" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save</button>
            </div>
        </form>
    </div>
</div>
<!-- EDIT ROLE MODAL -->
<div id="editRoleModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">
  <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 relative">
    <button type="button" onclick="closeEditRoleModal()" class="absolute top-4 right-4 ...">✕</button>
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Edit Role</h3>
    <form id="editRoleForm">
      @csrf
      @method('PUT')
      <input type="hidden" name="role_id" id="editRoleId">
      <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-1">Role Name</label>
        <input type="text" name="name" id="editRoleName"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Role Name" required="">
            
      </div>

      {{-- <div x-data="{ open: false }" class="mb-4">
        <button type="button" @click="open = !open" class="w-full ...">Assign Permissions</button>
        <div x-show="open" x-transition class="mt-2 max-h-48 overflow-y-auto border rounded-lg p-3">
          @foreach($permissions as $perm)
            <div class="flex items-center mb-2">
              <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="edit_perm_{{ $perm->id }}">
              <label for="edit_perm_{{ $perm->id }}">{{ $perm->name }}</label>
            </div>
          @endforeach
        </div>
      </div> --}}

      <div class="flex justify-end space-x-3">
        <button type="button" onclick="closeEditRoleModal()" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Save</button>
      </div>
    </form>
  </div>
</div>
<!-- EDIT PERMISSION MODAL -->
<div id="editPermissionModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">
  <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 relative">
    <button type="button" onclick="closeEditPermissionModal()" class="absolute top-4 right-4 ...">✕</button>
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Edit Permission</h3>
    <form id="editPermissionForm">
      @csrf
      @method('PUT')
      <input type="hidden" name="permission_id" id="editPermissionId">
      <div class="mb-4">
         <input type="text" name="name" id="editPermissionName"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
      </div>
      <div class="flex justify-end space-x-3">
        <button type="button" onclick="closeEditPermissionModal()" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg">Save</button>
      </div>
    </form>
  </div>
</div>
<!-- DELETE CONFIRM -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">
  <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 relative">
    <button type="button" onclick="closeDeleteModal()" class="absolute top-4 right-4 ...">✕</button>
    <h3 class="text-lg font-semibold mb-2">Confirm Delete</h3>
    <p id="deleteMessage" class="mb-4"></p>
    <div class="flex justify-end space-x-3">
      <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
      <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded-lg">Delete</button>
    </div>
  </div>
</div>

<script>
    const rolesAjaxUrl = "{{ route('roles.ajax') }}";
    const roleStoreUrl = "{{ route('roles.store') }}";
    const permissionStoreUrl = "{{ route('permissions.store') }}";
    const assignUrl = "{{ route('roles.assign', ':id') }}";
     const baseRoleUrl = "{{ url('roles') }}";
    const basePermissionUrl = "{{ url('permissions') }}";
     window.deleteUrls = {
        role: "{{ url('roles') }}",
        permission: "{{ url('permissions') }}"
    };
       window.appUrls = {
        roles: "{{ url('roles') }}",
        permissions: "{{ url('permissions') }}"
    };
</script>

@vite('resources/js/roles_permissions.js')
@endsection
