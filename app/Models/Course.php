<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // Tüm sütunların doldurulmasına izin ver (Mass Assignment)
    protected $guarded = [];

    // Veri tiplerini dönüştür (Vue tarafında sorun yaşamamak için)
    protected $casts = [
        'is_published' => 'boolean',
        'price' => 'decimal:2',
    ];

    /**
     * --------------------------------------------------
     * İLİŞKİLER (RELATIONSHIPS)
     * --------------------------------------------------
     */

    // Kursun Eğitmeni
    public function instructor() {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    // Kursun Kategorisi
    public function category() {
        return $this->belongsTo(Category::class);
    }

    // Kursun Dersleri
    public function lessons() {
        return $this->hasMany(Lesson::class);
    }

    // Kursa Kayıtlı Öğrenciler (Pivot Tablo: course_user)
    public function students() {
        return $this->belongsToMany(User::class, 'course_user')->withTimestamps();
    }

    // Kursun Ödevleri (Controller'da with('assignments') için gerekli)
    public function assignments() {
        return $this->hasMany(Assignment::class);
    }

    // Kursun Sınavları (Controller'da with('quizzes') için gerekli)
    public function quizzes() {
        return $this->hasMany(Quiz::class);
    }

    /**
     * --------------------------------------------------
     * OTOMATİK TEMİZLİK (CASCADE DELETE)
     * --------------------------------------------------
     * Bir kurs silindiğinde, ona bağlı her şeyi temizler.
     */
    protected static function booted()
    {
        static::deleting(function ($course) {
            // Kurs silinirken bağlı dersleri sil
            $course->lessons()->each(function($lesson) {
                $lesson->delete();
            });

            // Bağlı ödevleri sil
            $course->assignments()->each(function($assignment) {
                $assignment->delete();
            });

            // Bağlı sınavları sil
            $course->quizzes()->each(function($quiz) {
                $quiz->delete();
            });
            
            // Kayıtlı öğrencilerin kaydını düş (Pivot tablodan sil)
            $course->students()->detach();
        });
    }
}