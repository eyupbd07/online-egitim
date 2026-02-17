# 🎓 Online Eğitim Yönetim Sistemi (LMS)

Bu proje, **Laravel 11** ve **Vue.js 3** teknolojileri kullanılarak **XAMPP** ortamında geliştirilmiş, açık kaynaklı bir Öğrenim Yönetim Sistemi (LMS) prototipidir.

Temel eğitim süreçlerini (ders izleme, sınav, ödev, sertifika) kapsayan ve **Inertia.js** ile SPA (Tek Sayfa Uygulama) mantığında çalışan başlangıç seviyesinde bir projedir.

🔗 **Repo:** [https://github.com/eyupbd07/online-egitim](https://github.com/eyupbd07/online-egitim)

## ✨ Özellikler

* **SPA Mimarisi:** Inertia.js ile sayfa yenilenmeden çalışan hızlı arayüz.
* **Canlı Sohbet (Chat):** Laravel Reverb (WebSocket) ile anlık mesajlaşma.
* **Ders & İçerik:** Video ve metin tabanlı ders takibi.
* **Quiz Sistemi:** Çoktan seçmeli sınav ve değerlendirme.
* **Ödev Modülü:** Dosya yüklemeli ödev teslimi ve eğitmen notlandırması.
* **Sertifika:** Eğitimi tamamlayanlara özel barkodlu PDF sertifika üretimi.
* **Rol Yönetimi:** Admin, Eğitmen ve Öğrenci panelleri.

## 🛠 Teknolojiler & Geliştirme Ortamı

* **Geliştirme Ortamı:** XAMPP (Apache/MySQL)
* **Backend:** Laravel 11.x
* **Frontend:** Vue.js 3 + Vuetify
* **İletişim:** Inertia.js + Laravel Reverb (WebSocket)
* **Veritabanı:** MySQL

## ⚙️ Kurulum

1.  **Projeyi İndirin:**
    ```bash
    git clone [https://github.com/eyupbd07/online-egitim.git](https://github.com/eyupbd07/online-egitim.git)
    cd online-egitim
    ```

2.  **Paketleri Yükleyin:**
    ```bash
    composer install
    npm install
    ```

3.  **Ayarları Yapın:**
    `.env.example` dosyasının adını `.env` yapın ve veritabanı bilgilerinizi girin.
    ```bash
    php artisan key:generate
    ```

4.  **Veritabanı Kurulumu (SQL):**
    Proje dosyaları içinde verilen **`.sql`** uzantılı veritabanı dosyasını **PhpMyAdmin** üzerinden içe aktarın (Import edin). Migration çalıştırmanıza gerek yoktur, tablolar ve veriler hazır gelecektir.

5.  **Sistemi Başlatın:**
    Projeyi tam fonksiyonlu çalıştırmak için aşağıdaki komutları ayrı terminallerde girin:

    ```bash
    php artisan serve        # Laravel Sunucusu
    npm run dev              # Frontend (Vue)
    php artisan reverb:start # Chat Sunucusu
    ```

## 📄 Lisans
Bu proje [MIT Lisansı](LICENSE) altındadır.
