<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddSendDigitalFileTemplateToEmailTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('email_template_types')){
            $type = DB::table('email_template_types')->where('id', 43)->first();
            if(!$type){
                DB::statement("INSERT INTO `email_template_types` (`id`, `type`, `created_at`, `updated_at`) VALUES
                    (43, 'Send digital file', NULL, '2021-01-20 12:40:47')
                ");
            }
        }

        if(Schema::hasTable('email_templates')){
            $template = DB::table('email_templates')->where('type_id', 43)->first();
            if(!$template){
                $emails = [
                    ['type_id' => '43', 'subject' => 'Send digital file', 'value' => '<div style="font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; color: rgb(255, 255, 255); text-align: center; background-color: rgb(152, 62, 81); padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0px;"><h1 style="margin: 20px 0px 10px; font-size: 36px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-weight: 500; line-height: 1.1; color: inherit;">Template</h1></div><div style="color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; padding: 20px;"><p style="color: rgb(85, 85, 85);">Hello,<br><br>You are receiving this email because of get access digital link.</p><p style="color: rgb(85, 85, 85);">File access link is :</p><p style="color: rgb(85, 85, 85);">{DIGITAL_FILE_LINK}<br></p><hr style="box-sizing: content-box; margin-top: 20px; margin-bottom: 20px; border-top-color: rgb(238, 238, 238);"><p style="color: rgb(85, 85, 85);"><br></p><p style="color: rgb(85, 85, 85);">{EMAIL_SIGNATURE}</p><p style="color: rgb(85, 85, 85);"><br></p></div><div style="font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; color: rgb(255, 255, 255); text-align: center; background-color: rgb(152, 62, 81); padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0px;"><h1 style="margin: 20px 0px 10px; font-size: 36px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-weight: 500; line-height: 1.1; color: inherit;">Template</h1></div><div style="color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; padding: 20px;"></div>', 'is_active' => 1, 'relatable_type'=> NULL, 'relatable_id'=>NULL, 'reciepnt_type'=>'["customer"]', 'created_at' => now()]
                ];
                DB::table('email_templates')->insert($emails);
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
