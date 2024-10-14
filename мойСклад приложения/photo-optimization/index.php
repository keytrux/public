<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Инструкция - Оптимизация фото товаров</title> <!-- Отображение заголовка страницы -->
    <link rel="icon" href="images/logo.png">
    <link rel="stylesheet" href="style_manual_v3.css?v=1.1">
	<script src="script_manual.js?v=1.1"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<?
		$filename = 'counter.txt';

		$visits = (int)file_get_contents($filename);

		$visits++;

		file_put_contents($filename, $visits);
	?>
</head>

<body style = "background-color: #e9ecef;">
	<div class="wrapper">
		<div class = "main_first">
			<div class="container-fluid">
				<h1>
					<img src = "images/logo.png" width = "70px"><b>Оптимизация фото товаров</b>
						<!-- <div class="block">
							<div class="preview">Фото хранятся на сервере в течении 3 дней - если возникли вопросы пишите на почту nosova@npotamara.ru</div> -->
							<!-- <img class="icon" src="images/question-circle.svg" alt="? icon"> -->
						<!-- </div>	 -->
				</h1>
				<p><b>Приложение для сжатия фотографий товаров, позволяющее уменьшить размер изображений</b></p>
			</div>

			<!-- Контейнер начало -->
			<div class = "main_container">
				<div>    
					<p class = "step">Шаг 1 </p><p>Выберите необходимые фильтры</p>
				</div>
				<div class="block">
					<div class="preview">	
						Период - период обновления фото в товаре<br>
					</div>
					<img src="images/step1.png" alt="Описание изображения">
				</div>
				<hr>
				<div>    
					<p class = "step">Шаг 2 </p><p>Нажмите кнопку - "Получить список фото"</p>
				</div>
				<div>
					<img src = "images/step2.png">
				</div>
				<hr>
				<div>    
					<p class = "step">Шаг 3 </p><p>Выберите фото которые необходимо сжать (если не выбрано ни одно - выбраны все) и нажмите кнопку - "Сжать фото"</p>
				</div>
				<div>
					<img src = "images/step3.png">
				</div>
				<hr>
				<div>    
					<p class = "step">Шаг 4 </p><p>Готово. Вам выгрузяться фото до сжатия в виде архива .zip</p>
				</div>
				<div>
					<img src = "images/step4.png">
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
