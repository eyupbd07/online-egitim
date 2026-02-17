<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Veritabanında kategoriler tablosuna yazılmasına izin verdiğimiz alanlar
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active'
    ];

    // Bir kategorinin birden fazla kursu olabilir
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}