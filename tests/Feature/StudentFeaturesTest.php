<?php

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Interest;
use App\Models\User;

it('returns course recommendations based on student interests', function (): void {
    $teacher = User::factory()->create(['role' => 'teacher']);
    $student = User::factory()->create(['role' => 'student']);
    $interest = Interest::create(['name' => 'design']);
    $student->interests()->attach($interest);

    Course::create([
        'teacher_id' => $teacher->id,
        'title' => 'UI Design',
        'description' => 'Design better interfaces',
        'price' => 120,
        'domain' => 'design',
    ]);

    Course::create([
        'teacher_id' => $teacher->id,
        'title' => 'DevOps',
        'description' => 'Infra course',
        'price' => 180,
        'domain' => 'cloud',
    ]);

    $this->getJson('/api/recommendations', authHeadersFor($student))
        ->assertOk()
        ->assertJsonCount(1, 'courses')
        ->assertJsonPath('courses.0.title', 'UI Design');
});

it('allows a student to manage wishlist items', function (): void {
    $teacher = User::factory()->create(['role' => 'teacher']);
    $student = User::factory()->create(['role' => 'student']);
    $course = Course::create([
        'teacher_id' => $teacher->id,
        'title' => 'Saved Course',
        'description' => 'Wishlist target',
        'price' => 99,
        'domain' => 'web',
    ]);

    $this->postJson("/api/wishlist/{$course->id}", [], authHeadersFor($student))
        ->assertCreated();

    $this->getJson('/api/wishlist', authHeadersFor($student))
        ->assertOk()
        ->assertJsonPath('wishlist.0.course.title', 'Saved Course');

    $this->deleteJson("/api/wishlist/{$course->id}", [], authHeadersFor($student))
        ->assertOk();
});

it('enrolls a student with simulated stripe payment and auto assigns a group', function (): void {
    $teacher = User::factory()->create(['role' => 'teacher']);
    $student = User::factory()->create(['role' => 'student']);
    $course = Course::create([
        'teacher_id' => $teacher->id,
        'title' => 'Paid Course',
        'description' => 'Enrollment target',
        'price' => 250,
        'domain' => 'backend',
    ]);

    $this->postJson("/api/courses/{$course->id}/enroll", [
        'payment_method' => 'pm_card_visa',
    ], authHeadersFor($student))
        ->assertCreated()
        ->assertJsonPath('enrollment.payment_status', 'paid')
        ->assertJsonPath('enrollment.group.name', 'Group 1');

    expect(Enrollment::first()->payment_reference)->toStartWith('stripe_simulated_');
});

it('creates a second group after the first group reaches 25 students', function (): void {
    $teacher = User::factory()->create(['role' => 'teacher']);
    $course = Course::create([
        'teacher_id' => $teacher->id,
        'title' => 'Scalable Course',
        'description' => 'Group split test',
        'price' => 300,
        'domain' => 'backend',
    ]);

    foreach (range(1, 26) as $index) {
        $student = User::factory()->create([
            'email' => "student{$index}@example.com",
            'role' => 'student',
        ]);

        $this->postJson("/api/courses/{$course->id}/enroll", [
            'payment_method' => "pm_token_{$index}",
        ], authHeadersFor($student))->assertCreated();
    }

    expect($course->fresh()->groups()->count())->toBe(2);
    expect($course->groups()->orderBy('id')->first()->enrollments()->count())->toBe(25);
    expect($course->groups()->orderByDesc('id')->first()->enrollments()->count())->toBe(1);
});

it('returns teacher statistics and enrolled students', function (): void {
    $teacher = User::factory()->create(['role' => 'teacher']);
    $student = User::factory()->create(['role' => 'student']);
    $course = Course::create([
        'teacher_id' => $teacher->id,
        'title' => 'Analytics Course',
        'description' => 'Stats test',
        'price' => 150,
        'domain' => 'data',
    ]);

    $this->postJson("/api/courses/{$course->id}/enroll", [
        'payment_method' => 'pm_card_mastercard',
    ], authHeadersFor($student))->assertCreated();

    $this->getJson('/api/teacher/stats', authHeadersFor($teacher))
        ->assertOk()
        ->assertJsonPath('total_courses', 1)
        ->assertJsonPath('total_paid_enrollments', 1);

    $this->getJson("/api/teacher/courses/{$course->id}/students", authHeadersFor($teacher))
        ->assertOk()
        ->assertJsonPath('students.0.student.id', $student->id);
});
