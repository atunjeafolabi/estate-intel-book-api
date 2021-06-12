<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
		$this->app->bind(
			\App\Repositories\Contract\BookRepositoryInterface::class,
			\App\Repositories\Eloquent\BookRepository::class
		);//{{END_OF_FILE}}
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
