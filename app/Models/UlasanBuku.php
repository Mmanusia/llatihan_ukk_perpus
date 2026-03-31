<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UlasanBuku extends Model
{
    /**
     * Tabel yang digunakan.
     *
     * @var string
     */
    // Mengambil tabel 'ulasanbuku'
    protected $table = 'ulasanbuku';
    public $timestamps = false;

    /**
     * kolom yang dapat diisi.
     *
     * @var array
     */
    protected $fillable = [
        'buku_id',
        'user_id',
        'ulasan',
        'rating',
    ];

    /**
     * Relasi tabel ulasanbuku.
     *
     * @return BelongsTo
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
