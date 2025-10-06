<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VidioTutorialModel;
use App\Models\WebProfile;

class VideoTutorial extends BaseController
{
    protected $videoModel;

    public function __construct()
    {
        $this->videoModel = new VidioTutorialModel();
    }

    /**
     * Tampilkan halaman player
     * URL contoh:
     *  - /id/video-tutorial/{slug}
     *  - /en/video-tutorial/{slug}
     */
    public function watch($slug)
    {
        $lang = service('request')->getLocale();

        $video = $this->videoModel->getVideoBySlug($slug);
        $model_webprofile = new WebProfile();
        $webprofile = $model_webprofile->findAll();
        
        if (!$video) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Video not found');
        }

        $related_videos = $this->videoModel->getRelatedVideos($video['id_kategori_video'], $video['id_video']);

        // Contoh meta
        $meta = [
            'meta_title_tutorial' => $video['judul_video'],
            'meta_title_tutorial_en' => $video['judul_video_en'],
            'meta_description_tutorial' => strip_tags($video['deskripsi_video']),
            'meta_description_tutorial_en' => strip_tags($video['deskripsi_video_en']),
        ];

        return view('video-tutorial/video_tutorial_detail', [
            'video' => $video,
            'kategori' => $video, // Sudah join kategori, tinggal ambil nama_kategori_video di view
            'related_videos' => $related_videos,
            'meta' => $meta,
            'lang' => $lang,
            'webprofile' => $webprofile,
        ]);
    }

    /**
     * Proxy streaming Google Drive: https://drive.google.com/uc?export=download&id=FILE_ID
     * URL: /video-tutorial/stream/{FILE_ID}
     */
    public function stream($video_url)
    {
        $url = "https://drive.google.com/file/d/" . $video_url . "/view?usp=sharing";

        $context = stream_context_create([
            "http" => [
                "header" => "User-Agent: Mozilla/5.0"
            ]
        ]);

        $stream = fopen($url, 'rb', false, $context);

        if (!$stream) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot stream this video.');
        }

        header('Content-Type: video/mp4');
        header('Content-Disposition: inline');

        fpassthru($stream);
        fclose($stream);
    }
}
