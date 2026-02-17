<!DOCTYPE html>
<html lang="tr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Başarı Sertifikası</title>
    <style>
        @page { margin: 0; size: A4 landscape; }
        
        body {
            font-family: 'DejaVu Serif', serif; /* Daha şık, tırnaklı font */
            background-color: #fffdf5; /* Çok hafif krem, lüks hissi verir */
            margin: 0; padding: 0;
            color: #333;
        }

        /* === ZENGİN ÇERÇEVE SİSTEMİ === */
        .outer-border {
            position: absolute;
            top: 10mm; left: 10mm; right: 10mm; bottom: 10mm;
            border: 2px solid #D4AF37; /* Altın Dış Çizgi */
            z-index: 1;
        }
        
        .middle-border {
            position: absolute;
            top: 12mm; left: 12mm; right: 12mm; bottom: 12mm;
            border: 5px solid #1a237e; /* Lacivert Kalın Çerçeve */
            z-index: 1;
        }

        .inner-line {
            position: absolute;
            top: 14mm; left: 14mm; right: 14mm; bottom: 14mm;
            border: 1px solid #D4AF37; /* En içteki ince altın çizgi */
            z-index: 1;
        }

        /* Köşe Süsleri (Lüks Görünüm İçin) */
        .corner-box {
            position: absolute; width: 15px; height: 15px;
            background: #D4AF37; z-index: 2;
        }
        .tl { top: 10mm; left: 10mm; } .tr { top: 10mm; right: 10mm; }
        .bl { bottom: 10mm; left: 10mm; } .br { bottom: 10mm; right: 10mm; }

        /* === ROZET VE KURDELE (CSS İLE ÇİZİM) === */
        .badge-container {
            position: relative;
            width: 100px; height: 120px;
            margin: 45px auto 10px auto; /* Yukarıdan boşluk */
            text-align: center;
            z-index: 5;
        }

        .badge-circle {
            width: 70px; height: 70px;
            background-color: #1a237e; /* Lacivert */
            border: 4px solid #D4AF37; /* Altın */
            border-radius: 50%;
            margin: 0 auto;
            position: relative;
            z-index: 2;
            color: white;
            line-height: 70px;
            font-size: 30px;
            box-shadow: 0 0 0 2px #fff, 0 0 0 4px #1a237e; /* Katmanlı Kenar */
        }

        .ribbon-tail-left {
            position: absolute; top: 50px; left: 15px;
            width: 0; height: 0;
            border-left: 15px solid transparent;
            border-right: 15px solid transparent;
            border-top: 50px solid #1a237e; /* Lacivert Kurdele */
            transform: rotate(20deg);
            z-index: 1;
        }
        .ribbon-tail-right {
            position: absolute; top: 50px; right: 15px;
            width: 0; height: 0;
            border-left: 15px solid transparent;
            border-right: 15px solid transparent;
            border-top: 50px solid #1a237e;
            transform: rotate(-20deg);
            z-index: 1;
        }

        /* === İÇERİK === */
        .content {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            text-align: center; z-index: 5;
        }

        h1 {
            font-size: 38px;
            color: #D4AF37; /* Altın Başlık */
            text-transform: uppercase;
            letter-spacing: 5px;
            margin-bottom: 5px;
            margin-top: 10px;
        }

        .main-title-sub {
            font-size: 12px; color: #1a237e; letter-spacing: 2px;
            text-transform: uppercase; font-weight: bold;
        }

        /* İSİM ALANI - EN ÖNEMLİ KISIM */
        .student-name {
            font-family: 'DejaVu Serif', serif;
            font-style: italic; /* El yazısı efekti */
            font-size: 56px;
            color: #1a237e; /* Lacivert */
            margin: 20px 0 10px 0;
            padding: 10px;
            text-shadow: 1px 1px 0px rgba(0,0,0,0.1);
        }

        .text-body {
            font-family: 'DejaVu Sans', sans-serif; /* Okunabilirlik için düz font */
            font-size: 15px;
            color: #555;
            width: 65%;
            margin: 10px auto;
            line-height: 1.6;
        }

        .course-name {
            font-family: 'DejaVu Serif', serif;
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-top: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #D4AF37;
            display: inline-block;
        }

        /* === ALT BÖLÜM === */
        .footer {
            position: absolute; bottom: 35mm; width: 100%;
        }
        
        /* Sahte İmza */
        .signature {
            font-family: 'DejaVu Serif', serif;
            font-style: italic;
            font-size: 24px;
            color: #1a237e;
        }
        
        .job-title {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #777;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 5px;
            border-top: 1px solid #ccc;
            display: inline-block;
            padding-top: 5px;
            width: 200px;
        }

        .code {
            position: absolute; bottom: 5mm; width: 100%;
            text-align: center; font-size: 9px; color: #aaa; font-family: sans-serif;
        }
    </style>
</head>
<body>

    <div class="outer-border"></div>
    <div class="middle-border"></div>
    <div class="inner-line"></div>
    
    <div class="corner-box tl"></div>
    <div class="corner-box tr"></div>
    <div class="corner-box bl"></div>
    <div class="corner-box br"></div>

    <div class="content">
        
        <div class="badge-container">
            <div class="ribbon-tail-left"></div>
            <div class="ribbon-tail-right"></div>
            <div class="badge-circle">★</div>
        </div>

        <h1>Başarı Belgesi</h1>
        <div class="main-title-sub">Üstün Başarı ve Katılım Sertifikası</div>
        
        <div class="student-name">
            Sn. {{ $certificate->user->name }}
        </div>

        <div class="text-body">
            Online Eğitim Platformu tarafından düzenlenen eğitim programını başarıyla tamamlayarak, mesleki yetkinliklerini geliştirmiş ve bu belgeyi almaya hak kazanmıştır.
        </div>

        <br>
        <div class="course-name">“ {{ $certificate->course->title }} ”</div>

        <div class="footer">
            <div class="signature">
                Yönetim Kurulu
            </div>
            <div class="job-title">Online Eğitim Direktörlüğü</div>
        </div>

        <div class="code">
            Sertifika Doğrulama Kodu: {{ $certificate->certificate_code }}
        </div>
    </div>
</body>
</html>
