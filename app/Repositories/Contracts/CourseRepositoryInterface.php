<?php

namespace App\Repositories\Contracts;

use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CourseRepositoryInterface
{
    public function paginateForCatalog(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function create(array $data): Course;

    public function update(Course $course, array $data): Course;

    public function delete(Course $course): void;

    public function findOrFail(int $id): Course;

    public function getTeacherCoursesWithStats(int $teacherId): Collection;
}
