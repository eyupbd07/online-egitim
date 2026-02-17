<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Eğitmen Paneli (Dashboard)
     */
    public function dashboard()
    {
        $instructorId = auth()->id();
        $courseIds = Course::where('instructor_id', $instructorId)->pluck('id');

        return Inertia::render('Instructor/Dashboard', [
            'stats' => [
                'total_courses' => $courseIds->count(),
                'total_students' => DB::table('course_user')
                    ->whereIn('course_id', $courseIds)
                    ->distinct('user_id')
                    ->count('user_id'),
                'total_lessons' => Lesson::whereIn('course_id', $courseIds)->count(),
            ],
            'recentCourses' => Course::where('instructor_id', $instructorId)
                ->withCount('students')
                ->latest()
                ->take(5)
                ->get()
        ]);
    }

    /**
     * Kurs Listesi
     */
    public function index()
    {
        $courses = Course::where('instructor_id', auth()->id())
            ->withCount(['lessons', 'students'])
            ->latest()
            ->get();

        return Inertia::render('Instructor/Courses/Index', [
            'courses' => $courses
        ]);
    }

    /**
     * Kurs Oluşturma Sayfası
     */
    public function create()
    {
        return Inertia::render('Instructor/Courses/Create');
    }

    private function getYouTubeId($url) {
        if (!$url) return null;
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Kurs Kaydı
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'nullable|numeric|min:0',
            'youtube_url' => 'nullable|url',
        ]);

        $thumbnailPath = null;
        if (!empty($validated['youtube_url'])) {
            $videoId = $this->getYouTubeId($validated['youtube_url']);
            $thumbnailPath = $videoId ? "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg" : null;
        }

        Course::create([
            'instructor_id'  => auth()->id(),
            'title'          => $validated['title'],
            'slug'           => Str::slug($validated['title']) . '-' . rand(1000, 9999),
            'description'    => $validated['description'],
            'price'          => $validated['price'] ?? 0,
            'thumbnail'      => $thumbnailPath,
            'youtube_url'    => $validated['youtube_url'] ?? null,
            'is_published'   => false,
        ]);

        return redirect()->route('instructor.courses.index')->with('success', 'Kurs başarıyla oluşturuldu.');
    }

    /**
     * 🔥 HATA ÇÖZÜMÜ BURADA: Kurs Düzenleme Sayfası
     * Sınavları çekerken 'questions' sayısını da (questions_count) alıyoruz.
     */
    public function edit($id)
    {
        $course = Course::where('instructor_id', auth()->id())
            ->with([
                'lessons', 
                'assignments',
                // Sınavları çekerken içindeki soru sayısını da saydırıyoruz
                'quizzes' => function ($query) {
                    $query->withCount('questions'); 
                }
            ])
            ->withCount('students')
            ->findOrFail($id);

        return Inertia::render('Instructor/Courses/Edit', [
            'course' => $course
        ]);
    }

    /**
     * Kurs Güncelleme
     */
    public function update(Request $request, $id)
    {
        $course = Course::where('instructor_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'nullable|numeric|min:0',
            'youtube_url' => 'nullable|url',
            'image'       => 'nullable|image|max:2048',
        ]);

        $updateData = [
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'price'       => $validated['price'],
        ];

        if ($request->filled('youtube_url')) {
            $videoId = $this->getYouTubeId($validated['youtube_url']);
            $updateData['youtube_url'] = $validated['youtube_url'];
            if (!$request->hasFile('image')) {
                $updateData['thumbnail'] = $videoId ? "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg" : $course->thumbnail;
            }
        }

        if ($request->hasFile('image')) {
            if ($course->thumbnail && !Str::startsWith($course->thumbnail, 'http')) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $path = $request->file('image')->store('courses', 'public');
            $updateData['thumbnail'] = $path;
        }

        $course->update($updateData);

        return redirect()->route('instructor.courses.index')->with('success', 'Kurs başarıyla güncellendi.');
    }

    /**
     * Kurs Silme
     */
    public function destroy($id)
    {
        $course = Course::where('instructor_id', auth()->id())->findOrFail($id);
        
        if ($course->thumbnail && !Str::startsWith($course->thumbnail, 'http')) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return redirect()->route('instructor.courses.index')->with('success', 'Kurs silindi.');
    }
}