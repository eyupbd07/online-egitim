<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\AssignmentSubmission; // Doğru Model
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class AssignmentController extends Controller
{
    // ... store, edit, update, destroy metodları aynen kalabilir ...

    /**
     * Ödev Kaydetme (Store)
     */
    public function store(Request $request, Course $course)
    {
        if ($course->instructor_id !== auth()->id()) abort(403);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
        ]);

        Assignment::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'],
        ]);

        return Redirect::back()->with('message', 'Ödev eklendi.');
    }

    /**
     * Ödev Düzenleme (Edit)
     */
    public function edit(Assignment $assignment)
    {
         if ($assignment->course->instructor_id !== auth()->id()) abort(403);
         return Inertia::render('Instructor/Assignment/Edit', ['assignment' => $assignment]);
    }

    /**
     * Ödev Güncelleme (Update)
     */
    public function update(Request $request, Assignment $assignment)
    {
        if ($assignment->course->instructor_id !== auth()->id()) abort(403);
        $assignment->update($request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
        ]));
        return Redirect::back()->with('message', 'Ödev güncellendi.');
    }
    
     /**
     * Ödev Silme (Destroy)
     */
    public function destroy(Assignment $assignment)
    {
        if ($assignment->course->instructor_id !== auth()->id()) abort(403);
        $assignment->delete();
        return Redirect::back()->with('message', 'Ödev silindi.');
    }

    /**
     * 🔥 DÜZELTİLEN METOT: Teslimleri Görüntüleme
     */
    public function showSubmissions(Assignment $assignment)
    {
        if ($assignment->course->instructor_id !== auth()->id()) {
            abort(403, 'Yetkisiz işlem.');
        }

        // Doğru tablodan (assignment_submissions) verileri çekiyoruz
        $submissions = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->with('user:id,name,email') 
            ->latest()
            ->get();

        return Inertia::render('Instructor/Assignment/Submissions', [
            'assignment' => $assignment->load('course'),
            'submissions' => $submissions
        ]);
    }
}