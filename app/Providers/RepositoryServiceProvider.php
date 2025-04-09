<?php

namespace App\Providers;

use App\Interfaces\SimRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;

use App\Repositories\SimRepository;
use App\Repositories\UserRepository;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {

    public function register() {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(SimRepositoryInterface::class, SimRepository::class);
    }
}
