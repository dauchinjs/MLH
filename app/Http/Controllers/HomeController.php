<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home');
    }

    public function getInfo(): LengthAwarePaginator
    {
        return Course::query()
            ->join('enrollments', 'courses.id', '=', 'enrollments.course_id')
            ->join('users', 'enrollments.user_id', '=', 'users.id')
            ->select('courses.id as courseID', 'users.id as userID', 'courses.title', 'users.name', 'users.email', 'enrollments.status', 'courses.created_at as joined',
                'courses.updated_at as completed')
            ->orderBy('courses.created_at', 'desc')
            ->paginate(50);
    }

    public function deleteCourse(int $courseId, int $userId): void
    {
        $course = Course::query()->where('id', '=', $courseId)->first();
        $enrollments = Enrollment::query()
            ->where('course_id', '=', $courseId)
            ->where('user_id', '=', $userId)
            ->get();
        foreach ($enrollments as $enrollment) {
            $enrollment->delete();
        }
        $course->delete();
    }

    public function updateStatus(int $courseId, int $userId, int $status): void
    {
        Enrollment::where('course_id', $courseId)
            ->where('user_id', $userId)
            ->update([
                'status' => $status,
            ]);
        Course::where('id', $courseId)
            ->update([
                'updated_at' => now(),
            ]);
    }

    public function createCourse(string $courseTitle, string $email, int $status): void
    {
        $user = User::query()->where('email', '=', $email)->firstOrFail();

        $course = (new Course())->fill([
            'title' => $courseTitle,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $course->save();
        $enrollment = (new Enrollment())->fill([
            'course_id' => $course->id,
            'user_id' => $user->id,
            'status' => $status,
        ]);
        $enrollment->save();
    }
}
