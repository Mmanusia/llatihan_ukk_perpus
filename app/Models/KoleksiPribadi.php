<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KoleksiPribadi extends Model
{
    /**
     * Tabel yang digunakan.
     *
     * @var string
     */
    // Mengambil tabel 'koleksipribadi'
    protected $table = 'koleksipribadi';
    public $timestamps = false;

    /**
     * kolom yang dapat diisi.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'buku_id',
    ];


        /**
     * Relasi tabel KoleksiPribadi.
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
