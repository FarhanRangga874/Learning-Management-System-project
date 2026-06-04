<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function request(Course $course)
    {
        // Cek apakah sudah pernah request
        $existingCert = Certificate::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();

        if ($existingCert) {
            return back()->with('error', 'Anda sudah mengajukan sertifikat ini.');
        }

        // === [LOGIKA PEMBUATAN ID SERTIFIKAT] ===
        // Format yang diinginkan: C[ID_KURSUS]_[TANGGAL]_[RANDOM]
        // Contoh Hasil: C012_01022026_X7Z9
        
        // 1. Ambil ID Kursus dan pad dengan nol di depan (misal ID 5 jadi 005)
        $courseCode = str_pad($course->id, 3, '0', STR_PAD_LEFT);
        
        // 2. Ambil Tanggal Hari Ini (Format: HariBulanTahun -> 01022026)
        $dateCode = date('dmY');
        
        // 3. String Random 4 karakter agar unik
        $randomCode = strtoupper(Str::random(4));

        // 4. Gabungkan
        $finalCertificateId = "C{$courseCode}_{$dateCode}_{$randomCode}";
        // =========================================

        // Tentukan status berdasarkan certificate_policy
        $status = 'pending';
        $issuedAt = null;
        $message = 'Permintaan sertifikat dikirim! Tunggu verifikasi admin.';

        if ($course->certificate_policy === 'auto') {
            $status = 'approved';
            $issuedAt = now();
            $message = 'Sertifikat telah diterbitkan! Silakan unduh sekarang.';
        }

        Certificate::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'status' => $status,
            'certificate_code' => $finalCertificateId,
            'issued_at' => $issuedAt,
        ]);

        return back()->with('success', $message);
    }

    public function download(Certificate $certificate)
    {
        // Validasi User / Admin
        if (Auth::id() !== $certificate->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Cek status
        if ($certificate->status !== 'approved' && Auth::user()->role !== 'admin') {
            return back()->with('error', 'Sertifikat belum disetujui.');
        }

        // Ambil Template & Load PDF
        $template = CertificateTemplate::first();
        $pdf = Pdf::loadView('certificates.template', compact('certificate', 'template'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('Sertifikat-' . $certificate->certificate_code . '.pdf');
    }
}