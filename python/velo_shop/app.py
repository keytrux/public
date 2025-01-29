from flask import Flask, render_template, request, redirect, url_for, send_from_directory, flash, jsonify, session
import sqlite3
import os
from datetime import datetime
import hashlib

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

    # Извлечение всех категорий и продуктов из базы данных
    conn = get_db_connection()
    categories_filter = conn.execute('SELECT * FROM categories').fetchall()

    categories = conn.execute('SELECT * FROM categories').fetchall()
    
    products = conn.execute('SELECT * FROM products').fetchall()
    conn.close()

    categories_sorted = sorted(categories, key = lambda category: category['name'])

    # Группируем продукты по категориям
    category_products = {category['id_category']: {'name': category['name'], 'products': []} for category in categories_sorted}
    for product in products:
        category_products[product['id_category']]['products'].append(product)

    for item in cart:
        quantity += item['quantity']

    return render_template('index.html', 
        categories_filter=categories_filter,
        categories=category_products,
        cart_length=quantity
    )

@application.route('/filter', methods=['GET', 'POST'])
def filter():
    id_category = request.form.get('category')
    quantity = 0

    # Извлечение всех категорий и продуктов из базы данных
    conn = get_db_connection()

    categories_filter = conn.execute('SELECT * FROM categories').fetchall()

    name_category = conn.execute('SELECT * FROM categories WHERE id_category = ?', (id_category,)).fetchone()

    products = conn.execute('SELECT * FROM products WHERE id_category = ?', (id_category,)).fetchall()
    conn.close()

    products_data = []
    for product in products:
        products_data.append({
            'id_product': product['id_product'],
            'image': product['image'],
            'name': product['name'],
            'price': product['price']
        })

    return jsonify(products=products_data, name_category=name_category['name'])  # Отправляем данные в формате JSON

@application.route('/cart/quantity')
# Ф-я для получения кол-ва товаров в корзине
def cart_quantity():
    quantity = sum(item['quantity'] for item in cart)
    return jsonify(quantity=quantity)

@application.route('/add_to_cart', methods=['POST'])
# Ф-я для добавления товара в корзину
def add_to_cart():
    product_id = request.form.get('id_product')
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
            'id_product': product_id,
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

    product = next((item for item in products if item['id_product'] == product_id), None)

    return render_template('product.html', product=product, cart=cart)

@application.route('/register', methods=['GET', 'POST'])
def register():
    if request.method == 'POST':
        login = request.form.get('login')
        password = request.form.get('password')
        confirm_password = request.form.get('confirm_password')
        phone = request.form.get('phone')
        name = request.form.get('name')

        if password != confirm_password:
            return jsonify({'success': False, 'message': 'Пароли не совпадают!'}), 400

        # Проверка на заполненность полей
        if not login or not password or not phone or not name:
            return jsonify({'success': False, 'message': 'Пожалуйста, заполните все поля.'}), 400

        # Хешируем пароль
        hashed_password = hashlib.sha256(password.encode()).hexdigest()

        conn = get_db_connection()
        try:
            # Вставка нового пользователя
            conn.execute('INSERT INTO users (login, password, phone, name, role) VALUES (?, ?, ?, ?, ?)',
                         (login, hashed_password, phone, name, "user"))
            conn.commit()

            # Авторизация пользователя
            user = conn.execute('SELECT * FROM users WHERE login = ?', (login,)).fetchone()
            session['logged_in'] = True
            session['id_user'] = user['id_user']
            session['role'] = user['role']
            
            return jsonify({'success': True, 'redirect_url': url_for('personal_account')}), 200

        except sqlite3.IntegrityError:
            return jsonify({'success': False, 'message': 'Данный логин уже используется.'}), 400

        finally:
            conn.close()

    return render_template('login.html')  # Отображаем форму регистрации


@application.route('/sign_in', methods=['GET', 'POST'])
def sign_in():
    conn = get_db_connection()
    products = conn.execute('SELECT * FROM products').fetchall()
    conn.close()

    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        hashed_password = hashlib.sha256(password.encode()).hexdigest()

        conn = get_db_connection()
        users = conn.execute('SELECT * FROM users WHERE login = ? AND password = ?', (username, hashed_password)).fetchone()

        if users:
            session['logged_in'] = True
            session['id_user'] = users['id_user']
            session['role'] = users['role']
            
            response = {
                'success': True,
                'redirect_url': url_for('admin' if users['role'] == 'admin' else 'personal_account')
            }
            return jsonify(response)

        conn.close()
        flash('Неправильный логин или пароль', 'error')
        
        response = {
            'success': False,
            'message': 'Неправильный логин или пароль',  # Возвращаем сообщение
        }
        return jsonify(response)

    if session.get('logged_in'):
        return redirect(url_for('admin' if session.get('role') == 'admin' else 'personal_account'))

    return render_template('login.html', products=products)


