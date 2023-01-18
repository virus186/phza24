(function ($) {
    "use strict";

    $(window).on("load", function () {
        setTimeout(function () {
            $(".newsletter_form_wrapper").addClass("newsletter_active").fadeIn();
        }, 1500);
    });

    //* Navbar Fixed
    var nav_offset_top = $("header").height() + 50;
    /*-------------------------------------------------------------------------------
	  Navbar 
	-------------------------------------------------------------------------------*/
    $(window).on("scroll", function () {
        var scroll = $(window).scrollTop();
        if (scroll < 400) {
            $("#sticky-header").removeClass("navbar_fixed");
            $("#back-top").fadeOut(500);
        } else {
            $("#sticky-header").addClass("navbar_fixed");
            $("#back-top").fadeIn(500);
        }
    });
    // search home6


    // back to top
    $("#back-top a").on("click", function () {
        $("body,html").animate({
                scrollTop: 0,
            },
            1000
        );
        return false;
    });

    // #######################
    //   MOBILE MENU
    // #######################

    var menu = $("ul#mobile-menu");
    if (menu.length) {
        menu.slicknav({
            prependTo: ".mobile_menu",
            closedSymbol: '<i class="ti-angle-down"></i>',
            openedSymbol: '<i class="ti-angle-up"></i>',
        });
    }
    $(document).ready(function () {
        if ($("#scrolling_nav").length > 0) {
            $("#scrolling_nav").onePageNav();
        }
    });
    // .scrollbar activation
    if ($(".full_with_menu").length > 0) {
        const ps = new PerfectScrollbar(".full_with_menu", {
            wheelSpeed: 0.5,
            wheelPropagation: 0,
            minScrollbarLength: 10,
        });
    }

    //active sidebar
    $(".sidebar_icon").on("click", function () {
        $(".sidebar").toggleClass("active_sidebar");
    });

    $(".sidebar_close_icon i").on("click", function () {
        $(".sidebar").removeClass("active_sidebar");
    });

    //remove sidebar
    $(document).click(function (event) {
        if (!$(event.target).closest(".sidebar_icon, .sidebar").length) {
            $("body").find(".sidebar").removeClass("active_sidebar");
        }
    });

    // home 5 search
    $(".search_btn").on("click", function () {
        $(this).parent().toggleClass("active");
        $("i", this).toggleClass("ti-search ti-close");
    });

    $(document).click(function (event) {
        if (!$(event.target).closest(".search_btn, .search_field").length) {
            $("body").find(".search_field").removeClass("active");
            $("body").find(".search_btn i").toggleClass("ti-close ti-search ");
        }
    });

    //notification
    $(".notification_open > a").on("click", function () {
        $(".notification_area").toggleClass("active");
    });
    //remove sidebar
    $(document).click(function (event) {
        if (
            !$(event.target).closest(".notification_area,.notification_open > a")
            .length
        ) {
            $("body").find(".notification_area").removeClass("active");
        }
    });
    //active courses option
    $(".courses_option, .collaps_icon").on("click", function () {
        $(this).parent(".custom_select, .collaps_part").toggleClass("active");
    });
    $(document).click(function (event) {
        if (!$(event.target).closest(".custom_select").length) {
            $("body").find(".custom_select").removeClass("active");
        }
        if (!$(event.target).closest(".collaps_part").length) {
            $("body").find(".collaps_part").removeClass("active");
        }
    });

    // elemts sidebar
    $(".elemnts_sidebar_toogler").on("click", function () {
        $(".elemts_sidebar").toggleClass("active");
    });

    //remove sidebar
    $(document).click(function (event) {
        if (
            !$(event.target).closest(".elemnts_sidebar_toogler, .elemts_sidebar")
            .length
        ) {
            $("body").find(".elemts_sidebar").removeClass("active");
        }
    });

    
    // for MENU POPUP
    
    $(document).ready(function () {
        $(document).on("click", ".chart_close", function () {
            $(".shoping_cart ,.dark_overlay").removeClass("active");
        });

        $(document).on("click",".side_chartView_total", function () {
            $(".shoping_cart ,.dark_overlay").toggleClass("active");
        });
        // sidebar menu open
        $(document).on("click", ".sidebar_menu_toggle", function () {
            $(".sidebar_menu_wrapper").toggleClass("sidebar_open");
        });

        // catgory toggle
        $(document).on("click", ".category_toggler", function () {
            $("#product_category_chose").toggleClass("active");
        });
        $(document).on("click", "#catgory_sidebar_closeIcon", function () {
            $("#product_category_chose").removeClass("active");
        });
    });


    $(document).click(function (event) {
        if (
            !$(event.target).closest(".sidebar_menu_toggle ,.sidebar_menu_wrapper")
            .length
        ) {
            $("body").find(".sidebar_menu_wrapper").removeClass("sidebar_open");
        }
    });
    
    $(document).click(function (event) {
        if (
            !$(event.target).closest(".category_toggler ,#product_category_chose")
            .length
        ) {
            $("body").find("#product_category_chose").removeClass("active");
        }
    });
    // hideporobar
    $(".close_promobar").on("click", function () {
        $(this).closest(".promotion_bar").remove();
    });
    // language
    $(".language_toggle_btn").on("click", function () {
        $(this).siblings(".language_toggle_box").toggleClass("active");
    });

    $(document).click(function (event) {
        if (
            !$(event.target).closest(".language_toggle_btn ,.language_toggle_box")
            .length
        ) {
            $("body").find(".language_toggle_box").removeClass("active");
        }
    });
    // summernote
    $(document).ready(function () {
        if ($("#summernote").length > 0) {
            $("#summernote").summernote({
                placeholder: "Hello stand alone ui",
                tabsize: 2,
                height: 150,
                toolbar: [
                    ["style", ["style"]],
                    ["font", ["bold", "underline", "clear"]],
                    ["color", ["color"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["table", ["table"]],
                    ["insert", ["link", "picture", "video"]],
                    ["view", ["fullscreen", "codeview", "help"]],
                ],
            });
        }
    });
    // select
    if ($(".small_select").length > 0) {
        $(".small_select").niceSelect();
    }
    if ($(".theme_select").length > 0) {
        $(".theme_select").niceSelect();
    }
    if ($(".amaz_select").length > 0) {
        $(".amaz_select").niceSelect();
    }
    if ($(".amaz_select2").length > 0) {
        $(".amaz_select2").niceSelect();
    }
    if ($(".amaz_select3").length > 0) {
        $(".amaz_select3").niceSelect();
    }
    if ($(".amaz_select4").length > 0) {
        $(".amaz_select4").niceSelect();
    }
    if ($(".amaz_select5").length > 0) {
        $(".amaz_select5").niceSelect();
    }
    if ($(".amaz_select6").length > 0) {
        $(".amaz_select6").niceSelect();
    }
    if ($(".home10_select2").length > 0) {
        $(".home10_select2").niceSelect();
    }

    $(".product_size li").on("click", function (event) {
        $(this).siblings(".active").removeClass("active");
        $(this).addClass("active");
        event.preventDefault();
    });
    // BARFILLER
    $(document).ready(function () {
        var proBar = $("#bar1");
        if (proBar.length) {
            proBar.barfiller({
                barColor: "#fb6600",
                duration: 2000
            });
        }
        var proBar = $("#bar2");
        if (proBar.length) {
            proBar.barfiller({
                barColor: "#fb6600",
                duration: 2100
            });
        }
        var proBar = $("#bar3");
        if (proBar.length) {
            proBar.barfiller({
                barColor: "#fb6600",
                duration: 2200
            });
        }
    });

    // #######################
    //  carousel_active
    // #######################
    let dir = $('html').attr('dir');
    let dir_val  = false;
    if(dir == 'rtl'){
        dir_val = true;
    }
    if (".carousel_active".length > 0) {
        $(".carousel_active").owlCarousel({
            loop: true,
            margin: 30,
            items: 1,
            autoplay: true,
            navText: [
                '<i class="fa fa-angle-left"></i>',
                '<i class="fa fa-angle-right"></i>',
            ],
            nav: false,
            dots: false,
            rtl: dir_val,
            autoplayHoverPause: true,
            autoplaySpeed: 800,
            responsive: {
                0: {
                    items: 2,
                },
                767: {
                    items: 4,
                },
                992: {
                    items: 5,
                },
                1400: {
                    items: 6,
                },
            },
        });
    }

    if (".product_slide".length > 0) {
        $(".product_slide").owlCarousel({
            loop: true,
            margin: -1,
            items: 1,
            autoplay: true,
            navText: [
                '<i class="fa fa-angle-left"></i>',
                '<i class="fa fa-angle-right"></i>',
            ],
            nav: true,
            dots: false,
            rtl: dir_val,
            autoplayHoverPause: true,
            autoplaySpeed: 800,
            responsive: {
                0: {
                    items: 1,
                    nav: false,
                },
                767: {
                    items: 2,
                },
                992: {
                    items: 3,
                },
                1400: {
                    items: 4,
                },
            },
        });
    }
    if (".product_slide2".length > 0) {
        $(".product_slide2").owlCarousel({
            loop: true,
            margin: -1,
            items: 1,
            autoplay: true,
            navText: [
                '<i class="fa fa-angle-left"></i>',
                '<i class="fa fa-angle-right"></i>',
            ],
            nav: false,
            dots: false,
            rtl: dir_val,
            autoplayHoverPause: true,
            autoplaySpeed: 800,
            responsive: {
                0: {
                    items: 1,
                    nav: false,
                },
                767: {
                    items: 2,
                },
                992: {
                    items: 3,
                },
                1400: {
                    items: 4,
                },
            },
        });
    }
    if (".brand_active".length > 0) {
        $(".brand_active").owlCarousel({
            loop: true,
            margin: -1,
            items: 1,
            autoplay: true,
            navText: [
                '<i class="fa fa-angle-left"></i>',
                '<i class="fa fa-angle-right"></i>',
            ],
            nav: false,
            dots: false,
            rtl: true,
            autoplayHoverPause: true,
            autoplaySpeed: 800,
            responsive: {
                0: {
                    items: 1,
                },
                767: {
                    items: 3,
                },
                992: {
                    items: 4,
                },
                1400: {
                    items: 5,
                },
            },
        });
    }


    /* -------------------------------------------------------------------------- */
    /*                               AmazcartUi                              */
    /* -------------------------------------------------------------------------- */
    if (".bannerUi_active".length > 0) {
        $(".bannerUi_active").owlCarousel({
            loop: true,
            margin: 0,
            items: 1,
            autoplay: true,
            navText: [
                '<i class="ti-angle-left"></i>',
                '<i class="ti-angle-right"></i>',
            ],
            nav: false,
            rtl: dir_val,
            autoplayHoverPause: true,
            autoplaySpeed: 800,
            responsive: {
                0: {
                    items: 1,
                    nav: false,
                    dots:false
                },
                1200: {
                    items: 1,
                    dots: true,
                },
            },
        });
    }
    if (".bannerUi_Recommendation_active".length > 0) {
        $(".bannerUi_Recommendation_active").owlCarousel({
            loop: true,
            margin: -1,
            items: 1,
            autoplay: true,
            navText: [
                '<i class="ti-angle-left"></i>',
                '<i class="ti-angle-right"></i>',
            ],
            nav: true,
            dots: false,
            rtl: dir_val,
            autoplayHoverPause: true,
            autoplaySpeed: 800,
            responsive: {
                0: {
                    items: 2,
                    nav: false,
                },
                768: {
                    items: 2,
                },
                992: {
                    items: 3,
                },
                1200: {
                    items: 4,
                },
                1640: {
                    items: 5,
                },
            },
        });
    }
    if (".amaz_fieature_active".length > 0) {
        $(".amaz_fieature_active").owlCarousel({
            loop: true,
            margin: -1,
            items: 1,
            autoplay: true,
            stagePadding: 1,
            navText: [
                '<i class="ti-angle-left"></i>',
                '<i class="ti-angle-right"></i>',
            ],
            nav: true,
            dots: false,
            rtl: dir_val,
            autoplayHoverPause: true,
            autoplaySpeed: 800,
            responsive: {
                0: {
                    items: 1,
                },
                390: {
                    items: 2,
                },
                992: {
                    items: 3,
                },
                1200: {
                    items: 4,
                },
                1640: {
                    items: 5,
                },
            },
        });
    }
    
    if (".trending_product_active".length > 0) {
        $(".trending_product_active").owlCarousel({
          loop: true,
          margin: 30,
          items: 1,
          autoplay: true,
          navText: [
            '<i class="ti-angle-left"></i>',
            '<i class="ti-angle-right"></i>',
          ],
          nav: false,
          dots: false,
          rtl: dir_val,
          autoplayHoverPause: true,
          autoplaySpeed: 800,
          responsive: {
            0: {
              items: 1,
              nav: false,
            },
            768: {
              items: 2,
            },
            992: {
              items: 3,
            },
            1200: {
              items: 4,
            },
          },
        });
      }
    // counter
    $(".counter").counterUp({
        delay: 10,
        time: 10000,
    });

    /* magnificPopup img view */
    $(".popup-image").magnificPopup({
        type: "image",
        gallery: {
            enabled: true,
        },
    });

    /* magnificPopup video view */
    $(".popup-video").magnificPopup({
        type: "iframe",
        mainClass: "mfp-fade",
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false,
    });

    $("#container").imagesLoaded(function () {
        // for filter
        // init Isotope
        var $grid = $(".grid_active").isotope({
            itemSelector: ".grid-item",
            percentPosition: true,
            masonry: {
                // use outer width of grid-sizer for columnWidth
                columnWidth: 1,
                gutter: 0,
            },
        });

        // filter items on button click
        $(".portfolio-menu").on("click", "button", function () {
            var filterValue = $(this).attr("data-filter");
            $grid.isotope({
                filter: filterValue
            });
        });

        //for menu active class
        $(".portfolio-menu button").on("click", function (event) {
            $(this).siblings(".active").removeClass("active");
            $(this).addClass("active");
            event.preventDefault();
        });
        //for wallet_payent_box active class
        $(".wallet_payent_box button").on("click", function (event) {
            $('.wallet_elemnt').removeClass("active");
            $(this).addClass("active");
        });
    });
    /*=============================================== 
          Parallax business_image
    ================================================*/
    if ($(".man_img").length > 0) {
        $(".man_img").parallax({
            scalarX: 7.0,
            scalarY: 7.0,
        });
    }

    $(".btnNext").click(function () {
        $(".nav-pills .active").parent().next("li").find("a").trigger("click");
    });

    $(".btnPrevious").click(function () {
        $(".nav-pills .active").parent().prev("li").find("a").trigger("click");
    });


    if ($("#count_down").length > 0) {
        $("#count_down").countdown("2021/3/10", function (event) {
            $(this).html(
                event.strftime(
                    '<div class="single_count"><span>%D</span></div><span class="count_separator">:</span><div class="single_count"><span>%H</span></div><span class="count_separator">:</span><div class="single_count"><span>%M</span></div><span class="count_separator">:</span><div class="single_count"><span>%S</span></div>'
                )
            );
        });
    }
    if ($("#count_down2").length > 0) {
        $("#count_down2").countdown("2021/3/10", function (event) {
            $(this).html(
                event.strftime(
                    '<div class="single_count"><span>%D</span><p>Days</p></div><div class="single_count"><span>%H</span><p>Hours</p></div><div class="single_count"><span>%M</span><p>Minute</p></div><div class="single_count"><span>%S</span><p>Second</p></div>'
                )
            );
        });
    }
    if ($("#count_small").length > 0) {
        $("#count_small").countdown("2021/3/10", function (event) {
            $(this).html(
                event.strftime(
                    '<div class="single_count"><span>%D</span><p>Days</p></div><div class="single_count"><span>%H</span><p>Hrs</p></div><div class="single_count"><span>%M</span><p>Mins</p></div><div class="single_count"><span>%S</span><p>Secs</p></div>'
                )
            );
        });
    }
    if ($("#week_countdown").length > 0) {
        $("#week_countdown").countdown("2022/3/10", function (event) {
            $(this).html(
                event.strftime(
                    '<div class="single_count"><span>%w</span><p>Weeks</p></div><div class="single_count"><span>%D</span><p>Days</p></div><div class="single_count"><span>%H</span><p>Hrs</p></div><div class="single_count"><span>%M</span><p>Mins</p></div><div class="single_count"><span>%S</span><p>Secs</p></div>'
                )
            );
        });
    }
    if ($("#week_countdown2").length > 0) {
        $("#week_countdown2").countdown("2022/10/10", function (event) {
            $(this).html(
                event.strftime(
                    '<div class="single_count"><span>%w</span><p>Weeks</p></div><div class="single_count"><span>%D</span><p>Days</p></div><div class="single_count"><span>%H</span><p>Hrs</p></div><div class="single_count"><span>%M</span><p>Mins</p></div><div class="single_count"><span>%S</span><p>Secs</p></div>'
                )
            );
        });
    }

    $(document).ready(function () {
        $("#start_datepicker").datepicker();
        $("#end_datepicker").datepicker();
        $("#start_datepicker2").datepicker();
        $("#end_datepicker2").datepicker();
    });

    $(".add_collaspe_btn").on("click", function () {
        $(this).hide();
        $(this)
            .closest(".single_apply_wrapper")
            .find(".collaspe_form")
            .slideDown(200);
    });
    $(".hide_collape_form").on("click", function () {
        $(this)
            .closest(".single_apply_wrapper")
            .find(".collaspe_form")
            .slideUp(500);
        $(this).closest(".single_apply_wrapper").find(".add_collaspe_btn").show();
    });

    // PRODUCT DETAILS CROUSEL
    $(function () {
        // Card's slider
        var $carousel = $(".slider-for");

        $carousel.slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            fade: true,
            asNavFor: ".slider-nav",
            centerMode: true,
            pauseOnHover: true,
            useTransform: false,
            autoplay: false,
            infinite: true,
            prevArrow: "<button type='button' class='slick-prev pull-left slick_only_mobile'><i class='fa fa-angle-left' aria-hidden='true'></i></button>",
            nextArrow: "<button type='button' class='slick-next pull-right slick_only_mobile'><i class='fa fa-angle-right' aria-hidden='true'></i></button>"
        });

        $(".slider-nav").slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            asNavFor: ".slider-for",
            dots: false,
            centerPadding: "0px",
            centerMode: true,
            useTransform: false,
            autoplay: false,
            infinite: true,
            focusOnSelect: true,
            prevArrow: "<button type='button' class='slick-prev pull-left'><i class='fa fa-angle-left' aria-hidden='true'></i></button>",
            nextArrow: "<button type='button' class='slick-next pull-right'><i class='fa fa-angle-right' aria-hidden='true'></i></button>",
        });
    });

    $(".close_modal").on("click", function () {
        $(".newsletter_form_wrapper").removeClass("newsletter_active");
    });

    $(document).click(function (event) {
        if (!$(event.target).closest(".newsletter_form_inner").length) {
            $("body")
                .find(".newsletter_form_wrapper")
                .removeClass("newsletter_active");
        }
    });

    $(".Categories_togler").on("click", function () {
        $(".catdropdown_menu").toggleClass("dropdown_menu_active");
    });
    $(document).click(function (event) {
        if (
            !$(event.target).closest(".Categories_togler, .catdropdown_menu").length
        ) {
            $("body").find(".catdropdown_menu").removeClass("dropdown_menu_active");
        }
    });

    // search home6
    $(".home6_search_toggle").on("click", function () {
        $(".menu_search_popup").toggleClass("active");
    });
    $(".home6_search_hide").on("click", function () {
        $(".menu_search_popup").removeClass("active");
    });
    $(document).click(function (event) {
        if (
            !$(event.target).closest(".home6_search_toggle, .menu_search_popup")
            .length
        ) {
            $("body").find(".menu_search_popup").removeClass("active");
        }
    });

    // FOR SIGNUP MODAL PROBLEM
    $(document).on("show.bs.modal", function (event) {
        if (!event.relatedTarget) {
            $(".modal").not(event.target).modal("hide");
        }
        if ($(event.relatedTarget).parents(".modal").length > 0) {
            $(event.relatedTarget).parents(".modal").modal("hide");
        }
    });

    $(document).on("shown.bs.modal", function (event) {
        if ($("body").hasClass("modal-open") == false) {
            $("body").addClass("modal-open");
        }
    });
    

    // active dashboard menu
    jQuery(function ($) {
        var path = window.location.href;
        $(".dashboard_sidebar_menuList a").each(function () {
            if (this.href === path) {
                $(this).addClass("active");
            }
        });
    });
    $(document).ready(function () {
        $("#date_dynamic").html(new Date().getFullYear());
    });

})(jQuery);

    function config(key, default_value){
        let value = _.get(_config, key)
    
        if(typeof value == 'undefined'){
            return default_value;
        }
        return value;
    }
  
    function user_currency(key, default_value){
        let value = _.get(_user_currency, key)
    
        if(typeof value == 'undefined'){
            return default_value;
        }
        return value;
    }
  
    function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
        try {
            decimalCount = Math.abs(decimalCount);
            decimalCount = isNaN(decimalCount) ? 2 : decimalCount;
    
            const negativeSign = amount < 0 ? "-" : "";
    
            let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
            let j = (i.length > 3) ? i.length % 3 : 0;
    
            let number = negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
            return numbertrans(number);
        } catch (e) {
            console.log(e)
        }
    }
  
    window.currency_format = function(amount){
        if(_user_currency.length !== 0){
        if(config('currency_symbol_position') === 'left'){
            return user_currency('symbol')+formatMoney(parseFloat(amount) * parseFloat(user_currency('convert_rate')),config('decimal_limit'));
        }
        else if(config('currency_symbol_position') === 'left_with_space'){
            return user_currency('symbol')+ " " +formatMoney(parseFloat(amount) * parseFloat(user_currency('convert_rate')),config('decimal_limit'));
        }
        else if(config('currency_symbol_position') === 'right'){
            return formatMoney(parseFloat(amount) * parseFloat(user_currency('convert_rate')),config('decimal_limit')) + user_currency('symbol');
        }
        else if(config('currency_symbol_position') === 'right_with_space'){
            return formatMoney(parseFloat(amount) * parseFloat(user_currency('convert_rate')),config('decimal_limit')) + " " + user_currency('symbol');
        }
        }else if(config('currency_symbol')){
        if(config('currency_symbol_position') === 'left'){
            return config('currency_symbol')+formatMoney(parseFloat(amount),config('decimal_limit'));
        }
        else if(config('currency_symbol_position') === 'left_with_space'){
            return config('currency_symbol')+ " " +formatMoney(parseFloat(amount),config('decimal_limit'));
        }
        else if(config('currency_symbol_position') === 'right'){
            return formatMoney(parseFloat(amount),config('decimal_limit')) + config('currency_symbol');
        }
        else if(config('currency_symbol_position') === 'right_with_space'){
            return formatMoney(parseFloat(amount),config('decimal_limit')) + " " + config('currency_symbol');
        }
        }else{
        return "$ " + formatMoney(parseFloat(amount),2);
        }  
        
    }
