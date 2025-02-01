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
    
    showAdmin();
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


function showAdmin()
{
    document.getElementById('admin').style.display = 'block';
    document.getElementById('admin-product').style.display = 'none';
    document.getElementById('admin-category').style.display = 'none';
    document.getElementById('admin-order').style.display = 'none';
    document.getElementById('admin-user').style.display = 'none';
    setActiveTabAdmin('adminTab');
}

function showProduct()
{
    document.getElementById('admin').style.display = 'none';
    document.getElementById('admin-product').style.display = 'block';
    document.getElementById('admin-category').style.display = 'none';
    document.getElementById('admin-order').style.display = 'none';
    document.getElementById('admin-user').style.display = 'none';
    setActiveTabAdmin('productTab');
}

function showCategory()
{
    document.getElementById('admin').style.display = 'none';
    document.getElementById('admin-product').style.display = 'none';
    document.getElementById('admin-category').style.display = 'block';
    document.getElementById('admin-order').style.display = 'none';
    document.getElementById('admin-user').style.display = 'none';
    setActiveTabAdmin('categoryTab');
}

function showOrder()
{
    document.getElementById('admin').style.display = 'none';
    document.getElementById('admin-product').style.display = 'none';
    document.getElementById('admin-category').style.display = 'none';
    document.getElementById('admin-order').style.display = 'block';
    document.getElementById('admin-user').style.display = 'none';
    setActiveTabAdmin('orderTab');
}

function showUser()
{
    document.getElementById('admin').style.display = 'none';
    document.getElementById('admin-product').style.display = 'none';
    document.getElementById('admin-category').style.display = 'none';
    document.getElementById('admin-order').style.display = 'none';
    document.getElementById('admin-user').style.display = 'block';
    setActiveTabAdmin('userTab');
}

function setActiveTabAdmin(activeId) {
    document.getElementById('adminTab').classList.remove('active');
    document.getElementById('productTab').classList.remove('active');
    document.getElementById('categoryTab').classList.remove('active');
    document.getElementById('orderTab').classList.remove('active');
    document.getElementById('userTab').classList.remove('active');
    document.getElementById(activeId).classList.add('active');
}


$(document).ready(function() {

        // Обработка отправки фильтра
        $('.category-filter-form').submit(function(event) {
            event.preventDefault();
            
            const formData = $(this).serialize();
    
            $.ajax({
                url: '/filter',
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.log('1224');
                    if (response.products) {
                        $('.main-products').empty();
                        
                        // Группируем продукты по категориям
                        const productsByCategory = response.products.reduce((acc, product) => {
                            const category = product.name_category;
                            if (!acc[category]) {
                                acc[category] = []; // Если категория не существует, создаем массив для товаров
                            }
                            acc[category].push(product); // Добавляем продукт в соответствующую категорию
                            return acc;
                        }, {});
                    
                        // Сортируем категории от А до Я
                        const sortedCategories = Object.keys(productsByCategory).sort();
                    
                        // Проходим по отсортированным категориям и выводим их с товарами
                        sortedCategories.forEach(category => {
                            const h3 = `<h3>${category}</h3>`;
                            $('.main-products').append(h3); // Добавляем заголовок категории
                    
                            const divOpen = '<div class="products">';
                            const divClose = '</div>';
                            $('.main-products').append(divOpen); // Открываем блок продуктов
                    
                            productsByCategory[category].forEach(function(product) {
                                const productHtml = `
                                    <div class="product animate__animated animate__fadeInUp">
                                        <a href="/product/${product.id_product}">
                                            <img src="${product.image}" width="220" height="150" alt="${product.name}">
                                            <h3>${product.name}</h3>
                                            <p>Цена: ${product.price}₽</p>
                                        </a>
                                        <form action="/add_to_cart" method="post" class="add-to-cart-form">
                                            <input type="hidden" name="id_product" value="${product.id_product}">
                                            <input type="hidden" name="image" value="${product.image}">
                                            <input type="hidden" name="price" value="${product.price}">
                                            <input type="hidden" name="name" value="${product.name}">
                                            <button type="submit" class="add-to-cart">Добавить в корзину</button>
                                        </form>
                                    </div>
                                `;
                                $('.main-products .products:last').append(productHtml); // Добавляем товар в последний открытый блок
                            });
                    
                            $('.main-products').append(divClose); // Закрываем блок с продуктами
                        });
                    }
                                    
                    
                     else {
                        console.log("Ошибка получения продуктов.");
                    }
                }
                ,
                error: function() {
                    console.log("Ошибка в AJAX запросе");
                }
            });
        });
    
        // Делегирование событий для динамически добавленных форм
        $(document).on('submit', '.add-to-cart-form', function(event) {
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

    

    // Обработка нажатия на категорию
    $(document).on('click', '.select-items div', function() {
        const selectedValue = $(this).data('value');
        $('#selected-category').val(selectedValue);
        $('#selected-role').val(selectedValue);
        $('#selected-status').val(selectedValue);
        $('.select-selected').text($(this).text());
        console.log('Выбрано: ' + selectedValue); // Проверка вывода
        $('.select-items').addClass('select-hide'); // Скрыть выпадающий список

        // Подаем форму
        // $('.category-filter-form').submit();
    });

    // Показ/скрытие выпадающего списка
    $('.select-selected').click(function() {
        $(this).next('.select-items').toggleClass('select-hide');
    });

    // Закрытие выпадающего списка при клике вне
    $(document).click(function(e) {
        if (!$(e.target).closest('.custom-select').length) {
            $('.select-items').addClass('select-hide');
        }
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
                    const $listItem = form.closest('li');
                    const $list = $listItem.parent();
                    
                    $listItem.remove(); // Удаляем элемент в списке
                    updateTotalSum();
                    
                    // Проверяем, остался ли в списке еще элемент
                    if ($list.children('li').length === 0) {
                        // Если последний элемент был удален, удаляем кнопки
                        $('main.cart h2').text('Корзина пуста'); // Меняем текст заголовка
                        $('ul').remove(); // Удаляем список товаров
                        $('button').remove(); // Удаляем кнопки
                        $('.amount-cart').remove(); // Удаляем элементы с суммой или количеством
                    }

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
    updateTotalSum();
    $('.quantity-form').submit(function(event) {
        event.preventDefault();
        
        const formData = $(this).serialize();
        const form = $(this);
        const countElement = form.closest('.quantity-controls').find('#count');
        
        const price = parseFloat(form.find('input[name="price"]').val());
        const totalPriceElement = $('.total-price[data-price="' + price + '"]');

        $.ajax({
            url: '/change_quantity',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    console.log(response.message);
                    const quantity = response.quantity; // Обновляем количество
                    
                    countElement.text('Количество: ' + quantity); // Обновляем количество

                    // Пересчитываем итоговую цену
                    const totalPrice = price * quantity;
                    
                    // Обновляем текст итоговой цены на странице
                    totalPriceElement.text('Итого: ' + totalPrice + '₽');
                    
                    // Обновляем общую сумму
                    updateTotalSum();
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

function updateTotalSum() {
    let totalSum = 0;

    $('.total-price').each(function() {
        const itemTotal = parseFloat($(this).text().replace('Итого: ', '').replace('₽', ''));
        totalSum += itemTotal;
    });
    console.log('Сумма: ' + totalSum + '₽');
    // Обновляем элемент, где отображается сумма
    $('label:contains("Сумма:")').text('Сумма: ' + totalSum + '₽');
}





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
                    $('.amount-cart').remove();
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

