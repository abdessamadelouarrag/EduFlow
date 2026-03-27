<?php

namespace App\Repositories\Contracts;

use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Collection;

interface EnrollmentRepositoryInterface
{
    public function create(array $data): Enrollment;

    public function findByStudentAndCourse(int $studentId, int $courseId): ?Enrollment;

    public function delete(Enrollment $enrollment): void;

    public function getTeacherEnrollmentsByCourse(int $teacherId, int $courseId): Collection;

    public function countPaidByCourse(int $courseId): int;
}
