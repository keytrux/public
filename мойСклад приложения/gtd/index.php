<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="nosova@npotamara.ru">
	<title>Инструкция - ГТД</title> <!-- Отображение заголовка страницы -->
    <link rel="icon" href="images/icon.png">
    <link rel="stylesheet" href="style_manual_v4.css?v=1.1">
	<script src="script_manual.js?v=1.1"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body style = "background-color: #98c1d9;">
	<div class="wrapper">
		<div class = "main_first">
			<div class="container-fluid">
				<h1>
					<img src = "images/icon.png" width = "70px"><b>ГТД</b>
				</h1>
				<p><b>С помощью данного приложения пользователи могут легко и быстро проставить ГТД в оприходования и приёмки <br>
					на основании предыдущих</b></p>
			</div>

			<!-- Контейнер начало -->
			<div class = "main_container">
				<div>    
					<p class = "step">Начало </p><p>Выберите с чем хотите работать с Оприходованиями, Приемками или Товарами</p>
				</div>
				<div>
					<img src = "images/begin.png">
				</div>
			</div>

			<!-- Главный контейнер -->
			<div class="main">
				<!-- Массовое обновление -->
				<div class="main-mass" onclick="toggleContent('massContent')">
					Массовое обновление
				</div>
				<div class="massContent content">
					<div><p class="step">Шаг 1. </p><p>Выберите период создания оприходований. По умолчанию выбран месяц. Нажмите кнопку "Получить все оприходования/приемки"</p></div>
					<div><img src="images/mass1.png" alt="Период для всех оприходования"></div>

					<div><p class="step">Шаг 2. </p><p>Выберите все необходимые № и нажмите кнопку "Проставить ГТД". (Если ни один № не выбран - значит выбраны все)</p></div>
					<div><img src="images/mass2.png" alt="№ оприходований"></div>

					<div><p class = "step">Шаг 3. </p><p>Готово</p></div>
					<div><img src = "images/mass3.png" alt="Проставить ГТД"></div>
				</div>
				<!-- Оприходование -->
				<div class="main-enter" onclick="toggleContent('enterContent')">
					Оприходования
				</div>
				<div class="enterContent content">
					<div><p class="step">Шаг 1. </p><p>Выберите период создания оприходования. По умолчанию выбран месяц. Нажмите кнопку "Выбрать период"</p></div>
					<div><img src="images/enter1.png" alt="Период для оприходования"></div>

					<div><p class="step">Шаг 2. </p><p>Введите или выберите № оприходования и нажмите кнопку "Получить оприходование"</p></div>
					<div><img src="images/enter2.png" alt="№ оприходования"></div>

					<div><p class = "step">Шаг 3. </p><p>После получения таблицы с позициями нажмите кнопку "Проставить ГТД"</p></div>
					<div><img src = "images/enter3.png" alt="Проставить ГТД"></div>

					<div><p class = "step">Шаг 4. </p><p>Готово. В таблице "После" отображены обновленные позиции. В таблице "Не найдено" отображены позиции у которых не найдено ГТД</p></div>
					<div><img src = "images/enter4.png" alt="Таблица после"></div>

					<div><p class = "step">Шаг 5. </p><p>Если необходимо, вы можете проставить в таблице "Не найдено" ГТД товара и нажать кнопку "Проставить ГТД вручную"</p></div>
					<div><img src = "images/enter5.png" alt="Проставить ГТД вручную"></div>
				</div>
				<!-- Приемки -->
				<div class="main-supply" onclick="toggleContent('supplyContent')">
					Приемки
				</div>
				<div class="supplyContent content">
					<div><p class="step">Шаг 1. </p><p>Выберите период создания приемки. По умолчанию выбран месяц. Нажмите кнопку "Выбрать период"</p></div>
					<div><img src="images/supply1.png" alt="Период для приемок"></div>

					<div><p class = "step">Шаг 2. </p><p>Введите или выберите № приемки и нажмите кнопку "Получить приемку"</p></div>
					<div><img src = "images/supply2.png" alt="№ приемки"></div>

					<div><p class = "step">Шаг 3. </p><p>После получения таблицы с позициями нажмите кнопку "Проставить ГТД"</p></div>
					<div><img src = "images/supply3.png" alt="Проставить ГТД"></div>

					<div><p class = "step">Шаг 4. </p><p>Готово. В таблице "После" отображены обновленные позиции. В таблице "Не найдено" отображены позиции у которых не найдено ГТД</p></div>
					<div><img src = "images/supply4.png" alt="Таблица после"></div>

					<div><p class = "step">Шаг 5. </p><p>Если необходимо, вы можете проставить в таблице "Не найдено" ГТД товара и нажать кнопку "Проставить ГТД вручную"</p></div>
					<div><img src = "images/supply5.png" alt="Проставить ГТД вручную"></div>
				</div>
				<!-- Товары -->
				<div class="main-product" onclick="toggleContent('productContent')">
					Товары
				</div>
				<div class="productContent content">
					<div><p class = "step">Шаг 1. </p><p>Введите код товара и нажмите кнопку "Поиск товара"</p></div>
					<div><img src = "images/product1.png" alt="Ввод кода товара"></div>

					<div><p class = "step">Шаг 2. </p><p>Вы получите список оприходований и приемок с данным товаром. Для заполнения ГТД в документах, где он отсутствует, нажмите кнопку "Проставить всем нехватающим". Если нужного ГТД нет, введите его в текстовое поле и снова нажмите "Проставить всем нехватающим"</p></div>
					<div><img src = "images/product2.png" alt="Проставить ГТД всем отсутствующим"></div>
				</div>
			</div>
		</div>
		<footer class="text-light pt-5">
			<div class="container px-5">
				<hr>
				<div class="d-sm-flex justify-content-between py-1">
					<p>© <?echo date('Y')?> Тамара. Все права защищены.</p>
					<a href = "https://npotamara.ru/" target="_blank" ><img src = "images/tamara_res_chb.png" width = "90%"></a>
				</div>
			</div>
		</footer>
	</div>
</body>
