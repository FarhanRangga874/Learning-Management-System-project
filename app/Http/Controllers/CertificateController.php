<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf; // <--- Import PDF

class CertificateController extends Controller
{
    // 1. SISWA: Request / Generate Sertifikat
    public function request(Course $course)
    {
        $userId = Auth::id();

        // A. CEK APAKAH SUDAH SELESAI 100% (Safety Check)
        $totalLessons = Lesson::whereHas('chapter', fn($q) => $q->where('course_id', $course->id))->count();
        $completedLessons = LessonCompletion::where('user_id', $userId)
            ->where('course_id', $course->id)
            ->count();

        if ($completedLessons < $totalLessons) {
            return back()->with('error', 'Anda belum menyelesaikan seluruh materi.');
        }

        // B. CEK APAKAH SUDAH PERNAH REQUEST
        $existing = Certificate::where('user_id', $userId)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Sertifikat sudah diajukan.');
        }

        // C. TENTUKAN STATUS BERDASARKAN KEBIJAKAN KURSUS
        // Jika Policy 'auto', status langsung 'approved'
        // Jika Policy 'manual', status 'pending'
        if ($course->certificate_policy === 'auto') {
            $status = 'approved';
            $issuedAt = now();
            $msg = 'Selamat! Sertifikat Anda berhasil diterbitkan.';
        } else {
            $status = 'pending';
            $issuedAt = null;
            $msg = 'Permintaan dikirim! Menunggu persetujuan Admin.';
        }

        // D. CREATE DATABASE
        Certificate::create([
            'user_id' => $userId,
            'course_id' => $course->id,
            'certificate_code' => 'CERT-' . strtoupper(Str::random(8)),
            'status' => $status,
            'issued_at' => $issuedAt,
        ]);

        return back()->with('success', $msg);
    }

    // 2. SISWA: Download PDF
    public function download(Certificate $certificate)
    {
        // Validasi Pemilik
        if ($certificate->user_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Validasi Status
        if ($certificate->status != 'approved') {
            return back()->with('error', 'Sertifikat belum disetujui.');
        }

        // Data untuk dicetak di PDF
        $data = [
            'name' => $certificate->user->name,
            'course' => $certificate->course->title,
            'date' => $certificate->issued_at->format('d F Y'),
            'code' => $certificate->certificate_code,
        ];

        // Load View PDF
        $pdf = Pdf::loadView('certificates.template', $data);
        
        // Atur ukuran kertas (Landscape A4 biasanya untuk sertifikat)
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Sertifikat-' . $certificate->course->slug . '.pdf');
    }
}