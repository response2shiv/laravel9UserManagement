<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Models\Notification;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $currentDateTime = date('Y-m-d H:i:s');
            $notifications = Notification::where(['status' => 'pending', 'type' => 'schedule'])
                ->where('schedule', '<=', $currentDateTime)
                ->get();
            if (count($notifications) > 0) {
                foreach ($notifications as $notification) {
                    $email = "response2shiv@gmail.com";
                    $messageData = ['name' => 'Shiv', 'messageText' => $notification->message];
                    Mail::send('email.notification', $messageData, function ($message) use ($email) {
                        $message->to($email)->subject('Hostcob Solutions Pvt Ltd: notification');
                    });
                    if ($notification->id) {
                        Notification::where('id', $notification->id)->update(['status' => 'sent']);
                    }
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
