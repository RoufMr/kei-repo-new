<?php

namespace App\Models;

use CodeIgniter\Model;

class KalkulatorState extends Model
{
    protected $table         = 'kalkulator_state';
    protected $primaryKey    = 'id_state';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'id_member',
        'nama_produk',
        'jumlah_barang',
        'hpp',
        'keuntungan',
        'updated_at'
    ];
    protected $useTimestamps = false;
}
