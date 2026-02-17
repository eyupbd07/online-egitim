<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Lesson;

class MyCourseController extends Controller
{
    // 🔥 DÜZELTME BURADA YAPILDI
    public function index()
    {
        $courses = Auth::user()->courses()
            ->with(['instructor'])
            ->orderByPivot('created_at', 'desc')
            ->get();

        // ESKİSİ: 'Student/MyCourses/Index' (Hata veriyordu)
        // YENİSİ: 'Student/MyCourse/Index' (Senin klasör isminle uyumlu)
        return Inertia::render('Student/MyCourse/Index', [
            'courses' => $courses
        ]);
    }

    public function show($slug)
    {
        $course = Course::where('slug', $slug)
            ->with([
                'lessons' => function ($query) { $query->orderBy('id', 'asc'); },
                'instructor',
                'assignments' => function($query) {
                    $query->orderBy('due_date', 'asc')
                          ->with(['submissions' => function($subQuery) {
                              $subQuery->where('user_id', Auth::id());
                          }]);
                },
                'quizzes' => function($query) {
                    $query->withCount('questions');
                }
            ])
            ->firstOrFail();

        $user = Auth::user();
        
        if (!$user->courses()->where('course_id', $course->id)->exists()) {
             return redirect()->route('student.courses.index');
        }

        $completedLessonIds = $user->completedLessons()
            ->whereIn('lesson_id', $course->lessons->pluck('id'))
            ->pluck('lesson_id')
            ->toArray();

        $activeLesson = $course->lessons->first();

        return Inertia::render('Student/MyCourse/Show', [
            'course' => $course,
            'completedLessonIds' => $completedLessonIds,
            'activeLesson' => $activeLesson,
        ]);
    }
    
    public function toggleComplete(Lesson $lesson)
    {
        Auth::user()->completedLessons()->toggle($lesson->id);
        return back();
    }
}