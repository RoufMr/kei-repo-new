<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriVideoModel extends Model
{
    protected $table = 'kategori_video';
    protected $primaryKey = 'id_kategori_video';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_kategori_video',
        'nama_kategori_video',
        'nama_kategori_video_en',
        'slug',
        'slug_en',
        'title_kategori_video', 
        'title_kategori_video_en', 
        'meta_description_kategori_video', 
        'meta_description_kategori_video_en',
        'created_at', 
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
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
