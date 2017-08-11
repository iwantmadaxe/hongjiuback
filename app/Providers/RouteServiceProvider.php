<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Class RouteServiceProvider
 * @package App\Providers
 */
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

	protected $namespace_admin = 'App\Http\Controllers\Admin';

	/**
	 * @var string
	 */
	protected $namespace_api_v1 = 'App\API\V1\Controllers';

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

		$this->mapFrontRoutes();

        $this->mapWebRoutes();

        $this->mapAdminRoutes();
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
        Route::middleware('web')
			 ->prefix('admin')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    protected function mapFrontRoutes()
	{
		Route::middleware('web')
			->namespace($this->namespace)
			->group(base_path('routes/front.php'));
	}

	/**
	 *
	 */
	protected function mapAdminRoutes()
	{
		Route::prefix('api/v1/admin')
			->middleware('web')
			->namespace($this->namespace_admin)
			->group(base_path('routes/admin.php'));
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
        Route::prefix('api/v1')
             ->middleware('api')
             ->namespace($this->namespace_api_v1)
             ->group(base_path('routes/api.php'));
    }
}
