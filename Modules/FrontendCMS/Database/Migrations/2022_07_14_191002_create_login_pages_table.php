<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\FrontendCMS\Entities\LoginPage;
use Modules\RolePermission\Entities\Permission;
use Modules\SidebarManager\Entities\Sidebar;

class CreateLoginPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title', 500)->nullable();
            $table->string('sub_title', 500)->nullable();
            $table->string('cover_img', 250)->nullable();
            $table->string('login_slug', 50)->nullable();
            $table->timestamps();
        });

        if(Schema::hasTable('login_pages')){
            $sql = [
                ['title' => 'Turn your ideas into reality.', 'sub_title' => 'Consistent quality and experience across all platforms and devices.', 'cover_img' => 'frontend/amazy/img/banner/admin_login_img.png','login_slug' => 'admin-login' ],
                ['title' => 'Turn your ideas into reality.', 'sub_title' => 'Consistent quality and experience across all platforms and devices.', 'cover_img' => 'frontend/amazy/img/banner/login_img.png','login_slug' => 'login' ],
                ['title' => 'Turn your ideas into reality.', 'sub_title' => 'Consistent quality and experience across all platforms and devices.', 'cover_img' => 'frontend/amazy/img/banner/seller_login_img.png','login_slug' => 'seller-login' ],
                ['title' => 'Turn your ideas into reality.', 'sub_title' => 'Consistent quality and experience across all platforms and devices.', 'cover_img' => 'frontend/amazy/img/banner/password_reset_login_img.png','login_slug' => 'password-reset' ]
            ];

            LoginPage::insert($sql);
        }

        if(Schema::hasTable('permissions')){
            $sql = [
                ['id'  => 721, 'module_id' => 3, 'parent_id' => 26, 'name' => 'Login Page', 'route' => 'frontendcms.login_page', 'type' => 2 ],
                ['id'  => 722, 'module_id' => 3, 'parent_id' => 721, 'name' => 'Update', 'route' => 'frontendcms.login_page.update', 'type' => 3 ],
            ];
            DB::table('permissions')->insert($sql);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('login_page');
        if(Schema::hasTable('permissions')){
            Permission::destroy([721,722]);
        }
    }
}
