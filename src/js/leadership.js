import './main';

$(document).ready(function() {

    $('.leaders__block__btn').on('click', function() {
        const $button = $(this);
        const $block = $button.closest('.leaders__block');
        const $infoBlocks = $block.find('.leaders__block__info');

        $infoBlocks.each(function() {
            const $info = $(this);
            if ($info.hasClass('info-hidden')) {
                $info.removeClass('info-hidden').addClass('info-show');
                $button.find('.leaders__block__btn-span').text("Закрити");
                $button.addClass('open');
            } else {
                $info.removeClass('info-show').addClass('info-hidden');
                $button.find('.leaders__block__btn-span').text("Детальніше");
                $button.removeClass('open');
            }
        });
    });

});

