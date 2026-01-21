@extends('layouts.app')

@section('title', 'Sistem Petani Alpukat')

@section('content')

    <main id="home" class="bg-gradient-to-t from-green-100 via-white to-green-50 w-full min-h-screen flex items-center">
        <div
            class="max-w-[1280px] mx-auto px-6 md:px-16 flex flex-col md:flex-row items-center justify-between gap-12 py-20">

            <!-- Kiri: Teks -->
            <div class="md:w-1/2 space-y-6 text-center md:text-left">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight text-[#1B5E20]">
                    <span class="block">Manajemen & Monitoring</span>
                    <span class="block text-[#2E7D32]">Petani Alpukat</span>
                </h1>

                <p class="text-gray-700 text-base sm:text-lg max-w-xl mx-auto md:mx-0 leading-relaxed">
                    Platform digital untuk membantu petani mencatat kegiatan tanam,
                    memantau pertumbuhan, mengelola pemupukan, hingga laporan panen
                    — semua dalam satu sistem terpadu.
                </p>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-center md:justify-start gap-5 mt-6">
                    <a href="#fitur"
                        class="bg-[#4CAF50] text-white text-base font-medium px-7 py-3 rounded-full hover:bg-[#388E3C] shadow-md transition whitespace-nowrap">
                        Lihat Fitur
                    </a>

                    <span class="text-sm sm:text-base text-gray-700">
                        Ingin bergabung sebagai petani?
                        <a href="#" class="text-[#2E7D32] font-semibold hover:underline">
                            Daftar Sekarang
                        </a>
                    </span>
                </div>
            </div>

            <!-- Kanan: Gambar -->
            <div class="md:w-1/2 w-full flex justify-center items-center">
                <div
                    class="relative w-full 
                max-w-[220px] 
                sm:max-w-[250px] 
                md:max-w-[280px] 
                lg:max-w-[320px] 
                mx-auto">
                    <img src="{{ asset('images/mockup.png') }}" alt="Petani Alpukat"
                        class="w-full h-auto object-contain rounded-2xl shadow-xl animate-float mx-auto"
                        style="
                mask-image: linear-gradient(to bottom, black 90%, transparent);
                -webkit-mask-image: linear-gradient(to bottom, black 90%, transparent);
            ">
                    <div class="absolute inset-0 rounded-2xl ring-4 ring-green-200 opacity-70 blur-sm"></div>
                </div>
            </div>


        </div>
    </main>

    <section id="about" class="min-h-screen py-24 bg-gradient-to-t from-green-50 to-white flex items-center">
        <div class="container mx-auto px-6 md:px-16">
            <div class="grid md:grid-cols-2 gap-12 items-center">

                <div class="flex justify-center">
                    <img src="{{ asset('images/avocado-farm.jpg') }}" alt="Tentang Sistem Petani Alpukat"
                        class="w-full max-w-md md:max-w-lg drop-shadow-xl rounded-3xl">
                </div>

                <div>
                    <h2 class="text-5xl md:text-6xl font-extrabold text-[#1B5E20] mb-6 leading-tight">
                        Apa itu
                        <span class="text-[#4CAF50]">Sistem Petani Alpukat</span>?
                    </h2>
                    <p class="text-lg text-gray-700 leading-relaxed mb-4">
                        Ini adalah platform digital untuk membantu petani alpukat
                        dalam mengelola kegiatan pertanian mereka.
                    </p>
                    <p class="text-lg text-gray-700 leading-relaxed">
                        Dengan sistem ini, data pertanian menjadi lebih teratur,
                        mudah dipantau, dan meningkatkan hasil panen.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <section id="fitur" class="relative min-h-screen py-36 bg-cover bg-center bg-no-repeat bg-fixed"
        style="background-image: url('{{ asset('images/bg-avocado.jpg') }}');">

        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <div class="relative flex flex-col items-center justify-center h-full px-6 md:px-32 text-white">

            <div class="max-w-4xl text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    Fitur Utama Sistem Petani Alpukat
                </h2>
                <p class="text-lg leading-relaxed">
                    Semua kebutuhan petani alpukat dalam satu platform.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 max-w-5xl w-full">
                <div class="bg-white bg-opacity-20 backdrop-blur-md rounded-2xl p-6 shadow-lg">
                    <h3 class="text-xl font-semibold mb-3">Manajemen Tanam</h3>
                    <p>Catat detail tanam dan data kebun.</p>
                </div>

                <div class="bg-white bg-opacity-20 backdrop-blur-md rounded-2xl p-6 shadow-lg">
                    <h3 class="text-xl font-semibold mb-3">Monitoring</h3>
                    <p>Pantau pertumbuhan tanaman secara berkala.</p>
                </div>

                <div class="bg-white bg-opacity-20 backdrop-blur-md rounded-2xl p-6 shadow-lg">
                    <h3 class="text-xl font-semibold mb-3">Laporan Panen</h3>
                    <p>Analisis hasil panen dan produktivitas.</p>
                </div>
            </div>

        </div>
    </section>

@endsection
