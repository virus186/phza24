<?php

namespace Database\Seeders;

use Botble\Ecommerce\Models\GlobalOption;
use Botble\Ecommerce\Models\GlobalOptionValue;
use Botble\Ecommerce\Models\Option;
use Botble\Ecommerce\Models\OptionValue;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Option\OptionType\Dropdown;
use Botble\Ecommerce\Option\OptionType\RadioButton;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $options = [
            [
                'name' => 'Warranty',
                'option_type' => RadioButton::class,
                'required' => true,
                'values' => [
                    [
                        'option_value' => '1 Year',
                        'affect_price' => 0,
                        'affect_type' => 0,
                    ],
                    [
                        'option_value' => '2 Year',
                        'affect_price' => 10,
                        'affect_type' => 0,
                    ],
                    [
                        'option_value' => '3 Year',
                        'affect_price' => 20,
                        'affect_type' => 0,
                    ],
                ],
            ],
            [
                'name' => 'RAM',
                'option_type' => RadioButton::class,
                'required' => true,
                'values' => [
                    [
                        'option_value' => '4GB',
                        'affect_price' => 0,
                        'affect_type' => 0,
                    ],
                    [
                        'option_value' => '8GB',
                        'affect_price' => 10,
                        'affect_type' => 0,
                    ],
                    [
                        'option_value' => '16GB',
                        'affect_price' => 20,
                        'affect_type' => 0,
                    ],
                ],
            ],
            [
                'name' => 'CPU',
                'option_type' => RadioButton::class,
                'required' => true,
                'values' => [
                    [
                        'option_value' => 'Core i5',
                        'affect_price' => 0,
                        'affect_type' => 0,
                    ],
                    [
                        'option_value' => 'Core i7',
                        'affect_price' => 10,
                        'affect_type' => 0,
                    ],
                    [
                        'option_value' => 'Core i9',
                        'affect_price' => 20,
                        'affect_type' => 0,
                    ],
                ],
            ],
            [
                'name' => 'HDD',
                'option_type' => Dropdown::class,
                'required' => false,
                'values' => [
                    [
                        'option_value' => '128GB',
                        'affect_price' => 0,
                        'affect_type' => 0,
                    ],
                    [
                        'option_value' => '256GB',
                        'affect_price' => 10,
                        'affect_type' => 0,
                    ],
                    [
                        'option_value' => '512GB',
                        'affect_price' => 20,
                        'affect_type' => 0,
                    ],
                ],
            ],
        ];

        DB::table('ec_global_options')->truncate();
        DB::table('ec_global_option_value')->truncate();
        DB::table('ec_options')->truncate();
        DB::table('ec_option_value')->truncate();

        $this->saveGlobalOption($options);
        // $this->seedProductOption($options);
    }

    /**
     * @param array $options
     * @return void
     */
    protected function saveGlobalOption(array $options)
    {
        foreach ($options as $option) {
            $globalOption = new GlobalOption();
            $globalOption->name = $option['name'];
            $globalOption->option_type = $option['option_type'];
            $globalOption->required = $option['required'];
            $globalOption->save();
            $optionValue = $this->formatGlobalOptionValue($option['values']);
            $globalOption->values()->saveMany($optionValue);
        }
    }

    /**
     * @param array $options
     * @return void
     */
    protected function seedProductOption(array $options)
    {
        $products = Product::inRandomOrder(5)->get();

        foreach ($products as $product) {
            foreach ($options as $option) {
                $productOption = new Option();
                $productOption->name = $option['name'];
                $productOption->option_type = $option['option_type'];
                $productOption->required = $option['required'];
                $productOption->product_id = $product->id;
                $productOption->save();

                $productOption->values()->saveMany($this->formatOptionValue($option['values']));
            }
        }
    }

    /**
     * @param $data
     * @return array
     */
    protected function formatOptionValue($data): array
    {
        $values = [];
        foreach ($data as $item) {
            $globalOptionValue = new OptionValue();
            $item['affect_price'] = !empty($item['affect_price']) ? $item['affect_price'] : 0;
            $globalOptionValue->fill($item);
            $values[] = $globalOptionValue;
        }

        return $values;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function formatGlobalOptionValue(array $data): array
    {
        $values = [];
        foreach ($data as $item) {
            $globalOptionValue = new GlobalOptionValue();
            $item['affect_price'] = !empty($item['affect_price']) ? $item['affect_price'] : 0;
            $globalOptionValue->fill($item);
            $values[] = $globalOptionValue;
        }

        return $values;
    }
}
