<?php

namespace App\Repositories\Contracts;

use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Collection;

interface WishlistRepositoryInterface
{
    public function firstOrCreate(array $attributes): Wishlist;

    public function deleteByStudentAndCourse(int $studentId, int $courseId): void;

    public function getStudentWishlist(int $studentId): Collection;
}
