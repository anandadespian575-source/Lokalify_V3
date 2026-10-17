<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaleriDestinasi extends Model
{
    protected $table = 'galeri_destinasi';
    protected $fillable = ['destinasi_id', 'url_gambar'];
    public $timestamps = false;
}