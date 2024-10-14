<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>ГТД</title>
    <meta name="description" content="photo-optimization for Marketplace of MoySklad">
    <meta name="author" content="nosova@npotamara.ru">
	  <link rel="stylesheet" href="style_v8.css?11" />
	
	  <script>
		let accountId = "<?=$accountId?>",
			size = <?=$product_size?>,
			product = JSON.parse('<?=$array_product?>'),
			group = JSON.parse('<?=$array_group?>'),
			podgroup = JSON.parse('<?=$array_podgroup?>');
		
	</script>
	  <script src="script_v16.js?v=1.1"></script>
</head>

<body>
	<div name = "get" class = "get">
		<div class = "input-text">
			<div>Код<input type="text" size="25" name="code" id="code" class="code" onChange="codeFunction(this)"></div>
			<div>Название<input type="text" size="50" name="name"  id="name" class="name" onChange="nameFunction(this)"></div>
			<div>Артикул<input type="text" size="25" name="article" id="article" class="article" onChange="articleFunction(this)"></div>
		</div>

		<div class="rows" style="display: flex; align-items: center; margin: 0;">

			<div style="display: flex; align-items: center;">
				<div style="margin-right: 5px;">Период</div>
				<div style="margin-left: 5px;">
					<span class="linkDate" onClick="fillingFunction(this)">вчера</span>
					<span class="linkDate" onClick="fillingFunction(this)">сегодня</span>
					<span class="linkDate" onClick="fillingFunction(this)">неделя</span>
					<span class="linkDate" onClick="fillingFunction(this)">месяц</span>
					<span class="linkDate" onClick="fillingFunction(this)">год</span>
				</div>
			</div>

			<div style="margin: 0 10px 0 20px;">Группа товаров</div>
			<div class="rows" style="position: relative; margin-right: 20px;">
				<div style="display: flex; width: 400px; position: relative;" class="select">
					<div id="selectedListGroup" class="input text" style="width: 374px; border-right: none; border-radius: 3px 0 0 3px"></div>
					<div class="selector" onClick="openFunction(this)" style="border-radius: 0 3px 3px 0;">
        				<div class="icon"></div>
    				</div>
				</div>
				<div onmouseleave="closeFunction(this)" class="absolute" style="display: none; border: 1px solid #ccc; width: 400px; padding: 5px; box-sizing: border-box; border-top: none; border-radius: 3px; position: absolute; top: 100%; left: 0;">
        			<ul id="listGroup" class="list" style="max-height: 130px; overflow: auto; margin: 0; padding: 0;"></ul>
    			</div>
			</div>
		</div>

		<div class="rows"> 
      			<input type="date" name="dateStart" id="dateStart" class="input date" value="" onChange="dateStartFunction(this)" /> 
				<input type="date" name="dateEnd" id="dateEnd" class="input date" value="" onChange="dateEndFunction(this)"/>
    	</div>

		<div>Фото более</div>
			<select id = "select-size-before">
				<option value = "0" selected = "selected">0 КБ</option>
				<option value = "300">300 КБ</option>
				<option value = "500" >500 КБ</option>
				<option value = "1024">1 МБ</option>
				<option value = "1536">1.5 МБ</option>
				<option value = "2048">2 МБ</option>
				<option value = "2560">2.5 МБ</option>
				<option value = "3072">3 МБ</option>
				<option value = "4096">4 МБ</option>
				<option value = "5120">5 МБ</option>
			</select>
		</div>
		<br>
		<button id = "get" onClick="SearchPhoto(100, 0, this)" class = "search">Получить список фото</button>
		<button id = "clear" onClick="Clear(this)" class = "clear">Очистить</button>
	</div>

	<div name = "info-photo" id = "info-photo"></div>
	<div class="loader" id="loader"></div>
	
</body>
</html>