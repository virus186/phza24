<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\GeneralSetting\Entities\EmailTemplate;
use Modules\SidebarManager\Entities\Backendmenu;

class AddMsegatSmsGateway extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('business_settings')){
            DB::table('business_settings')->insert([
                'category_type' => 'sms_gateways',
                'type' => 'MsegatSMS',
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        if(Schema::hasTable('general_settings')){
            Schema::table('general_settings', function (Blueprint $table) {
                $table->boolean('verify_on_newsletter')->default(0)->after('city_id');
            });
        }

        if(Schema::hasTable('subscriptions')){
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->boolean('is_verified')->default(0)->after('status');
                $table->string('verify_code')->nullable()->after('is_verified');
            });
        }

        if(Schema::hasTable('email_template_types')){
            $type = DB::table('email_template_types')->where('id', 42)->first();
            if(!$type){
                DB::statement("INSERT INTO `email_template_types` (`id`, `type`, `created_at`, `updated_at`) VALUES
                    (42, 'Subscription email verify', NULL, '2021-01-20 12:40:47')
                ");
            }
        }
        if(Schema::hasTable('email_templates')){
            $template = DB::table('email_templates')->where('type_id', 42)->first();
            if(!$template){
                $emails = [
                    ['type_id' => '42', 'subject' => 'Subscription email verify', 'value' => '<div style="font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; color: rgb(255, 255, 255); text-align: center; background-color: rgb(152, 62, 81); padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0px;"><h1 style="margin: 20px 0px 10px; font-size: 36px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-weight: 500; line-height: 1.1; color: inherit;">Template</h1></div><div style="color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; padding: 20px;"><p style="color: rgb(85, 85, 85);">Hello,<br><br>You are receiving this email because we received a Newsletter subscribe request.</p><p style="color: rgb(85, 85, 85);">Your verify link is :</p><p style="color: rgb(85, 85, 85);">{VERIFICATION_LINK}<br></p><hr style="box-sizing: content-box; margin-top: 20px; margin-bottom: 20px; border-top-color: rgb(238, 238, 238);"><p style="color: rgb(85, 85, 85);"><br></p><p style="color: rgb(85, 85, 85);">{EMAIL_SIGNATURE}</p><p style="color: rgb(85, 85, 85);"><br></p></div><div style="font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; color: rgb(255, 255, 255); text-align: center; background-color: rgb(152, 62, 81); padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0px;"><h1 style="margin: 20px 0px 10px; font-size: 36px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-weight: 500; line-height: 1.1; color: inherit;">Template</h1></div><div style="color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; padding: 20px;"></div>', 'is_active' => 1, 'relatable_type'=> NULL, 'relatable_id'=>NULL, 'reciepnt_type'=>'["admin","customer","seller"]', 'created_at' => now()]
                ];
                DB::table('email_templates')->insert($emails);
            }
        }
        
        if(Schema::hasTable('general_settings')){
            DB::statement("ALTER TABLE `general_settings` CHANGE `preloader_image` `preloader_image` VARCHAR(255) NULL DEFAULT NULL;");
        }
        if(Schema::hasTable('holidays')){
            DB::statement("ALTER TABLE `holidays` CHANGE `date` `date` VARCHAR(255) NOT NULL;");
        }

        // add push notification setting to permission & backend menu
        if(Schema::hasTable('permissions')){
            $sql = [
                ['id' => 732, 'module_id' => 18, 'parent_id' => 329, 'name' => 'Push Notification Setup', 'route' => 'admin.push.notification', 'type' => 2],
                ['id' => 733, 'module_id' => 18, 'parent_id' => 732, 'name' => 'Update', 'route' => 'push.notification.store', 'type' => 3],
            ];
            DB::table('permissions')->insert($sql);

        }

        if(Schema::hasTable('backendmenus')){
            $menu_sql = [
                ['is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-cog', 'name' => 'general_settings.Push Notification Setup','parent_route' => 'system_settings', 'route' => 'admin.push.notification', 'position' => 99999]//Submenu
            ];

            foreach($menu_sql as $menu){
                $children = null;
                $parent = null;
                if(array_key_exists('children',$menu)){
                    $children = $menu['children'];
                    unset( $menu['children']);
                }
                if(array_key_exists('parent_route', $menu) && !array_key_exists('parent_id', $menu)){
                    $parent = Backendmenu::where('route', $menu['parent_route'])->where('is_seller', $menu['is_seller'])->first();
                    unset( $menu['parent_route']);
                    $menu['parent_id'] = $parent->id;
                    $parent = Backendmenu::create($menu);
                }else{
                    $parent = Backendmenu::create($menu);
                }
                if($children){
                    foreach($children as $menu){
                        $sub_children = null;
                        if(array_key_exists('children',$menu)){
                            $sub_children = $menu['children'];
                            unset( $menu['children']);
                        }
                        $menu['parent_id'] = $parent->id;
                        $parent_children = Backendmenu::create($menu);
                        if($sub_children){
                            foreach($sub_children as $menu){
                                $subsubmenu['parent_id'] = $parent_children->id;
                                Backendmenu::create($subsubmenu);
                            }
                        }
                    }
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
        if(Schema::hasTable('business_settings')){
            DB::table('business_settings')->where('type', 'MsegatSMS')->where('category_type', 'sms_gateways')->delete();
        }

        if(Schema::hasTable('general_settings')){
            Schema::table('general_settings', function (Blueprint $table) {
                $table->dropColumn('verify_on_newsletter');
            });
        }
        if(Schema::hasTable('subscriptions')){
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('is_verified');
                $table->dropColumn('verify_code');
            });
        }
        if(Schema::hasTable('email_template_types')){
            $type = DB::table('email_template_types')->where('id', 42)->first();
            if($type){
                $type->delete;
            }
        }
        if(Schema::hasTable('email_templates')){
            $templates = EmailTemplate::where('type_id', 42)->pluck('id')->toArray();
            EmailTemplate::destroy($templates);
        }

    }
}
