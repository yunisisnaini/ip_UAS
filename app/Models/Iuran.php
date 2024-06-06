<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iuran extends Model
{
    protected $table = 'iurans';

    protected $fillable = [
        'id_wargas',
        'bulan',
        'jumlah_iuran',
        'status'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Mendefinisikan relasi Many-to-One dengan model Warga.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warga()
    {
        return $this->belongsTo(Warga::class, 'id_wargas');
    }
}
