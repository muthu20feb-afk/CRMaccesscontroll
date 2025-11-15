<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('settings.index', compact('setting'));
    }

    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240', 
        ], [
            'file_path.max' => 'The file must be smaller than 10MB.',
        ]);

        $setting = Setting::first() ?? new Setting();

        $oldPath = $setting->file_path;

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');

            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            Storage::disk('public')->makeDirectory('uploads/settings');

            $file = $request->file('file_path');

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        $fileName = $originalName . '.' . $extension;

    
        $path = $file->storeAs('uploads/settings', $fileName, 'public');
        } else {
            $path = $oldPath;
        }

        $setting->fill(['name' => $request->name,'file_path' => $path,])->save();

        return redirect()->back()->with('success', 'Setting saved successfully.');
    }

}
