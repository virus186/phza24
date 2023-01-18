<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\RolePermission\Entities\Permission;

class CreateBackendmenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('backendmenus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_admin')->default(0);
            $table->boolean('is_seller')->default(0);
            $table->string('route')->nullable();
            $table->unsignedBigInteger('position')->default(0);
            $table->string('module')->nullable();
            $table->timestamps();
        });

        $sql = [
                // Dashboard 
                ['id' => 1, 'parent_id' => null, 'is_admin' => 1,'is_seller' => 1, 'icon' =>'fas fa-th', 'name' => 'common.dashboard', 'route' => null, 'position' => 1], //Section menu
                ['id' => 165, 'parent_id' => 1, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-th', 'name' => 'common.dashboard', 'route' => 'admin.dashboard', 'position' => 1],// Menu 
                
                //User manages
                ['id' => 2, 'parent_id' => null, 'is_admin' => 1,'is_seller' => 1, 'icon' =>null, 'name' => 'common.user_manages', 'route' => null, 'position' => 2], //Section menu 
                //customer
                ['id' => 3, 'parent_id' => 2, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-users', 'name' => 'common.customer', 'route' => 'cusotmer.list_active', 'position' => 1],// Menu
                ['id' => 4, 'parent_id' => 3, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-users', 'name' => 'common.all_customer', 'route' => 'cusotmer.list_active', 'position' => 1],//Submenu
                // human resource
                ['id' => 5, 'parent_id' => 2, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-users', 'name' => 'hr.human_resource', 'route' => 'human_resource', 'position' => 2],// Menu
                ['id' => 6, 'parent_id' => 5, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-users', 'name' => 'hr.staff', 'route' => 'staffs.index', 'position' => 1],//Submenu
                ['id' => 7, 'parent_id' => 5, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-users', 'name' => 'hr.role', 'route' => 'permission.roles.index', 'position' => 2],//Submenu
                ['id' => 8, 'parent_id' => 5, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-users', 'name' => 'hr.department', 'route' => 'departments.index', 'position' => 3],//Submenu
                ['id' => 9, 'parent_id' => 5, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-users', 'name' => 'hr.attendance', 'route' => 'attendances.index', 'position' => 4],//Submenu
                ['id' => 10, 'parent_id' => 5, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-users', 'name' => 'hr.attendance_report', 'route' => 'attendance_report.index', 'position' => 5],//Submenu
                ['id' => 11, 'parent_id' => 5, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-users', 'name' => 'hr.event', 'route' => 'events.index', 'position' => 6],//Submenu
                ['id' => 12, 'parent_id' => 5, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-users', 'name' => 'hr.holiday_setup', 'route' => 'holidays.index', 'position' => 7],//Submenu

                //Frontend CMS
                ['id' => 13, 'parent_id' => null, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'frontendCms.frontend_cms', 'route' => null, 'position' => 3], //Section menu 
                // frontend cms
                ['id' => 14, 'parent_id' => 13, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'ti-eye', 'name' => 'frontendCms.frontend_cms', 'route' => 'frontend_cms', 'position' => 1],// Menu
                ['id' => 15, 'parent_id' => 14, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'ti-home', 'name' => 'frontendCms.home_page', 'route' => 'frontendcms.widget.index', 'position' => 1],//Submenu
                ['id' => 16, 'parent_id' => 14, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'ti-eye', 'name' => 'frontendCms.features', 'route' => 'frontendcms.features.index', 'position' => 2],//Submenu
                ['id' => 17, 'parent_id' => 14, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'ti-eye', 'name' => 'frontendCms.return_exchange', 'route' => 'frontendcms.return-exchange.index', 'position' => 3],//Submenu
                ['id' => 18, 'parent_id' => 14, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'ti-eye', 'name' => 'frontendCms.contact_content', 'route' => 'frontendcms.contact-content.index', 'position' => 4],//Submenu
                ['id' => 19, 'parent_id' => 14, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'ti-eye', 'name' => 'frontendCms.dynamic_pages', 'route' => 'frontendcms.dynamic-page.index', 'position' => 5],//Submenu
                ['id' => 20, 'parent_id' => 14, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'ti-eye', 'name' => 'frontendCms.footer_setting', 'route' => 'footerSetting.footer.index', 'position' => 6],//Submenu
                ['id' => 21, 'parent_id' => 14, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'ti-eye', 'name' => 'frontendCms.subscription', 'route' => 'frontendcms.subscribe-content.index', 'position' => 7],//Submenu
                ['id' => 22, 'parent_id' => 14, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'ti-eye', 'name' => 'frontendCms.popup_content', 'route' => 'frontendcms.popup-content.index', 'position' => 8],//Submenu
                ['id' => 23, 'parent_id' => 14, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'ti-eye', 'name' => 'frontendCms.promotion_bar', 'route' => 'frontendcms.promotionbar.index', 'position' => 9],//Submenu
                ['id' => 24, 'parent_id' => 14, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'ti-eye', 'name' => 'frontendCms.ads_bar', 'route' => 'frontendcms.ads_bar.index', 'position' => 10],//Submenu
                ['id' => 25, 'parent_id' => 14, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'ti-eye', 'name' => 'frontendCms.related_sale_setting', 'route' => 'frontendcms.title_index', 'position' => 11],//Submenu
                ['id' => 26, 'parent_id' => 14, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'ti-eye', 'name' => 'frontendCms.about_us', 'route' => 'frontendcms.about-us.index', 'position' => 12],//Submenu
                ['id' => 27, 'parent_id' => 14, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'ti-eye', 'name' => 'frontendCms.login_page', 'route' => 'frontendcms.login_page', 'position' => 13],//Submenu
               
                // appearance
                ['id' => 28, 'parent_id' => 13, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-wrench', 'name' => 'appearance.appearance', 'route' => 'appearance', 'position' => 2],// Menu
                ['id' => 29, 'parent_id' => 28, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'ti-panel', 'name' => 'appearance.themes', 'route' => 'appearance.themes.index', 'position' => 1],//Submenu
                ['id' => 30, 'parent_id' => 28, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-wrench', 'name' => 'appearance.Color scheme', 'route' => 'appearance.themeColor.index', 'position' => 2],//Submenu
                ['id' => 31, 'parent_id' => 28, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-wrench', 'name' => 'appearance.menu', 'route' => 'menu.manage', 'position' => 3],//Submenu
                ['id' => 32, 'parent_id' => 28, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-wrench', 'name' => 'appearance.header', 'route' => 'appearance.header.index', 'position' => 4],//Submenu
                ['id' => 33, 'parent_id' => 28, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-wrench', 'name' => 'setup.dashboard_setup', 'route' => 'appearance.dashoboard.index', 'position' => 5],//Submenu
                ['id' => 34, 'parent_id' => 28, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-wrench', 'name' => 'appearance.Dashboard color', 'route' => 'appearance.color.index', 'position' => 6],//Submenu
                ['id' => 35, 'parent_id' => 28, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-wrench', 'name' => 'general_settings.preloader_setting', 'route' => 'appearance.pre-loader', 'position' => 7],//Submenu
                // Page Builder
                ['id' => 36, 'parent_id' => 13, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-cogs', 'name' => 'page-builder.Page Builder', 'route' => 'page_builder', 'position' => 3],// Menu
                ['id' => 37, 'parent_id' => 36, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-cogs', 'name' => 'page-builder.Pages', 'route' => 'page_builder.pages.index', 'position' => 1],//Submenu
                // Form Builder
                ['id' => 38, 'parent_id' => 13, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-cogs', 'name' => 'formBuilder.form_builder', 'route' => 'form_builder','position' => 4],// Menu
                ['id' => 39, 'parent_id' => 38, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-cogs', 'name' => 'formBuilder.forms', 'route' => 'form_builder.forms.index', 'position' => 1],//Submenu
                  
                //Order manages
                ['id' => 40, 'parent_id' => null, 'is_admin' => 1,'is_seller' => 1, 'icon' =>null, 'name' => 'order.order_manages', 'route' => null,'position' => 4], //Section menu 
                // Order manage
                ['id' => 41, 'parent_id' => 40, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-shopping-cart', 'name' => 'order.order_manage', 'route' => 'order_manage', 'position' => 1],// Menu
                ['id' => 42, 'parent_id' => 41, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-shopping-cart', 'name' => 'order.inhouse_orders', 'route' => 'admin.inhouse-order.index', 'position' => 1],// Submenu
                ['id' => 43, 'parent_id' => 41, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-shopping-cart', 'name' => 'order.total_order', 'route' => 'order_manage.total_sales_index', 'position' => 2],//Submenu
                ['id' => 44, 'parent_id' => 41, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-shopping-cart', 'name' => 'order.delivery_process', 'route' => 'order_manage.process_index', 'position' => 3],//Submenu
                ['id' => 45, 'parent_id' => 41, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-shopping-cart', 'name' => 'order.cancel_reason', 'route' => 'order_manage.cancel_reason_index', 'position' => 4],//Submenu
                ['id' => 46, 'parent_id' => 41, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-shopping-cart', 'name' => 'order.Track order setting', 'route' => 'track_order_configuration', 'position' => 5],//Submenu
                // refund manage
                ['id' => 47, 'parent_id' => 40, 'is_admin' => 1,'is_seller' => 1, 'icon' =>'fas fa-shopping-cart', 'name' => 'refund.refund_manage', 'route' => 'refund_manage', 'position' => 2],// Menu
                ['id' => 48, 'parent_id' => 47, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-shopping-cart', 'name' => 'refund.pending_refund_requests', 'route' => 'refund.total_refund_list', 'position' => 1],// Submenu
                ['id' => 49, 'parent_id' => 47, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-shopping-cart', 'name' => 'refund.confirmed_refund_requests', 'route' => 'refund.confirmed_refund_requests', 'position' => 2],// Submenu
                ['id' => 50, 'parent_id' => 47, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-shopping-cart', 'name' => 'refund.reasons', 'route' => 'refund.reasons_list', 'position' => 3],// Submenu
                ['id' => 51, 'parent_id' => 47, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-shopping-cart', 'name' => 'refund.refund_process', 'route' => 'refund.process_index', 'position' => 4],// Submenu
                ['id' => 52, 'parent_id' => 47, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-wrench', 'name' => 'refund.Refund setting', 'route' => 'refund.config', 'position' => 5],// Submenu

                    //Product Manage
                ['id' => 53, 'parent_id' => null, 'is_admin' => 1,'is_seller' => 1, 'icon' =>null, 'name' => 'product.product_manage', 'route' => null, 'position' => 5], //Section menu 
                // products
                ['id' => 54, 'parent_id' => 53, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fa fa-product-hunt', 'name' => 'product.products', 'route' => 'product_module', 'position' => 1],// Menu
                ['id' => 55, 'parent_id' => 54, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fa fa-product-hunt', 'name' => 'product.category', 'route' => 'product.category.index', 'position' => 1],//Submenu
                ['id' => 56, 'parent_id' => 54, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fa fa-product-hunt', 'name' => 'product.brand', 'route' => 'product.brands.index', 'position' => 2],//Submenu
                ['id' => 57, 'parent_id' => 54, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fa fa-product-hunt', 'name' => 'product.attribute', 'route' => 'product.attribute.index', 'position' => 3],//Submenu
                ['id' => 58, 'parent_id' => 54, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fa fa-product-hunt', 'name' => 'product.units', 'route' => 'product.units.index', 'position' => 4],//Submenu
                ['id' => 59, 'parent_id' => 54, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fa fa-product-hunt', 'name' => 'product.add_new_product', 'route' => 'product.create', 'position' => 5],//Submenu
                ['id' => 60, 'parent_id' => 54, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fa fa-product-hunt', 'name' => 'product.product_list', 'route' => 'product.index', 'position' => 6],//Submenu
                ['id' => 61, 'parent_id' => 54, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fa fa-product-hunt', 'name' => 'product.bulk_product_upload', 'route' => 'product.bulk_product_upload_page', 'position' => 7],//Submenu
                ['id' => 62, 'parent_id' => 54, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fa fa-product-hunt', 'name' => 'product.recent_view_config', 'route' => 'product.recent_view_product_config', 'position' => 8],//Submenu
                // review
                ['id' => 63, 'parent_id' => 53, 'is_admin' => 1,'is_seller' => 1, 'icon' =>'fas fa-user', 'name' => 'review.review', 'route' => 'review_module', 'position' => 2],// Menu
                ['id' => 64, 'parent_id' => 63, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fa fa-product-hunt', 'name' => 'review.product_review', 'route' => 'review.product.index', 'position' => 1],//Submenu
                ['id' => 65, 'parent_id' => 63, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-user', 'name' => 'review.company_review', 'route' => 'review.seller.index', 'position' => 2],//Submenu
                ['id' => 66, 'parent_id' => 63, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-wrench', 'name' => 'review.Review setting', 'route' => 'review.review_configuration', 'position' => 3],//Submenu
                // shipping
                ['id' => 67, 'parent_id' => 53, 'is_admin' => 1,'is_seller' => 1, 'icon' =>'ti-truck', 'name' => 'shipping.shipping', 'route' => 'shipping_methods', 'position' => 3],// Menu
                ['id' => 68, 'parent_id' => 67, 'is_admin' => 1,'is_seller' => 1, 'icon' =>'ti-truck', 'name' => 'shipping.carriers', 'route' => 'shipping.carriers.index', 'position' => 1],//Submenu
                ['id' => 69, 'parent_id' => 67, 'is_admin' => 1,'is_seller' => 1, 'icon' =>'ti-truck', 'name' => 'shipping.shipping_rates', 'route' => 'shipping_methods.index', 'position' => 2],//Submenu
                ['id' => 70, 'parent_id' => 67, 'is_admin' => 1,'is_seller' => 1, 'icon' =>'ti-truck', 'name' => 'shipping.pickup_locations', 'route' => 'shipping.pickup_locations.index', 'position' => 3],//Submenu
                ['id' => 71, 'parent_id' => 67, 'is_admin' => 1,'is_seller' => 1, 'icon' =>'ti-truck', 'name' => 'shipping.shipping_orders', 'route' => 'shipping.pending_orders.index', 'position' => 4],//Submenu
                ['id' => 72, 'parent_id' => 67, 'is_admin' => 1,'is_seller' => 1, 'icon' =>'fas fa-wrench', 'name' => 'shipping.Shipping Setting', 'route' => 'shipping.configuration.index', 'position' => 5],//Submenu
                // Media Manager
                ['id' => 73, 'parent_id' => 53, 'is_admin' => 1,'is_seller' => 1, 'icon' =>'ti-image', 'name' => 'common.media_manager', 'route' => 'media-manager', 'position' => 4],// Menu
                ['id' => 74, 'parent_id' => 73, 'is_admin' => 1,'is_seller' => 1, 'icon' =>'ti-image', 'name' => 'common.all_upload_files', 'route' => 'media-manager.upload_files', 'position' => 1],//Submenu
                ['id' => 75, 'parent_id' => 73, 'is_admin' => 1,'is_seller' => 1, 'icon' =>'ti-image', 'name' => 'common.new_upload', 'route' => 'media-manager.new-upload', 'position' => 2],//Submenu
                 // Promotional
                ['id' => 76, 'parent_id' => null, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'common.promotional', 'route' => null, 'position' => 6], //Section menu 
                // Marketing
                ['id' => 77, 'parent_id' => 76, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-user', 'name' => 'marketing.marketing', 'route' => 'marketing_module', 'position' => 1],// Menu
                ['id' => 78, 'parent_id' => 77, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'marketing.flash_deals', 'route' => 'marketing.flash-deals', 'position' => 1],//Submenu
                ['id' => 79, 'parent_id' => 77, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'marketing.coupons', 'route' => 'marketing.coupon', 'position' => 2],//Submenu
                ['id' => 80, 'parent_id' => 77, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'marketing.new_user_zone', 'route' => 'marketing.new-user-zone', 'position' => 3],//Submenu
                ['id' => 81, 'parent_id' => 77, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'marketing.news_letters', 'route' => 'marketing.news-letter', 'position' => 4],//Submenu
                ['id' => 82, 'parent_id' => 77, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'marketing.bulk_sms', 'route' => 'marketing.bulk-sms', 'position' => 5],//Submenu
                ['id' => 83, 'parent_id' => 77, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'marketing.subscribers', 'route' => 'marketing.subscriber', 'position' => 6],//Submenu
                ['id' => 84, 'parent_id' => 77, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'marketing.referral_code_setup', 'route' => 'marketing.referral-code', 'position' => 7],//Submenu
                ['id' => 85, 'parent_id' => 77, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'marketing.marketing setting', 'route' => 'marketing.configuration', 'position' => 8],//Submenu
                // Gift Card
                ['id' => 86, 'parent_id' => 76, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-gift', 'name' => 'common.gift_card', 'route' => 'gift_card_manage', 'position' => 2],// Menu
                ['id' => 87, 'parent_id' => 86,'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'common.gift_card_list', 'route' => 'admin.giftcard.index', 'position' => 1],//Submenu
                  // Finance
                  ['id' => 88, 'parent_id' => null, 'is_admin' => 1,'is_seller' => 1, 'icon' =>null, 'name' => 'common.finance', 'route' => null, 'position' => 7], //Section menu 
                  // Account
                  ['id' => 89, 'parent_id' => 88, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-comment-dollar', 'name' => 'account.Account', 'route' => 'account_module', 'position' => 1],// Menu
                  ['id' => 90, 'parent_id' => 89, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'account.Chart Of Accounts', 'route' => 'account.chart-of-accounts.index', 'position' => 1],//Submenu
                  ['id' => 91, 'parent_id' => 89, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'account.Bank Accounts', 'route' => 'account.bank-accounts.index', 'position' => 2],//Submenu
                  ['id' => 92, 'parent_id' => 89, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'list.Income', 'route' => 'account.incomes.index', 'position' => 3],//Submenu
                  ['id' => 93, 'parent_id' => 89, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'account.Expenses', 'route' => 'account.expenses.index', 'position' => 4],//Submenu
                  ['id' => 94, 'parent_id' => 89, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'account.Profit', 'route' => 'account.profit', 'position' => 5],//Submenu
                  ['id' => 95, 'parent_id' => 89, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'account.Transaction', 'route' => 'account.transaction', 'position' => 6],//Submenu
                  ['id' => 96, 'parent_id' => 89, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'account.Statement', 'route' => 'account.statement', 'position' => 7],//Submenu
                    // Wallet manage
                  ['id' => 97, 'parent_id' => 88, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-wallet', 'name' => 'wallet.wallet_manage', 'route' => 'wallet_manage', 'position' => 2],// Menu
                  ['id' => 98, 'parent_id' => 97, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'wallet.online_recharge', 'route' => 'wallet_recharge.index', 'position' => 1],//Submenu
                  ['id' => 99, 'parent_id' => 97, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'wallet.bank_recharge', 'route' => 'bank_recharge.index', 'position' => 2],//Submenu
                  ['id' => 100, 'parent_id' => 97, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'wallet.offline_recharge', 'route' => 'wallet_recharge.offline_index', 'position' => 3],//Submenu
                  ['id' => 101, 'parent_id' => 97, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'wallet.Wallet setting', 'route' => 'wallet.wallet_configuration', 'position' => 4],//Submenu
                   // Content
                   ['id' => 102, 'parent_id' => null, 'is_admin' => 1,'is_seller' => 1, 'icon' =>null, 'name' => 'common.content', 'route' => null, 'position' => 8], //Section menu 
                   // Blog
                   ['id' => 103, 'parent_id' => 102, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-users', 'name' => 'blog.blog', 'route' => 'blog_module', 'position' => 1],// Menu
                   ['id' => 104, 'parent_id' => 103, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'blog.Blog posts', 'route' => 'blog.posts.index', 'position' => 1],//Submenu  
                   ['id' => 105, 'parent_id' => 103, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'blog.blog_category', 'route' => 'blog.categories.index', 'position' => 2],//Submenu  
                    // Contact Request
                   ['id' => 106, 'parent_id' => 102, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-user', 'name' => 'contactRequest.contact_request', 'route' => 'contact_request', 'position' => 2],// Menu
                   ['id' => 107, 'parent_id' => 106, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'contactRequest.contact_mail', 'route' => 'contactrequest.contact.index', 'position' => 1],//Submenu 
                    // Admin Reports
                   ['id' => 108, 'parent_id' => 102, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'ti-agenda', 'name' => 'report.admin_reports', 'route' => 'admin_report', 'position' => 3],// Menu
                   ['id' => 109, 'parent_id' => 108, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'report.Keywords search', 'route' => 'report.user_searches', 'position' => 1],//Submenu 
                   ['id' => 110, 'parent_id' => 108, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'report.visitor', 'route' => 'report.visitor_report', 'position' => 2],//Submenu 
                   ['id' => 111, 'parent_id' => 108, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'report.inhouse_product_sale', 'route' => 'report.inhouse_product_sale', 'position' => 3],//Submenu 
                   ['id' => 112, 'parent_id' => 108, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'report.product_stock', 'route' => 'report.product_stock', 'position' => 4],//Submenu 
                   ['id' => 113, 'parent_id' => 108, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'defaultTheme.wishlist', 'route' => 'report.wishlist', 'position' => 5],//Submenu 
                   ['id' => 114, 'parent_id' => 108, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'report.wallet_recharge_history', 'route' => 'report.wallet_recharge_history', 'position' => 6],//Submenu 
                   ['id' => 115, 'parent_id' => 108, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'dashboard.top_customers', 'route' => 'report.top_customer', 'position' => 7],//Submenu 
                   ['id' => 116, 'parent_id' => 108, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'report.top_selling_item', 'route' => 'report.top_selling_item', 'position' => 8],//Submenu 
                   ['id' => 117, 'parent_id' => 108, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'common.order', 'route' => 'report.order', 'position' => 9],//Submenu 
                   ['id' => 118, 'parent_id' => 108, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'common.payment', 'route' => 'report.payment', 'position' => 10],//Submenu 
                   ['id' => 119, 'parent_id' => 108, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'review.Product review', 'route' => 'report.product_review', 'position' => 11],//Submenu 
                   ['id' => 120, 'parent_id' => 108, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'review.company_review', 'route' => 'report.seller_review', 'position' => 12],//Submenu 
                    // Support Ticket
                   ['id' => 121, 'parent_id' => 102, 'is_admin' => 1,'is_seller' => 1, 'icon' =>'fas fa-wrench', 'name' => 'ticket.support_ticket', 'route' => 'support_tickets', 'position' => 4],// Menu
                   ['id' => 122, 'parent_id' => 121, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'ticket.support_ticket', 'route' => 'ticket.tickets.index', 'position' => 1],//Submenu 
                   ['id' => 123, 'parent_id' => 121, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'common.category', 'route' => 'ticket.category.index', 'position' => 2],//Submenu 
                   ['id' => 124, 'parent_id' => 121, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'ticket.priority', 'route' => 'ticket.priority.index', 'position' => 3],//Submenu 
                   ['id' => 125, 'parent_id' => 121, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'common.status', 'route' => 'ticket.status.index', 'position' => 4],//Submenu 
                   ['id' => 126, 'parent_id' => 121, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'ticket.my_tickets', 'route' => 'ticket.my_ticket', 'position' => 5],//Submenu 
                    // All Activity Logs
                    ['id' => 127, 'parent_id' => 102, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-clock-o', 'name' => 'common.all_activity_logs', 'route' => 'activity_logs', 'position' => 5],// Menu
                    ['id' => 128, 'parent_id' => 127, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'common.activity_logs', 'route' => 'activity_log', 'position' => 1],//Submenu 
                    ['id' => 129, 'parent_id' => 127, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'common.login_activity', 'route' => 'activity_log.login', 'position' => 2],//Submenu 
                    // visitor setup
                    ['id' => 130, 'parent_id' => 102, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-wrench', 'name' => 'common.visitors_setup', 'route' => 'visitors_setup', 'position' => 6],// Menu
                    ['id' => 131, 'parent_id' => 130, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'common.ignore_ip', 'route' => 'ignore_ip_list', 'position' => 1],//Submenu 
                    // System
                   ['id' => 132, 'parent_id' => null, 'is_admin' => 1,'is_seller' => 1, 'icon' =>null, 'name' => 'common.system', 'route' => null, 'position' => 9], //Section menu 
                   // System Settings
                   ['id' => 133, 'parent_id' => 132, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-cog', 'name' => 'general_settings.system_settings', 'route' => 'system_settings', 'position' => 1],// Menu
                   ['id' => 134, 'parent_id' => 133, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'general_settings.general_settings', 'route' => 'generalsetting.index', 'position' => 1],//Submenu  
                   ['id' => 135, 'parent_id' => 133, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'general_settings.Email Template', 'route' => 'email_templates.index', 'position' => 2],//Submenu  
                   ['id' => 136, 'parent_id' => 133, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'general_settings.sms_template', 'route' => 'sms_templates.index', 'position' => 3],//Submenu  
                   ['id' => 137, 'parent_id' => 133, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'general_settings.company_information', 'route' => 'generalsetting.company_index', 'position' => 4],//Submenu  
                   ['id' => 138, 'parent_id' => 133, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'general_settings.email_settings', 'route' => 'generalsetting.smtp_index', 'position' => 5],//Submenu  
                   ['id' => 139, 'parent_id' => 133, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'general_settings.sms_settings', 'route' => 'generalsetting.sms_index', 'position' => 6],//Submenu  
                   ['id' => 140, 'parent_id' => 133, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'setup.google_maps_api', 'route' => 'setup.maps.index', 'position' => 7],//Submenu  
                   ['id' => 141, 'parent_id' => 133, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'setup.google_recaptcha', 'route' => 'setup.recaptcha.index', 'position' => 8],//Submenu  
                   ['id' => 142, 'parent_id' => 133, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'setup.analytics', 'route' => 'setup.analytics.index', 'position' => 9],//Submenu  
                   ['id' => 143, 'parent_id' => 133, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'general_settings.activation', 'route' => 'generalsetting.activation_index', 'position' => 10],//Submenu  
                   ['id' => 144, 'parent_id' => 133, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'general_settings.Notification setting', 'route' => 'notificationsetting.index', 'position' => 11],//Submenu  
                   ['id' => 145, 'parent_id' => 133, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'general_settings.social_configuration', 'route' => 'generalsetting.social_login_configuration', 'position' => 12],//Submenu  
                   ['id' => 146, 'parent_id' => 133, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'general_settings.maintenance', 'route' => 'maintenance.index', 'position' => 13],//Submenu  
                   ['id' => 147, 'parent_id' => 133, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'general_settings.about_update', 'route' => 'generalsetting.updatesystem', 'position' => 14],//Submenu  
                   ['id' => 148, 'parent_id' => 133, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'general_settings.Module manager', 'route' => 'modulemanager.index', 'position' => 15],//Submenu  
                   ['id' => 149, 'parent_id' => 133, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'general_settings.homepage_seo_setup', 'route' => 'generalsetting.seo-setup', 'position' => 16],//Submenu  
                    // Sidebar Manage
                   ['id' => 150, 'parent_id' => 132, 'is_admin' => 1,'is_seller' => 1, 'icon' =>'fas fa-bars', 'name' => 'common.sidebar_manager', 'route' => 'sidebar-manager.index', 'position' => 2],// Menu
                    // Payment Gateways
                   ['id' => 151, 'parent_id' => 132, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'far fa-money-bill-alt', 'name' => 'general_settings.Payment Gateways', 'route' => 'payment_gateway.index', 'position' => 3],// Menu
                    // Setup
                   ['id' => 152, 'parent_id' => 132, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-wrench', 'name' => 'setup.setup', 'route' => 'setup_module', 'position' => 4],// Menu
                   ['id' => 153, 'parent_id' => 152, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'language.Language Settings', 'route' => 'languages.index', 'position' => 1],//Submenu 
                   ['id' => 154, 'parent_id' => 152, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'general_settings.currency_list', 'route' => 'currencies.index', 'position' => 2],//Submenu 
                   ['id' => 155, 'parent_id' => 152, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'common.tag', 'route' => 'tags.index', 'position' => 3],//Submenu 
                   ['id' => 156, 'parent_id' => 152, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'setup.location', 'route' => 'location_manage', 'position' => 4],//Submenu 
                   ['id' => 157, 'parent_id' => 156, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'common.country', 'route' => 'setup.country.index', 'position' => 1],//SubChildmenu 
                   ['id' => 158, 'parent_id' => 156, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'common.state', 'route' => 'setup.state.index', 'position' => 2],//SubChildmenu 
                   ['id' => 159, 'parent_id' => 156, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'common.city', 'route' => 'setup.city.index', 'position' => 3],//SubChildmenu 
                    // GST Setup
                    ['id' => 160, 'parent_id' => 132, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-wrench', 'name' => 'gst.gst_setup', 'route' => 'gst_setup', 'position' => 5],// Menu
                    ['id' => 161, 'parent_id' => 160, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'gst.gst_list', 'route' => 'gst_tax.index', 'position' => 1],//Submenu  
                    ['id' => 162, 'parent_id' => 160, 'is_admin' => 1,'is_seller' => 0, 'icon' =>null, 'name' => 'gst.GST setting', 'route' => 'gst_tax.configuration_index', 'position' => 2],//Submenu  
                    // Backup
                   ['id' => 163, 'parent_id' => 132, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'fas fa-file-download', 'name' => 'general_settings.backup', 'route' => 'backup.index', 'position' => 6],// Menu
                    // utilities
                   ['id' => 164, 'parent_id' => 132, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'ti-lock', 'name' => 'utilities.utilities', 'route' => 'utilities.index', 'position' => 7],// Menu

                   // file storage 
                   ['id' => 166, 'parent_id' => 132, 'is_admin' => 1,'is_seller' => 0, 'icon' =>'ti-file', 'name' => 'general_settings.file_storage', 'route' => 'file-storage.index', 'position' => 2],// Menu
                   
                   //last id 227  .....
            ];

        DB::table('backendmenus')->insert($sql);

        if(Schema::hasTable('permissions')){
            $total_order = Permission::where('route', 'order_manage.total_sales_get_data')->first();
            if($total_order){
                $total_order->update([
                    'route' => 'order_manage.total_sales_index'
                ]);
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
        Schema::dropIfExists('backendmenus');
    }
}
