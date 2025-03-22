<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class ScheduleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->afterResolving(Schedule::class, function (Schedule $schedule) {
            // پاکسازی کدهای تأیید قدیمی هر روز در ساعت 3 صبح
            $schedule->command('verification:cleanup')->dailyAt('03:00');
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
