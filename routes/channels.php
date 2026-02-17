<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
| 
| Burası, uygulamanızın desteklediği tüm yayın kanallarını kaydettiğiniz yerdir.
|
*/

// Kullanıcıların kendi özel bildirimlerini/mesajlarını dinlemesi için
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// 🔥 BİZİM CHAT SİSTEMİ İÇİN GEREKLİ OLAN KANAL
Broadcast::channel('chat.user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});