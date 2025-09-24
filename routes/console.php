<?php

use App\Console\Commands\UpdateExpiredTopups;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\CheckUserActivity;

app(Schedule::class)->command(CheckUserActivity::class)->dailyAt('00:00');
app(Schedule::class)->command(UpdateExpiredTopups::class)->dailyAt('00:00');
app(Schedule::class)->command('journal:create-monthly')->monthlyOn(now()->endOfMonth()->day, '00:15');
app(Schedule::class)->command('queue:work --daemon')->everyMinute();
