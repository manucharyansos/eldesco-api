<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Setting::query()->orderBy('group')->orderBy('key')->get()
        );
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*.key' => ['required', 'string', 'max:160'],
            'settings.*.group' => ['nullable', 'string', 'max:80'],
            'settings.*.value' => ['nullable'],
            'settings.*.is_public' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['settings'] as $row) {
                Setting::updateOrCreate(
                    ['key' => $row['key']],
                    [
                        'group' => $row['group'] ?? 'general',
                        'value' => $row['value'] ?? null,
                        'is_public' => $row['is_public'] ?? true,
                    ]
                );
            }
        });

        return $this->index();
    }
}
