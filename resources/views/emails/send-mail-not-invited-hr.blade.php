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
        .text-center { text-align: center; }
        @media (max-width: 600px) {
            .p-6 { padding: 1rem; }
            .text-2xl { font-size: 1.25rem; }
            .p-4 { padding: 0.75rem; }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden my-8 p-6">
        <div class="text-center">
            <img src="{{ $message->embed(public_path('frontend-new/img/viscus.png')) }}" alt="Viscus Media Dharma" style="width: 150px;">
        </div>
        <h3 class="text-2xl font-bold mb-4 text-gray-900 mt-5">Dear {{ $data['first_name'] }} {{ $data['last_name'] }},</h3>
        <p class="mb-4 text-gray-700">
            Terima kasih anda telah melamar sebagai {{ $data['job_title'] }} di Viscus Media Dharma.
        </p>

        <p class="mb-4 text-gray-700">
            Terima kasih atas kesediaan Anda untuk melamar pada posisi {{ $data['job_title'] }} di Viscus Media Dharma
            dan mengikuti proses seleksi yang tersedia.
            Kami telah membaca resume Anda, namun tidak dapat meneruskannya ke seleksi tahap selanjutnya.
            Kualifikasi Anda saat ini belum sesuai dengan kebutuhan perusahaan kami.
        </p>

        <p class="mb-4 text-gray-700">Kami telah menyimpan resume Anda di database dan akan memberitahu Anda apabila tersedia posisi yang tepat di perusahaan kami.
            Demikian informasi ini kami sampaikan, semoga sukses selalu! Terima kasih.
        </p>



        <div class="border-t pt-4 text-sm text-gray-600">
            <p>Tim Rekrutmen</p>
            <p>{{ config('app.name') }}</p>
            <p>Jl. Karang Ploso No.50, Gempol, Condongcatur, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281</p>
            <p>Email: halo@viscusmedia.com | Tel: +62 81-3282-666-46</p>
        </div>
    </div>
</body>

</html>
