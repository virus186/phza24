<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Appearance\Entities\Theme;

class AddColumnInThemeTable extends Migration
{

    public function up()
    {
        Schema::table('themes', function ($table) {
            if (!Schema::hasColumn('themes', 'purchase_code')) {
                $table->text('purchase_code')->nullable();
            }

            if (!Schema::hasColumn('themes', 'email')) {
                $table->text('email')->nullable();
            }

            if (!Schema::hasColumn('themes', 'activated_date')) {
                $table->text('activated_date')->nullable();
            }


            if (!Schema::hasColumn('themes', 'item_code')) {
                $table->text('item_code')->nullable();
            }

            if (!Schema::hasColumn('themes', 'checksum')) {
                $table->text('checksum')->nullable();
            }

            if (!Schema::hasColumn('themes', 'installed_domain')) {
                $table->text('installed_domain')->nullable();
            }

            $default_theme = Theme::where('name', 'Default')->first();
            if($default_theme){
                if($default_theme->image == 'frontend/default/img/amazcart.JPG'){
                    $default_image = 'frontend/default/img/amazcart.jpg';
                }else{
                    $default_image = $default_theme->image;
                }
                $default_theme->update([
                    'live_link' => 'https://amaz.rishfa.com/',
                    'image' => $default_image
                ]);
            }
            
            $amazy_theme = Theme::where('name', 'Amazy')->first();
            if(!$amazy_theme){
                Theme::create([
                    'name' => 'Amazy',
                    'image' => '/frontend/amazy/img/amazy.jpg',
                    'version' => '1.0.0',
                    'folder_path' => 'amazy',
                    'live_link' => 'http://amazy.rishfa.com',
                    'description' => 'Amazy theme description',
                    'is_active' => 0,
                    'status' => 1,
                    'tags' => 'amazy'
                ]);
            }
        });
    }


    public function down()
    {
        //
    }
}
