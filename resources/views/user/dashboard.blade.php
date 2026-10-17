<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - Lokalify 📍</title>
    <!-- Tailwind CSS CDN & Font Plus Jakarta Sans -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(2deg); }
        }
        .animate-float {
            animation: float-slow 5s ease-in-out infinite;
        }
        .glow-teal {
            box-shadow: 0 0 35px -5px rgba(20, 184, 166, 0.25);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex text-xs" x-data="{ sidebarOpen: false }">

    <!-- OVERLAY MOBILE -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-30 lg:hidden"></div>

    <!-- SIDEBAR KIRI -->
    <aside 
        class="bg-[#07162d] text-white flex flex-col justify-between p-4 fixed h-screen z-40 transition-all duration-300 ease-in-out border-r border-slate-800 shadow-2xl w-60"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
        <div class="space-y-6">
            <div class="flex items-center space-x-3 px-1 py-1">
                <div class="bg-teal-500 p-2 rounded-xl text-slate-950 font-extrabold text-sm flex items-center justify-center shadow-lg shadow-teal-500/20 flex-shrink-0">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div class="whitespace-nowrap overflow-hidden">
                    <h1 class="font-extrabold text-sm tracking-wide text-white">Lokalify</h1>
                    <p class="text-[10px] text-teal-400 font-medium">Jelajahi Wisata Lokal</p>
                </div>
            </div>

            <nav class="space-y-1.5">
                <a href="{{ route('user.dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 text-xs font-bold bg-teal-500 text-slate-950 rounded-xl shadow-md shadow-teal-500/20 transition hover:bg-teal-400">
                    <i class="fa-solid fa-house text-sm w-4 text-center"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('user.wishlist') }}" class="flex items-center space-x-3 px-3.5 py-2.5 text-xs font-semibold text-slate-300 hover:text-white hover:bg-white/5 rounded-xl transition">
                    <i class="fa-solid fa-heart text-rose-400 text-sm w-4 text-center"></i>
                    <span>My Wishlist</span>
                </a>
                <a href="{{ route('user.pengaturan') }}" class="flex items-center space-x-3 px-3.5 py-2.5 text-xs font-semibold text-slate-300 hover:text-white hover:bg-white/5 rounded-xl transition">
                    <i class="fa-solid fa-gear text-sm w-4 text-center"></i>
                    <span>Pengaturan</span>
                </a>
            </nav>
        </div>

        <div class="space-y-3 pt-4 border-t border-slate-800/80">
            <div class="flex items-center justify-between px-2 py-1">
                <div class="flex items-center space-x-2.5 overflow-hidden">
                    <div class="w-8 h-8 rounded-lg bg-teal-500 text-slate-950 font-black flex items-center justify-center text-xs flex-shrink-0">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="truncate">
                        <p class="text-xs font-bold text-white truncate leading-tight">{{ auth()->user()->name ?? 'Profile' }}</p>
                        <p class="text-[10px] text-slate-400 truncate">@user</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-rose-400 p-1.5 transition" title="Keluar">
                        <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 min-h-screen flex flex-col transition-all duration-300 ease-in-out bg-slate-950" :class="sidebarOpen ? 'lg:ml-60' : 'lg:ml-0'">
        <header class="bg-slate-900/80 backdrop-blur-md border-b border-slate-800 h-16 flex items-center justify-between px-6 sticky top-0 z-20">
            <div class="flex items-center space-x-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl bg-slate-800 text-teal-400 hover:bg-slate-700 transition border border-slate-700">
                    <i class="fa-solid fa-bars text-sm"></i>
                </button>

                <!-- FORM SEARCH DENGAN REKOMENDASI TEKS SAJA -->
                <div class="relative hidden sm:block w-72 md:w-96" 
                     x-data="{ 
                        query: '{{ request('search') }}',
                        items: {{ json_encode($semuaDestinasi ?? []) }},
                        focused: false,
                        get filtered() {
                            if (!this.query.trim()) return [];
                            return this.items.filter(item => 
                                item.nama_destinasi.toLowerCase().includes(this.query.toLowerCase()) ||
                                (item.alamat && item.alamat.toLowerCase().includes(this.query.toLowerCase()))
                            );
                        }
                     }"
                     @click.outside="focused = false"
                >
                    <form action="{{ route('user.dashboard') }}" method="GET" class="relative w-full">
                        <input 
                            type="text" 
                            name="search" 
                            x-model="query"
                            @focus="focused = true"
                            @input="focused = true"
                            placeholder="Cari destinasi..." 
                            class="w-full bg-slate-800/90 border border-slate-700 rounded-2xl pl-4 pr-11 py-2 text-xs text-slate-200 focus:outline-none focus:border-teal-500 transition"
                            autocomplete="off"
                        >
                        <!-- TOMBOL SEARCH DI SEBELAH KANAN (Dapat Ditekan) -->
                        <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-teal-400 transition" title="Cari">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </button>
                    </form>

                    <!-- REKOMENDASI HANYA TEKS (TIDAK MUNCUL GAMBAR) -->
                    <div x-show="focused && filtered.length > 0" 
                         x-transition
                         class="absolute top-full left-0 right-0 mt-2 bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl overflow-hidden z-50 max-h-60 overflow-y-auto"
                    >
                        <div class="p-2 text-[10px] text-slate-400 font-bold border-b border-slate-800">
                            Rekomendasi kata kunci untuk "<span x-text="query"></span>":
                        </div>
                        <template x-for="item in filtered" :key="item.id">
                            <a :href="'{{ url('user/destinasi') }}/' + item.id" 
                               class="block px-4 py-2.5 hover:bg-slate-800 transition border-b border-slate-800/50 last:border-none"
                            >
                                <p class="text-xs font-bold text-white group-hover:text-teal-400" x-text="item.nama_destinasi"></p>
                                <p class="text-[10px] text-teal-400/80 truncate mt-0.5" x-text="item.alamat ?? 'Ciwidey, Bandung'"></p>
                            </a>
                        </template>
                    </div>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <button class="w-9 h-9 rounded-xl bg-slate-800 text-slate-300 flex items-center justify-center border border-slate-700"><i class="fa-regular fa-bell text-xs"></i></button>
            </div>
        </header>

        <main class="p-6 space-y-6 flex-1 max-w-7xl mx-auto w-full">

            <!-- HERO BANNER SAMBUTAN USER & SOCIAL MEDIA BUTTONS -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-[#0a233c] to-slate-900 border border-slate-800/80 p-6 md:p-8 shadow-2xl glow-teal group hover:border-teal-500/40 transition duration-500">
                <div class="absolute -top-12 -right-12 w-64 h-64 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-blue-600/10 rounded-full blur-2xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="space-y-2.5 max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/20 text-teal-400 font-bold text-[10px] tracking-wide uppercase">
                            <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                            Ready for Next Adventure?
                        </div>
                        <h1 class="text-xl md:text-2xl font-black text-white tracking-tight leading-tight">
                            Halo, <span class="bg-clip-text text-transparent bg-gradient-to-r from-teal-400 via-emerald-300 to-cyan-400">{{ auth()->user()->name ?? 'Petualang' }}</span>! 🚀
                        </h1>
                        <p class="text-slate-300 text-xs md:text-sm font-medium leading-relaxed">
                            Jangan cuma wacana! Cari udara segar, temukan surga tersembunyi lokal, dan buat momen seru bareng <span class="text-teal-400 font-extrabold">Lokalify</span> hari ini.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2.5 w-full md:w-auto pt-2 md:pt-0 border-t md:border-t-0 border-slate-800/80">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block md:hidden mb-1">Ikuti Media Sosial Kami:</span>
                        
                        <div class="flex items-center gap-2.5 w-full sm:w-auto">
                            <!-- Tombol TikTok[cite: 12] -->
                            <a href="https://www.tiktok.com/@brokolii_047?is_from_webapp=1&sender_device=pc" target="_blank" class="flex-1 sm:flex-initial flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl bg-slate-900/90 hover:bg-black text-slate-200 hover:text-white border border-slate-700/80 hover:border-teal-400/50 shadow-md hover:shadow-teal-500/20 transition-all duration-300 transform hover:-translate-y-0.5 group/btn">
                                <i class="fa-brands fa-tiktok text-sm text-cyan-400 group-hover/btn:scale-110 transition duration-300"></i>
                                <span class="font-extrabold text-[11px]">TikTok</span>
                            </a>

                            <!-- Tombol Instagram[cite: 12] -->
                            <a href="https://www.instagram.com/hey_nezhh?dlrf=ZWh4ZmxvNW4wbGE2" target="_blank" class="flex-1 sm:flex-initial flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl bg-slate-900/90 hover:bg-gradient-to-r hover:from-purple-600 hover:to-pink-600 text-slate-200 hover:text-white border border-slate-700/80 hover:border-pink-500/50 shadow-md hover:shadow-pink-500/20 transition-all duration-300 transform hover:-translate-y-0.5 group/btn">
                                <i class="fa-brands fa-instagram text-sm text-pink-400 group-hover/btn:text-white group-hover/btn:scale-110 transition duration-300"></i>
                                <span class="font-extrabold text-[11px]">Instagram</span>
                            </a>

                            <!-- Tombol YouTube[cite: 12] -->
                            <a href="https://youtube.com/@lokalify-s4w?si=lq1zJuPyI18qrqrL" target="_blank" class="flex-1 sm:flex-initial flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl bg-slate-900/90 hover:bg-rose-600 text-slate-200 hover:text-white border border-slate-700/80 hover:border-rose-500/50 shadow-md hover:shadow-rose-500/20 transition-all duration-300 transform hover:-translate-y-0.5 group/btn">
                                <i class="fa-brands fa-youtube text-sm text-rose-500 group-hover/btn:text-white group-hover/btn:scale-110 transition duration-300"></i>
                                <span class="font-extrabold text-[11px]">YouTube</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION HEADER: DISCOVER DESTINATIONS -->
            <div class="flex justify-between items-center pt-2">
                <h2 class="text-base font-extrabold text-white tracking-tight">Discover Destinations</h2>
                <a href="{{ route('user.dashboard') }}" class="text-xs font-bold text-teal-400 hover:underline">Show all</a>
            </div>

            <!-- Kategori Filter -->
            <div class="relative flex items-center w-full" x-data="{
                scrollLeft() { $refs.categoryContainer.scrollBy({ left: -300, behavior: 'smooth' }); },
                scrollRight() { $refs.categoryContainer.scrollBy({ left: 300, behavior: 'smooth' }); }
            }">
                <button @click="scrollLeft()" class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-900 border border-slate-800 text-slate-300 hover:bg-teal-500 hover:text-slate-950 flex items-center justify-center shadow-lg transition mr-2 z-10" title="Geser Kiri">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>

                <div x-ref="categoryContainer" class="flex items-center gap-2 overflow-x-auto pb-1 no-scrollbar scroll-smooth w-full">
                    <a href="{{ route('user.dashboard') }}" class="px-4 py-1.5 rounded-full text-[11px] font-bold whitespace-nowrap transition {{ !request('kategori') ? 'bg-teal-500 text-slate-950' : 'bg-slate-900 text-slate-300 border border-slate-800' }}">Semua Tempat</a>
                    @if(isset($kategori))
                        @foreach($kategori as $kat)
                            <a href="{{ route('user.dashboard', ['kategori' => $kat->id]) }}" class="px-4 py-1.5 rounded-full text-[11px] font-bold whitespace-nowrap transition {{ request('kategori') == $kat->id ? 'bg-teal-500 text-slate-950' : 'bg-slate-900 text-slate-300 border border-slate-800' }}">{{ $kat->nama_kategori }}</a>
                        @endforeach
                    @endif
                </div>

                <button @click="scrollRight()" class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-900 border border-slate-800 text-slate-300 hover:bg-teal-500 hover:text-slate-950 flex items-center justify-center shadow-lg transition ml-2 z-10" title="Geser Kanan">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
            </div>

            <!-- GRID KARTU DESTINASI -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 items-start">
                @if(isset($destinasi) && count($destinasi) > 0)
                    @foreach($destinasi as $item)
                        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-3.5 space-y-3 shadow-lg hover:border-teal-500/50 transition flex flex-col justify-between h-full group/card">
                            
                            <div class="space-y-2.5">
                                <div class="relative w-full aspect-video rounded-xl overflow-hidden bg-slate-950">
                                    @if(!empty($item->foto))
                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_destinasi }}" class="w-full h-full object-cover group-hover/card:scale-105 transition duration-500">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-teal-500 to-blue-600 flex items-center justify-center text-slate-950 font-bold">
                                            <i class="fa-solid fa-mountain-sun text-2xl"></i>
                                        </div>
                                    @endif
                                    <span class="absolute top-2 left-2 bg-slate-950/80 backdrop-blur px-2 py-0.5 rounded-lg text-[9px] font-extrabold text-teal-400 border border-slate-800">
                                        {{ $item->kategori->nama_kategori ?? 'Wisata' }}
                                    </span>
                                </div>

                                <div class="space-y-1">
                                    <h3 class="font-extrabold text-white text-xs group-hover/card:text-teal-400 transition">{{ $item->nama_destinasi }}</h3>
                                    <p class="text-[10px] text-slate-400 flex items-center space-x-1">
                                        <i class="fa-solid fa-location-dot text-teal-400 text-[9px]"></i>
                                        <span>{{ $item->alamat ?? 'Ciwidey, Bandung' }}</span>
                                    </p>

                                    @if($item->deskripsi_singkat)
                                        <p class="text-[10px] text-teal-300/90 mt-1.5 italic leading-relaxed">
                                            "{{ $item->deskripsi_singkat }}"
                                        </p>
                                    @endif
                                </div>
                            </div>

                            @php
                                $totalReviews = $item->reviews ? $item->reviews->count() : 0;
                                $avgRating = $totalReviews > 0 ? round($item->reviews->avg('rating'), 1) : 0;
                            @endphp

                            <div class="flex items-center justify-between pt-3 border-t border-slate-800/80 mt-2">
                                <div class="flex items-center space-x-1 text-amber-400 font-bold text-[10px]">
                                    <i class="fa-solid fa-star text-[9px]"></i>
                                    <span>{{ number_format($avgRating, 1) }}</span>
                                    <span class="text-slate-500 font-normal">({{ $totalReviews }})</span>
                                </div>
                                <a href="{{ route('user.destinasi.detail', $item->id) }}" class="px-3 py-1 bg-teal-500 hover:bg-teal-400 text-slate-950 font-extrabold rounded-lg text-[10px] transition">
                                    Detail Wisata →
                                </a>
                            </div>

                        </div>
                    @endforeach
                @else
                    <div class="col-span-full py-12 text-center bg-slate-900 rounded-2xl border border-slate-800">
                        <p class="text-xs text-slate-400 font-semibold">Belum ada destinasi wisata di database.</p>
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>