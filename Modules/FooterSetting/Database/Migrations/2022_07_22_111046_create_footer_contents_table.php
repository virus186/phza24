<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FooterSetting\Entities\FooterContent;
use Modules\GeneralSetting\Entities\GeneralSetting;

class CreateFooterContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('footer_contents', function (Blueprint $table) {
            $table->id();
            $table->text('about_title')->nullable();
            $table->text('about_description')->nullable();
            $table->text('copy_right')->nullable();
            $table->string('section_one_title',255)->nullable();
            $table->string('section_two_title',255)->nullable();
            $table->string('section_three_title',255)->nullable();
            $table->string('play_store',300)->nullable()->default('#');
            $table->string('app_store',300)->nullable()->default('#');
            $table->string('payment_image',300)->nullable();
            $table->boolean('show_play_store')->default(0);
            $table->boolean('show_app_store')->default(0);
            $table->boolean('show_payment_image')->default(0);
            $table->timestamps();
        });

        if(Schema::hasTable('footer_contents')){
            FooterContent::create([
                'play_store' => '#'
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('footer_contents');
    }
}
