<?php

namespace App\Http\Controllers\Api;

use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $lang = $request->query('lang', 'en');
        $members = TeamMember::orderBy('order_index')->get();
        
        return response()->json(
            $members->map(fn($m) => $m->getTranslated($lang))
        );
    }

    public function show($id, Request $request)
    {
        $lang = $request->query('lang', 'en');
        $member = TeamMember::find($id);

        if (!$member) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json($member->getTranslated($lang));
    }

    // Admin endpoints
    public function store(Request $request)
    {
        $this->authorize('isAdmin');

        $validated = $request->validate([
            'name_hy' => 'required|string',
            'name_en' => 'required|string',
            'name_ru' => 'string|nullable',
            'position_hy' => 'string|nullable',
            'position_en' => 'string|nullable',
            'position_ru' => 'string|nullable',
            'image_url' => 'string|nullable',
            'email' => 'email|nullable',
            'order_index' => 'integer|nullable'
        ]);

        $member = TeamMember::create($validated);
        return response()->json($member, 201);
    }

    public function update($id, Request $request)
    {
        $this->authorize('isAdmin');

        $member = TeamMember::find($id);
        if (!$member) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'name_hy' => 'string|nullable',
            'name_en' => 'string|nullable',
            'name_ru' => 'string|nullable',
            'position_hy' => 'string|nullable',
            'position_en' => 'string|nullable',
            'position_ru' => 'string|nullable',
            'image_url' => 'string|nullable',
            'email' => 'email|nullable',
            'order_index' => 'integer|nullable'
        ]);

        $member->update($validated);
        return response()->json($member);
    }

    public function destroy($id)
    {
        $this->authorize('isAdmin');

        $member = TeamMember::find($id);
        if (!$member) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $member->delete();
        return response()->json(null, 204);
    }
}
