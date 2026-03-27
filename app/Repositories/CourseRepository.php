<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CourseRepository implements CourseRepositoryInterface
{
    public function paginateForCatalog(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Course::query()
            ->with('teacher:id,name,email')
            ->when($filters['domain'] ?? null, fn ($query, $domain) => $query->where('domain', $domain))
            ->when($filters['search'] ?? null, function ($query, $search): void {
                $query->where(function ($innerQuery) use ($search): void {
                    $innerQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('domain', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Course
    {
        return Course::create($data);
    }

    public function update(Course $course, array $data): Course
    {
        $course->update($data);

        return $course->refresh();
    }

    public function delete(Course $course): void
    {
        $course->delete();
    }

    public function findOrFail(int $id): Course
    {
        return Course::with(['teacher:id,name,email', 'groups', 'enrollments'])->findOrFail($id);
    }

    public function getTeacherCoursesWithStats(int $teacherId): Collection
    {
        return Course::query()
            ->withCount([
                'enrollments as enrollments_count' => fn ($query) => $query->where('payment_status', 'paid'),
                'groups',
            ])
            ->where('teacher_id', $teacherId)
            ->get();
    }
}
