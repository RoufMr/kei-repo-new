<?php

namespace App\Models;

use CodeIgniter\Model;

class Meta extends Model
{
    protected $table            = 'meta';
    protected $primaryKey       = 'id_meta';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'meta_title_beranda',
        'meta_title_beranda_en',
        'meta_description_beranda',
        'meta_description_beranda_en',
        'meta_title_tentang',
        'meta_title_tentang_en',
        'meta_description_tentang',
        'meta_description_tentang_en',
        'meta_title_materi',
        'meta_title_materi_en',
        'meta_description_materi',
        'meta_description_materi_en',
        'meta_title_tutorial',
        'meta_title_tutorial_en',
        'meta_description_tutorial',
        'meta_description_tutorial_en',
        'meta_title_member',
        'meta_title_member_en',
        'meta_description_member',
        'meta_description_member_en',
        'meta_title_daftar',
        'meta_title_daftar_en',
        'meta_description_daftar',
        'meta_description_daftar_en',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
