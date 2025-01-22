from flask import Flask, render_template, request, redirect, url_for, send_from_directory, flash, jsonify, session
import sqlite3
import os

application = Flask(__name__)
application.secret_key = '333'
application.config['UPLOAD_FOLDER'] = 'images/product'

# Ф-я для подключения к бд
def get_db_connection():
    conn = sqlite3.connect('VeloShop.db')
    conn.row_factory = sqlite3.Row  # Чтобы можно было обращаться к полям по имени
    return conn

# Массив для хранения товаров в корзине
cart = []

@application.route('/images/<path:filename>')
def send_image(filename):
    return send_from_directory('images', filename)

@application.route('/')
def home():
    quantity = 0

    # Извлечение всех продуктов из базы данных
    conn = get_db_connection()
    products = conn.execute('SELECT * FROM products').fetchall()
    conn.close()

    for item in cart:
        quantity += item['quantity']

    return render_template('index.html', 
        products=products,
        cart_length=quantity
    )

@application.route('/cart/quantity')
# Ф-я для получения кол-ва товаров в корзине
def cart_quantity():
    quantity = sum(item['quantity'] for item in cart)
    return jsonify(quantity=quantity)

@application.route('/add_to_cart', methods=['POST'])
# Ф-я для добавления товара в корзину
def add_to_cart():
    product_id = request.form.get('id')
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
            'id': product_id,
            'name': product_name,
            'price': product_price,
            'image': product_image,
            'quantity': 1
        })
        return jsonify(success=True, message='Товар добавлен в корзину'), 200
        
    return jsonify(success=False, message='Ошибка при добавлении товара.'); # Обработка ошибки

@application.route('/product/<int:product_id>')
# Ф-я для карточки товара
def view_product(product_id):
    conn = get_db_connection()
    products = conn.execute('SELECT * FROM products').fetchall()
    conn.close()

    product = next((item for item in products if item['id'] == product_id), None)

    return render_template('product.html', product=product, cart=cart)

@application.route('/admin', methods=['GET', 'POST'])
def view_admin():
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        
        if username == 'admin' and password == 'admin':
            session['logged_in'] = True
            return redirect(url_for('view_admin'))

        flash('Неправильный логин или пароль', 'error')

    if not session.get('logged_in'):
        return render_template('login.html')

    # Если авторизован, отображаем страницу админки
    conn = get_db_connection()
    products = conn.execute('SELECT * FROM products').fetchall()
    conn.close()

    return render_template('admin.html', products=products)


@application.route('/add_product', methods=['POST'])
# Ф-я для добавления товара
def add_product():
    name = request.form['name']
    price = request.form['price']
    
    # Обработка изображения
    if 'image' not in request.files:
        return "Нет файла для загрузки"
    
    file = request.files['image']
    if file.filename == '':
        return "Нет выбранного файла"

    # Сохранение изображения
    filename = file.filename
    file_path = os.path.join(application.config['UPLOAD_FOLDER'], filename)
    file.save(file_path)

    # Сохранение записи в БД
    conn = get_db_connection()
    conn.execute('INSERT INTO products (name, image, price) VALUES (?, ?, ?)',
                 (name, f'/images/product/{filename}', price))
    conn.commit()
    conn.close()
    flash("Товар добавлен!", "success")
    return redirect('/admin')

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
