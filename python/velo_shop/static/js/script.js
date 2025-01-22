// Анимации
document.addEventListener("DOMContentLoaded", function() {
    function addAnimation(selector) {
        const elements = document.querySelectorAll(selector);
        elements.forEach((element, index) => {
            setTimeout(() => {
                element.classList.add('animate__animated', 'animate__fadeInUp');
            }, index * 200);
        });
    }

    addAnimation('.product');
    addAnimation('li');

    const header = document.querySelector('header');
    if (header) {
        header.classList.add('animate');
    }
});

// Ф-я для ввода только цифр в цену
function validateInput(evt) {
    const key = evt.key;

    // Разрешаем клавиши: backspace, delete, tab, escape, enter
    if (['Backspace', 'Delete', 'Tab', 'Escape', 'Enter'].includes(key)) {
        return true;
    }

    // Разрешаем ввод только цифр
    if (/[0-9]/.test(key)) {
        return true;
    }

    // Запретить другие символы
    return false;
}

// Ф-я при добавлении товара в корзину
$(document).ready(function() {
    $('.add-to-cart-form').submit(function(event) {
        event.preventDefault();
        
        const formData = $(this).serialize();

        $.ajax({
            url: '/add_to_cart',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    updateCartQuantity(); // Обновление кол-ва товаров в корзине 
                    console.log(response.message);
                } else {
                    console.log(response.message);
                }
            },
            error: function() {
                console.log("error");
            }
        });
    });
});

// Ф-я для обновления кол-ва товаров в корзине
function updateCartQuantity() {
    $.getJSON('/cart/quantity', function(data) {
        $('#item-count').text(data.quantity);
    });
}

$(document).ready(function() {
    updateCartQuantity(); // Обновление кол-ва товаров в корзине при загрузке страницы
});

// Ф-я при удалении товара в корзине
$(document).ready(function() {
    $('.delete-form').submit(function(event) {
        event.preventDefault();
        
        const formData = $(this).serialize();
        const form = $(this);

        $.ajax({
            url: '/delete_item',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    form.closest('li').remove(); // Удаляем элемент в списке
                    console.log(response.message);
                } else {
                    console.log(response.message);
                }
            },
            error: function() {
                console.log("error");
            }
        });
    });
});

// Ф-я при изменении кол-ва товара в корзине
$(document).ready(function() {
    $('.quantity-form').submit(function(event) {
        event.preventDefault();
        
        const formData = $(this).serialize();
        const form = $(this);
        const countElement = form.closest('.quantity-controls').find('#count');

        $.ajax({
            url: '/change_quantity',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    console.log(response.message);
                    countElement.text('Количество: ' + response.quantity); // Вывод нового кол-ва
                } else {
                    console.log(response.message);
                }
            },
            error: function() {
                console.log("error");
            }
        });
    });
});

// Ф-я при очистке корзины
$(document).ready(function() {
    $('.reset-form').submit(function(event) {
        event.preventDefault();
        
        const formData = $(this).serialize();
        const form = $(this);

        $.ajax({
            url: '/clear_cart',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('main.cart h2').text('Корзина пуста'); // Меняем текст заголовка
                    $('ul').remove(); // Удаляем список товаров
                    $('button').remove(); // Удаляем кнопки
                    console.log(response.message);
                    
                } else {
                    console.log(response.message);
                }
            },
            error: function() {
                console.log("error");
            }
        });
    });
});