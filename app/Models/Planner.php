<?php

namespace App\Models;

use CodeIgniter\Model;

class Planner extends Model
{
    protected $table            = 'planner';
    protected $primaryKey       = 'id_planner';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'konten_pilar',
        'deskripsi_pilar',
        'konten_tipe',
        'gambar',
        'deskripsi_planner',
        'caption',
        'status',
        'link',
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

    public function updateOrInsert($id_planner, $data)
    {
        $existing = $this->find($id_planner);
        if ($existing) {
            return $this->update($id_planner, $data);
        } else {
            return $this->insert($data);
        }
    }
}
