<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Repositories\Contracts\AssetRepositoryInterface;
use App\Repositories\Contracts\IncidentRepositoryInterface;
use App\Repositories\Contracts\ChangeRequestRepositoryInterface;
use App\Repositories\Contracts\MaintenanceRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\TicketRepository;
use App\Repositories\AssetRepository;
use App\Repositories\IncidentRepository;
use App\Repositories\ChangeRequestRepository;
use App\Repositories\MaintenanceRepository;
use App\Repositories\UserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TicketRepositoryInterface::class , TicketRepository::class);
        $this->app->bind(AssetRepositoryInterface::class , AssetRepository::class);
        $this->app->bind(IncidentRepositoryInterface::class , IncidentRepository::class);
        $this->app->bind(ChangeRequestRepositoryInterface::class , ChangeRequestRepository::class);
        $this->app->bind(MaintenanceRepositoryInterface::class , MaintenanceRepository::class);
        $this->app->bind(UserRepositoryInterface::class , UserRepository::class);
        $this->app->bind(\App\Repositories\Contracts\TicketReporterRepositoryInterface::class , \App\Repositories\TicketReporterRepository::class);
    }
}
