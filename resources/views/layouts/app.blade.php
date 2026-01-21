<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>@yield('title', 'My Portfolio')</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Tailwind CDN (boleh, tapi untuk production sebaiknya Vite) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        @keyframes floatUpDown {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .animate-float {
            animation: floatUpDown 3s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-white text-gray-900 min-h-screen m-0">

    {{-- Navbar --}}
    @include('layouts.navbar')

    <main class="mt-6">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sections = document.querySelectorAll("section[id], main[id]");
            const navLinks = document.querySelectorAll(".nav-link");

            function onScroll() {
                const scrollY = window.scrollY;

                sections.forEach((section) => {
                    const sectionTop = section.offsetTop - 60;
                    const sectionHeight = section.offsetHeight;
                    const sectionId = section.getAttribute("id");

                    if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                        navLinks.forEach((link) => {
                            link.classList.remove(
                                "text-[#4CAF50]",
                                "border-b-2",
                                "border-[#4CAF50]",
                                "font-semibold"
                            );
                            link.classList.add("text-black", "border-transparent");

                            if (link.dataset.target === sectionId) {
                                link.classList.remove("text-black", "border-transparent");
                                link.classList.add(
                                    "text-[#4CAF50]",
                                    "border-b-2",
                                    "border-[#4CAF50]",
                                    "font-semibold"
                                );
                            }
                        });
                    }
                });
            }

            window.addEventListener("scroll", onScroll);
            onScroll();
        });
    </script>

</body>

</html>
