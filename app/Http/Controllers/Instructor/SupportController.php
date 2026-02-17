<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SupportController extends Controller
{
    /**
     * Display a listing of the instructor's support tickets.
     * (Eğitmenin kendi destek taleplerini listeler - Detaylar Eklendi)
     */
    public function index()
    {
        $userId = Auth::id();
        $tickets = SupportTicket::where('user_id', $userId)
                                ->latest()
                                ->get();

        return Inertia::render('Instructor/Support/Index', [
            'tickets' => $tickets->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'subject' => $ticket->subject,
                    'message' => $ticket->message, // <-- YENİ EKLENDİ
                    'admin_reply' => $ticket->admin_reply, // <-- YENİ EKLENDİ
                    'status' => $ticket->status,
                    'created_at' => $ticket->created_at ? $ticket->created_at->format('d/m/Y H:i') : null,
                    'updated_at' => $ticket->updated_at ? $ticket->updated_at->format('d/m/Y H:i') : null,
                ];
            }),
            'flash' => session()->all(),
        ]);
    }

    // ... (create ve store fonksiyonları) ...
     /**
     * Show the form for creating a new support ticket.
     * (Yeni destek talebi oluşturma formunu gösterir)
     */
    public function create()
    {
        // 'Instructor/Support/Create' Vue sayfasını yönlendir
        return Inertia::render('Instructor/Support/Create');
    }

    /**
     * Store a newly created support ticket in storage.
     * (Yeni destek talebini kaydeder)
     */
    public function store(Request $request)
    {
        // Gelen veriyi doğrula
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Destek talebini veritabanına kaydet
        SupportTicket::create([
            'user_id' => Auth::id(), // Giriş yapan eğitmenin ID'si
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'open', // Yeni talepler 'open' (açık) olarak başlar
        ]);

        // Başarılı bir mesajla talep listesi sayfasına geri yönlendir
        return redirect()->route('instructor.support.index')->with('message', 'Destek talebiniz başarıyla gönderildi.');
    }
}

