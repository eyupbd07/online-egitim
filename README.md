İşte projenin **Bitirme Projesi** olduğunu, **Yapay Zeka** desteğini, **YouTube entegrasyonunu** ve **SQL kurulumunu** içeren en kapsamlı ve düzenli `README.md` dosyası.

Bunu kopyalayıp tek seferde kullanabilirsin:

```markdown
# 🎓 Online Eğitim Yönetim Sistemi (LMS) - Bitirme Projesi

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=flat&logo=vue.js&logoColor=white)
![Inertia](https://img.shields.io/badge/Inertia.js-SPA-9553E9?style=flat&logo=inertia&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-blue.svg)

Bu proje, **Laravel 11** ve **Vue.js 3** teknolojileri kullanılarak üniversite **Bitirme Projesi** kapsamında geliştirilmiş, açık kaynaklı bir Öğrenim Yönetim Sistemi (LMS) prototipidir.

Geliştirme sürecinde **Yapay Zeka (AI)** teknolojilerinden aktif destek alınarak; modern web mimarisi (SPA), gerçek zamanlı iletişim (WebSocket) ve maliyet etkin çözümler (YouTube Entegrasyonu) bir araya getirilmiştir.

🔗 **Repo:** [https://github.com/eyupbd07/online-egitim](https://github.com/eyupbd07/online-egitim)

## ✨ Öne Çıkan Özellikler

Proje, temel LMS gereksinimlerini modern çözümlerle karşılar:

* **⚡ SPA Mimarisi:** Inertia.js sayesinde sayfa yenilenmeden çalışan, uygulama hissiyatında hızlı arayüz.
* **🎥 YouTube Tabanlı Ders Sistemi:** Sunucu maliyetini düşürmek için ders videoları doğrudan YouTube API/Embed mantığıyla entegre edilmiştir.
* **💬 Canlı Sohbet (Real-Time):** Laravel Reverb (WebSocket) altyapısı ile öğrenci ve eğitmen arasında anlık mesajlaşma.
* **📜 Dinamik Sertifika:** Eğitimi başarıyla tamamlayan öğrencilere özel, barkodlu ve doğrulanabilir PDF sertifika üretimi.
* **📝 Quiz & Ödev:** Çoktan seçmeli sınav sistemi ve dosya yüklemeli ödev teslim modülü.
* **👥 Rol Yönetimi:** Admin, Eğitmen ve Öğrenci için ayrıştırılmış özel yönetim panelleri.

## 🛠 Teknolojiler & Geliştirme Ortamı

Proje **XAMPP** ortamında geliştirilmiştir ve aşağıdaki teknoloji yığınını kullanır:

* **Geliştirme Ortamı:** XAMPP (Apache/MySQL)
* **Backend:** Laravel 11.x
* **Frontend:** Vue.js 3 + Vuetify (Materio Template)
* **Full-Stack Köprü:** Inertia.js
* **WebSocket:** Laravel Reverb
* **Veritabanı:** MySQL

## ⚙️ Kurulum ve Çalıştırma

Projeyi yerel bilgisayarınızda (Localhost) çalıştırmak için adımları takip edin:

### 1. Projeyi Klonlayın
```bash
git clone [https://github.com/eyupbd07/online-egitim.git](https://github.com/eyupbd07/online-egitim.git)
cd online-egitim

```

### 2. Bağımlılıkları Yükleyin

Backend (PHP) ve Frontend (JS) paketlerini yükleyin:

```bash
composer install
npm install

```

### 3. Çevre Ayarlarını Yapın

`.env.example` dosyasının kopyasını oluşturup adını `.env` yapın ve veritabanı bilgilerinizi girin.

```bash
cp .env.example .env
php artisan key:generate

```

### 4. Veritabanı Kurulumu (SQL İçe Aktarma)

Bu projede Migration çalıştırmanıza gerek yoktur. Hazır veriler ve tablo yapısı için:

* Proje dosyaları içinde verilen **`.sql`** uzantılı veritabanı dosyasını **PhpMyAdmin** veya veritabanı yönetim aracınızdan içeri aktarın (Import). Tablolar ve veriler hazır gelecektir.

### 5. Sistemi Başlatın

Sistemin tam fonksiyonlu çalışması için aşağıdaki 3 komutu **ayrı terminallerde** çalıştırın:

**Terminal 1 (Laravel Sunucusu):**

```bash
php artisan serve

```

**Terminal 2 (Frontend Derleyici):**

```bash
npm run dev

```

**Terminal 3 (Canlı Sohbet - Reverb):**

```bash
php artisan reverb:start

```

Artık tarayıcınızdan `http://localhost:8000` adresine giderek projeyi inceleyebilirsiniz.

## 🤝 Katkıda Bulunma

Bu proje bir öğrenci bitirme projesidir ve geliştirilmeye açıktır. Hata bildirimleri ve "Pull Request" gönderimleri memnuniyetle karşılanır.

## 📄 Lisans

Bu proje [MIT Lisansı]() altında sunulmaktadır.

```

```
