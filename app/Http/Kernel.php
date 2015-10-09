<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'manager' => \App\Http\Middleware\CheckIsManager::class,
        'is_disabled' => \App\Http\Middleware\CheckUserDisabled::class,
        'check_edit' => \App\Http\Middleware\CheckUserHasEdit::class,
        'direct_access' => \App\Http\Middleware\CheckDirectAccess::class,
        'check_delete' => \App\Http\Middleware\CheckDeleteMember::class,
    ];

    /**
     * Convert ConfigureLogging of Illuminate to custom ConfigureLogging bootstrap at all route of app
     *
     * @param Application $app
     * @param Router      $router
     */
    public function __construct(Application $app, Router $router)
    {
        parent::__construct($app, $router);

        array_walk($this->bootstrappers, function(&$bootstrapper)
        {
            if($bootstrapper === 'Illuminate\Foundation\Bootstrap\ConfigureLogging')
            {
                $bootstrapper = 'Bootstrap\ConfigureLogging';
            }
        });
    }
}
