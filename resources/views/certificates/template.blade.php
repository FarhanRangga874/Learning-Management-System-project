<!DOCTYPE html>
<html>
<head>
    <title>Sertifikat</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; text-align: center; border: 10px solid #4F46E5; padding: 50px; height: 90%; }
        .logo { font-size: 30px; font-weight: bold; color: #4F46E5; margin-bottom: 20px; }
        .title { font-size: 50px; font-weight: bold; color: #333; margin-bottom: 10px; }
        .subtitle { font-size: 20px; color: #666; margin-bottom: 40px; }
        .name { font-size: 40px; font-weight: bold; color: #111; border-bottom: 2px solid #ccc; display: inline-block; padding-bottom: 10px; margin-bottom: 30px; min-width: 400px; }
        .course-name { font-size: 25px; font-weight: bold; color: #4F46E5; margin: 20px 0; }
        .footer { margin-top: 60px; font-size: 14px; color: #888; }
        .code { font-size: 12px; margin-top: 10px; color: #aaa; }
    </style>
</head>
<body>
    <div class="logo">LMS PLATFORM</div>
    
    <div class="title">SERTIFIKAT PENYELESAIAN</div>
    <div class="subtitle">Diberikan dengan bangga kepada:</div>
    
    <div class="name">{{ $name }}</div>
    
    <div class="subtitle">Atas dedikasinya menyelesaikan kursus:</div>
    <div class="course-name">{{ $course }}</div>

    <div class="footer">
        <p>Diterbitkan pada: {{ $date }}</p>
        <p>Direktur Utama</p>
    </div>

    <div class="code">ID Verifikasi: {{ $code }}</div>
</body>
</html>