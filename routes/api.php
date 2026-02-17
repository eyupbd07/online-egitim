<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;   // Kullanıcı Modeli
use Illuminate\Support\Facades\DB; // DB Sorguları için

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/dashboard-stats', function () {
    // --- GERÇEK VERİTABANI SORGULARI ---
    
    // 1. Toplam Kurs Sayısı (Courses tablosu varsa sayar, yoksa 0 döner)
    $totalCourses = 0;
    try {
        $totalCourses = DB::table('courses')->count();
    } catch (\Exception $e) {}

    // 2. Toplam Öğrenci Sayısı (Role = 'student' olanlar)
    $totalStudents = 0;
    try {
        $totalStudents = User::where('role', 'student')->count();
    } catch (\Exception $e) {}

    // 3. Toplam Kazanç (Örnek: orders tablosundan toplam tutar)
    $totalEarnings = 0;
    // Eğer orders tablon varsa açabilirsin:
    // try { $totalEarnings = DB::table('orders')->sum('price'); } catch(\Exception $e){}

    // 4. Grafik Verisi (Son 7 gün sahte veri - Veritabanı dolunca burası da bağlanır)
    $series = [
        [
            'name' => 'Yeni Kayıtlar',
            'data' => [2, 5, 1, 8, 3, 10, 4] // Şimdilik görsel dolu dursun
        ]
    ];

    return response()->json([
        'totalCourses' => $totalCourses,
        'totalStudents' => $totalStudents,
        'totalEarnings' => $totalEarnings,
        'series' => $series,
        'recentActivities' => []
    ]);
});