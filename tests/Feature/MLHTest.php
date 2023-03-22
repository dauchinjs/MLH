<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Tests\TestCase;

class MLHTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    public function testGetInfo()
    {
        $course = Course::factory()->create(['title' => 'Test Course']);
        $user = User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
        Enrollment::factory()->create([
            'course_id' => $course->id,
            'user_id' => $user->id,
            'status' => 1,
        ]);

        $response = $this->getJson('/api/');

        $response->assertOk();

        $response->assertJsonFragment([
            'courseID' => $course->id,
            'userID' => $user->id,
            'title' => 'Test Course',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'status' => 1,
        ]);
    }

    public function testDeleteCourse()
    {
        $course = Course::factory()->create(['title' => 'Test Course 2']);
        $user = User::factory()->create(['name' => 'John Doe', 'email' => 'john.doe@example.com']);
        Enrollment::factory()->create([
            'course_id' => $course->id,
            'user_id' => $user->id,
            'status' => 1,
        ]);

        $response = $this->deleteJson("/api/delete/course/{$course->id}/user/{$user->id}");

        $response->assertOk();

        $this->assertDatabaseMissing('courses', [
            'id' => $course->id,
            'title' => 'Test Course 2',
        ]);

        $this->assertDatabaseMissing('enrollments', [
            'course_id' => $course->id,
            'user_id' => $user->id,
            'status' => 1,
        ]);
    }

    public function testUpdateStatus()
    {
        $course = Course::factory()->create(['title' => 'Test Course 3']);
        $user = User::factory()->create(['name' => 'John Doe', 'email' => 'john@test.com']);
        Enrollment::factory()->create([
            'course_id' => $course->id,
            'user_id' => $user->id,
            'status' => 1,
        ]);

        $response = $this->putJson("/api/update/course/{$course->id}/user/{$user->id}/status/2");

        $response->assertOk();

        $this->assertDatabaseHas('enrollments', [
            'course_id' => $course->id,
            'user_id' => $user->id,
            'status' => 2,
        ]);

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'updated_at' => now(),
        ]);
    }

    public function testCreateCourse()
    {
        $course = Course::factory()->create(['title' => 'Test Course 4']);
        $user = User::factory()->create(['name' => 'John Doe', 'email' => 'john.doe@test.com']);
        Enrollment::factory()->create([
            'course_id' => $course->id,
            'user_id' => $user->id,
            'status' => 1,
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@test.com',
        ]);

        $this->assertDatabaseHas('courses', [
            'title' => 'Test Course 4',
        ]);

        $course = Course::query()->where('title', '=', 'Test Course 4')->firstOrFail();

        $this->assertDatabaseHas('enrollments', [
            'course_id' => $course->id,
            'user_id' => $user->id,
            'status' => 1,
        ]);
    }
}
