<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\PaymentGateway\Entities\PaymentMethod;
use Modules\PaymentGateway\Entities\SellerWisePaymentGateway;
use Modules\RolePermission\Entities\Permission;
use Modules\SidebarManager\Entities\Backendmenu;

class CreateSellerWisePaymentGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_wise_payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_method_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('status')->default(0);
            $table->string('perameter_1')->nullable();
            $table->string('perameter_2')->nullable();
            $table->string('perameter_3')->nullable();
            $table->string('perameter_4')->nullable();
            $table->string('perameter_5')->nullable();
            $table->string('perameter_6')->nullable();
            $table->string('perameter_7')->nullable();
            $table->timestamps();
        });

        if(Schema::hasTable('general_settings') && !Schema::hasColumn('general_settings','seller_wise_payment')){
            Schema::table('general_settings', function (Blueprint $table) {
                $table->boolean('seller_wise_payment')->default(0)->after('verify_on_newsletter');
            });
        }

        if(Schema::hasTable('order_payments')){
            Schema::table('order_payments', function (Blueprint $table) {
                $table->boolean('amount_goes_to_seller')->default(0)->after('status');
                $table->double('commision_amount')->default(0)->after('amount_goes_to_seller');
            });
        }

        if(Schema::hasTable('permissions')){
            $permission = Permission::find(305);
            if($permission){
                $permission->update([
                    'route' => 'admin.inhouse-order.index'
                ]);
            }
        }

        if(Schema::hasTable('backendmenus')){
            $menu = Backendmenu::where('route', 'payment_gateway.index')->first();
            if($menu){
                $menu->update([
                    'is_admin' => 1,
                    'is_seller' => 1
                ]);
            }
        }

        if(Schema::hasTable('payment_methods')){
            $methods = PaymentMethod::all();
            foreach($methods as $method){
                if($method->method == 'Cash On Delivery'){
                    SellerWisePaymentGateway::where('user_id', 1)->where('payment_method_id', $method->id)->updateOrCreate([
                        'payment_method_id' => $method->id,
                        'user_id' => 1,
                        'status' => $method->active_status
                    ]);
                }
                if($method->method == 'Wallet'){
                    SellerWisePaymentGateway::where('user_id', 1)->where('payment_method_id', $method->id)->updateOrCreate([
                        'payment_method_id' => $method->id,
                        'user_id' => 1,
                        'status' => $method->active_status
                    ]);
                }
                if($method->method == 'PayPal'){
                    SellerWisePaymentGateway::where('user_id', 1)->where('payment_method_id', $method->id)->updateOrCreate([
                        'payment_method_id' => $method->id,
                        'user_id' => 1,
                        'status' => $method->active_status,
                        'perameter_1' => env("PAYPAL_MODE"),
                        'perameter_2' => env('PAYPAL_CLIENT_ID'),
                        'perameter_3' => env('PAYPAL_CLIENT_SECRET')
                    ]);
                }
                if($method->method == 'Stripe'){
                    SellerWisePaymentGateway::where('user_id', 1)->where('payment_method_id', $method->id)->updateOrCreate([
                        'payment_method_id' => $method->id,
                        'user_id' => 1,
                        'status' => $method->active_status,
                        'perameter_1' => env('STRIPE_KEY'),
                        'perameter_2' => env('STRIPE_USER_NAME'),
                        'perameter_3' => env('STRIPE_SECRET')
                    ]);
                }
                if($method->method == 'PayStack'){
                    SellerWisePaymentGateway::where('user_id', 1)->where('payment_method_id', $method->id)->updateOrCreate([
                        'payment_method_id' => $method->id,
                        'user_id' => 1,
                        'status' => $method->active_status,
                        'perameter_1' => env('PAYSTACK_MERCHANT_EMAIL'),
                        'perameter_2' => env('PAYSTACK_KEY'),
                        'perameter_3' => env('PAYSTACK_SECRET'),
                        'perameter_4' => env('PAYSTACK_PAYMENT_URL')
                    ]);
                }
                if($method->method == 'RazorPay'){
                    SellerWisePaymentGateway::where('user_id', 1)->where('payment_method_id', $method->id)->updateOrCreate([
                        'payment_method_id' => $method->id,
                        'user_id' => 1,
                        'status' => $method->active_status,
                        'perameter_1' => env('RAZOR_KEY'),
                        'perameter_2' => env('RAZORPAY_SECRET')
                    ]);
                }
                if($method->method == 'Bank Payment'){
                    SellerWisePaymentGateway::where('user_id', 1)->where('payment_method_id', $method->id)->updateOrCreate([
                        'payment_method_id' => $method->id,
                        'user_id' => 1,
                        'status' => $method->active_status,
                        'perameter_1' => env('BANK_NAME'),
                        'perameter_2' => env('BRANCH_NAME'),
                        'perameter_3' => env('ACCOUNT_NUMBER'),
                        'perameter_4' => env('ACCOUNT_HOLDER')
                    ]);
                }
                if($method->method == 'Instamojo'){
                    SellerWisePaymentGateway::where('user_id', 1)->where('payment_method_id', $method->id)->updateOrCreate([
                        'payment_method_id' => $method->id,
                        'user_id' => 1,
                        'status' => $method->active_status,
                        'perameter_1' => env('IM_API_KEY'),
                        'perameter_2' => env('IM_AUTH_TOKEN'),
                        'perameter_3' => env('IM_URL')
                    ]);
                }
                if($method->method == 'PayTM'){
                    SellerWisePaymentGateway::where('user_id', 1)->where('payment_method_id', $method->id)->updateOrCreate([
                        'payment_method_id' => $method->id,
                        'user_id' => 1,
                        'status' => $method->active_status,
                        'perameter_1' => env('PAYTM_ENVIRONMENT'),
                        'perameter_2' => env('PAYTM_MERCHANT_ID'),
                        'perameter_3' => env('PAYTM_MERCHANT_WEBSITE'),
                        'perameter_4' => env('PAYTM_MERCHANT_KEY'),
                        'perameter_5' => env('PAYTM_CHANNEL'),
                        'perameter_6' => env('PAYTM_INDUSTRY_TYPE')
                    ]);
                }
                if($method->method == 'Midtrans'){
                    SellerWisePaymentGateway::where('user_id', 1)->where('payment_method_id', $method->id)->updateOrCreate([
                        'payment_method_id' => $method->id,
                        'user_id' => 1,
                        'status' => $method->active_status,
                        'perameter_1' => env("MIDTRANS_ENVIRONMENT"),
                        'perameter_2' => env('MIDTRANS_MERCHANT_KEY'),
                        'perameter_3' => env('MIDTRANS_CLIENT_KEY')
                    ]);
                }
                if($method->method == 'PayUMoney'){
                    SellerWisePaymentGateway::where('user_id', 1)->where('payment_method_id', $method->id)->updateOrCreate([
                        'payment_method_id' => $method->id,
                        'user_id' => 1,
                        'status' => $method->active_status,
                        'perameter_1' => env("PAYU_MONEY_MODE"),
                        'perameter_2' => env('PAYU_MONEY_KEY'),
                        'perameter_3' => env('PAYU_MONEY_SALT'),
                        'perameter_4' => env('PAYU_MONEY_AUTH')
                    ]);
                }
                if($method->method == 'JazzCash'){
                    SellerWisePaymentGateway::where('user_id', 1)->where('payment_method_id', $method->id)->updateOrCreate([
                        'payment_method_id' => $method->id,
                        'user_id' => 1,
                        'status' => $method->active_status,
                        'perameter_1' => env("JAZZ_MODE"),
                        'perameter_2' => env('JAZZ_MERCHANT_ID'),
                        'perameter_3' => env('JAZZ_PASSWORD'),
                        'perameter_4' => env('JAZZ_SALT'),
                        'perameter_5' => env('JAZZ_LIVE_URL')
                    ]);
                }
                if($method->method == 'Google Pay'){
                    SellerWisePaymentGateway::where('user_id', 1)->where('payment_method_id', $method->id)->updateOrCreate([
                        'payment_method_id' => $method->id,
                        'user_id' => 1,
                        'status' => $method->active_status,
                        'perameter_1' => env("GOOGLE_PAY_ENVIRONMENT"),
                        'perameter_2' => env('GOOGLE_PAY_GATEWAY'),
                        'perameter_3' => env('GOOGLE_PAY_MERCHANT_ID'),
                        'perameter_4' => env('GOOGLE_PAY_MERCHANT_NAME')
                    ]);
                }
                if($method->method == 'FlutterWave'){
                    SellerWisePaymentGateway::where('user_id', 1)->where('payment_method_id', $method->id)->updateOrCreate([
                        'payment_method_id' => $method->id,
                        'user_id' => 1,
                        'status' => $method->active_status,
                        'perameter_1' => env('FLW_PUBLIC_KEY'),
                        'perameter_2' => env('FLW_SECRET_KEY'),
                        'perameter_3' => env('FLW_SECRET_HASH')
                    ]);
                }
                if($method->method == 'Bkash'){
                    SellerWisePaymentGateway::where('user_id', 1)->where('payment_method_id', $method->id)->updateOrCreate([
                        'payment_method_id' => $method->id,
                        'user_id' => 1,
                        'status' => $method->active_status,
                        'perameter_1' => env('IS_BKASH_LOCALHOST'),
                        'perameter_2' => env('BKASH_APP_KEY'),
                        'perameter_3' => env('BKASH_APP_SECRET'),
                        'perameter_4' => env('BKASH_USERNAME'),
                        'perameter_5' => env('BKASH_PASSWORD')
                    ]);
                }
                if($method->method == 'SslCommerz'){
                    SellerWisePaymentGateway::where('user_id', 1)->where('payment_method_id', $method->id)->updateOrCreate([
                        'payment_method_id' => $method->id,
                        'user_id' => 1,
                        'status' => $method->active_status,
                        'perameter_1' => env('SSL_Commerz_IS_LOCALHOST'),
                        'perameter_2' => env('SSL_Commerz_API_DOMAIN_URL'),
                        'perameter_3' => env('SSL_Commerz_STORE_ID'),
                        'perameter_4' => env('SSL_Commerz_STORE_PASSWORD')
                    ]);
                }
                if($method->method == 'Mercado Pago'){
                    SellerWisePaymentGateway::where('user_id', 1)->where('payment_method_id', $method->id)->updateOrCreate([
                        'payment_method_id' => $method->id,
                        'user_id' => 1,
                        'status' => $method->active_status,
                        'perameter_1' => env('MERCADO_PUBLIC_KEY'),
                        'perameter_2' => env('MERCADO_ACCESS_TOKEN')
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
        Schema::dropIfExists('seller_wise_payment_gateways');
    }
}
