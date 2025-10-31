<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class GuestFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Sesuaikan key session dengan punyamu: 'user_id' dan/atau 'logged_in'
        if (session()->get('user_id') || session()->get('logged_in')) {
            // AJAX → JSON, selain itu redirect
            if ($request->isAJAX()) {
                return service('response')
                    ->setStatusCode(409)
                    ->setJSON(['message' => 'Sudah login', 'redirect' => site_url('beranda')]);
            }
            return redirect()->to('/beranda')->with('info', 'Kamu sudah login.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
