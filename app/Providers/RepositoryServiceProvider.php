<?php

namespace App\Providers;

use App\Note\Repositories\Note\NoteInterface;
use App\Note\Repositories\Note\NoteRepositories;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(NoteInterface::class, NoteRepositories::class);
    }

    public function provides()
    {
        return [
            NoteInterface::class
        ];
    }
}
