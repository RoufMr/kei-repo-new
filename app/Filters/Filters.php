public $aliases = [
    'csrf'   => \CodeIgniter\Filters\CSRF::class,
    'toolbar'=> \CodeIgniter\Filters\DebugToolbar::class,
    'auth'   => \App\Filters\AuthFilter::class,     // sudah ada di project-mu
    'admin'  => \App\Filters\AdminFilter::class,    // sudah ada di project-mu
    'guest'  => \App\Filters\GuestFilter::class,    // <-- tambahkan ini
];
