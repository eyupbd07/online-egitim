<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Message;
use App\Models\Course;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Chat Ana Sayfası (Kişi Listesi)
     */
    public function index()
    {
        $user = Auth::user();
        $contacts = collect();

        // EĞER EĞİTMENSE: Kendi kurslarındaki öğrencileri listele
        if ($user->role === 'instructor') {
            $courseIds = Course::where('instructor_id', $user->id)->pluck('id');
            
            $contacts = User::whereHas('courses', function($query) use ($courseIds) {
                $query->whereIn('course_id', $courseIds);
            })
            ->where('id', '!=', $user->id)
            ->distinct()
            ->get();
        } 
        // EĞER ÖĞRENCİYSE: Kayıtlı olduğu kursların eğitmenlerini listele
        else {
            $courseIds = $user->courses()->pluck('courses.id');

            $contacts = User::whereHas('coursesAsInstructor', function($query) use ($courseIds) {
                $query->whereIn('id', $courseIds);
            })
            ->where('id', '!=', $user->id)
            ->distinct()
            ->get();
        }

        return Inertia::render('Chat/Index', [
            'contacts' => $contacts,
            'currentUser' => $user
        ]);
    }

    /**
     * Seçilen kişiyle olan mesaj geçmişini getir
     */
    public function getMessages($userId)
    {
        $myId = Auth::id();

        $messages = Message::where(function($q) use ($myId, $userId) {
            $q->where('sender_id', $myId)->where('receiver_id', $userId);
        })->orWhere(function($q) use ($myId, $userId) {
            $q->where('sender_id', $userId)->where('receiver_id', $myId);
        })
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'asc')
        ->get();

        return response()->json($messages);
    }

    /**
     * Mesaj Gönderme İşlemi (Dosya Destekli)
     */
    public function sendMessage(Request $request)
    {
        // Validasyon: Mesaj boş olabilir ama o zaman dosya dolu olmalı
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'file' => 'nullable|file|max:10240', // Max 10MB
        ]);

        $data = [
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'message' => $validated['message'] ?? null,
        ];

        // Dosya Yükleme İşlemi
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // Dosyayı 'public/chat_files' klasörüne kaydet
            $path = $file->store('chat_files', 'public');

            // Veritabanı için verileri hazırla
            $data['file_url'] = Storage::url($path);
            $data['file_name'] = $file->getClientOriginalName();
            
            // Dosya tipini belirle (resim mi dosya mı?)
            $mimeType = $file->getMimeType();
            $data['file_type'] = Str::startsWith($mimeType, 'image/') ? 'image' : 'file';
        }

        // Mesajı veritabanına kaydet
        $message = Message::create($data);

        // WebSocket Eventini Tetikle
        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }

    /**
     * Sohbet Geçmişini Temizle (Silme)
     */
    public function clearChat($userId)
    {
        $myId = Auth::id();

        // İki kullanıcı arasındaki tüm mesajları bul ve sil
        // Not: Gerçek bir uygulamada 'soft delete' kullanmak veya
        // sadece silen kişi için görünmez yapmak (deleted_by_sender, deleted_by_receiver) daha iyidir.
        // Ancak isteğin üzerine burada komple siliyoruz.
        
        Message::where(function($q) use ($myId, $userId) {
            $q->where('sender_id', $myId)->where('receiver_id', $userId);
        })->orWhere(function($q) use ($myId, $userId) {
            $q->where('sender_id', $userId)->where('receiver_id', $myId);
        })->delete();

        // Not: Yüklenen dosyaları sunucudan silmek isterseniz burada ek işlem yapmanız gerekir.

        return response()->json(['message' => 'Sohbet temizlendi']);
    }
}