<?php

namespace App\Services;

use App\Repositories\Contracts\WishlistRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class WishlistService
{
    public function __construct(
        private readonly WishlistRepositoryInterface $wishlistRepository
    ) {
    }

    public function add(int $studentId, int $courseId): void
    {
        $this->wishlistRepository->firstOrCreate([
            'student_id' => $studentId,
            'course_id' => $courseId,
        ]);
    }

    public function remove(int $studentId, int $courseId): void
    {
        $this->wishlistRepository->deleteByStudentAndCourse($studentId, $courseId);
    }

    public function list(int $studentId): Collection
    {
        return $this->wishlistRepository->getStudentWishlist($studentId);
    }
}
