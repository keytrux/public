$(document).ready(function () {
    $('.offers_child_item-description').on('click', function () {
        const $item = $(this).closest('.offers_child_item');
        const $charList = $item.find('.offers_child_item-char-list');
        const $img = $item.find('.offers_child_item-img');
        const $sale = $item.find('.sale-badge');

        // Переключаем классы
        $charList.toggleClass('hidden');
        $img.toggleClass('hide');
        $sale.toggleClass('hide');
        $item.find('.offers_child_item-contents').toggleClass('expanded');
    });
});