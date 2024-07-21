<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- @vite('resources/css/app.css') --}}
    <style>
        /* Inline Tailwind CSS */
        .bg-gray-100 { background-color: #f7fafc; }
        .font-sans { font-family: Arial, sans-serif; }
        .leading-normal { line-height: 1.5; }
        .tracking-normal { letter-spacing: 0; }
        .max-w-3xl { max-width: 48rem; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .bg-white { background-color: #fff; }
        .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
        .rounded-lg { border-radius: 0.5rem; }
        .overflow-hidden { overflow: hidden; }
        .my-8 { margin-top: 2rem; margin-bottom: 2rem; }
        .p-6 { padding: 1.5rem; }
        .text-2xl { font-size: 1.5rem; }
        .font-bold { font-weight: 700; }
        .mb-4 { margin-bottom: 1rem; }
        .text-blue-500 { color: #3b82f6; }
        .hover\:underline:hover { text-decoration: underline; }
        .text-gray-700 { color: #4a5568; }
        .text-gray-900 { color: #1a202c; }
        .text-sm { font-size: 0.875rem; }
        .border-t { border-top: 1px solid #e2e8f0; }
        .pt-4 { padding-top: 1rem; }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden my-8 p-6">
        <h1 class="text-2xl font-bold mb-4 text-gray-900">Dengan Hormat,</h1>
        <p class="mb-4 text-gray-700">Sehubungan dengan kelanjutan proses seleksi, bersama ini kami mengundang Anda untuk mengikuti proses selanjutnya yang akan diadakan pada:</p>

        <p class="mb-4 text-gray-700"><strong>Hari/Tanggal:</strong> {{ date('l, d F Y', strtotime($data['interview_date'])) }}</p>
        <p class="mb-4 text-gray-700"><strong>Waktu:</strong> {{ date('H.i', strtotime($data['interview_date'])) }} - Selesai</p>
        <p class="mb-4 text-gray-700"><strong>Agenda:</strong> Interview Online (jadwal terlampir)</p>

        <p class="mb-4 text-gray-700"><strong>Join Zoom Meeting</strong></p>
        <p class="mb-4">
            <a href="{{ $data['google_meet_link'] }}" class="text-blue-500 hover:underline">{{ $data['google_meet_link'] }}</a>
        </p>
        {{-- <p class="mb-4 text-gray-700"><strong>Meeting ID:</strong> 836 2718 5674</p>
        <p class="mb-4 text-gray-700"><strong>Passcode:</strong> 645492</p> --}}

        <p class="mb-4 text-gray-700">Dimohon juga untuk dapat hadir 10 menit sebelum jadwal yang telah ditentukan.</p>

        <p class="mb-4 text-gray-700">Demikian kami sampaikan, apabila ada hal-hal yang perlu ditanyakan lebih lanjut, dapat menghubungi kami melalui e-mail atau melalui telepon.</p>

        <p class="mb-4 text-gray-700">Terima kasih atas kerjasamanya.</p>
        
        <div class="border-t pt-4 text-sm text-gray-600">
            <p>{{ config('app.name') }}</p>
            <p>Jl. Raya No.123, Jakarta, Indonesia</p>
            <p>Email: info@BukaLowongan.com | Tel: +62 21 1234 5678</p>
        </div>
    </div>
</body>

</html>
