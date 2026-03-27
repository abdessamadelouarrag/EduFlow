<?php

namespace App\Repositories\Contracts;

use App\Models\Group;
use Illuminate\Database\Eloquent\Collection;

interface GroupRepositoryInterface
{
    public function findAvailableForCourse(int $courseId, int $maxStudents = 25): ?Group;

    public function create(array $data): Group;

    public function countByCourse(int $courseId): int;

    public function getTeacherGroups(int $teacherId): Collection;
}
