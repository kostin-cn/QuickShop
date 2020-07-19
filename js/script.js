jQuery.noConflict();
(function($){
    $(function(){
        $('#menu_button').click(function () {
            $('#menu_button').toggleClass('active');
            $('#mainNav').toggleClass('active');
            $('#mainNavMenu').toggleClass('active');
        });

        $('#catalogCategoriesBtn').click(function () {
            $('.cat-menu').toggleClass('active');
        });

        $('.current-city').click(function () {
            $(this).parent().find('.cities').toggleClass('active');
        });

        $('.reserveBtn').click(function(event) {
            event.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                context: $('#pop-content'),
                type:'GET',
                success: function(data){
                    console.log(data);
                    $(this).html(data);
                },
                complete: function() {
                    $('#pop-up').addClass('active');
                    $('body').addClass('popUnder');
                    history.pushState(null, '', url);
                }
            });
        });
        $('.pop-close').click(function(){
            $('.pop-up').removeClass('active');
            $('body').removeClass('popUnder');
        });

        $('.usrLogin').click(function (event) {
            event.preventDefault();
            let target = $(this).attr('href');
            $.ajax({
                async: true,
                url: target,
                context: $('#feedbackContent'),
                type: 'GET',
                success: function(data){
                    $(this).html(data);
                },
                complete: function () {
                    if (!$('#feedbackForm').hasClass('active')) {
                        $('#feedbackForm').addClass('active');
                    }
                }
            });
        });

        $('#feedback a').click(function (event) {
            event.preventDefault();
            let target = $(this).attr('href');
            $.ajax({
                async: true,
                url: target,
                context: $('#feedbackContent'),
                type: 'GET',
                success: function(data){
                    $(this).html(data);
                },
                complete: function () {
                    if (!$('#feedbackForm').hasClass('active')) {
                        $('#feedbackForm').addClass('active');
                    }
                    // звёздочки на странице отзывов

                    $("#reviews-rate label i").click(function () {
                        let $current = $(this).parent().find('input').val();

                        $("#reviews-rate label").each(function () {
                            if ($(this).find('input').val() <= $current) {
                                $(this).find('i').removeClass('icon-star-o').addClass('icon-star');
                            }
                            else {
                                $(this).find('i').removeClass('icon-star').addClass('icon-star-o');
                            }
                        });
                    });
                }
            });
        });

        $('#closeFeedback').click(function () {
            $('#feedbackForm').removeClass('active');
            $('#feedbackContent').empty();
        });

        $('#cityEvnt').on('change', function () {
            let city = $(this).val();
            $('.optionRestItem').removeClass('show');
            $('.optionRestItem').each(function () {
                if ($(this).data('city') == city) {
                    $(this).addClass('show');
                }
            });
        });

        $('#restEvnt').on('change', function () {
            let current = $(this).val();
            let target = $(this).data('href')+'?rest_id='+current;
            $.ajax({
                url: target,
                context: $('#pageContainer'),
                type: 'GET',
                success: function(data){
                    $(this).html(data);
                },
                complete: function () {
                    iasNews();
                }
            });
        });

        // CART AJAX FORM
        let cartControlBtn = $('.cartBtn');
        let cartUpdate = $('.cart-update');
        let cartAjaxBlock = document.getElementById('cartPopUp');
        let cartAddForm = $('#menu_item_form');

        function closeAjax(){
            cartAjaxBlock.classList.remove('active');
        }

        //ADD
        if (cartAddForm.length) {
            cartAddForm.submit(function (event) {
                event.preventDefault();
                let ajaxDataBlock = $('#cartData');
                let url = cartAddForm.attr('action');
                $.ajax({
                    url: url,
                    type:'post',
                    data: cartAddForm.serialize()
                }).done(function(data) {
                    if(!data) return false;
                    ajaxDataBlock.html(data);
                    let cartClose = document.getElementById('cartClose');
                    cartClose.addEventListener('click', closeAjax, false);
                    cartAjaxBlock.classList.add('active');
                    let amount = $('#cartRender').attr('data-amount');
                    let cartCount = $('#cartCount');
                    if (amount) {
                        cartCount.text(amount);
                        if (!cartCount.hasClass('visible')) {cartCount.addClass('visible');}
                        if (amount == "0") {cartCount.removeClass('visible');}
                    }else {
                        cartCount.removeClass('visible');
                    }
                }).fail(function() {
                    alert( "error" );
                });
            })
        }

        //PLUS / MINUS
        if (cartUpdate.length){
            cartUpdate.click(function () {
                window.location = $(this).attr('data-href')
            })}

        if (cartControlBtn.length){
            cartControlBtn.click(
                function (event) {
                    event.preventDefault();
                    let ajaxDataBlock = $('#cartData');
                    let url = $(this).attr("href");
                    cartAjaxBlock.classList.remove('active');
                    $.ajax({
                        url: url,
                        type:'GET'
                    }).done(function(data) {
                        if(!data) return false;
                        ajaxDataBlock.html(data);
                        let cartClose = document.getElementById('cartClose');
                        cartClose.addEventListener('click', closeAjax, false);
                        cartAjaxBlock.classList.add('active');
                        let cartRender = $('#cartRender');
                        let amount = cartRender.attr('data-amount');
                        let cartCount = $('#cartCount');
                        let total = cartRender.attr('data-total');
                        let cartTotal = $('#cartTotal');
                        let cartTotalBlock = $('.cartTotalBlock');
                        if (amount) {
                            cartCount.text(amount);
                            cartTotal.text(total);
                            if (!cartCount.hasClass('visible')) {cartCount.addClass('visible');}
                            if (!cartTotalBlock.hasClass('visible')) {cartTotalBlock.addClass('visible');}
                            if (amount == "0") {cartCount.removeClass('visible');}
                            if (total == "0") {cartTotalBlock.removeClass('visible');}
                        }else {
                            cartCount.removeClass('visible');
                            cartTotalBlock.removeClass('visible');
                        }
                    }).fail(function() {
                        alert( "error" );
                    });
                }
            );
        }

        $('.product-cart-add label').click(function () {
            var $current = $(this).find('input').val();

            $(".product-cart-add label").each(function () {
                if ($(this).find('input').val() == $current) {
                    $(this).addClass('active');
                }
                else {
                    $(this).removeClass('active');
                }
            });
        });
        $(".product-radio").click(function () {
            // $(this).data('ct', $(this).find('input').val());
            var $price = parseInt($("#for-js-cost").text());
            var $current = $(this).find('input').val() * $price;
            $("#menu_item_cost").text($current);
        });

        $(".product-input").keyup(function () {
            // $(this).data('ct', $(this).find('input').val());
            var $price = parseInt($("#for-js-cost").text());
            var $current = parseInt($(".product-input").val()) * $price;
            if (!$current) {
                $current = $('.active').find('input').val() * $price;
            }
            $("#menu_item_cost").text($current);
        });

        var $grid = $('.grid').isotope({
            itemSelector: '.grid-item',
            layoutMode: 'masonry',
        });
        $grid.isotope();

        var scrolled;
        let sp = $(window).height();
        scrolled = window.pageYOffset || document.documentElement.scrollTop;

        window.onscroll = function() {
            scrolled = window.pageYOffset || document.documentElement.scrollTop;
            sp = $(window).height();
            show_jq_hidden();

            if(scrolled > 10){
                $(".topMenu__container").addClass("filled");
                $("#slideMenu").addClass("filled");
            }
            if(10 > scrolled){
                $(".topMenu__container").removeClass("filled");
                $("#slideMenu").removeClass("filled");
            }

            w_p = $(window).scrollTop() - 10;
            if ($('.respons__block').length) {
                var b_h = $('.respons__block').outerHeight();
                if (b_h >= w_p) {
                    $('.respons__block').animate({
                        'background-position-x': '50%',
                        'background-position-y': $(window).scrollTop()/3
                    }, 0);
                }
            }
        };

        function show_jq_hidden() {
            $('.jq_hidden').each(function(){
                let scroll_pos = window.pageYOffset;
                if (($(this).offset().top) < (scroll_pos + $(window).height() + 100)) {
                    $(this).addClass('jq_active');
                }
                if (($(this).offset().top) > (scroll_pos + $(window).height() + 100)) {
                    $(this).removeClass('jq_active');
                }
            });
        }

        let w_w = $(window).width();

        if ($('.articles-container').length) {
            iasNews();
        }

        function iasNews() {
            let ias = jQuery.ias({
                container:  '.articles-container',
                item:       '.article-block',
                pagination: '#pagination',
                next:       '.next a'
            });

            // Add a loader image which is displayed during loading
            ias.extension(new IASSpinnerExtension());

            ias.extension(new IASTriggerExtension({
                offset: 0,
                text: '<span class="news-plus"><span class="vertical-news-plus"></span><span class="horisontal-news-plus"></span></span><span class="text-news-plus">  загрузить еще</span>' // optionally
            }));

            // Add a text when there are no more pages left to load
            ias.extension(new IASNoneLeftExtension({text: "Это все события"}));
        }

        //MainPage Slider
        let $slider = $('#mainSlider');
        if ($slider.length) {
            let sl_slider = $slider.slick({
                infinite: true,
                autoplay: true,
                // prevArrow: ".left-arrow",
                // nextArrow: ".right-arrow",
                arrows: false,
                slide: ".slide",
                slidesToShow: 1,
                slidesToScroll: 1,
                dots: true,
            });
        }

        let $cat_slider = $('#mainCatSlider');
        if ($cat_slider.length) {
            let sl_slider = $cat_slider.slick({
                infinite: true,
                autoplay: false,
                prevArrow: ".cat-left-arrow",
                nextArrow: ".cat-right-arrow",
                arrows: true,
                slide: ".categoryItem",
                slidesToShow: 1,
                slidesToScroll: 1,
                dots: false,
            });
        }

        let $evnt_slider = $('#mainEvents');
        if ($evnt_slider.length) {
            let sl_slider = $evnt_slider.slick({
                infinite: true,
                autoplay: true,
                // prevArrow: ".event-left-arrow",
                // nextArrow: ".event-right-arrow",
                arrows: false,
                slide: ".eventItem",
                slidesToShow: 5,
                slidesToScroll: 1,
                dots: false,
                responsive: [
                    {
                        breakpoint: 1290,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 1080,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 500,
                        settings: {
                            variableWidth: true,
                            slidesToShow: 2,
                        }
                    }
                ]
            });
        }

        show_jq_hidden();
    });
})(jQuery);