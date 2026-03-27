<?php

namespace App\Repositories;

use App\Models\Enrollment;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function create(array $data): Enrollment
    {
        return Enrollment::create($data);
    }

    public function findByStudentAndCourse(int $studentId, int $courseId): ?Enrollment
    {
        return Enrollment::query()
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->first();
    }

    public function delete(Enrollment $enrollment): void
    {
        $enrollment->delete();
    }

    public function getTeacherEnrollmentsByCourse(int $teacherId, int $courseId): Collection
    {
        return Enrollment::query()
            ->with(['student:id,name,email', 'group:id,name,course_id'])
            ->where('course_id', $courseId)
            ->whereHas('course', fn ($query) => $query->where('teacher_id', $teacherId))
            ->get();
    }

    public function countPaidByCourse(int $courseId): int
    {
        return Enrollment::query()
            ->where('course_id', $courseId)
            ->where('payment_status', 'paid')
            ->count();
    }
}
