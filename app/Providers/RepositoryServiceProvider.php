<?php

namespace App\Providers;

use App\Repositories\TagRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepository;
use App\Interfaces\TagRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;

/**
 
* @OA\Info(
* title="E-Learning",
* version="1.0.0"
* )
*/


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class,CategoryRepository::class);
        $this->app->bind(TagRepositoryInterface::class,TagRepository::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
