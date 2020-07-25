<?php namespace App\Providers;

use App\Repositories\Access\User\UserRepository;
use App\Repositories\Access\User\UserRepositoryInterface;
use App\Repositories\Business\Product\ProductRepository;
use App\Repositories\Business\Product\ProductRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind( UserRepositoryInterface::class, UserRepository::class );
        $this->app->bind( ProductRepositoryInterface::class, ProductRepository::class );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
