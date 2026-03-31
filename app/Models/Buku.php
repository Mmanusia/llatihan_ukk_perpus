<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buku extends Model
{
    /**
     * Tabel yang digunakan.
     *
     * @var string
     */
    // Mengambil tabel 'buku'
    protected $table = 'buku';
    public $timestamps = false;

    /**
     * kolom yang dapat diisi.
     *
     * @var array
     */
    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
    ];


    /**
     * Relasi tabel buku.
     *
     * @return HasMany
     */
    public function buku(): HasMany
    {
        return $this->hasMany(Buku::class, 'buku_id');
    }

    public function ulasans(): HasMany
    {
        return $this->hasMany(UlasanBuku::class, 'buku_id');
    }

}
