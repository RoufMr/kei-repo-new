<?php

namespace App\Models;

use CodeIgniter\Model;

class VidioTutorialModel extends Model
{
    protected $table            = 'video_tutorial';
    protected $primaryKey       = 'id_video';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'id_kategori_video',
        'judul_video',
        'judul_video_en',
        'video_url',
        'thumbnail',
        'deskripsi_video',
        'deskripsi_video_en',
        'slug',
        'slug_en',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Ambil video berdasarkan slug (ID unik URL)
     * Join kategori untuk kebutuhan tampilan
     */
    public function getVideoBySlug($slug)
    {
        return $this->select('video_tutorial.*, kategori_video.nama_kategori_video, kategori_video.nama_kategori_video_en')
            ->join('kategori_video', 'video_tutorial.id_kategori_video = kategori_video.id_kategori_video')
            ->groupStart()
            ->where('video_tutorial.slug', $slug)
            ->orWhere('video_tutorial.slug_en', $slug)
            ->groupEnd()
            ->first();
    }

    /**
     * Ambil video terkait dalam kategori yang sama, selain video yg sedang ditonton
     */
    public function getRelatedVideos($id_kategori_video, $id_video)
    {
        return $this->where('id_kategori_video', $id_kategori_video)
            ->where('id_video !=', $id_video)
            ->limit(5)
            ->findAll();
    }

    /**
     * Ambil video berdasarkan kategori, bisa paginasi
     */
    public function getVideosByKategoriWithPagination($slug, $perPage, $page)
    {
        return $this->select('video_tutorial.*, kategori_video.nama_kategori_video, kategori_video.nama_kategori_video_en')
            ->join('kategori_video', 'kategori_video.id_kategori_video = video_tutorial.id_kategori_video')
            ->groupStart()
            ->where('kategori_video.slug', $slug)
            ->orWhere('kategori_video.slug_en', $slug)
            ->groupEnd()
            ->paginate($perPage, 'default', $page);
    }

    /**
     * Ambil semua video + nama kategori
     */
    public function getAllVideos()
    {
        return $this->select('video_tutorial.*, kategori_video.nama_kategori_video')
            ->join('kategori_video', 'video_tutorial.id_kategori_video = kategori_video.id_kategori_video')
            ->findAll();
    }

    /**
     * Ambil video terbatas berdasarkan kategori
     */
    public function getLimitedVideosByKategori($kategoriSlug, $limit = 3)
    {
        return $this->select('video_tutorial.*, kategori_video.nama_kategori_video')
            ->join('kategori_video', 'video_tutorial.id_kategori_video = kategori_video.id_kategori_video')
            ->groupStart()
            ->where('kategori_video.slug', $kategoriSlug)
            ->orWhere('kategori_video.slug_en', $kategoriSlug)
            ->groupEnd()
            ->limit($limit)
            ->findAll();
    }

    /**
     * Ambil semua video di kategori tertentu (tanpa paginasi)
     */
    public function getVideosByKategori($slug)
    {
        return $this->select('video_tutorial.*, kategori_video.nama_kategori_video, kategori_video.nama_kategori_video_en')
            ->join('kategori_video', 'kategori_video.id_kategori_video = video_tutorial.id_kategori_video')
            ->groupStart()
            ->where('kategori_video.slug', $slug)
            ->orWhere('kategori_video.slug_en', $slug)
            ->groupEnd()
            ->findAll();
    }
}
