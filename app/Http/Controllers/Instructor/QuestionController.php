<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Facades\Redirect;

class QuestionController extends Controller
{
    public function store(Request $request, $quizId)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|string', 
        ]);

        $quiz = Quiz::findOrFail($quizId);
        $question = new Question();
        $question->quiz_id = $quiz->id;
        $question->question_text = $validated['question_text'];
        $question->option_a = $validated['option_a'];
        $question->option_b = $validated['option_b'];
        $question->option_c = $validated['option_c'];
        $question->option_d = $validated['option_d'];
        $question->correct_option = $validated['correct_answer']; 
        $question->save();

        return Redirect::back()->with('success', 'Soru başarıyla eklendi.');
    }

    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        $validated = $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|string',
        ]);

        $question->question_text = $validated['question_text'];
        $question->option_a = $validated['option_a'];
        $question->option_b = $validated['option_b'];
        $question->option_c = $validated['option_c'];
        $question->option_d = $validated['option_d'];
        $question->correct_option = $validated['correct_answer'];
        $question->save();

        return Redirect::back()->with('success', 'Soru başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();
        return Redirect::back()->with('success', 'Soru silindi.');
    }
}