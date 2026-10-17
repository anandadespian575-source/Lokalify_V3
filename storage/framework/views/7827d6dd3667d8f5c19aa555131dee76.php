<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($destinasi->nama_destinasi); ?> - Detail Destinasi 📍</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4 text-xs">

    <div class="bg-slate-900 border border-slate-800 w-full max-w-2xl rounded-3xl overflow-hidden shadow-2xl relative space-y-5 p-6 md:p-8 my-6">
        
        <!-- Notifikasi Sukses (Opsional) -->
        <?php if(session('success')): ?>
            <div class="bg-teal-500/10 border border-teal-500/30 text-teal-400 p-3 rounded-xl text-xs flex items-center space-x-2">
                <i class="fa-solid fa-circle-check"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>

        <!-- Tombol Kembali & Tombol Lapor Destinasi (Pojok Kanan Atas Card) -->
        <div class="absolute top-5 right-5 flex items-center space-x-2 z-10">
            <!-- Tombol Lapor -->
            <button onclick="openReportModal()" class="h-8 px-3 rounded-full bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 flex items-center space-x-1 transition border border-rose-500/30 text-[11px] font-semibold" title="Laporkan Destinasi">
                <i class="fa-solid fa-flag text-xs"></i>
                <span>Lapor</span>
            </button>
            <!-- Tombol Kembali -->
            <a href="<?php echo e(route('user.dashboard')); ?>" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 flex items-center justify-center transition border border-slate-700" title="Kembali">
                <i class="fa-solid fa-xmark text-xs"></i>
            </a>
        </div>

        <!-- Foto Utama & Tombol Love (Wishlist) di Pojok Kanan Atas Foto -->
        <div class="relative w-full h-64 rounded-2xl overflow-hidden bg-slate-950 border border-slate-800">
            <?php if($destinasi->foto): ?>
                <img src="<?php echo e(asset('storage/' . $destinasi->foto)); ?>" alt="<?php echo e($destinasi->nama_destinasi); ?>" class="w-full h-full object-cover">
            <?php else: ?>
                <div class="flex items-center justify-center h-full text-6xl">🏝️</div>
            <?php endif; ?>
            
            <span class="absolute top-3 left-3 bg-slate-950/80 backdrop-blur px-3 py-1 rounded-xl text-xs font-bold text-teal-400 border border-slate-800">
                <?php echo e($destinasi->kategori->nama_kategori ?? 'Wisata'); ?>

            </span>

            <!-- Tombol Love Wishlist (Dipindahkan ke pojok kanan atas foto sesuai area yang dilingkari) -->
            <form action="<?php echo e(route('user.wishlist.toggle', $destinasi->id)); ?>" method="POST" class="absolute top-3 right-3 z-10">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-9 h-9 rounded-xl bg-slate-950/80 backdrop-blur border border-slate-800 flex items-center justify-center transition shadow-lg group" title="Wishlist">
                    <?php if(isset($isWishlist) && $isWishlist): ?>
                        <i class="fa-solid fa-heart text-rose-500 text-sm scale-110 transition"></i>
                    <?php else: ?>
                        <i class="fa-regular fa-heart text-slate-300 group-hover:text-rose-400 text-sm transition"></i>
                    <?php endif; ?>
                </button>
            </form>
        </div>

        <!-- Nama Destinasi, Alamat & Jam Operasional -->
        <div class="space-y-1.5">
            <h2 class="text-xl font-extrabold text-white"><?php echo e($destinasi->nama_destinasi); ?></h2>
            <p class="text-xs text-teal-400 font-medium flex items-center space-x-1">
                <i class="fa-solid fa-location-dot"></i>
                <span><?php echo e($destinasi->alamat ?? 'Lokasi tidak tersedia'); ?></span>
            </p>
            <?php if($destinasi->jam_buka): ?>
                <p class="text-[11px] text-slate-400 flex items-center space-x-1">
                    <i class="fa-regular fa-clock text-teal-400"></i>
                    <span>Jam Operasional: <?php echo e($destinasi->jam_buka); ?></span>
                </p>
            <?php endif; ?>
        </div>

        <!-- Profil Singkat / Highlight -->
        <?php if($destinasi->deskripsi_singkat): ?>
            <div class="bg-teal-500/10 border border-teal-500/20 p-3.5 rounded-2xl space-y-1">
                <h4 class="font-bold text-teal-300 text-xs uppercase">Highlight / Profil Singkat</h4>
                <p class="text-xs text-slate-300"><?php echo e($destinasi->deskripsi_singkat); ?></p>
            </div>
        <?php endif; ?>

        <!-- Detail Lengkap & Fasilitas -->
        <div class="space-y-1.5 bg-slate-950/50 p-4 rounded-2xl border border-slate-800">
            <h3 class="font-extrabold text-slate-200 text-xs uppercase tracking-wider">Detail Lengkap & Fasilitas</h3>
            <p class="text-xs text-slate-400 leading-relaxed font-normal whitespace-pre-line">
                <?php echo e($destinasi->deskripsi_lengkap ?? $destinasi->deskripsi ?? 'Belum ada detail penjelasan.'); ?>

            </p>
        </div>

        <!-- Section Ulasan Pengguna & Ringkasan Rating -->
        <?php
            $totalReviews = $destinasi->reviews ? $destinasi->reviews->count() : 0;
            $avgRating = $totalReviews > 0 ? round($destinasi->reviews->avg('rating'), 1) : 0;
            $ratingPercentage = round(($avgRating / 5) * 100);
        ?>

        <div class="space-y-3 pt-2">
            <div class="flex justify-between items-center border-b border-slate-800 pb-2">
                <h3 class="font-extrabold text-white text-xs">User Reviews</h3>
                <div class="text-amber-400 font-bold text-xs flex items-center space-x-1">
                    <i class="fa-solid fa-star text-amber-400"></i>
                    <span><?php echo e(number_format($avgRating, 1)); ?> / 5.0 (<?php echo e($ratingPercentage); ?>%)</span>
                </div>
            </div>
            
            <div class="space-y-2.5 max-h-48 overflow-y-auto no-scrollbar">
                <?php $__empty_1 = true; $__currentLoopData = $destinasi->reviews ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-slate-950 p-3.5 rounded-xl border border-slate-800 flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-full bg-teal-500 text-slate-950 font-black flex items-center justify-center text-xs flex-shrink-0">
                            <?php echo e(substr($rev->user->name ?? 'U', 0, 1)); ?>

                        </div>
                        <div class="space-y-1 flex-1">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-white text-xs"><?php echo e($rev->user->name ?? 'Pengguna'); ?></span>
                                <div class="text-amber-400 text-[10px]">
                                    <?php for($i = 0; $i < $rev->rating; $i++): ?>
                                        <i class="fa-solid fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <p class="text-xs text-slate-300 leading-relaxed"><?php echo e($rev->komentar); ?></p>
                            <?php if($rev->foto): ?>
                                <img src="<?php echo e(asset('storage/' . $rev->foto)); ?>" class="w-20 h-20 object-cover rounded-lg border border-slate-800 mt-2">
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-xs text-slate-500 italic text-center py-2">Belum ada ulasan untuk destinasi ini.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Form Tambah Ulasan Baru -->
        <div class="bg-slate-950/80 p-4 rounded-2xl border border-slate-800 space-y-3">
            <h4 class="font-bold text-white text-xs">➕ Beri Ulasan Kamu</h4>
            <form action="<?php echo e(route('user.review.store', $destinasi->id)); ?>" method="POST" class="space-y-3">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-[11px] text-slate-400 mb-1">Rating Bintang:</label>
                    <select name="rating" class="w-full bg-slate-900 border border-slate-800 text-white rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-teal-500">
                        <option value="5">⭐⭐⭐⭐⭐ 5 Star</option>
                        <option value="4">⭐⭐⭐⭐ 4 Star</option>
                        <option value="3">⭐⭐⭐ 3 Star</option>
                        <option value="2">⭐⭐ 2 Star</option>
                        <option value="1">⭐ 1 Star</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] text-slate-400 mb-1">Komentar / Pengalaman:</label>
                    <textarea name="komentar" rows="2" required placeholder="Tuliskan ulasan kamu..." class="w-full bg-slate-900 border border-slate-800 text-white rounded-xl p-3 text-xs focus:outline-none focus:border-teal-500"></textarea>
                </div>
                <button type="submit" class="w-full py-2 bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold rounded-xl text-xs transition">
                    Kirim Ulasan
                </button>
            </form>
        </div>

        <!-- Tombol Buka Maps -->
        <?php if($destinasi->link_maps): ?>
            <div class="pt-1">
                <a href="<?php echo e($destinasi->link_maps); ?>" target="_blank" class="block w-full text-center py-3 bg-teal-500 hover:bg-teal-400 text-slate-950 font-black rounded-2xl text-xs transition shadow-lg shadow-teal-500/20">
                    Buka Google Maps / Lokasi 🗺️
                </a>
            </div>
        <?php endif; ?>

    </div>

    <!-- Modal Form Lapor Destinasi -->
    <div id="reportModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 w-full max-w-md rounded-3xl p-6 space-y-4 shadow-2xl relative">
            <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                <h3 class="font-extrabold text-white text-sm flex items-center space-x-2">
                    <i class="fa-solid fa-flag text-rose-500"></i>
                    <span>Laporkan Destinasi Ini</span>
                </h3>
                <button onclick="closeReportModal()" class="w-7 h-7 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>

            <form action="<?php echo e(route('user.destinasi.report', $destinasi->id)); ?>" method="POST" class="space-y-3">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-[11px] text-slate-400 mb-1">Alasan Pelaporan:</label>
                    <select name="alasan" required class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-3 py-2.5 text-xs focus:outline-none focus:border-rose-500">
                        <option value="" disabled selected>Pilih alasan laporan...</option>
                        <option value="Informasi / Alamat Palsu">Informasi / Alamat Palsu</option>
                        <option value="Destinasi Sudah Tutup / Permanen">Destinasi Sudah Tutup / Permanen</option>
                        <option value="Konten / Foto Tidak Pantas">Konten / Foto Tidak Pantas</option>
                        <option value="Duplikat Destinasi">Duplikat Destinasi</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] text-slate-400 mb-1">Keterangan Tambahan:</label>
                    <textarea name="keterangan" rows="3" required placeholder="Jelaskan detail masalah pada destinasi ini..." class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl p-3 text-xs focus:outline-none focus:border-rose-500"></textarea>
                </div>
                <div class="flex space-x-2 pt-2">
                    <button type="button" onclick="closeReportModal()" class="w-1/2 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-xl text-xs transition">
                        Batal
                    </button>
                    <button type="submit" class="w-1/2 py-2.5 bg-rose-500 hover:bg-rose-400 text-white font-bold rounded-xl text-xs transition shadow-lg shadow-rose-500/20">
                        Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script JavaScript Popup Modal -->
    <script>
        function openReportModal() {
            const modal = document.getElementById('reportModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closeReportModal() {
            const modal = document.getElementById('reportModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>

</body>
</html><?php /**PATH C:\xampp\htdocs\lokalify\resources\views/user/detail.blade.php ENDPATH**/ ?>