@extends('layouts.app')
@section('title', 'Manage Permissions')

@section('content')
    <div class="flex justify-between items-center mb-6 overflow-x-auto bg-white rounded-lg shadow p-6 ">
        <h2 class="text-2xl font-bold text-gray-800">Edit Permissions</h2>
        <div class="space-x-3">
           
              <a href="{{ route('roles.index') }}" 
           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg shadow">
           Back
        </a>
        </div>
    </div>
<div class="flex justify-between items-center mb-6 overflow-x-auto bg-white rounded-lg shadow p-6 ">
    <h2 class="text-2xl font-bold text-gray-800 mb-6"></h2>



    <table class="min-w-full border border-gray-200 text-gray-700">
        <thead class="bg-gray-100 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left font-semibold">ID</th>
                <th class="px-4 py-3 text-left font-semibold">Permission Name</th>
                <th class="px-4 py-3 text-center font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permissions as $perm)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $perm->id }}</td>
                    <td class="px-4 py-2">
                        <form method="POST" action="{{ route('permissions.update', $perm->id) }}" class="flex items-center space-x-2">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $perm->name }}"
                                   class="border rounded px-2 py-1 w-100 focus:ring focus:ring-indigo-300">
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                                Save
                            </button>
                        </form>
                    </td>
                    <td class="text-center px-4 py-2">
                    <button type="button"onclick="openDeleteModal('{{ $perm->id }}', '{{ $perm->name }}')"class="text-red-600 hover:text-red-800 font-semibold">
                        Delete
                    </button>
                    <form id="deleteForm-{{ $perm->id }}" method="POST" action="{{ url('permissions/' . $perm->id) }}">
                        @csrf
                        @method('DELETE')
                    </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- Delete Confirmation Modal -->
<div id="popup-modal" tabindex="-1"
    class="hidden fixed inset-0 z-50 flex justify-center items-center bg-gray-900 bg-opacity-50">
    <div class="relative p-4 w-full max-w-md">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Close button -->
            <button type="button"
                onclick="closeDeleteModal()"
                class="absolute top-3 right-2.5 text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white">
                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1l6 6m0 0 6 6M7 7l6-6M7 7 1 13" />
                </svg>
            </button>

            <div class="p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <h3 id="deleteMessage"
                    class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                    Are you sure you want to delete this permission?
                </h3>

                <div class="flex justify-center space-x-3">
                    <button id="confirmDeleteBtn" type="button"
                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Yes, delete it
                    </button>
                    <button type="button" onclick="closeDeleteModal()"
                        class="py-2.5 px-5 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let currentDeleteFormId = null;

    // Open modal and set message dynamically
    function openDeleteModal(permId, permName) {
        currentDeleteFormId = 'deleteForm-' + permId;
        document.getElementById('deleteMessage').textContent =
            `Are you sure you want to delete the permission "${permName}"?`;
        document.getElementById('popup-modal').classList.remove('hidden');
    }

    // Close modal
    function closeDeleteModal() {
        document.getElementById('popup-modal').classList.add('hidden');
        currentDeleteFormId = null;
    }

    // Confirm deletion
    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (currentDeleteFormId) {
            document.getElementById(currentDeleteFormId).submit();
        }
    });
</script>

@endsection
