<?php

namespace App\Providers;

use App\Events\UserSignup;
use App\Listeners\SendEmailVerification;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    protected $listen = [
        UserSignup::class =>[
            SendEmailVerification::class,
        ],
    ];
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
