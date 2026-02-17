<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\User; 
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule; 

class SupportController extends Controller
{
    /**
     * Display a listing of all support tickets.
     * (Tüm destek taleplerini listeler - Admin için)
     */
    public function index()
    {
        // Tüm destek taleplerini, talebi oluşturan kullanıcı (eğitmen) bilgisiyle birlikte çek
        $tickets = SupportTicket::with('user')
                                ->latest()
                                ->get();

        // Veriyi 'Admin/Support/Index' Vue sayfasına gönder
        return Inertia::render('Admin/Support/Index', [
            'tickets' => $tickets->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'subject' => $ticket->subject,
                    'user_name' => $ticket->user ? $ticket->user->name : 'Bilinmeyen Kullanıcı', 
                    'status' => $ticket->status,
                    'created_at' => $ticket->created_at ? $ticket->created_at->format('d/m/Y H:i') : null,
                    'updated_at' => $ticket->updated_at ? $ticket->updated_at->format('d/m/Y H:i') : null,
                ];
            }),
            'flash' => session()->all(),
        ]);
    }

    /**
     * Display the specified support ticket and allow admin to reply.
     * (Belirli bir destek talebini gösterir ve adminin cevaplamasına izin verir)
     */
    public function show(SupportTicket $supportTicket) 
    {
        // Talebi oluşturan kullanıcıyı da yükleyelim
        $supportTicket->load('user');

        // Veriyi 'Admin/Support/Show' Vue sayfasına gönder
        return Inertia::render('Admin/Support/Show', [
            'ticket' => [
                'id' => $supportTicket->id,
                'subject' => $supportTicket->subject,       // <-- Konu burada
                'message' => $supportTicket->message,       // <-- Mesaj burada
                'admin_reply' => $supportTicket->admin_reply,
                'status' => $supportTicket->status,
                'user_name' => $supportTicket->user ? $supportTicket->user->name : 'Bilinmeyen Kullanıcı', 
                'user_email' => $supportTicket->user ? $supportTicket->user->email : '', 
                'created_at' => $supportTicket->created_at ? $supportTicket->created_at->format('d/m/Y H:i') : null, 
                'updated_at' => $supportTicket->updated_at ? $supportTicket->updated_at->format('d/m/Y H:i') : null,
            ],
            // Durum değiştirme seçenekleri
            'statuses' => ['open', 'pending', 'closed'],
             'flash' => session()->all(), // Flash mesajları da gönderelim
        ]);
    }


    /**
     * Update the specified support ticket in storage (Admin reply and status change).
     * (Destek talebini günceller - Admin cevabı ve durum değişikliği)
     */
    public function update(Request $request, SupportTicket $supportTicket)
    {
        // Gelen veriyi doğrula
        $validated = $request->validate([
            'admin_reply' => 'required|string', // Admin cevabı zorunlu
            'status' => ['required', Rule::in(['open', 'pending', 'closed'])], // Durum geçerli olmalı
        ]);

        // Destek talebini güncelle
        $supportTicket->update([
            'admin_reply' => $validated['admin_reply'],
            'status' => $validated['status'],
        ]);

        // Başarılı bir mesajla talep listesi sayfasına geri yönlendir
        return redirect()->route('admin.support.index')->with('message', "Destek talebi #{$supportTicket->id} başarıyla güncellendi.");
    }
}

