<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    // Tablo adı (Görseldeki tablo ile eşleşmeli)
    protected $table = 'submissions';

    // Toplu atama yapılabilir sütunlar
    protected $fillable = [
        'assignment_id',
        'user_id',
        'file_path',
        'student_note', // Bunu ekleyelim, birazdan migration ile tabloya da ekleyeceğiz
        'grade',
        'feedback'
    ];

    // İlişkiler
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}