<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminFaqController extends Controller
{
    public function index()
    {
        // 1. Ambil Data FAQ
        $faqs = Faq::orderBy('ordering', 'asc')->get();
        
        // 2. Ambil Data WhatsApp (Pindahkan ke sini sebelum return)
        $whatsapp = SiteSetting::where('key', 'whatsapp_number')->first();

        // 3. Kirim kedua data ke View
        return view('admin.faqs.index', compact('faqs', 'whatsapp'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'ordering' => 'nullable|integer',
        ]);

        Faq::create($request->all());

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil ditambahkan');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'ordering' => 'nullable|integer',
        ]);

        $faq->update($request->all());

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil diperbarui');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil dihapus');
    }

    public function updateContact(Request $request)
    {
        $request->validate([
            'whatsapp_number' => 'required', // Ubah jadi required saja, validasi angka kita handle manual
        ]);

        // 1. Ambil input
        $number = $request->whatsapp_number;

        // 2. Hapus karakter selain angka (jika admin iseng ngetik spasi atau strip)
        $number = preg_replace('/[^0-9]/', '', $number);

        // 3. Cek awalan nomor dan format ulang ke 62...
        if (substr($number, 0, 1) === '0') {
            // Jika berawalan 0 (misal 0812...), ganti 0 dengan 62
            $number = '62' . substr($number, 1);
        } elseif (substr($number, 0, 2) === '62') {
            // Jika user sudah mengetik 62 di awal, biarkan saja
            $number = $number;
        } else {
            // Jika user mengetik 812... (tanpa 0 dan tanpa 62), tambahkan 62 di depan
            $number = '62' . $number;
        }

        // 4. Simpan ke database
        SiteSetting::updateOrCreate(
            ['key' => 'whatsapp_number'],
            ['value' => $number]
        );

        return redirect()->route('admin.faqs.index')->with('success', 'Nomor WhatsApp berhasil diperbarui!');
    }
}