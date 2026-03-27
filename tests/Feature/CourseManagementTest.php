<?php

use App\Models\Course;
use App\Models\User;

it('allows a teacher to create a course', function (): void {
    $teacher = User::factory()->create(['role' => 'teacher']);

    $this->postJson('/api/courses', [
        'title' => 'Laravel API',
        'description' => 'Build APIs with Laravel',
        'price' => 199.99,
        'domain' => 'web',
    ], authHeadersFor($teacher))
        ->assertCreated()
        ->assertJsonPath('course.teacher_id', $teacher->id);
});

it('prevents a student from creating a course', function (): void {
    $student = User::factory()->create(['role' => 'student']);

    $this->postJson('/api/courses', [
        'title' => 'Unauthorized',
        'description' => 'Should fail',
        'price' => 50,
        'domain' => 'web',
    ], authHeadersFor($student))->assertForbidden();
});

it('lists available courses publicly', function (): void {
    $teacher = User::factory()->create(['role' => 'teacher']);
    Course::create([
        'teacher_id' => $teacher->id,
        'title' => 'PHP Basics',
        'description' => 'Intro course',
        'price' => 20,
        'domain' => 'web',
    ]);

    $this->getJson('/api/courses')
        ->assertOk()
        ->assertJsonPath('data.0.title', 'PHP Basics');
});
