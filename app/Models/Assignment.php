<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;
    
    // Veritabanına toplu yazmaya izin ver
    protected $guarded = [];

    // Tarihi, Laravel'in Carbon formatına çevir (İşlem yapmayı kolaylaştırır)
    protected $casts = [
        'due_date' => 'datetime',
    ];

    // İlişki: Bir ödev, bir kursa aittir
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // İlişki: Bir ödevin, birçok teslimi (submission) olabilir
    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    // Yardımcı Fonksiyon: Belirli bir öğrenci bu ödevi teslim etmiş mi?
    public function isSubmittedBy($userId)
    {
        return $this->submissions()->where('user_id', $userId)->exists();
    }
}