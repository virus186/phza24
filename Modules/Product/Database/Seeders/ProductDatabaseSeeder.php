<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\UnitType;
use Modules\Setup\Entities\Tag;

class ProductDatabaseSeeder extends Seeder
{
    protected $faker;
    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products')->truncate();
        DB::table('product_sku')->truncate();
        DB::table('product_tag')->truncate();
        DB::table('seller_products')->truncate();
        DB::table('seller_product_s_k_us')->truncate();
        DB::table('category_product')->truncate();
        DB::table('unit_types')->truncate();
        DB::table('tags')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        UnitType::factory()->count(50)->create();
        $tag = Tag::create([
            'name' => 'test'
        ]);
        
        $t = 1;
        $i = 1;
        $z = 4000;
        for($t;$t <= 10; $t++){
            $product_sql = [];
            $product_sku = [];
            $product_tag = [];
            $seller_product = [];
            $seller_product_sku = [];
            $product_category = [];

            for($i; $i <= $z; $i++){
                $prod_name = $this->faker->unique()->name;
                $prod_slug = $this->faker->unique()->slug;
                $product_sql[] = [
                    'id' => $i,
                    'product_name' => $prod_name,
                    'slug' => $prod_slug,
                    'product_type' => 1,
                    'unit_type_id' => rand(1,50),
                    'brand_id' => rand(1,1000),
                    'discount_type' => 0,
                    'discount' => 0,
                    'tax_type' => 0,
                    'minimum_order_qty' => 1,
                    'max_order_qty' => 10,
                    'is_physical' => 1,
                    'is_approved' => 1,
                    'requested_by' => 1,
                    'created_by' => 1
                ];
                $sku = $this->faker->unique()->slug;
                $product_sku[] = [
                    'id' => $i,
                    'product_id' => $i,
                    'sku' => $sku,
                    'selling_price' => rand(100, 200),
                    'track_sku' => $sku,

                ];
                $product_tag[] = [
                    'id' => $i,
                    'product_id' => $i,
                    'tag_id' => $tag->id
                ];

                $seller_product[] = [
                    'id' => $i,
                    'user_id' => 1,
                    'product_id' => $i,
                    'tax_type' => 0,
                    'discount_type' => 0,
                    'product_name' => $prod_name,
                    'slug' => $prod_slug,
                    'is_approved' => 1
                ];

                $seller_product_sku[] = [
                    'id' => $i,
                    'user_id' => 1,
                    'product_id' => $i,
                    'product_sku_id' => $i,
                    'selling_price' => rand(100, 200),
                    'status' => 1
                ];
                $product_category[] = [
                    'id' => $i,
                    'product_id' => $i,
                    'category_id' => rand(1, 3000)
                ];

                
            }
            DB::table('products')->insert($product_sql);
            DB::table('product_sku')->insert($product_sku);
            DB::table('product_tag')->insert($product_tag);
            DB::table('seller_products')->insert($seller_product);
            DB::table('seller_product_s_k_us')->insert($seller_product_sku);
            DB::table('category_product')->insert($product_category);
            $i = $i + 1;
            $z = $z + 4000;
        }

    }
}
