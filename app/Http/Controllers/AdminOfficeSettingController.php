<?php

namespace App\Http\Controllers;

use App\Models\OfficeSetting;
use Illuminate\Http\Request;

class AdminOfficeSettingController extends Controller
{
    /**
     * Update office location
     */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:10|max:10000',
        ]);

        OfficeSetting::setValue('office_latitude', $request->latitude);
        OfficeSetting::setValue('office_longitude', $request->longitude);
        
        if ($request->has('radius')) {
            OfficeSetting::setValue('allowed_radius', $request->radius);
        }

        return response()->json([
            'success' => true,
            'message' => 'Office location updated successfully!',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius ?? OfficeSetting::getValue('allowed_radius', 500),
        ]);
    }

    /**
     * Get current office location
     */
    public function getLocation()
    {
        return response()->json([
            'latitude' => OfficeSetting::getValue('office_latitude', '-6.2088'),
            'longitude' => OfficeSetting::getValue('office_longitude', '106.8456'),
            'radius' => OfficeSetting::getValue('allowed_radius', '500'),
        ]);
    }
}
