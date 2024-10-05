<?php

namespace App\Providers;

use App\Http\Resources\Car\CarResource;
use App\Http\Resources\RateResource;
use App\Http\Resources\ReservationResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        CarResource::withoutWrapping();
        RateResource::withoutWrapping();
        ReservationResource::withoutWrapping();
        UserResource::withoutWrapping();
    }
}
