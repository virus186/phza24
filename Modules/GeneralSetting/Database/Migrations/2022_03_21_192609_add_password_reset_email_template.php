<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\GeneralSetting\Entities\NotificationSetting;
use Modules\GeneralSetting\Entities\UserNotificationSetting;

class AddPasswordResetEmailTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(Schema::hasTable('email_template_types')){
            $type = DB::table('email_template_types')->where('id', 41)->first();
            if(!$type){
                DB::statement("INSERT INTO `email_template_types` (`id`, `type`, `created_at`, `updated_at`) VALUES
                    (41, 'Password Reset', NULL, '2021-01-20 12:40:47')
                ");
            }
        }

        if(Schema::hasTable('email_templates')){
            $template = DB::table('email_templates')->where('type_id', 41)->first();
            if(!$template){
                $emails = [
                    ['type_id' => '41', 'subject' => 'Password Reset', 'value' => '<div style="font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; color: rgb(255, 255, 255); text-align: center; background-color: rgb(152, 62, 81); padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0px;"><h1 style="margin: 20px 0px 10px; font-size: 36px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-weight: 500; line-height: 1.1; color: inherit;">Template</h1></div><div style="color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; padding: 20px;"><p style="color: rgb(85, 85, 85);">Hello,<br><br>You are receiving this email because we received a password reset request for your account.</p><p style="color: rgb(85, 85, 85);">Your reset link is :</p><p style="color: rgb(85, 85, 85);">{RESET_LINK}<br></p><hr style="box-sizing: content-box; margin-top: 20px; margin-bottom: 20px; border-top-color: rgb(238, 238, 238);"><p style="color: rgb(85, 85, 85);"><br></p><p style="color: rgb(85, 85, 85);">{EMAIL_SIGNATURE}</p><p style="color: rgb(85, 85, 85);"><br></p></div><div style="font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; color: rgb(255, 255, 255); text-align: center; background-color: rgb(152, 62, 81); padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0px;"><h1 style="margin: 20px 0px 10px; font-size: 36px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-weight: 500; line-height: 1.1; color: inherit;">Template</h1></div><div style="color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; padding: 20px;"></div>', 'is_active' => 1, 'relatable_type'=> NULL, 'relatable_id'=>NULL, 'reciepnt_type'=>'["admin","customer","seller"]', 'created_at' => now()]
                ];
                DB::table('email_templates')->insert($emails);
            }
        }

        if(Schema::hasTable('notification_settings')){
            $exsist = NotificationSetting::where('event', 'New Order')->first();

            if(!$exsist){
                $sql = ['delivery_process_id' => null, 'event' => 'New Order', 'type' => 'system', 'message' => 'Order Is placed.','admin_msg' => 'New Order placed', 'user_access_status' => 1, 'seller_access_status' => 1, 'admin_access_status' => 1, 'staff_access_status' => 1, 'created_at' => now(), 'updated_at' => now()];
                $setting = NotificationSetting::create($sql);
                $users = User::all();
                foreach($users as $user){
                    UserNotificationSetting::create([
                        'user_id' => $user->id,
                        'notification_setting_id' =>  $setting->id,
                        'type' => $setting->type
                    ]);
                }
            }
        }



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
