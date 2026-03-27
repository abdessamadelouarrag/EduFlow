<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct(
        private readonly WishlistService $wishlistService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'wishlist' => $this->wishlistService->list($request->user()->id),
        ]);
    }

    public function store(Request $request, Course $course): JsonResponse
    {
        $this->wishlistService->add($request->user()->id, $course->id);

        return response()->json([
            'message' => 'Course added to wishlist.',
        ], 201);
    }

    public function destroy(Request $request, Course $course): JsonResponse
    {
        $this->wishlistService->remove($request->user()->id, $course->id);

        return response()->json([
            'message' => 'Course removed from wishlist.',
        ]);
    }
}
