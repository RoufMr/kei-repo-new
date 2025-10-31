<?php

namespace App\Models;

use CodeIgniter\Model;

class UkuranKontainer extends Model
{
    protected $table            = 'ukuran_kontainer';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'nama',
        'keterangan',
        'urutan',
        'is_active'
    ];
    protected $useTimestamps    = true;
}
