<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class AdminCertificateController extends Controller
{
    // List Permintaan Sertifikat
    public function index()
    {
        // Tampilkan yang pending paling atas
        $certificates = Certificate::with(['user', 'course'])
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->latest()
            ->paginate(10);

        return view('admin.certificates.index', compact('certificates'));
    }

    // Aksi Approve / Reject
    public function update(Request $request, Certificate $certificate)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $data = [
            'status' => $request->status,
        ];

        // Jika diapprove, set tanggal terbit sekarang
        if ($request->status == 'approved') {
            $data['issued_at'] = now();
        }

        $certificate->update($data);

        return back()->with('success', 'Status sertifikat diperbarui.');
    }
}