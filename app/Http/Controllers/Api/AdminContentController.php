<?php

namespace App\Http\Controllers\Api;

use App\Models\News;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use Illuminate\Http\JsonResponse;

class AdminContentController extends Controller
{
    public function services(): JsonResponse
    {
        return response()->json(Service::orderBy('order_index')->get());
    }

    public function service(int $id): JsonResponse
    {
        return response()->json(Service::findOrFail($id));
    }

    public function projects(): JsonResponse
    {
        return response()->json(Project::orderBy('order_index')->get());
    }

    public function project(int $id): JsonResponse
    {
        return response()->json(Project::findOrFail($id));
    }

    public function team(): JsonResponse
    {
        return response()->json(TeamMember::orderBy('order_index')->get());
    }

    public function teamMember(int $id): JsonResponse
    {
        return response()->json(TeamMember::findOrFail($id));
    }

    public function news(): JsonResponse
    {
        return response()->json(News::orderByDesc('created_at')->get());
    }

    public function newsItem(int $id): JsonResponse
    {
        return response()->json(News::findOrFail($id));
    }
}
