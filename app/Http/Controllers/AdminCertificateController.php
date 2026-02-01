<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use App\Models\CertificateTemplate;
use Illuminate\Support\Facades\Storage;

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

    public function settings()
    {
        // Kita ambil data pertama, jika tidak ada buat objek kosong
        $template = CertificateTemplate::first() ?? new CertificateTemplate();
        return view('admin.certificates.settings', compact('template'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            'signature_image'  => 'nullable|image|mimes:png|max:1024', // PNG transparan disarankan
            'signature_name'   => 'required|string|max:255',
            'signature_position' => 'required|string|max:255',
        ]);

        $template = CertificateTemplate::first();
        if (!$template) {
            $template = new CertificateTemplate();
        }

        // Handle Upload Background
        if ($request->hasFile('background_image')) {
            // Hapus file lama jika ada
            if ($template->background_image) {
                Storage::disk('public')->delete($template->background_image);
            }
            $template->background_image = $request->file('background_image')->store('certificates/assets', 'public');
        }

        // Handle Upload Tanda Tangan
        if ($request->hasFile('signature_image')) {
            if ($template->signature_image) {
                Storage::disk('public')->delete($template->signature_image);
            }
            $template->signature_image = $request->file('signature_image')->store('certificates/assets', 'public');
        }

        $template->signature_name = $request->signature_name;
        $template->signature_position = $request->signature_position;
        $template->save();

        return redirect()->route('admin.certificates.settings')->with('success', 'Template sertifikat berhasil diperbarui!');
    }
}