<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    use HasFactory;

    // Görseldeki tablo adınız (image_1218ab.jpg)
    protected $table = 'assignment_submissions';

    protected $fillable = [
        'assignment_id',
        'user_id',
        'file_path',
        'student_note',
        'submitted_at',
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