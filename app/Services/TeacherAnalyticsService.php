<?php

namespace App\Services;

use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use App\Repositories\Contracts\GroupRepositoryInterface;

class TeacherAnalyticsService
{
    public function __construct(
        private readonly CourseRepositoryInterface $courseRepository,
        private readonly EnrollmentRepositoryInterface $enrollmentRepository,
        private readonly GroupRepositoryInterface $groupRepository
    ) {
    }

    public function getCourseStats(int $teacherId): array
    {
        $courses = $this->courseRepository->getTeacherCoursesWithStats($teacherId);

        return [
            'total_courses' => $courses->count(),
            'total_paid_enrollments' => $courses->sum('enrollments_count'),
            'total_groups' => $courses->sum('groups_count'),
            'courses' => $courses,
        ];
    }

    public function getCourseStudents(int $teacherId, int $courseId)
    {
        return $this->enrollmentRepository->getTeacherEnrollmentsByCourse($teacherId, $courseId);
    }

    public function getGroups(int $teacherId)
    {
        return $this->groupRepository->getTeacherGroups($teacherId);
    }
}
