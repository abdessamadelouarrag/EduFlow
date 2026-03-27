<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(
        private readonly CourseService $courseService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $courses = $this->courseService->list($request->only(['domain', 'search']));

        return response()->json($courses);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'domain' => ['required', 'string', 'max:255'],
        ]);

        $course = $this->courseService->create($validated, $request->user()->id);

        return response()->json([
            'message' => 'Course created successfully.',
            'course' => $course,
        ], 201);
    }

    public function show(int $course): JsonResponse
    {
        return response()->json([
            'course' => $this->courseService->show($course),
        ]);
    }

    public function update(Request $request, Course $course): JsonResponse
    {
        abort_if($course->teacher_id !== $request->user()->id, 403, 'You can only update your own courses.');

        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'domain' => ['sometimes', 'string', 'max:255'],
        ]);

        $course = $this->courseService->update($course, $validated);

        return response()->json([
            'message' => 'Course updated successfully.',
            'course' => $course,
        ]);
    }

    public function destroy(Request $request, Course $course): JsonResponse
    {
        abort_if($course->teacher_id !== $request->user()->id, 403, 'You can only delete your own courses.');

        $this->courseService->delete($course);

        return response()->json([
            'message' => 'Course deleted successfully.',
        ]);
    }
}
