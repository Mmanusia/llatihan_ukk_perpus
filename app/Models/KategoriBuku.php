<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriBuku extends Model
{
    /**
     * Tabel yang digunakan.
     *
     * @var string
    */
    // Mengambil tabel 'kategoribuku'    
    protected $table = 'kategoribuku';
    public $timestamps = false;

    /**
     * kolom yang dapat diisi.
     *
     * @var array
     */
    protected $fillable = [
        'kategori_id',
        'nama_kategori',
    ];


    /**
     * Relasi tabel KategoriBuku.
     *
     * @return HasMany
     */
    public function kategoriBuku(): HasMany
    {
        return $this->hasMany(KategoriBuku::class, 'kategori_id');
    }
}
