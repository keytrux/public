from flask import Flask, render_template, request, redirect, url_for, send_from_directory, flash, jsonify

application = Flask(__name__)
application.secret_key = '333'

# Массив для хранения товаров в корзине
cart = []

@application.route('/images/<path:filename>')
def send_image(filename):
    return send_from_directory('images', filename)

@application.route('/')
def home():
    quantity = 0

    for item in cart:
        quantity += item['quantity']

    return render_template('index.html', 
        # Массив с товарами в каталоге
        products=
            [
                {'name': 'Велошлем Met Crossover MIPS Orange', 'image': '/images/helmet/helmet_1.jpg', 'price': 5499}, 
                {'name': 'Велошлем MET MILES', 'image': '/images/helmet/helmet_2.jpg', 'price': 5890}, 
                {'name': 'Велошлем Met Miles Helmet', 'image': '/images/helmet/helmet_3.jpg', 'price': 5900},

                {'name': 'Велосипедная фара и задний фонарь 0,5W/2ф красный M-WAVE', 'image': '/images/flashlight/flashlight_1.jpg', 'price': 2549}, 
                {'name': 'Фара передняя STG FL1580', 'image': '/images/flashlight/flashlight_2.png', 'price': 4760}, 
                {'name': 'Фара передняя KMS EOS-100, 300 лм, USB', 'image': '/images/flashlight/flashlight_3.jpg', 'price': 586},

                {'name': 'Шоссейный велосипед Fuji Bikes Gran Fondo', 'image': '/images/bicycle/bicycle_1.jpg', 'price': 79990}, 
                {'name': 'Велосипед для триатлона Bianchi', 'image': '/images/bicycle/bicycle_2.jpg', 'price': 99000}, 
                {'name': 'Велосипед Pinarello Catena CrMo', 'image': '/images/bicycle/bicycle_3.jpg', 'price': 65000},
            ],
        cart_length = quantity
    )

@application.route('/cart/quantity')
# Ф-я для получения кол-ва товаров в корзине
def cart_quantity():
    quantity = sum(item['quantity'] for item in cart)
    return jsonify(quantity=quantity)

@application.route('/add_to_cart', methods=['POST'])
# Ф-я для добавления товара в корзину
def add_to_cart():
    product_name = request.form.get('name')
    product_price = request.form.get('price')
    product_image = request.form.get('image')

    if product_name:
        for item in cart:
            # Если товар уже есть в корзине
            if item['name'] == product_name:
                item['quantity'] += 1
                return jsonify(success=True, message='Товар добавлен в корзину'), 200
        
        # Если товар новый
        cart.append({
            'name': product_name,
            'price': product_price,
            'image': product_image,
            'quantity': 1
        })
        return jsonify(success=True, message='Товар добавлен в корзину'), 200
        
    return jsonify(success=False, message='Ошибка при добавлении товара.'); # Обработка ошибки

@application.route('/cart')
# Ф-я для отображения страницы корзины
def view_cart():
    return render_template('cart.html', cart=cart)

@application.route('/delete_item', methods=['POST'])
# Ф-я для удаления товара с корзины
def delete_item():
    product_name = request.form.get('name')
    if product_name:
        # Ищем товар и удаляем его из корзины
        global cart
        cart = [item for item in cart if item['name'] != product_name]
        return jsonify(success=True, message='Товар удален'), 200
    return jsonify(success=False, message='Ошибка при удалении товара.'); # Обработка ошибки

@application.route('/change_quantity', methods=['POST'])
# Ф-я для изменения кол-ва товара в корзине
def change_quantity():
    product_name = request.form.get('name')
    sign = request.form.get('sign')

    for item in cart:
        if item['name'] == product_name:
            # Уменьшение
            if sign == "-":
                if item['quantity'] > 1:
                    item['quantity'] -= 1
                    return jsonify(success=True, message='Количество уменьшено', quantity=item['quantity']), 200
            # Увеличение
            else:
                item['quantity'] += 1
                return jsonify(success=True, message='Количество увеличено', quantity=item['quantity']), 200
            break
    return jsonify(success=False, message='Ошибка при изменении количества товара.'); # Обработка ошибки

@application.route('/clear_cart', methods=['POST'])
# Ф-я очистки корзины
def clear_cart():
    cart.clear()
    return jsonify(success=True, message='Корзина очищена'), 200

@application.route('/create_order', methods=['POST'])
# Ф-я создания заказа
def create_order():
    cart.clear()
    flash("Заказ оформлен!", "success")  # Добавляем flash-сообщение
    return redirect('/cart')

if __name__ == "__main__":
    application.run(debug=True)
