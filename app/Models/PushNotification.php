<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\GeneralSetting\Entities\UserNotificationSetting;

class PushNotification extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function user_notification_setting()
    {
        return $this->belongsTo(UserNotificationSetting::class,'user_notification_setting_id','id');
    }
}
