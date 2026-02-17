<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission; // Doğru Model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function submit(Request $request, Assignment $assignment)
    {
        // Tarih Kontrolü
        if ($assignment->due_date && now()->greaterThan($assignment->due_date)) {
            return Redirect::back()->with('error', 'Son teslim tarihi geçti.');
        }

        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,zip,rar,png,jpg,jpeg|max:10240',
            'student_note' => 'nullable|string|max:1000'
        ]);

        if ($request->hasFile('file')) {
            try {
                // Eski dosyayı bul (Doğru model ile)
                $existingSubmission = AssignmentSubmission::where('assignment_id', $assignment->id)
                    ->where('user_id', Auth::id())
                    ->first();

                // Varsa eski dosyayı sil
                if ($existingSubmission && $existingSubmission->file_path) {
                    if(Storage::disk('public')->exists($existingSubmission->file_path)) {
                        Storage::disk('public')->delete($existingSubmission->file_path);
                    }
                }

                // Yeni dosyayı kaydet
                $path = $request->file('file')->store('assignments', 'public');

                // Veritabanına Yaz (assignment_submissions tablosuna)
                AssignmentSubmission::updateOrCreate(
                    [
                        'assignment_id' => $assignment->id,
                        'user_id' => Auth::id()
                    ],
                    [
                        'file_path' => $path,
                        'student_note' => $request->student_note,
                        'submitted_at' => now(),
                    ]
                );

                return Redirect::back()->with('message', 'Ödev başarıyla yüklendi.');

            } catch (\Exception $e) {
                return Redirect::back()->with('error', 'Hata: ' . $e->getMessage());
            }
        }

        return Redirect::back()->with('error', 'Dosya seçilmedi.');
    }
}