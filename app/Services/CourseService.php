<?php

namespace App\Services;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CourseService
{
    public function __construct(
        private readonly CourseRepositoryInterface $courseRepository
    ) {
    }

    public function list(array $filters = []): LengthAwarePaginator
    {
        return $this->courseRepository->paginateForCatalog($filters);
    }

    public function create(array $data, int $teacherId): Course
    {
        return $this->courseRepository->create([
            ...$data,
            'teacher_id' => $teacherId,
        ]);
    }

    public function update(Course $course, array $data): Course
    {
        return $this->courseRepository->update($course, $data);
    }

    public function delete(Course $course): void
    {
        $this->courseRepository->delete($course);
    }

    public function show(int $id): Course
    {
        return $this->courseRepository->findOrFail($id);
    }
}
