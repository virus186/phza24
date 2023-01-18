@push('scripts')
    <script type="text/javascript">

        (function($){
            "use strict";

            $(document).ready(function() {

                $(document).on('click', ".addToCartFromThumnail", function() {
                    event.preventDefault();
                    var className = this.className;
                    $("."+className).prop("disabled", true);
                    if ($(this).data('producttype') == 1) {
                        let prod_info = $(this).data('prod_info');
                        let is_stock_manage = $(this).data('stock_manage');
                        let stock = $(this).data('stock');
                        let min_qty = $(this).data('min_qty');

                        if(is_stock_manage == 1 && stock > min_qty){
                            addToCart($(this).attr('data-product-sku'),$(this).attr('data-seller'),min_qty,$(this).attr('data-base-price'),0,'product', prod_info)
                            $("."+className).prop("disabled", false);

                        }else if(is_stock_manage == 0){
                            addToCart($(this).attr('data-product-sku'),$(this).attr('data-seller'),min_qty,$(this).attr('data-base-price'),0,'product',prod_info)
                            $("."+className).prop("disabled", false);
                        }else{
                            toastr.warning("{{__('defaultTheme.out_of_stock')}}");
                            $("."+className).prop("disabled", false);
                        }

                    }
                    else {
                        $('#pre-loader').show();
                        $.post('{{ route('frontend.item.show_in_modal') }}', {_token:'{{ csrf_token() }}', product_id:$(this).attr('data-product-id')}, function(data){
                            $(".add-product-to-cart-using-modal").html(data);
                            $("#theme_modal").modal('show');
                            $('.nc_select, .select_address, #product_short_list, #paginate_by').niceSelect();
                            $("."+className).prop("disabled", false);
                            $('#pre-loader').hide();
                        });
                    }
                });

                $(document).on('click', '.qtyChangePlus', function(){
                    qtyChange(this.value);
                    $('.nc_select, .select_address, #product_short_list, #paginate_by').niceSelect('update');
                });

                $(document).on('click', '.qtyChangeMinus', function(){
                    qtyChange(this.value);
                    $('.nc_select, .select_address, #product_short_list, #paginate_by').niceSelect('update');
                });

                $(document).on('click', '.add_to_wishlist_modal', function(event){
                    event.preventDefault();
                    let product_id = $(this).data('product_id');
                    let seller_id = $(this).data('seller_id');
                    let is_login = $('#login_check').val();
                    let type = 'product';
                    if(is_login == 1){
                        addToWishlist(product_id,seller_id, type);
                        $("#theme_modal").modal('hide');
                    }else{
                        toastr.warning("{{__('defaultTheme.please_login_first')}}","{{__('common.warning')}}");
                    }
                });

                $(document).on('click', '#add_to_cart_btn_modal', function(event){
                    event.preventDefault();
                    var showData = {
                        'name' : $('#product_name_modal').val(),
                        'url' : $('#product_url_modal').val(),
                        'price' : currency_format($('#final_price_modal').val()),
                        'thumbnail' : $('#thumb_image_modal').val()
                    };
                    addToCart($('#product_sku_id_modal').val(),$('#seller_id_modal').val(),$('#qty_modal').data('value'),$('#base_sku_price_modal').val(),$('#shipping_type').val(),'product', showData);
                });

                $(document).on("click", ".buy_now_btn_modal", function(event){
                    event.preventDefault();
                    buyNow($('#product_sku_id_modal').val(),$('#seller_id_modal').val(),$('#qty_modal').data('value'),$('#base_sku_price_modal').val(),$('#shipping_type').val(),'product',$('#owner_modal').val());
                });

                $(document).on('click', '.attr_val_name', function(){

                    $(this).parent().parent().find('.attr_value_name').val($(this).attr('data-value')+'-'+$(this).attr('data-value-key'));
                    $(this).parent().parent().find('.attr_value_id').val($(this).attr('data-value')+'-'+$(this).attr('data-value-key'));

                    if ($(this).attr('color') == "color") {
                        $('.attr_clr').removeClass('selected_btn');
                    }
                    if ($(this).attr('color') == "not") {
                        $('.not_111').removeClass('selected_btn');
                    }
                    $(this).addClass('selected_btn');
                    get_price_accordint_to_sku();

                });

                function qtyChange(val){
                    $('.cart-qty-minus-modal').prop('disabled',false);
                    let available_stock = $('#availability_modal').html();
                    let stock_manage_status = $('#stock_manage_status_modal').val();
                    let maximum_order_qty = $('#maximum_order_qty_modal').val();
                    let minimum_order_qty = $('#minimum_order_qty_modal').val();
                    let qty = $('#qty_modal').data('value');
                    if (stock_manage_status != 0) {

                        if(val == '+'){
                            if (parseInt(qty) < parseInt(available_stock)) {
                                if(maximum_order_qty != ''){
                                    if(parseInt(qty) < parseInt(maximum_order_qty)){
                                    let qty1 = parseInt(++qty);
                                    $('#qty_modal').val(numbertrans(qty1))
                                    $('#qty_modal').data('value',qty1)
                                    totalValue(qty1, '#base_price_modal','#total_price_modal');
                                    }else{
                                        toastr.warning('{{__("defaultTheme.maximum_quantity_limit_is")}}'+maximum_order_qty+'.', '{{__("common.warning")}}');
                                    }
                                }else{
                                    let qty1 = parseInt(++qty);
                                    $('#qty_modal').val(numbertrans(qty1))
                                    $('#qty_modal').data('value',qty1)
                                    totalValue(qty1, '#base_price_modal','#total_price_modal');
                                }
                            }else{
                                toastr.error("{{__('defaultTheme.no_more_stock')}}", "{{__('common.error')}}");
                            }
                        }
                        if(val == '-'){
                            if (parseInt(qty) <= parseInt(available_stock)) {
                                if(minimum_order_qty != ''){
                                    if(parseInt(qty) > parseInt(minimum_order_qty)){
                                        if(qty>1){
                                            let qty1 = parseInt(--qty)
                                            $('#qty_modal').val(numbertrans(qty1))
                                            $('#qty_modal').data('value',qty1)
                                            totalValue(qty1, '#base_price_modal','#total_price_modal')
                                            $('.cart-qty-minus-modal').prop('disabled',false);
                                        }else{
                                            $('.cart-qty-minus-modal').prop('disabled',true);
                                        }
                                    }else{
                                        toastr.warning('{{__("defaultTheme.minimum_quantity_Limit_is")}}'+minimum_order_qty+'.', '{{__("common.warning")}}')
                                    }
                                }else{
                                    if(parseInt(qty)>1){
                                        let qty1 = parseInt(--qty)
                                        $('#qty_modal').val(numbertrans(qty1))
                                        $('#qty_modal').data('value',qty1)
                                        totalValue(qty1, '#base_price_modal','#total_price_modal')
                                        $('.cart-qty-minus-modal').prop('disabled',false);
                                    }else{
                                        $('.cart-qty-minus-modal').prop('disabled',true);
                                    }
                                }
                            }else{
                                toastr.error("{{__('defaultTheme.no_more_stock')}}", "{{__('common.error')}}");
                            }
                        }

                    }
                    else {
                        if(val == '+'){
                            if(maximum_order_qty != ''){
                                if(parseInt(qty) < parseInt(maximum_order_qty)){
                                    let qty1 = parseInt(++qty);
                                    $('#qty_modal').val(numbertrans(qty1))
                                    $('#qty_modal').data('value',qty1)
                                    totalValue(qty1, '#base_price_modal','#total_price_modal');
                                }else{
                                    toastr.warning('{{__("defaultTheme.maximum_quantity_limit_is")}}'+maximum_order_qty+'.', '{{__("common.warning")}}')
                                }
                            }else{
                                let qty1 = parseInt(++qty);
                                $('#qty_modal').val(numbertrans(qty1))
                                $('#qty_modal').data('value',qty1)
                                totalValue(qty1, '#base_price_modal','#total_price_modal');
                            }


                        }
                        if(val == '-'){
                            if(minimum_order_qty != ''){
                                if(parseInt(qty) > parseInt(minimum_order_qty)){
                                    if(qty>1){
                                        let qty1 = parseInt(--qty)
                                        $('#qty_modal').val(numbertrans(qty1))
                                        $('#qty_modal').data('value',qty1)
                                        totalValue(qty1, '#base_price_modal','#total_price_modal')
                                        $('.cart-qty-minus-modal').prop('disabled',false);
                                    }else{
                                        $('.cart-qty-minus-modal').prop('disabled',true);
                                    }
                                }else{
                                    toastr.warning('{{__("defaultTheme.minimum_quantity_Limit_is")}}'+minimum_order_qty+'.', '{{__("common.warning")}}')
                                }
                            }else{
                                if(parseInt(qty)>1){
                                    let qty1 = parseInt(--qty)
                                    $('#qty_modal').val(numbertrans(qty1))
                                    $('#qty_modal').data('value',qty1)
                                    totalValue(qty1, '#base_price_modal','#total_price_modal')
                                    $('.cart-qty-minus-modal').prop('disabled',false);
                                }else{
                                    $('.cart-qty-minus-modal').prop('disabled',true);
                                }
                            }
                        }
                    }
                }

                function totalValue(qty, main_price, total_price){
                    if($('#isWholeSaleActiveModal').val() == 1){
                        let discount_type = $('#discount_type_modal').val();
                        let discount = $('#discount_modal').val();
                        var getWholesalePrice = '';
                        if($('#isWholeSaleActiveModal').val() == 1 && $('#getWholesalePriceModal').val() != 0){
                            getWholesalePrice = $('#getWholesalePriceModal').val();
                        }
                        if(getWholesalePrice.length){
                            getWholesalePrice = JSON.parse(getWholesalePrice);
                            var max_qty='',min_qty='',selling_price='';
                            for (let i = 0; i < getWholesalePrice.length; ++i) {
                                max_qty = getWholesalePrice[i].max_qty;
                                min_qty = getWholesalePrice[i].min_qty;
                                selling_price = getWholesalePrice[i].selling_price;

                                if ( (min_qty<=qty) && (max_qty>=qty) ){
                                    main_price = selling_price;
                                }
                                else if(max_qty < qty){
                                    main_price = selling_price;
                                }
                                else if(main_price=='#base_price_modal'){
                                    var main_price = $('#base_sku_price_modal').val();
                                }
                            }
                            if (discount_type == 0) {
                                discount = (main_price * discount) / 100;
                            }
                            var base_sku_price = (main_price - discount);
                        }else{
                            var base_sku_price = $('#base_sku_price_modal').val();
                        }
                    }else{
                        var base_sku_price = $('#base_sku_price_modal').val();
                    }
                    let value = parseInt(qty) * parseFloat(base_sku_price);
                    $(total_price).html(currency_format(value));
                    $('#final_price_modal').val(value);
                }

                var old_html = $("#myTabContent").html();
                $('.var_img_source').hover(function() {
                var logo = $(this).attr("src"); alert(logo)// get logo from data-icon parameter
                $('.var_img_show').attr("src", logo); // change logo
                }, function() {
                $("#myTabContent").html(old_html); // remove logo
                });


                function get_price_accordint_to_sku(){
                    var value = $("input[name='attr_val_name_modal[]']").map(function(){return $(this).val();}).get();
                    var id = $("input[name='attr_val_id_modal[]']").map(function(){return $(this).val();}).get();
                    var product_id = $("#product_id_modal").val();
                    var user_id = $('#seller_id_modal').val();
                    $('#pre-loader').show();
                    $.post('{{ route('seller.get_seller_product_sku_wise_price') }}', {_token:'{{ csrf_token() }}', id:id, product_id:product_id, user_id:user_id}, function(data){
                        if (data != 0) {
                            let discount_type = $('#discount_type_modal').val();
                            let discount = $('#discount_modal').val();
                            let qty = $('#qty_modal').data('value');
                            var getWholesalePrice = null;
                            if(data.data.whole_sale_prices.length){
                                var getWholesalePrice = data.data.whole_sale_prices;
                                $('#getWholesalePriceModal').val(JSON.stringify(getWholesalePrice));
                            }
                            calculatePrice(data.data.selling_price, discount, discount_type, qty, getWholesalePrice);
                            $('#sku_id_li_modal').text(data.data.sku.sku);
                            $('#product_sku_id_modal').val(data.data.id);

                            $('#availability_modal').html(data.data.product_stock);

                            if(parseInt(data.data.product_stock) >= parseInt(data.data.product.product.minimum_order_qty)){
                                $('#add_to_cart_div_modal').html(`
                                    <div class="col-md-6">
                                        <button type="button" id="add_to_cart_btn_modal" class="home10_primary_btn2 mb_20 w-100 text-center add_to_cart text-uppercase flex-fill text-center">{{__('defaultTheme.add_to_cart')}}</button>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="#" class="home10_primary_btn4  w-100 radius_5px mb_20 w-100 text-center justify-content-center text-uppercase buy_now_btn_modal" data-id="${data.data.product.id}" data-type="product">Buy now</a>
                                    </div>
                                `);
                            }
                            else if(data.data.product.stock_manage == 0){
                                $('#add_to_cart_div_modal').html(`
                                    <div class="col-md-6">
                                        <button type="button" id="add_to_cart_btn_modal" class="home10_primary_btn2 mb_20 w-100 text-center add_to_cart text-uppercase flex-fill text-center">{{__('defaultTheme.add_to_cart')}}</button>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="#" class="home10_primary_btn4  w-100 radius_5px mb_20 w-100 text-center justify-content-center text-uppercase buy_now_btn_modal" data-id="${data.data.product.id}" data-type="product">Buy now</a>
                                    </div>
                                `);
                            }
                            else{
                                $('#add_to_cart_div_modal').html(`
                                    <div class="col-md-6">
                                        <button type="button" disabled class="home10_primary_btn2 mb_20 w-100 text-center add_to_cart text-uppercase flex-fill text-center">{{__('defaultTheme.out_of_stock')}}</button>
                                    </div>
                                `);
                                toastr.warning("{{__('defaultTheme.out_of_stock')}}");
                            }
                        }else {
                            toastr.error("{{__('defaultTheme.no_stock_found_for_this_seller')}}", "{{__('common.error')}}");
                        }
                        $('#pre-loader').hide();
                    });
                }
                function calculatePrice(main_price, discount, discount_type, qty, getWholesalePrice){
                    var main_price = main_price;
                    var discount = discount;
                    var discount_type = discount_type;
                    var total_price = 0;
                    if($('#isWholeSaleActiveModal').val() == 1 && getWholesalePrice != null){
                        var max_qty='',min_qty='',selling_price='';
                        for (let i = 0; i < getWholesalePrice.length; ++i) {
                            max_qty = getWholesalePrice[i].max_qty;
                            min_qty = getWholesalePrice[i].min_qty;
                            selling_price = getWholesalePrice[i].selling_price;

                            if ( (min_qty<=qty) && (max_qty>=qty) ){
                                main_price = selling_price;
                            }
                        }
                    }

                    if (discount_type == 0) {
                        discount = (main_price * discount) / 100;
                    }
                    total_price = (main_price - discount);
                    $('#total_price_modal').html(currency_format((total_price * qty)));
                    $('#base_sku_price_modal').val(total_price);
                    $('#final_price_modal').val(total_price);
                }

            });
        })(jQuery);

    </script>
@endpush
