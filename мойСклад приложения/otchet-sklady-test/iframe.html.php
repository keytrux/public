<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Otchet</title>
    <meta name="description" content="Otchet for Marketplace of MoySklad">
    <meta name="author" content="nosov@npotamara.ru">
	<link rel="stylesheet" href="style.css?11" />
	<script>
		let accountId = '<?=$accountId?>', stores = '<?=json_encode($stores->rows)?>';
	</script>
	<script src="script.js?1"></script>
</head>
<body>
<div id="myProgress" style="display: none">
  <div id="myBar"></div>
</div>
<div name="loading"></div>
<div name="status" style="float: right;">
</div>
<div name="listFilter">
	<div style="display: flex;">
		<div>Период</div>
		<div style="margin: 0 0 0 5px;">
			<span class="link" onClick="fillingFunction(this)">вчера</span>
			<span class="link" onClick="fillingFunction(this)">сегодня</span>
			<span class="link" onClick="fillingFunction(this)">неделя</span>
			<span class="link" onClick="fillingFunction(this)">месяц</span>
			<span class="link" onClick="fillingFunction(this)">год</span>
		</div>
		
	</div>
	<div class="rows">
		<input type="date" name="dateStart" class="input date" value="<?=date("Y-m-d")?>" /> <input type="date" name="dateEnd" class="input date" value="<?=date("Y-m-d")?>" />
	</div>
	<div>Склад</div>
	<div class="rows" style="position: relative;">
		<div style="display: flex;">
			<div name="selectedListStore" class="input text" style="width: 204px; border-right: none; border-radius: 3px 0 0 3px"></div>
			<div class="selector" onClick="openFunction(this)" style="border-radius: 0 3px 3px 0;">
				<div class="icon"></div>
			</div>
		</div>
		<div class="absolute" onmouseleave ="closeFunction(this)" style="display: none; border: 1px solid #ccc; width: 253px; box-sizing: border-box; border-top: none; border-radius: 3px;">
			<div style="border-bottom: 1px solid #ccc;"><span class="link" onClick="selectedFunction(this)" style="padding: 5px;">Выбрать всех</span></div>
			<ul name="listStores" class="list" style="max-height: 200px; overflow: auto;"></ul>
		</div>
	</div>
	<div class="rows" style="position: relative; display: flex;">
		<input type="button" value="Среднее" class="input button success" onClick="submit2Function(this)" />&nbsp;<input type="button" value="Получить" class="input button success" onClick="submitFunction(this)" />
		<div style="margin: 0 0 0 5px;">
			<div onClick="optionsFunction(this)" class="options">
				<div class="icon"></div>
			</div>
			<div class="absolute" style="display: none; border: 1px solid #ccc; width: 253px; padding: 5px; box-sizing: border-box; border-radius: 3px;">
				<ul name="listOptions" class="list" style="max-height: 200px; overflow: auto; width: 100%;">
					<li><label class="checkbox" for="check_ot"><input type="checkbox" value="Отгрузка" id="check_ot" onChange="checkFunction(this)" checked /><span>Отгрузка</span></label></li>
					<li><label class="checkbox" for="check_prod"><input type="checkbox" value="Продажи общие" id="check_prod" onChange="checkFunction(this)" /><span>Продажи общие</span></label></li>
					<li><label class="checkbox" for="check_prod_nal"><input type="checkbox" value="Продажи по наличке" id="check_prod_nal" onChange="checkFunction(this)" checked /><span>Продажи по наличке</span></label></li>
					<li><label class="checkbox" for="check_prod_k"><input type="checkbox" value="Продажи по карте" id="check_prod_k" onChange="checkFunction(this)" checked /><span>Продажи по карте</span></label></li>
					<li><label class="checkbox" for="check_voz"><input type="checkbox" value="Возврат общий" id="check_voz" onChange="checkFunction(this)" /><span>Возврат общий</span></label></li>
					<li><label class="checkbox" for="check_voz_nal"><input type="checkbox" value="Возврат по наличке" id="check_voz_nal" onChange="checkFunction(this)" /><span>Возврат по наличке</span></label></li>
					<li><label class="checkbox" for="check_voz_k"><input type="checkbox" value="Возврат по карте" id="check_voz_k" onChange="checkFunction(this)" /><span>Возврат по карте</span></label></li>
					<!--
					<?php if ($viewProductCostAndProfit) { ?>
						<li><label class="checkbox" for="check_seb"><input type="checkbox" value="Себестоимость" id="check_seb" onChange="checkFunction(this)" /><span>Себестоимость</span></label></li>
					<?php } ?>
					-->
				</ul>
			</div>
		</div>
	</div>
</div>
<div name="listResult"></div>
</body>
</html>