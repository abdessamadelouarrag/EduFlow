<?php

namespace App\Repositories;

use App\Models\Wishlist;
use App\Repositories\Contracts\WishlistRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class WishlistRepository implements WishlistRepositoryInterface
{
    public function firstOrCreate(array $attributes): Wishlist
    {
        return Wishlist::firstOrCreate($attributes);
    }

    public function deleteByStudentAndCourse(int $studentId, int $courseId): void
    {
        Wishlist::query()
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->delete();
    }

    public function getStudentWishlist(int $studentId): Collection
    {
        return Wishlist::query()
            ->with('course.teacher:id,name,email')
            ->where('student_id', $studentId)
            ->get();
    }
}
