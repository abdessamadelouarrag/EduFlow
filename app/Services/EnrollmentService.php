<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use App\Repositories\Contracts\GroupRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EnrollmentService
{
    public function __construct(
        private readonly EnrollmentRepositoryInterface $enrollmentRepository,
        private readonly GroupRepositoryInterface $groupRepository
    ) {
    }

    public function enroll(User $student, Course $course, ?string $paymentMethod): Enrollment
    {
        if ($student->role !== 'student') {
            throw ValidationException::withMessages([
                'role' => ['Only students can enroll in a course.'],
            ]);
        }

        if ($course->teacher_id === $student->id) {
            throw ValidationException::withMessages([
                'course' => ['A teacher cannot enroll in their own course.'],
            ]);
        }

        if ($this->enrollmentRepository->findByStudentAndCourse($student->id, $course->id)) {
            throw ValidationException::withMessages([
                'course' => ['You are already enrolled in this course.'],
            ]);
        }

        if (! $paymentMethod) {
            throw ValidationException::withMessages([
                'payment_method' => ['A Stripe payment method token is required.'],
            ]);
        }

        return DB::transaction(function () use ($student, $course, $paymentMethod): Enrollment {
            $group = $this->groupRepository->findAvailableForCourse($course->id);

            if (! $group) {
                $nextIndex = $this->groupRepository->countByCourse($course->id) + 1;

                $group = $this->groupRepository->create([
                    'course_id' => $course->id,
                    'name' => 'Group '.$nextIndex,
                    'max_students' => 25,
                ]);
            }

            return $this->enrollmentRepository->create([
                'student_id' => $student->id,
                'course_id' => $course->id,
                'group_id' => $group->id,
                'payment_status' => 'paid',
                'payment_reference' => 'stripe_simulated_'.$paymentMethod,
            ]);
        });
    }

    public function withdraw(User $student, Course $course): void
    {
        $enrollment = $this->enrollmentRepository->findByStudentAndCourse($student->id, $course->id);

        if (! $enrollment) {
            throw ValidationException::withMessages([
                'course' => ['Enrollment not found for this student and course.'],
            ]);
        }

        $this->enrollmentRepository->delete($enrollment);
    }
}
