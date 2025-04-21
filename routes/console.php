<?php

use App\Console\Commands\UpdateExpiredTopups;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\CheckUserActivity;

app(Schedule::class)->command(CheckUserActivity::class)->everyMinute();
app(Schedule::class)->command(UpdateExpiredTopups::class)->everyMinute();
app(Schedule::class)->command('journal:create-monthly')->monthlyOn(28, '23:59') // Set a placeholder date like the 28th to avoid months with fewer days
                                                        ->when(function () {
                                                            return Carbon::now()->endOfMonth()->isToday();
                                                        });
app(Schedule::class)->command('queue:work --daemon')->everyMinute();
