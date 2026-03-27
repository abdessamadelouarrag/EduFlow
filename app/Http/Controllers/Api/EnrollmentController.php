<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\EnrollmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function __construct(
        private readonly EnrollmentService $enrollmentService
    ) {
    }

    public function store(Request $request, Course $course): JsonResponse
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'string'],
        ]);

        $enrollment = $this->enrollmentService->enroll($request->user(), $course, $validated['payment_method']);

        return response()->json([
            'message' => 'Enrollment completed successfully.',
            'enrollment' => $enrollment->load(['course', 'group']),
        ], 201);
    }

    public function destroy(Request $request, Course $course): JsonResponse
    {
        $this->enrollmentService->withdraw($request->user(), $course);

        return response()->json([
            'message' => 'Enrollment removed successfully.',
        ]);
    }
}
