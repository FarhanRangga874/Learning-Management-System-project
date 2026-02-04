<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sertifikat Kelulusan - {{ $certificate->user->name }}</title>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Great+Vibes&family=Lato:wght@300;400;700&display=swap');
        
        @page { margin: 0px; }
        
        body { 
            margin: 0px; 
            font-family: 'Lato', 'Helvetica', sans-serif;
            color: #1e293b;
        }
        
        .certificate-container {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0; left: 0;
            text-align: center;
        }

        /* --- BACKGROUND --- */
        .bg-image {
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
        }

        /* --- CONTENT --- */
        .content {
            position: relative;
            top: 12%; 
            width: 80%;
            margin: 0 auto;
            text-align: center;
        }

        .header-text { 
            font-family: 'Cinzel', serif;
            font-size: 48px; 
            font-weight: 700; 
            text-transform: uppercase; 
            letter-spacing: 6px;
            color: #1e293b;
            margin-bottom: 5px;
            line-height: 1.2;
        }

        .sub-header { 
            font-size: 16px; 
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #64748b; 
            margin-bottom: 35px; 
        }

        .candidate-name { 
            font-family: 'Great Vibes', cursive;
            font-size: 64px; 
            font-weight: normal; 
            color: #c5a059;
            margin: 10px 0; 
            line-height: 1;
            padding-bottom: 15px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .divider {
            width: 200px;
            height: 2px;
            background: #e2e8f0;
            margin: 0 auto 25px auto;
        }

        .description { 
            font-size: 16px; 
            color: #475569; 
            margin-bottom: 10px;
            font-weight: 300;
        }
        
        .course-title { 
            font-family: 'Cinzel', serif;
            font-size: 26px; 
            font-weight: 700; 
            color: #1e293b; 
            margin: 0 0 40px 0; 
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* --- SIGNATURE SECTION --- */
        .signature-section {
            margin-top: 50px;
            display: table;
            width: 100%;
            page-break-inside: avoid;
        }
        .signature-box {
            display: table-cell;
            vertical-align: bottom;
            text-align: center;
            width: 33%;
        }
        
        .signature-img {
            height: 80px;
            margin-bottom: 5px;
            object-fit: contain;
        }

        .signer-line {
            width: 180px;
            border-bottom: 1px solid #94a3b8;
            margin: 5px auto;
        }

        .signer-name { 
            font-family: 'Lato', sans-serif;
            font-weight: bold; 
            font-size: 16px; 
            color: #1e293b; 
            margin-top: 5px;
        }
        .signer-title { 
            font-size: 12px; 
            color: #64748b; 
            font-style: italic;
        }
        
        .date-text {
            font-size: 14px;
            font-weight: bold;
            color: #334155;
            margin-bottom: 5px;
        }
        
        .id-badge {
            font-size: 10px;
            color: #94a3b8;
            font-family: monospace;
            border: 1px solid #e2e8f0;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
        }

    </style>
</head>
<body>

    <div class="certificate-container">
        
        {{-- BACKGROUND --}}
        @if($template->background_image)
            {{-- Menggunakan background yang diupload user --}}
            <img src="{{ public_path('storage/' . $template->background_image) }}" class="bg-image" alt="Background">
        @else
            {{-- [PERBAIKAN] Menggunakan Background Default --}}
            {{-- Pastikan file 'certificate-bg.png' ada di folder: public/images/ --}}
            <img src="{{ public_path('images/certificate-bg.png') }}" class="bg-image" alt="Default Background">
        @endif

        <div class="content">
            {{-- Header --}}
            <div class="header-text">Sertifikat Kelulusan</div>
            
            <div class="sub-header">NO. {{ $certificate->certificate_code }}</div>
            
            <div class="description">Diberikan dengan bangga kepada:</div>
            
            {{-- Nama Kandidat --}}
            <div class="candidate-name">{{ $certificate->user->name }}</div>
            
            <div class="divider"></div>

            <div class="description">Telah menyelesaikan semua modul dan ujian pada kursus:</div>
            
            {{-- Judul Kursus --}}
            <div class="course-title">{{ $certificate->course->title }}</div>
            
            {{-- Tanda Tangan & Footer --}}
            <div class="signature-section">
                
                {{-- KIRI: Tanggal & ID --}}
                <div class="signature-box" style="text-align: left; padding-left: 40px;">
                    <div class="date-text">
                        {{ $certificate->created_at->format('d F Y') }}
                    </div>
                    <div style="font-size: 12px; color: #64748b;">Tanggal Penerbitan</div>
                    <br>
                    
                    <div class="id-badge">
                        ID: {{ $certificate->certificate_code }}
                    </div>
                </div>

                {{-- TENGAH: Kosong --}}
                <div class="signature-box"></div>

                {{-- KANAN: Tanda Tangan --}}
                <div class="signature-box" style="text-align: right; padding-right: 40px;">
                    <div style="text-align: center; display: inline-block;">
                        @if($template->signature_image)
                            <img src="{{ public_path('storage/' . $template->signature_image) }}" class="signature-img">
                        @else
                            <div style="height: 80px;"></div>
                        @endif
                        
                        <div class="signer-line"></div>
                        <div class="signer-name">{{ $template->signature_name }}</div>
                        <div class="signer-title">{{ $template->signature_position }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>