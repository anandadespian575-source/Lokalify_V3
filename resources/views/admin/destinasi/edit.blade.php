<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Destinasi - Lokalify Admin 📍</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); border: 1px solid rgba(226, 232, 240, 0.8); }
        .sidebar-gradient { background: linear-gradient(180deg, #091a2f 0%, #032b56 100%); }
        .input-futuristic:focus { background-color: #ffffff; border-color: #0ea5e9; box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15); }
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
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Edit Destinasi Wisata ✏️</h2>
                <p class="text-xs text-slate-500 font-medium mt-1">Perbarui informasi detail tempat wisata ke database.</p>
            </div>
            <a href="{{ route('admin.destinasi.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2.5 rounded-xl text-xs font-bold transition">
                ← Kembali
            </a>
        </div>

        <div class="glass-card rounded-3xl shadow-xl p-8 max-w-4xl mx-auto border border-slate-200 animate__animated animate__fadeInUp">
            <form action="{{ route('admin.destinasi.update', $destinasi->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')[cite: 10]

                <div>
                    <label class="block text-[11px] font-extrabold text-slate-600 uppercase tracking-wider mb-2">Nama Destinasi</label>
                    <input type="text" name="nama_destinasi" value="{{ old('nama_destinasi', $destinasi->nama_destinasi) }}" required 
                        class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 input-futuristic">
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold text-slate-600 uppercase tracking-wider mb-2">Kategori Wisata</label>
                    <select name="kategori_id" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 input-futuristic">
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ $destinasi->kategori_id == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-600 uppercase tracking-wider mb-2">Profil Singkat</label>
                        <textarea name="deskripsi_singkat" rows="3" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 input-futuristic resize-none">{{ old('deskripsi_singkat', $destinasi->deskripsi_singkat) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-600 uppercase tracking-wider mb-2">Detail Lengkap</label>
                        <textarea name="deskripsi_lengkap" rows="3" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 input-futuristic resize-none">{{ old('deskripsi_lengkap', $destinasi->deskripsi_lengkap) }}</textarea>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold text-slate-600 uppercase tracking-wider mb-2">Ganti Foto Utama (Opsional)</label>
                    @if($destinasi->foto)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $destinasi->foto) }}" alt="Preview" class="w-20 h-20 object-cover rounded-xl border">
                        </div>
                    @endif
                    <input type="file" name="foto" accept="image/*" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-500">
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold text-slate-600 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                    <input type="text" name="alamat" value="{{ old('alamat', $destinasi->alamat) }}" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 input-futuristic">
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold text-slate-600 uppercase tracking-wider mb-2">Jam Operasional</label>
                    <input type="text" name="jam_buka" value="{{ old('jam_buka', $destinasi->jam_buka) }}" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 input-futuristic">
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold text-slate-600 uppercase tracking-wider mb-2">Link Google Maps</label>
                    <input type="text" name="link_maps" value="{{ old('link_maps', $destinasi->link_maps) }}" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 input-futuristic">
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.destinasi.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100 transition">Batal</a>
                    <button type="submit" class="bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white px-7 py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-sky-500/25 transition">
                        ✨ Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>