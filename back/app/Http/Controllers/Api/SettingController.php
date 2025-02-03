<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting; // Assuming you have a Setting model for storing settings

class SettingController extends Controller
{
    // Get all settings
    public function index()
    {
        // Retrieve all settings, assuming they are stored in a database model
        $settings = Setting::all();
        return response()->json($settings);
    }

    // Get a specific setting by key
    public function show($key)
    {
        // Retrieve the setting by key, you may want to store it as key-value pairs
        $setting = Setting::where('key', $key)->first();

        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], 404);
        }

        return response()->json($setting);
    }

    // Update a setting
    public function update(Request $request, $key)
    {
        $setting = Setting::where('key', $key)->first();

        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], 404);
        }

        // Update the setting with new value
        $setting->value = $request->input('value');
        $setting->save();

        return response()->json(['message' => 'Setting updated successfully', 'setting' => $setting]);
    }

    // Store a new setting
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'key' => 'required|string|unique:settings,key',
            'value' => 'required|string',
        ]);

        // Store the setting
        $setting = Setting::create([
            'key' => $request->input('key'),
            'value' => $request->input('value'),
        ]);

        return response()->json(['message' => 'Setting created successfully', 'setting' => $setting], 201);
    }
}
