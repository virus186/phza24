<?php

namespace App\Console\Commands;

use App\Models\DeviceToken;
use App\Models\PushNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Str;
class PushNotificationCommamd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:pushNotificatons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push Notifications description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = auth('api')->user()->id();
       $notifications = PushNotification::where('push_send_type',0)->with('user_notification_setting')->get();
       $devices = DeviceToken::where('user_id',$id)->get();

       foreach ($notifications as $notification) {
        if (Str::contains(@$notifications->user_notification_setting->type, 'mobile')) {
            foreach ($devices as $device) {
                Http::withToken(env('FCM_SERVER_KEY'))
                ->post('https://fcm.googleapis.com/fcm/send', [
                    "to" => $device->device_token,
                    "notification" => [
                        "priority" => "high",
                        "title" => $notification->title,
                        "body" => $notification->body,
                        "type" =>  $notification->type,
                        "click_action" => "FLUTTER_NOTIFICATION_CLICK", 
                    ],
                    "data" => [
                        "priority" => "high",
                        "title" => $notification->title,
                        "body" => $notification->body, 
                        "type" =>  $notification->type, 
                        "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                    ],
                ]);
            }
           
        }
        $notification->delete();
       }
        return true;
    }
}
