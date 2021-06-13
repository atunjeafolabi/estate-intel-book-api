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
		);
		$this->app->bind(
			\App\Repositories\Contract\AuthorRepositoryInterface::class,
			\App\Repositories\Eloquent\AuthorRepository::class
		);
		$this->app->bind(
			\App\Repositories\Contract\PublisherRepositoryInterface::class,
			\App\Repositories\Eloquent\PublisherRepository::class
		);
		$this->app->bind(
			\App\Repositories\Contract\CountryRepositoryInterface::class,
			\App\Repositories\Eloquent\CountryRepository::class
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
