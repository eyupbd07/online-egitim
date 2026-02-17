<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'status',
        'admin_reply',
    ];

    /**
     * Get the user that owns the support ticket.
     * (Talebi oluşturan kullanıcıyı getirir)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

