'use strict';

(function ($) {
    $(document).on(
        'click',
        '.quantity .increase, .quantity .decrease',
        function (e) {
            e.preventDefault();
            let $this = $(this),
                $wrapperBtn = $this.closest('.product-button'),
                $btn = $wrapperBtn.find('.quantity_button'),
                $qty = $this.siblings('.qty'),
                step = parseInt($qty.attr('step'), 10),
                current = parseInt($qty.val(), 10),
                min = parseInt($qty.attr('min'), 10),
                max = parseInt($qty.attr('max'), 10);
            min = min || 1;
            max = max || current + 1;
            if ($this.hasClass('decrease') && current > min) {
                $qty.val(current - step);
                $qty.trigger('change');
                let numQuantity = +$btn.attr('data-quantity');
                numQuantity = numQuantity - 1;
                $btn.attr('data-quantity', numQuantity);
            }
            if ($this.hasClass('increase') && current < max) {
                $qty.val(current + step);
                $qty.trigger('change');
                let numQuantity = +$btn.attr('data-quantity');
                numQuantity = numQuantity + 1;
                $btn.attr('data-quantity', numQuantity);
            }
        });
})(jQuery);
