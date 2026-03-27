<?php

namespace App\Repositories;

use App\Models\Group;
use App\Repositories\Contracts\GroupRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GroupRepository implements GroupRepositoryInterface
{
    public function findAvailableForCourse(int $courseId, int $maxStudents = 25): ?Group
    {
        return Group::query()
            ->withCount('enrollments')
            ->where('course_id', $courseId)
            ->orderBy('id')
            ->get()
            ->firstWhere('enrollments_count', '<', $maxStudents);
    }

    public function create(array $data): Group
    {
        return Group::create($data);
    }

    public function countByCourse(int $courseId): int
    {
        return Group::query()->where('course_id', $courseId)->count();
    }

    public function getTeacherGroups(int $teacherId): Collection
    {
        return Group::query()
            ->with(['course:id,title,teacher_id', 'enrollments.student:id,name,email'])
            ->whereHas('course', fn ($query) => $query->where('teacher_id', $teacherId))
            ->get();
    }
}
