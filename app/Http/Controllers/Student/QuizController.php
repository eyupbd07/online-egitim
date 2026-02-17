<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function show($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        
        return Inertia::render('Student/Quizzes/Show', [
            'quiz' => $quiz,
            'questions' => $quiz->questions,
            'server_time' => Carbon::now()->timestamp,
            // Eğer daha önce çözdüyse sonucu göster
            'quiz_result' => session('quiz_result') ?? null 
        ]);
    }

    public function submit(Request $request, $id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        $answers = $request->input('answers', []);
        
        $totalPoints = 0;
        $earnedPoints = 0;
        $correctCount = 0;

        foreach ($quiz->questions as $question) {
            $maxPoints = 10; 
            $totalPoints += $maxPoints;

            // Cevapları karşılaştır (Harf duyarlılığını kaldırarak)
            $userAnswer = isset($answers[$question->id]) ? strtolower($answers[$question->id]) : null;
            $correctOption = strtolower(str_replace('option_', '', $question->correct_option));

            if ($userAnswer && $userAnswer === $correctOption) {
                $earnedPoints += $maxPoints;
                $correctCount++;
            }
        }

        $score = ($totalPoints > 0) ? round(($earnedPoints / $totalPoints) * 100) : 0;
        $passingScore = $quiz->passing_score ?? 50;
        $isPassed = $score >= $passingScore;

        $result = [
            'score' => $score,
            'correct' => $correctCount,
            'total' => $quiz->questions->count(),
            'is_passed' => $isPassed
        ];

        // Sertifika Oluşturma (Geçtiyse)
        if ($isPassed) {
            Certificate::firstOrCreate(
                ['user_id' => Auth::id(), 'course_id' => $quiz->course_id],
                ['certificate_code' => 'CRT-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(3))), 'issued_at' => now()]
            );
        }

        // 🔥 DEĞİŞİKLİK BURADA: Başka sayfaya gitme, geri dön ve sonucu göster.
        return back()->with([
            'message' => 'Sınav tamamlandı.',
            'quiz_result' => $result
        ]);
    }
}