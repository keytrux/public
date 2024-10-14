<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>tseniki</title>
    <meta name="description" content="Tseniki for Marketplace of MoySklad">
    <meta name="author" content="nosov@npotamara.ru">
	<link rel="stylesheet" href="style_v1.css?9" />
	<script>
		
		let account_id = "<?=$account_id?>",
			uid = "<?=$uid?>",
			array_uom = JSON.parse('<?=$array_uom?>'),
			array_country = JSON.parse('<?=$array_country?>'),
			group = JSON.parse('<?=$array_group?>'),
			podgroup = JSON.parse('<?=$array_podgroup?>'),
			
			stores = JSON.parse('<?=$array_stores?>'),
			move = JSON.parse('<?=$array_move?>')
			// oprih = JSON.parse('<?=$array_oprih?>')
			;
		
	</script>
	<script src="script_v4.js?2"></script>
</head>
<body>
	<div class="rows">
		<input type="button" value="Печать" class="input button" onClick="printFunction(this)" />
		
		<span class="loading" style="display: none;"></span>
		<select class="input select" onChange="typeFunction(this)">
			<option value="1">%</option>
			<option value="2">Цена</option>
		</select>
		<input type="text" class="input text" onChange="skidkaFunction(this)" placeholder="Скидка\Цена" />
		<span style="line-height: 26px;">Размер:</span>
		<select class="input select" onChange="sizeFunction(this)">
			<option value="1">1 (37,5x24мм)</option>
			<option value="2">2 (40x47мм)</option>
			<option value="3">3 (56x97мм)</option>
			<option value="4">4 (139x197мм)</option>
			<option value="5">5 (40x47мм)</option>
			<option value="6">6 (56x97мм)</option>
			<option value="7">7 Акционный (69x98мм)</option>
		</select>
		
	</div>
	<div class="rows" style="display: flex; background: #f1f1f1; padding: 10px;">
		<div>
			<div class="rows" style="padding: 9px;"><input type="button" value="Найти" class="input button success" onClick="searchFunction(this)" /> <input type="button" value="Очистить" class="input button" onClick="clearFunction(this)" /></div>
		
			<div class="rows"><div>Код</div><div><input type="text" id="inputCode" onChange="codeFunction(this)" class="input text" /></div></div>
			<div class="rows"><div>Артикул</div><div><input type="text" id="inputAtr" onChange="artFunction(this)" class="input text" /></div></div>
			<div class="rows"><div>Номер перемещения</div><div><select id="selectedListOprih" class="input select" onChange="oprihFunction(this)" ><option></option></select></div></div>
			<div><div>Дата</div><div><input type="date" name="dateIzm" class="input date"  /></div></div>
		</div>
		<div style="margin: 0 0 0 5px;">
			<div class="rows"><div>Название</div><div><input type="text" id="inputName" onChange="nameFunction(this)" style="width: 400px;" class="input text" /></div></div>
			<div class="rows"><div>Штрихкод</div><div><input type="text" id="inputSht" onChange="shtFunction(this)" style="width: 400px;" class="input text" /></div></div>
			<div class="rows">
				<div>Группа</div>
				<div class="rows" style="position: relative;">
					<div style="display: flex;">
						<div id="selectedListGroup" class="input text" style="width: 374px; border-right: none; border-radius: 3px 0 0 3px"></div>
						<div class="selector" onClick="openFunction(this)" style="border-radius: 0 3px 3px 0;">
							<div class="icon"></div>
						</div>
					</div>
					<div onmouseleave="closeFunction(this)" class="absolute" style="display: none; border: 1px solid #ccc; width: 400px; padding: 5px; box-sizing: border-box; border-top: none; border-radius: 3px;">
						<ul id="listGroup" class="list" style="max-height: 300px; overflow: auto;"></ul>
					</div>
				</div>
			</div>
			<div>
				<div>Склад</div>
				<select id="selectedListStore" class="input select" onChange="storeFunction(this)" style="width: 400px;"></select>
			</div>
		</div>
		<div id="listChecked" style="margin: 0 0 0 5px;">
		<p> - При выборе Даты - список товара на который была изменена цена в этот день.</p>
		<p> - При выборе Номера оприходования - список оприходованного товара, с указанием остатка до момента оприходования.</p>
		</div>

	</div>
	<div id = "loading-div" class = "loading-div"></div>
	<div id="listResult">
		
	</div>
</body>
</html>