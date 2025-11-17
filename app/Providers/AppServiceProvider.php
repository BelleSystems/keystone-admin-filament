<?php

namespace App\Providers;

use App\Models\MeetingClassRequirement;
use App\Observers\MeetingClassRequirementObserver;
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
        MeetingClassRequirement::observe(MeetingClassRequirementObserver::class);
    }
}
