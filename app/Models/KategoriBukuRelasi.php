<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KategoriBukuRelasi extends Model
{
    /**
     * Tabel yang digunakan.
     *
     * @var string
     */
    // Mengambil tabel 'kategoribuku_relasi'
    protected $table = 'kategoribuku_relasi';
    public $timestamps = false;

    /**
    * kolom yang dapat diisi.
    *
    * @var array
    */
    protected $fillable = [
        'kategori_id',
        'buku_id',
    ];

    /**
     * Relasi tabel kategoribuku_relasi.
     *
     * @return BelongsTo
     */
    public function kategoriBuku(): BelongsTo
    {
        return $this->belongsTo(KategoriBuku::class, 'kategori_id');
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}
