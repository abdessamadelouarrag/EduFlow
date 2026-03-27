<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TeacherAnalyticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function __construct(
        private readonly TeacherAnalyticsService $teacherAnalyticsService
    ) {
    }

    public function students(Request $request, int $courseId): JsonResponse
    {
        return response()->json([
            'students' => $this->teacherAnalyticsService->getCourseStudents($request->user()->id, $courseId),
        ]);
    }

    public function stats(Request $request): JsonResponse
    {
        return response()->json($this->teacherAnalyticsService->getCourseStats($request->user()->id));
    }

    public function groups(Request $request): JsonResponse
    {
        return response()->json([
            'groups' => $this->teacherAnalyticsService->getGroups($request->user()->id),
        ]);
    }
}
