<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// --- BU İKİ SATIRI EKLEYİN ---
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
// --- EKLEME SONU ---

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // --- BU KOD BLOĞUNU 'boot' FONKSİYONUNUN İÇİNE EKLEYİN ---
        Inertia::share([
            'auth' => function () {
                // Giriş yapmış bir kullanıcı olup olmadığını kontrol et
                if (!Auth::check()) {
                    return ['user' => null];
                }
                
                // Kullanıcı giriş yapmışsa, verilerini al
                $user = Auth::user();

                // Sadece 'auth.user' verisini Vue tarafına gönder
                return [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role, // <- EN ÖNEMLİ KISIM
                    ],
                ];
            },
            
            // Hata mesajlarını da paylaşalım
            'errors' => function () {
                return session()->get('errors')
                    ? session()->get('errors')->getBag('default')->getMessages()
                    : (object) [];
            },
        ]);
        // --- EKLEME SONU ---
    }
}
