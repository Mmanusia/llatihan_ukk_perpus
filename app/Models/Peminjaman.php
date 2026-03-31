<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    /**
     * Tabel yang digunakan.
     *
     * @var string
     */
    // Mengambil tabel 'peminjaman'
    protected $table = 'peminjaman';
    public $timestamps = false;

    /**
     * kolom yang dapat diisi.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'buku_id',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'status_peminjaman',
    ];


    /**
     * Relasi tabel Peminjaman.
     *
     * @return HasMany
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}
