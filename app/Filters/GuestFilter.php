<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class GuestFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $s = session();

        // Jika sudah login, tolak akses halaman guest
        if ($s->get('user_id') || $s->get('logged_in')) {
            $s->setFlashdata('info', 'Kamu sudah login.');
            return redirect()->to('/beranda');
        }
        // kalau belum login, lanjut saja
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
