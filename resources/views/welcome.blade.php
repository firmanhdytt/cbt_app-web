<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CBT Portal - SMA Negeri 5 Medan</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;855&display=swap" rel="stylesheet">
        <link rel="icon" type="image/png" href="/images/logo-sman5medan.png">

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        },
                        animation: {
                            'slow-pulse': 'pulse 8s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                            'float': 'float 6s ease-in-out infinite',
                        },
                        keyframes: {
                            float: {
                                '0%, 100%': { transform: 'translateY(0)' },
                                '50%': { transform: 'translateY(-10px)' },
                            }
                        }
                    }
                }
            }
        </script>

        <style>
            .glow-backdrop {
                background: radial-gradient(circle at center, rgba(79, 70, 229, 0.18) 0%, rgba(147, 51, 234, 0.07) 40%, rgba(3, 7, 18, 0) 70%);
            }
            .text-glow {
                text-shadow: 0 0 40px rgba(99, 102, 241, 0.2);
            }
        </style>
    </head>
    <body class="bg-[#030712] text-slate-100 min-h-screen w-full relative overflow-x-hidden antialiased flex flex-col items-center">
        
        <!-- Background glows -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute inset-0 glow-backdrop"></div>
            <div class="absolute top-[-20%] left-[-10%] w-[600px] h-[600px] rounded-full bg-indigo-600/10 blur-[150px]"></div>
            <div class="absolute bottom-[-20%] right-[-10%] w-[600px] h-[600px] rounded-full bg-purple-600/10 blur-[150px]"></div>
        </div>

        <!-- Main Centered Layout Wrapper -->
        <div class="w-full max-w-7xl min-h-screen flex flex-col justify-between relative z-10 px-6 sm:px-8">
            
            <!-- Header (Top Right Login Button) -->
            <header class="w-full py-8 flex justify-between items-center relative z-50">
                <!-- Logo & School Brand -->
                <div class="flex items-center gap-3 select-none">
                    <img src="/images/logo-sman5medan.png" alt="Logo SMAN 5 Medan" class="w-9 h-9 rounded-full object-contain bg-white/10 p-0.5 shadow-sm border border-indigo-500/30">
                    <div>
                        <div class="text-sm font-extrabold tracking-wide text-white">SMAN 5 MEDAN</div>
                        <div class="text-[10px] text-indigo-400 font-semibold tracking-wider uppercase">CBT Exam Portal</div>
                    </div>
                </div>

                <!-- Login / Auth Button -->
                <div>
                    @if (Route::has('login'))
                        @auth
                            <a href="/dashboard" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white text-xs font-semibold rounded-full shadow-lg shadow-indigo-600/25 hover:shadow-indigo-600/35 hover:scale-[1.03] transition-all duration-300 tracking-wider uppercase">
                                Dashboard
                            </a>
                        @else
                            <a href="/login" class="px-7 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white text-xs font-bold rounded-full shadow-lg shadow-indigo-600/20 hover:shadow-indigo-650/30 hover:scale-[1.05] transition-all duration-300 tracking-wider uppercase">
                                Masuk Ujian
                            </a>
                        @endauth
                    @endif
                </div>
            </header>

            <!-- Centered CBT Project Text -->
            <main class="flex-1 flex flex-col items-center justify-center text-center -mt-6 w-full overflow-hidden py-8">
                <!-- Pill Badge -->
                <div class="mb-4 inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/30 text-[11px] font-bold tracking-widest text-indigo-300 uppercase">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </span>
                    SMA NEGERI 5 MEDAN
                </div>

                <!-- Title -->
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tighter bg-gradient-to-b from-white via-slate-100 to-indigo-300 bg-clip-text text-transparent drop-shadow-lg select-none text-glow pb-2 max-w-4xl break-words">
                    Portal Ujian CBT SMAN 5 Medan
                </h1>
                
                <!-- Description -->
                <p class="mt-4 text-slate-300 text-xs sm:text-sm tracking-wide font-medium max-w-xl border-t border-slate-800/80 pt-4 px-4 leading-relaxed">
                    Sistem Evaluasi & Ujian Berbasis Komputer Resmi SMA Negeri 5 Medan<br>
                    <span class="text-indigo-400 font-semibold">Integrasi Exambro Mobile Kiosk & Real-time Proctoring System</span>
                </p>

                <div class="mt-8 flex flex-wrap gap-4 justify-center">
                    <a href="/login" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm rounded-full shadow-lg shadow-indigo-600/30 transition-all duration-300 transform hover:-translate-y-0.5">
                        Mulai Masuk Ujian <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </main>

            <!-- Subtle Footer -->
            <footer class="w-full py-6 text-center text-[11px] text-slate-500 tracking-wider relative z-10">
                &copy; {{ date('Y') }} SMA Negeri 5 Medan. Jl. Pelajar No.17, Teladan Timur, Medan. Seluruh Hak Cipta Dilindungi.
            </footer>

        </div>

    </body>
</html>
