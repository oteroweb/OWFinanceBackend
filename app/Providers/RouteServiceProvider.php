<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware'  => 'web',
            'namespace'  => $this->namespace,
        ], function ($router) { 
            self::get_routes(base_path('routes/web/'));
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([ 'namespace'  => $this->namespace,
        ], function ($router) {
            self::get_routes(base_path('routes/api/'));
        }); 
    }

    public function get_routes($dir) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (!is_dir($dir . $file) && $file != "." && $file != "..") {
                    require $dir . $file;
                } elseif ($file != "." && $file != "..") {
                    self::get_routes($dir . $file . '/');
                }
            }
            closedir($dh);
        }
    }
}
