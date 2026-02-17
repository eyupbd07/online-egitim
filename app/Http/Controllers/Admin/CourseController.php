<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * YouTube linkini iframe dostu embed formatına çevirir.
     */
    private function convertToEmbedUrl($url)
    {
        if (!$url) return null;

        // YouTube video ID'sini yakalayan geliştirilmiş regex
        $pattern = '/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/';
        preg_match($pattern, $url, $matches);

        if (isset($matches[2]) && strlen($matches[2]) == 11) {
            $videoId = $matches[2];
            // rel=0: Video sonunda sadece aynı kanalın videolarını önerir.
            return "https://www.youtube.com/embed/{$videoId}?rel=0&modestbranding=1";
        }

        return null;
    }

    /**
     * INDEX METODU (Kurs Listeleme)
     */
    public function index()
    {
        // Kursları, eğitmenleri ve dersleri ile birlikte çekiyoruz
        $courses = Course::with(['instructor', 'lessons' => function ($query) {
            $query->orderBy('order');
        }])->latest()->get();

        return Inertia::render('Admin/Courses/Index', [
            'courses' => $courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'description' => Str::limit($course->description, 100),
                    'slug' => $course->slug,
                    'instructor_name' => $course->instructor ? $course->instructor->name : 'Eğitmen Yok',
                    'created_at' => $course->created_at ? $course->created_at->format('d/m/Y') : null,
                    'lessons' => $course->lessons->map(function ($lesson) use ($course) {
                        /**
                         * 🔥 KRİTİK DÜZELTME: 
                         * Veritabanı görüntüsüne göre linkler 'courses' tablosunda.
                         * Eğer derste youtube_url yoksa, kursun youtube_url değerini alıyoruz.
                         */
                        $rawUrl = $lesson->youtube_url ?? $course->youtube_url;

                        return [
                            'id' => $lesson->id,
                            'title' => $lesson->title,
                            'order' => $lesson->order,
                            'is_premium' => (bool) $lesson->is_premium,
                            // Vue'nun beklediği 'youtube_embed_url' ismine dönüştürülmüş veri gönderiliyor
                            'youtube_embed_url' => $this->convertToEmbedUrl($rawUrl),
                        ];
                    }),
                ];
            }),
            'flash' => [
                'message' => session('message'),
                'error' => session('error'),
            ]
        ]);
    }

    /**
     * EDIT METODU (Düzenleme Ekranı Verisi)
     */
    public function edit(Course $course)
    {
        $instructors = User::where('role', 'instructor')->get(['id', 'name']);
        // İlişkili dersleri yüklüyoruz
        $lessons = $course->lessons()->orderBy('order')->get();

        return Inertia::render('Admin/Courses/Edit', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'youtube_url' => $course->youtube_url, // Orijinal kolon ismi
                'instructor_id' => $course->instructor_id,
            ],
            'instructors' => $instructors,
            'lessons' => $lessons->map(function ($lesson) use ($course) {
                // Burada da aynı mantığı (ders yoksa kurs linki) uyguluyoruz
                $rawUrl = $lesson->youtube_url ?? $course->youtube_url;
                
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'order' => $lesson->order,
                    'is_premium' => (bool) $lesson->is_premium,
                    'youtube_embed_url' => $this->convertToEmbedUrl($rawUrl),
                ];
            }),
        ]);
    }

    /**
     * UPDATE METODU (Güncelleme İşlemi)
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructor_id' => 'required|exists:users,id',
            'youtube_url' => 'nullable|url',
        ]);

        $course->update($validated);

        return redirect()->route('admin.courses.index')->with('message', 'Kurs başarıyla güncellendi.');
    }

    /**
     * DESTROY METODU (Silme İşlemi)
     */
    public function destroy(Course $course)
    {
        // Önce dersleri temizle, sonra kursu sil
        $course->lessons()->delete();
        $course->delete();

        return redirect()->route('admin.courses.index')->with('message', 'Kurs ve ilgili tüm dersler silindi.');
    }
}