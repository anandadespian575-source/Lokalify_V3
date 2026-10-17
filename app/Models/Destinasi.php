<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destinasi extends Model
{
    use HasFactory;

    protected $table = 'destinasi'; // Menyesuaikan nama tabel di database Anda

    protected $fillable = [
        'nama_destinasi',
        'slug',
        'deskripsi',
        'kategori_id',
        'deskripsi_singkat',
        'deskripsi_lengkap',
        'foto',
        'alamat',
        'jam_buka',
        'link_maps',
    ];

    /**
     * Relasi ke model Kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relasi ke model Review (untuk mengatasi error reviews.user)
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'destinasi_id'); // Sesuaikan nama foreign key jika berbeda di database Anda
    }
}