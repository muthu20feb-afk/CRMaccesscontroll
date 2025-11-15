@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded shadow">

    <form action="{{ route('settings.save') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Company Name</label>
            <input type="text" name="name" value="{{ old('name', $setting->name ?? '') }}" class="border p-2 w-full rounded" required>
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Logo</label>
            @if(!empty($setting->file_path))
                <div class="mb-4">
                    <img 
                        src="{{ asset('storage/'.$setting->file_path) }}" 
                        alt="Uploaded Image"
                        class="w-full max-w-sm h-auto rounded shadow-md object-contain border"
                    >
                </div>
            @endif
            <input type="file" name="file_path" class="border p-2 w-full rounded">
            @error('file_path')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            {{ isset($setting) ? 'Update' : 'Save' }}
        </button>
    </form>
</div>
@endsection
