<?php

namespace App\Models;

use CodeIgniter\Model;

class VideoTutorialModel extends Model
{
    protected $table            = 'video_tutorial';
    protected $primaryKey       = 'id_video';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_kategori_video',
        'judul_video',
        'judul_video_en',
        'video_url',
        'thumbnail',
        'deskripsi_video',
        'deskripsi_video_en',
        'slug',
        'slug_en',
        'title_video',
        'title_video_en',
        'meta_deskripsi_video',
        'meta_deskripsi_video_en',
        'created_at',
        'updated_at',
        
    ];


    public function getAllWithCategory()
    {
        return $this->select('video_tutorial.*, kategori_video.nama_kategori_video, kategori_video.nama_kategori_video_en')
            ->join('kategori_video', 'kategori_video.id_kategori_video = video_tutorial.id_kategori_video')
            ->findAll();
    }

    public function getByCategoryWithPagination($id_kategori_video, $perPage, $page)
    {
        return $this->select('video_tutorial.*, kategori_video.nama_kategori_video, kategori_video.nama_kategori_video_en')
            ->join('kategori_video', 'kategori_video.id_kategori_video = video_tutorial.id_kategori_video')
            ->where('video_tutorial.id_kategori_video', $id_kategori_video)
            ->paginate($perPage, 'default', $page);
    }

    public function getAllWithCategoryAndPagination($perPage, $page)
    {
        return $this->select('video_tutorial.*, kategori_video.nama_kategori_video, kategori_video.nama_kategori_video_en')
            ->join('kategori_video', 'kategori_video.id_kategori_video = video_tutorial.id_kategori_video')
            ->paginate($perPage, 'default', $page);
    }

    public function getSearchAllWithCategory($keyword)
    {
        return $this->select('video_tutorial.*, kategori_video.nama_kategori_video, kategori_video.nama_kategori_video_en')
            ->join('kategori_video', 'kategori_video.id_kategori_video = video_tutorial.id_kategori_video')
            ->like('judul_video', $keyword)
            ->orLike('judul_video_en', $keyword)
            ->orLike('deskripsi_video', $keyword)
            ->orLike('deskripsi_video_en', $keyword)
            ->findAll();
    }

    public function getSearchAllWithCategoryAndPagination($keyword, $perPage, $page)
    {
        return $this->select('video_tutorial.*, kategori_video.nama_kategori_video, kategori_video.nama_kategori_video_en')
            ->join('kategori_video', 'kategori_video.id_kategori_video = video_tutorial.id_kategori_video')
            ->like('judul_video', $keyword)
            ->orLike('judul_video_en', $keyword)
            ->orLike('deskripsi_video', $keyword)
            ->orLike('deskripsi_video_en', $keyword)
            ->paginate($perPage, 'default', $page);
    }

    public function getByCategory($id_kategori_video)
    {
        return $this->select('video_tutorial.*, kategori_video.nama_kategori_video, kategori_video.nama_kategori_video_en')
            ->join('kategori_video', 'kategori_video.id_kategori_video = video_tutorial.id_kategori_video')
            ->where('video_tutorial.id_kategori_video', $id_kategori_video)
            ->findAll();
    }

    public function getSpecificByCategoryWithPagination($id_kategori_video, $perPage, $page)
    {
        return $this->select('video_tutorial.*, kategori_video.nama_kategori_video, kategori_video.nama_kategori_video_en')
            ->join('kategori_video', 'kategori_video.id_kategori_video = video_tutorial.id_kategori_video')
            ->where('video_tutorial.id_kategori_video', $id_kategori_video)
            ->paginate($perPage, 'default', $page);
    }

    public function getFreeCategory()
    {
        return $this->select('video_tutorial.*, kategori_video.nama_kategori_video, kategori_video.nama_kategori_video_en')
            ->join('kategori_video', 'kategori_video.id_kategori_video = video_tutorial.id_kategori_video')
            ->orderBy('created_at', 'ASC')
            ->limit(10)
            ->findAll();
    }

    public function getRelatedFreeCategory($slug)
    {
        return $this->select('video_tutorial.*, kategori_video.nama_kategori_video, kategori_video.nama_kategori_video_en')
            ->join('kategori_video', 'kategori_video.id_kategori_video = video_tutorial.id_kategori_video')
            ->where('video_tutorial.slug !=', $slug)
            ->orderBy('created_at', 'ASC')
            ->limit(3)
            ->findAll();
    }

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
