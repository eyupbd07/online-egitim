<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 'admin', 'instructor', 'student'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =========================================================================
    // İLİŞKİLER (RELATIONSHIPS)
    // =========================================================================

    /**
     * 1. Öğrencinin Kayıtlı Olduğu Kurslar (Many-to-Many)
     * Pivot Tablo: course_user
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_user', 'user_id', 'course_id')
                    ->withTimestamps();
    }

    /**
     * 2. Eğitmenin Verdiği/Oluşturduğu Kurslar (One-to-Many)
     * ChatController'da bu isimle çağırdık, o yüzden bu isim önemli.
     */
    public function coursesAsInstructor(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    /**
     * (Opsiyonel) Eski kodlarınızda 'instructorCourses' kullandıysanız bozulmasın diye
     * bunu da tutabilirsiniz, üsttekiyle aynı işi yapar.
     */
    public function instructorCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    /**
     * 3. Öğrencinin Tamamladığı Dersler (Many-to-Many)
     * Pivot Tablo: lesson_user
     */
    public function completedLessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user', 'user_id', 'lesson_id')
                    ->withTimestamps();
    }

    // =========================================================================
    // CHAT SİSTEMİ İLİŞKİLERİ
    // =========================================================================

    /**
     * 4. Kullanıcının Gönderdiği Mesajlar
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * 5. Kullanıcıya Gelen Mesajlar
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}