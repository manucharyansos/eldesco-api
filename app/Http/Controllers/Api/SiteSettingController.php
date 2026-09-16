<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        return response()->json(
            SiteSetting::query()->orderBy('group')->orderBy('key')->get()
        );
    }

    public function publicIndex()
    {
        return response()->json(
            SiteSetting::query()->get()->mapWithKeys(fn (SiteSetting $setting) => [
                $setting->key => $setting->value,
            ])
        );
    }

    public function upsert(Request $request, string $key)
    {
        $validated = $request->validate([
            'group' => ['nullable', 'string', 'max:80'],
            'value' => ['nullable', 'array'],
        ]);

        $setting = SiteSetting::updateOrCreate(
            ['key' => $key],
            [
                'group' => $validated['group'] ?? 'general',
                'value' => $validated['value'] ?? null,
            ]
        );

        return response()->json($setting);
    }

    public function destroy(string $key)
    {
        SiteSetting::where('key', $key)->delete();
        return response()->json(null, 204);
    }
}
