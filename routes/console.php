<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('regularFlowCheck', function () {
	app('\App\Console\Schedules\RegularFlowSchedule');
})->describe('check remind flow regular');

Artisan::command('hourFlowCheck', function () {
	app('\App\Console\Schedules\HourFlowSchedule');
})->describe('check remind flow hourly');

Artisan::command('minuteFlowCheck', function () {
	app('\App\Console\Schedules\MinuteFlowSchedule');
})->describe('check remind flow everyMinute');

Artisan::command('minutesOrderCheck', function () {
    app('\App\Console\Schedules\AutoReopenPackage');
})->describe('check remind flow every 5 Minutes');

Artisan::command('minutesPointExchangePackageCheck', function () {
    app('\App\Console\Schedules\AutoExchangePackage');
})->describe('check remind flow every 5 Minutes');
