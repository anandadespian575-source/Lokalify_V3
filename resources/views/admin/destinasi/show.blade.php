<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Destinasi - Lokalify Admin 📍</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); border: 1px solid rgba(226, 232, 240, 0.8); }
        .sidebar-gradient { background: linear-gradient(180deg, #091a2f 0%, #032b56 100%); }
    </style>
</head>
<body class="bg-[#f0f4f9] flex min-h-screen text-slate-800">

    <aside class="w-64 sidebar-gradient text-white p-5 flex flex-col justify-between hidden md:flex shrink-0 shadow-xl border-r border-slate-800/50">
        <div>
            <div class="flex items-center gap-3 mb-8 px-2">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-500 to-blue-600 flex items-center justify-center text-xl shadow-lg">🏔️</div>
                <div>
                    <h1 class="text-xl font-black text-white tracking-tight">Lokalify</h1>
                    <p class="text-[10px] text-sky-300 font-medium">Jelajahi Ciwidey, Temukan Cerita</p>
                </div>
            </div>
            <nav class="space-y-1.5 text-sm font-semibold">
                <a href="/admin/dashboard" class="flex items-center gap-3.5 py-3 px-4 rounded-xl text-slate-300 hover:bg-white/10">Dashboard</a>
                <a href="/admin/destinasi" class="flex items-center gap-3.5 py-3 px-4 rounded-xl bg-sky-500 text-white shadow-lg">Kelola Destinasi</a>
                <a href="/admin/kategori" class="flex items-center gap-3.5 py-3 px-4 rounded-xl text-slate-300 hover:bg-white/10">Kategori</a>
                <a href="/admin/laporan" class="flex items-center gap-3.5 py-3 px-4 rounded-xl text-slate-300 hover:bg-white/10">Laporan User</a>
                <a href="/admin/pengaturan" class="flex items-center gap-3.5 py-3 px-4 rounded-xl text-slate-300 hover:bg-white/10">Pengaturan</a>
            </nav>
        </div>
    </aside>

    <main class="flex-1 flex flex-col min-w-0 overflow-y-auto p-8">
        <div class="flex justify-between items-center mb-6 animate__animated animate__fadeInDown">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Detail Destinasi Wisata 🗺️</h2>
                <p class="text-xs text-slate-500 font-medium mt-1">Informasi lengkap tempat wisata dari database.</p>
            </div>
            <a href="{{ route('admin.destinasi.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2.5 rounded-xl text-xs font-bold transition">
                ← Kembali
            </a>
        </div>

        <div class="glass-card rounded-3xl shadow-xl p-8 max-w-4xl mx-auto border border-slate-200 space-y-6 animate__animated animate__fadeInUp w-full">
            
            <!-- Foto Utama dengan Ukuran Kotak Persegi (Aspect Square) -->
            <div class="w-full max-w-md mx-auto aspect-square rounded-2xl overflow-hidden shadow-md bg-slate-100 relative">
                @if(!empty($destinasi->foto))
                    <img src="{{ asset('storage/' . $destinasi->foto) }}" alt="{{ $destinasi->nama_destinasi }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-sky-400 to-blue-600 flex flex-col items-center justify-center text-white shadow-md">
                        <span class="text-4xl mb-2">🏔️</span>
                        <span class="font-extrabold text-sm">Tidak ada foto utama yang diunggah</span>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-sky-600 bg-sky-50 px-3 py-1 rounded-full border border-sky-200">
                        {{ $destinasi->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                    </span>
                    <h3 class="text-xl font-extrabold text-slate-800 mt-2">{{ $destinasi->nama_destinasi }}</h3>
                    <p class="text-xs text-slate-500 mt-1">📍 {{ $destinasi->alamat ?? 'Alamat belum diisi' }}</p>
                </div>
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex flex-col justify-center">
                    <p class="text-[11px] font-bold text-slate-400 uppercase">Jam Operasional</p>
                    <p class="text-xs font-bold text-slate-700 mt-0.5">⏰ {{ $destinasi->jam_buka ?? '08:00 - 17:00 WIB' }}</p>
                </div>
            </div>

            <div>
                <h4 class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider mb-1">Profil Singkat</h4>
                <p class="text-xs text-slate-600 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    {{ $destinasi->deskripsi_singkat ?? 'Belum ada profil singkat untuk destinasi ini.' }}
                </p>
            </div>

            <div>
                <h4 class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider mb-1">Detail Lengkap & Fasilitas</h4>
                <div class="text-xs text-slate-600 bg-slate-50 p-4 rounded-2xl border border-slate-100 leading-relaxed">
                    {!! $destinasi->deskripsi_lengkap ?? 'Belum ada detail lengkap untuk destinasi ini.' !!}
                </div>
            </div>

            @if($destinasi->link_maps)
                <div>
                    <h4 class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider mb-1">Link Google Maps</h4>
                    <a href="{{ $destinasi->link_maps }}" target="_blank" class="text-xs font-bold text-sky-600 hover:underline break-all">🔗 {{ $destinasi->link_maps }}</a>
                </div>
            @endif
        </div>
    </main>
</body>
</html>