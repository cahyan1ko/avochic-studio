<nav
    class="fixed top-4 left-4 right-4 z-50 h-[60px] px-5 py-2 bg-white/10 backdrop-blur-2xl backdrop-saturate-200 border border-white/20 shadow-md rounded-2xl md:rounded-none md:top-0 md:left-0 md:right-0 transition-all duration-300">
    <div class="max-w-[1200px] mx-auto px-5 h-full flex items-center justify-between relative">
        <!-- Logo -->
        <a href="#home" class="flex items-center gap-2 z-10">
            <img src="{{ asset('logo/avochic-landscape.png') }}" alt="Logo"
                class="h-6 sm:h-7 md:h-8 lg:h-9 xl:h-10 transition-all duration-300" />
        </a>

        <!-- Menu Desktop -->
        <div class="hidden md:block absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <ul class="flex gap-6 list-none items-center">
                <li class="w-24 text-center">
                    <a href="#home" data-target="home"
                        class="nav-link block text-black border-b-2 border-transparent">
                        Home
                    </a>
                </li>
                <li class="w-24 text-center">
                    <a href="#about" data-target="about"
                        class="nav-link block text-black border-b-2 border-transparent">
                        Tentang
                    </a>
                </li>
                <li class="w-24 text-center">
                    <a href="#fitur" data-target="fitur"
                        class="nav-link block text-black border-b-2 border-transparent">
                        Fitur
                    </a>
                </li>
                <li class="w-24 text-center">
                    <a href="#testi" data-target="testi"
                        class="nav-link block text-black border-b-2 border-transparent">
                        Testi
                    </a>
                </li>
                <li class="w-24 text-center">
                    <a href="#tutorial" data-target="tutorial"
                        class="nav-link block text-black border-b-2 border-transparent">
                        Tutorial
                    </a>
                </li>
            </ul>
        </div>

        <!-- Tombol -->
        <div class="flex items-center z-10">
            <a href="#"
                class="hidden md:inline-block bg-[#4CAF50] text-white text-sm px-5 py-2 rounded-full hover:bg-[#1B5E20] transition">
                Download Apk
            </a>

            <!-- Hamburger -->
            <button class="md:hidden ml-3" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
                <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu"
        class="md:hidden hidden px-5 pt-4 pb-6 mt-2 mx-4 backdrop-blur-xl backdrop-saturate-200 bg-white/90 border border-white/30 shadow-md rounded-2xl transition-all duration-300">
        <ul class="space-y-3 list-none">
            <li>
                <a href="#home" class="block text-gray-800">
                    Home
                </a>
            </li>
            <li>
                <a href="#about" class="block text-gray-800">
                    Tentang
                </a>
            </li>
            <li>
                <a href="#fitur" class="block text-gray-800">
                    Fitur
                </a>
            </li>
            <li>
                <a href="#testi" class="block text-gray-800">
                    Testi
                </a>
            </li>
            <li>
                <a href="#tutorial" class="block text-gray-800">
                    Tutorial
                </a>
            </li>
        </ul>
    </div>
</nav>
