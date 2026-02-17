<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf; // PDF kütüphanesi

class CertificateController extends Controller
{
    public function index()
    {
        // Kullanıcının tüm sertifikalarını, kurs bilgisiyle beraber çek
        $certificates = Certificate::where('user_id', auth()->id())
                                   ->with('course') // Kurs ismini görmek için şart
                                   ->latest()
                                   ->get();

        return Inertia::render('Student/Certificates/Index', [
            'certificates' => $certificates
        ]);
    }

    public function download($code)
    {
        $certificate = Certificate::where('certificate_code', $code)->with(['user', 'course'])->firstOrFail();

        $pdf = Pdf::loadView('pdf.certificate', compact('certificate'));
        return $pdf->download('sertifika-'.$code.'.pdf');
    }
}