<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    // Veritabanına yazılmasına izin verilen alanlar
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'passing_score'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}