<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class QuizController extends Controller
{
    /**
     * Sınav Oluşturma Sayfası
     * Hata Çözümü (image_8711f5.jpg): course_id verisinin varlığını garanti ediyoruz.
     */
    public function create(Request $request)
    {
        // Query parametresinden veya formdan gelen course_id'yi al
        $courseId = $request->query('course_id') ?? $request->course_id;

        if (!$courseId) {
            return Redirect::route('instructor.courses.index')->with('error', 'Geçerli bir kurs ID bulunamadı.');
        }

        return Inertia::render('Instructor/Quizzes/Create', [
            'course_id' => (int) $courseId // Sayısal değer olarak gönderildiğinden emin olun
        ]);
    }

    /**
     * Sınav Kaydetme
     */
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Kurs üzerinden sınav oluşturma
        $course->quizzes()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        return Redirect::route('instructor.courses.edit', $course->id)->with('message', 'Sınav başarıyla eklendi.');
    }

    /**
     * Sınav Düzenleme Sayfası
     */
    public function edit(Quiz $quiz)
    {
        // Yetki Kontrolü
        if ($quiz->course->instructor_id !== auth()->id()) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $quiz->load('questions');
        
        return Inertia::render('Instructor/Quizzes/Edit', [
            'quiz' => $quiz,
            'questions' => $quiz->questions,
            'course_id' => $quiz->course_id
        ]);
    }

    /**
     * Sınav Bilgilerini Güncelleme
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $quiz->update($validated);

        return Redirect::back()->with('message', 'Sınav başarıyla güncellendi.');
    }

    /**
     * Sınav Silme
     */
    public function destroy(Quiz $quiz)
    {
        $courseId = $quiz->course_id;
        $quiz->delete();
        
        return Redirect::route('instructor.courses.edit', $courseId)->with('message', 'Sınav silindi.');
    }
}