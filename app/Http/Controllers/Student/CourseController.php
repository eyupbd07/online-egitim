<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * 🔥 HATA ÇÖZÜMÜ: Kurs Kataloğu (Index)
     * 'is_published' filtresi kaldırıldı.
     */
    public function index()
    {
        return Inertia::render('Student/Courses/Index', [
            'courses' => Course::with('instructor')
                // ->where('is_published', true) // BU SATIR HATAYA SEBEP OLUYORDU, SİLDİK.
                ->latest()
                ->get()
                ->map(function ($course) {
                    return [
                        'id' => $course->id,
                        'title' => $course->title,
                        'slug' => $course->slug, // Linkler için gerekli
                        'description' => $course->description,
                        'thumbnail' => $course->thumbnail,
                        'youtube_url' => $course->youtube_url, // Vue tarafında küçük resim için kullanılıyor
                        'instructor_name' => $course->instructor ? $course->instructor->name : 'Eğitmen',
                        'lessons_count' => $course->lessons()->count(),
                        // Öğrenci bu kursa zaten kayıtlı mı?
                        'is_enrolled' => $course->students()->where('user_id', Auth::id())->exists(),
                    ];
                }),
        ]);
    }

    /**
     * Öğrenci Kurs İzleme Sayfası (Senin kodun korundu)
     */
    public function show($slug)
    {
        // 1. Kursu, eğitmenini, derslerini (sıralı), sınavlarını ve ödevlerini çekiyoruz.
        $course = Course::where('slug', $slug)
            ->with([
                'instructor',
                'lessons' => function($query) {
                    $query->orderBy('order', 'asc'); // Ders sırasına göre
                },
                'quizzes',      // Sınavlar
                'assignments'   // Ödevler
            ])
            ->firstOrFail();

        // 2. Öğrencinin bu kurstaki ödev teslim durumlarını çekiyoruz.
        $submissions = AssignmentSubmission::where('user_id', auth()->id())
            ->whereIn('assignment_id', $course->assignments->pluck('id'))
            ->get();

        // 3. Öğrencinin tamamladığı derslerin ID listesini alıyoruz.
        // Hata almamak için önce ilişkinin varlığını kontrol ediyoruz (Opsiyonel güvenlik)
        $completedLessonIds = [];
        if (method_exists(auth()->user(), 'completedLessons')) {
            $completedLessonIds = auth()->user()->completedLessons()->pluck('lesson_id');
        }

        return Inertia::render('Student/Courses/Show', [
            'course' => $course,
            'submissions' => $submissions,
            'completedLessonIds' => $completedLessonIds,
            // Vue tarafında "undefined" hatası almamak için ilk dersi gönderiyoruz.
            'activeLesson' => $course->lessons->first() 
        ]);
    }

    /**
     * Kursa Kayıt Olma (Enroll)
     * "Hemen Kaydol" butonu için gereklidir.
     */
    public function enroll($courseId)
    {
        $course = Course::findOrFail($courseId);

        // Zaten kayıtlı değilse kaydet
        if (!$course->students()->where('user_id', Auth::id())->exists()) {
            $course->students()->attach(Auth::id());
        }

        return redirect()->route('student.course.show', $course->slug)
            ->with('message', 'Kursa başarıyla kayıt oldunuz.');
    }
}