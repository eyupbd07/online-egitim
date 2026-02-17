<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * (Kullanıcı listesini gösterir)
     */
    public function index()
    {
        // Tüm kullanıcıları alıp, en yeniden eskiye doğru sıralıyoruz
        $users = User::latest()->get();
        
        // Veriyi 'Admin/Users/Index' Vue sayfasına 'users' prop'u olarak gönderiyoruz
        return Inertia::render('Admin/Users/Index', [
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    // created_at'ı formatlayarak gönderelim
                    'created_at' => $user->created_at ? $user->created_at->format('d/m/Y') : null,
                ];
            }),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * (Yeni kullanıcı oluşturma formu - Gelecekte Eğitmen Ekleme için)
     */
    public function create()
    {
        // Inertia::render('Admin/Users/Create');
    }

    /**
     * Store a newly created resource in storage.
     * (Yeni kullanıcıyı kaydetme - Gelecekte Eğitmen Ekleme için)
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     * (Tek bir kullanıcıyı gösterme - Gerekirse kullanılır)
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * (Kullanıcı düzenleme formunu gösterir)
     */
    public function edit(User $user)
    {
        // Düzenlenecek 'user' objesini ve seçebileceği 'roles' dizisini
        // 'Admin/Users/Edit' Vue sayfasına gönderiyoruz
        return Inertia::render('Admin/Users/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'roles' => ['admin', 'instructor', 'student']
        ]);
    }

    /**
     * Update the specified resource in storage.
     * (Kullanıcıyı veritabanında günceller)
     */
    public function update(Request $request, User $user)
    {
        // Gelen veriyi doğruluyoruz
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'instructor', 'student'])],
        ]);
        
        // Doğrulanan veriyi 'user' objesi üzerinde güncelliyoruz
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        // Başarılı bir 'message' (mesaj) ile kullanıcı listesi sayfasına geri yönlendiriyoruz
        return redirect()->route('admin.users.index')->with('message', 'Kullanıcı başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     * (Kullanıcıyı veritabanından siler)
     */
    public function destroy(User $user)
    {
        // --- GÜVENLİK KONTROLÜ ---
        // Adminin KENDİSİNİ silmesini engelliyoruz.
        if (Auth::id() === $user->id) {
            // Bir hata mesajı ile geri yönlendiriyoruz
            return redirect()->route('admin.users.index')
                ->with('error', 'Güvenlik nedeniyle kendinizi silemezsiniz.');
        }
        // --- GÜVENLİK KONTROLÜ SONU ---

        // Kullanıcıyı siliyoruz
        $user->delete();

        // Başarılı bir 'message' (mesaj) ile kullanıcı listesi sayfasına geri yönlendiriyoruz
        return redirect()->route('admin.users.index')->with('message', 'Kullanıcı başarıyla silindi.');
    }
}