@application.route('/exit')
def exit():
    quantity = 0

    # Извлечение всех продуктов из базы данных
    conn = get_db_connection()
    products = conn.execute('SELECT * FROM products').fetchall()
    conn.close()

    for item in cart:
        quantity += item['quantity']

    session.pop('logged_in', None)
    session.pop('id_user', None)
    session.pop('role', None)
    session.clear()
    return render_template('index.html', products=products, cart_length=quantity)

@application.route('/admin')
def admin():
    conn = get_db_connection()
    products = conn.execute('SELECT * FROM products').fetchall()
    categories = conn.execute('SELECT * FROM categories').fetchall()
    conn.close()
    return render_template('admin.html', products=products, categories=categories)
    
@application.route('/personal_account')
def personal_account():
    conn = get_db_connection()
    id_user = int(session.get('id_user'))
    user = conn.execute('SELECT * FROM users WHERE id_user = ?', (id_user,)).fetchone()
    orders = conn.execute('SELECT * FROM orders WHERE id_user = ?', (id_user,)).fetchall()

    # Получаем информацию о товарах
    products = conn.execute('SELECT * FROM products').fetchall()
    product_dict = {product['name']: {"id_product": product['id_product'], "image_url": product['image'], "price": product['price']} for product in products}


    conn.close()
    return render_template('personal_account.html', id=user['id_user'], login=user['login'], name=user['name'], phone=user['phone'], orders=orders, product_dict=product_dict)

@application.route('/add_product', methods=['POST'])
# Ф-я для добавления товара
def add_product():
    name = request.form['name']
    price = request.form['price']
    id_category = request.form['category']  # Получение выбранной категории
    
    # Обработка изображения
    if 'image' not in request.files:
        return "Нет файла для загрузки"
    
    file = request.files['image']
    if file.filename == '':
        return "Нет выбранного файла"

    conn = get_db_connection()

    category = conn.execute('SELECT * FROM categories WHERE id_category = ?', (id_category,)).fetchone()

    # Сохранение изображения
    filename = file.filename
    file_path = os.path.join(application.config['UPLOAD_FOLDER'], category['path'], filename)
    file.save(file_path)

    # Сохранение записи в БД
    

    conn.execute('INSERT INTO products (id_category, name, image, price) VALUES (?, ?, ?, ?)',
                 (id_category, name, f'/images/product/{category['path']}/{filename}', price))
    conn.commit()
    conn.close()
    flash("Товар добавлен!", "success")
    return redirect('/sign_in')

@application.route('/delete_product/<int:product_id>', methods=['POST'])
def delete_product(product_id):
    conn = get_db_connection()

    product = conn.execute('SELECT image FROM products WHERE id_product = ?', (product_id,)).fetchone()
    if product:
        image_path = os.path.join(os.getcwd(), product['image'][1:])  # Получаем полный путь к изображению
        
        try:
            if os.path.exists(image_path):
                os.remove(image_path)
            else:
                flash('Файл изображения не найден: ' + image_path, 'error')
        except Exception as e:
            flash('Ошибка при удалении изображения: ' + str(e), 'error')

    conn.execute('DELETE FROM products WHERE id_product = ?', (product_id,))
    conn.commit()
    conn.close()
    flash('Товар успешно удален.', 'success')  # Подтверждение успешного удаления
    return redirect('/sign_in')

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
    if not session.get('logged_in'):
        flash('Вы должны авторизоваться, чтобы оформить заказ', 'error')
        return redirect(url_for('sign_in'))

    
    id_user = session.get('id_user')

    # Формируем строку заказа и подсчитываем общую сумму
    order_details = ', '.join([f"{item['name']} (x{item['quantity']}) - {item['price']} руб." for item in cart])
    total_amount = sum(int(item['price']) * int(item['quantity']) for item in cart)

    # Получаем текущую дату
    order_date = datetime.now().strftime('%Y-%m-%d %H:%M:%S')

    # Сохранение записи в БД
    conn = get_db_connection()
    conn.execute('INSERT INTO orders (id_user, order_details, amount, date_order) VALUES (?, ?, ?, ?)',
                 (id_user, order_details, total_amount, order_date))
    conn.commit()
    conn.close()

    cart.clear()
    flash("Заказ оформлен!", "success")  # Добавляем flash-сообщение
    return redirect('/cart')

if __name__ == "__main__":
    application.run(debug=True)
