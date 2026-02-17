<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Certificate;

class StudentController extends Controller
{
    /**
     * Eğitmenin kurslarına kayıtlı öğrencileri anlık verilerle listeler.
     */
    public function index()
    {
        $instructorId = Auth::id();

        // 1. Öğrencileri Bul: Sadece bu eğitmenin kurslarına kayıtlı benzersiz öğrenciler
        $students = User::whereHas('courses', function($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })
        // 2. Kursları ve Sertifikaları Anlık Hesapla
        ->with(['courses' => function($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId)
                  ->select('courses.id', 'courses.title', 'courses.slug');
        }])
        ->latest()
        ->get()
        ->map(function ($student) use ($instructorId) {
            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'joined_at' => $student->created_at->format('d.m.Y'),
                'courses' => $student->courses,
                // 🔥 ANLIK SERTİFİKA SAYISI: 
                // Öğrencinin bu eğitmene ait kurslardan kazandığı toplam sertifika
                'certificate_count' => Certificate::where('user_id', $student->id)
                    ->whereIn('course_id', function($query) use ($instructorId) {
                        $query->select('id')->from('courses')->where('instructor_id', $instructorId);
                    })->count()
            ];
        });

        return Inertia::render('Instructor/Students/Index', [
            'students' => $students
        ]);
    }
}