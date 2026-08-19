<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CBT Project</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;855&display=swap" rel="stylesheet">

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
        
        <!-- Background glows (Wrapped in absolute overflow-hidden to prevent layout shifting/overflow) -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute inset-0 glow-backdrop"></div>
            <div class="absolute top-[-20%] left-[-10%] w-[600px] h-[600px] rounded-full bg-indigo-600/10 blur-[150px]"></div>
            <div class="absolute bottom-[-20%] right-[-10%] w-[600px] h-[600px] rounded-full bg-purple-600/10 blur-[150px]"></div>
        </div>

        <!-- Main Centered Layout Wrapper -->
        <div class="w-full max-w-7xl min-h-screen flex flex-col justify-between relative z-10 px-6 sm:px-8">
            
            <!-- Header (Top Right Login Button) -->
            <header class="w-full py-8 flex justify-between items-center relative z-50">
                <!-- Logo -->
                <div class="flex items-center gap-2 select-none">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-indigo-600 to-purple-500 flex items-center justify-center shadow-lg shadow-indigo-500/20 shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold tracking-wide text-slate-300">CBT <span class="text-indigo-400">Engine</span></span>
                </div>

                <!-- Login / Auth Button -->
                <div>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white text-xs font-semibold rounded-full shadow-lg shadow-indigo-600/25 hover:shadow-indigo-600/35 hover:scale-[1.03] transition-all duration-300 tracking-wider uppercase">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-7 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white text-xs font-bold rounded-full shadow-lg shadow-indigo-600/20 hover:shadow-indigo-650/30 hover:scale-[1.05] transition-all duration-300 tracking-wider uppercase">
                                Log in
                            </a>
                        @endauth
                    @endif
                </div>
            </header>

            <!-- Centered CBT Project Text -->
            <main class="flex-1 flex flex-col items-center justify-center text-center -mt-16 w-full overflow-hidden">
                <!-- Pill Badge -->
                <div class="mb-6 inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/5 border border-indigo-500/20 text-[10px] font-bold tracking-widest text-indigo-300 uppercase animate-float">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </span>
                    Sistem Ujian Online
                </div>

                <!-- Giant Title -->
                <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl xl:text-9xl font-extrabold tracking-tighter bg-gradient-to-b from-white via-slate-100 to-indigo-300 bg-clip-text text-transparent drop-shadow-lg select-none text-glow pb-2 max-w-full break-words">
                    CBT Project
                </h1>
                
                <!-- Description -->
                <p class="mt-4 text-slate-400 text-xs sm:text-sm tracking-widest uppercase font-semibold max-w-md border-t border-slate-900 pt-4">
                    Platform Ujian Berbasis Komputer Modern
                </p>
            </main>

            <!-- Subtle Footer -->
            <footer class="w-full py-8 text-center text-[10px] text-slate-600 tracking-wider relative z-10 uppercase">
                &copy; {{ date('Y') }} CBT Project. Seluruh Hak Cipta Dilindungi.
            </footer>

        </div>

    </body>
</html>
