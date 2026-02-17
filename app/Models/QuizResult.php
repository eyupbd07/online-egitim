<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    use HasFactory;

    // Toplu atama (Mass Assignment) yapılabilecek alanları tanımlıyoruz
    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'correct_answers',
        'wrong_answers',
        'answers',
        'is_passed',
    ];

    // Answers alanını otomatik olarak JSON/Dizi formatına çevirir
    protected $casts = [
        'answers' => 'json',
        'is_passed' => 'boolean',
    ];

    // İlişkiler (Opsiyonel ama önerilir)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}