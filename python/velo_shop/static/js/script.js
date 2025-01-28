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

function showLogin() {
    document.getElementById('flash-messages-container').innerHTML = '';

    document.getElementById('loginForm').style.display = 'block';
    document.getElementById('registerForm').style.display = 'none';
    setActiveTab('loginTab');
}

function showRegister() {
    document.getElementById('flash-messages-container').innerHTML = '';

    document.getElementById('loginForm').style.display = 'none';
    document.getElementById('registerForm').style.display = 'block';
    setActiveTab('registerTab');
}

// Ф-я для отображения/скрытия пароля
$(document).ready(function() {
    $('.toggle-password').click(function() {
        const input = $(`#${$(this).attr('toggle')}`);
        const type = input.attr('type') === 'password' ? 'text' : 'password';

        input.attr('type', type);

        const icon = type === 'password' ? 'images/site/eye.svg' : 'images/site/eye-off.svg'; // Изменим путь к SVG для зачеркнутого глаза
        $(this).find('.eye-icon').attr('src', icon);

        console.log(`Текущий тип поля для ${$(this).attr('toggle')}: ${type}`); // Отладочный вывод
    });
});




function setActiveTab(activeId) {
    document.getElementById('loginTab').classList.remove('active');
    document.getElementById('registerTab').classList.remove('active');
    document.getElementById(activeId).classList.add('active');
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
        
        // Получаем цену из скрытого поля
        const price = parseFloat(form.find('input[name="price"]').val());
        
        // Находим элемент для обновления итоговой цены по совпадению data-price
        const totalPriceElement = $('.total-price[data-price="' + price + '"]');

        $.ajax({
            url: '/change_quantity',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    console.log(response.message);
                    const quantity = response.quantity; // Обновляем количество
                    
                    // Пересчета итоговой цены
                    const totalPrice = price * quantity;
                    
                    // Обновляем текст итоговой цены на странице
                    totalPriceElement.text('Итого: ' + totalPrice + '₽');

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

// Ф-я при авторизации
$(document).ready(function() {
    $('.sign-in-form').submit(function(event) {
        event.preventDefault();
        
        const formData = $(this).serialize();

        $.ajax({
            url: '/sign_in',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Перенаправляем пользователя на нужную страницу
                    window.location.href = response.redirect_url;
                } else {
                    // Отображаем флеш-сообщение без перезагрузки
                    showFlashMessage(response.message);
                }
            },
            error: function() {
                console.log("Произошла ошибка");
            }
        });
    });

    function showFlashMessage(message) {
        const messageBox = $('<ul class="flashes"><li class="error animate__animated animate__fadeInUp">' + message + '</li></ul>');
        $('#flash-messages-container').html(messageBox); // Обновляем контейнер для сообщений
    }
});

// Ф-я при регистрации
$(document).ready(function() {
    $('.register-form').submit(function(event) {
        event.preventDefault();

        const formData = $(this).serialize();

        $.ajax({
            url: '/register',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Перенаправляем пользователя на нужную страницу
                    window.location.href = response.redirect_url;
                } else {
                    // Отображаем флеш-сообщение без перезагрузки
                    showFlashMessage(response.message);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showFlashMessage(response.message || "Произошла ошибка");
            }
        });
    });

    function showFlashMessage(message) {
        const messageBox = $('<ul class="flashes"><li class="error animate__animated animate__fadeInUp">' + message + '</li></ul>');
        $('#flash-messages-container').html(messageBox); // Обновляем контейнер для сообщений
    }
});

