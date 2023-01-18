<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\RolePermission\Entities\Permission;

class BlogPermisionIssueFixed extends Migration
{
    
    public function up()
    {
        if(Schema::hasTable('permissions')){
            $blog_list = Permission::where('id', 87)->first();
            if($blog_list){
                $blog_list->update([
                    'name' => 'Blog Posts',
                    'route' => 'blog.posts.index'
                ]);
            }
            $blog_destroy = Permission::where('id', 90)->first();
            if($blog_destroy){
                $blog_destroy->update([
                    'route' => 'blog.posts.destroy'
                ]);
            }
            $check = Permission::where('id', 716)->first();
            if(!$check){
                $sql = [
                    ['id' => 716, 'module_id' => 6, 'parent_id' => 87, 'name' => 'Show', 'route' => 'blog.posts.show', 'type' => 3 ]
                ];
                DB::table('permissions')->insert($sql);
            }
            
        }
    }

    
    public function down()
    {
        
    }
}
