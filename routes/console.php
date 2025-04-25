<?php

use App\Console\Commands\UpdateExpiredTopups;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\CheckUserActivity;

app(Schedule::class)->command(CheckUserActivity::class)->dailyAt('00:00');
app(Schedule::class)->command(UpdateExpiredTopups::class)->dailyAt('00:00');
app(Schedule::class)->command('journal:create-monthly')
    ->dailyAt('16:23')
    ->when(function () {
        return Carbon::now()->day === 25;
        // return Carbon::now()->isSameDay(Carbon::now()->endOfMonth());
    });
app(Schedule::class)->command('queue:work --daemon')->everyMinute();
