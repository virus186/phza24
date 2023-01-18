<?php

namespace App\Console;

use App\Console\Commands\BulkSMSCommand;
use App\Console\Commands\NewsLetterCommand;
use App\Console\Commands\ResetCartPriceForFlashDeal;
use App\Console\Commands\SellerSubscription;
use App\Console\Commands\PushNotificationCommamd;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        NewsLetterCommand::class,
        BulkSMSCommand::class,
        ResetCartPriceForFlashDeal::class,
        SellerSubscription::class,
        PushNotificationCommamd::class
    ];


    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:newsletter')->everyMinute();
        $schedule->command('command:bulk_sms')->everyMinute();
        $schedule->command('command:reset_cart_price')->everyMinute();
        $schedule->command('command:reset_recent_viewed_product')->daily();
        $schedule->command('command:reset_recent_viewed_product')->daily();
        $schedule->command('command:sellerSubscription')->daily();
        $schedule->command('command:pushNotificatons')->everyMinute();
    }


    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
