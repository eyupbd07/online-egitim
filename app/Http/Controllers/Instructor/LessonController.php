<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class LessonController extends Controller
{
    /**
     * Ders Kaydetme
     * Rota: instructor.lessons.store (Parametre: {course})
     */
    public function store(Request $request, Course $course)
    {
        // Yetki Kontrolü
        if ($course->instructor_id !== auth()->id()) {
            abort(403, 'Bu kursa ders ekleme yetkiniz yok.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'nullable|url',
            'content' => 'nullable|string',
        ]);

        // Kursa bağlı ders oluşturma
        $course->lessons()->create([
            'title' => $validated['title'],
            'video_url' => $validated['video_url'] ?? null,
            'content' => $validated['content'] ?? null,
            // Mevcut ders sayısına göre sıralama ekler
            'order' => $course->lessons()->count() + 1
        ]);

        // Inertia back() kullanımı, Kurs Düzenleme sayfasındaki listeyi anlık günceller
        return Redirect::back()->with('message', 'Ders başarıyla eklendi.');
    }

    /**
     * Ders Düzenleme Sayfası
     */
    public function edit(Lesson $lesson)
    {
        // Yetki Kontrolü
        if ($lesson->course->instructor_id !== auth()->id()) {
            abort(403);
        }

        return Inertia::render('Instructor/Lessons/Edit', [
            'lesson' => $lesson,
            'course_id' => $lesson->course_id
        ]);
    }

    /**
     * Ders Güncelleme
     */
    public function update(Request $request, Lesson $lesson)
    {
        if ($lesson->course->instructor_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'nullable|url',
            'content' => 'nullable|string',
        ]);

        $lesson->update($validated);

        return Redirect::route('instructor.courses.edit', $lesson->course_id)
                         ->with('message', 'Ders başarıyla güncellendi.');
    }

    /**
     * Ders Silme
     */
    public function destroy(Lesson $lesson)
    {
        if ($lesson->course->instructor_id !== auth()->id()) {
            abort(403);
        }

        $lesson->delete();
        
        return Redirect::back()->with('message', 'Ders silindi.');
    }
}   