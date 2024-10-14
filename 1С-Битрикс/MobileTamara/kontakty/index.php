<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle("");?><script>
BXMobileApp.UI.Page.TopBar.title.show();
BXMobileApp.UI.Page.TopBar.title.setText('НПО Тамара');
BXMobileApp.UI.Page.TopBar.title.setDetailText('Контакты');
BXMobileApp.UI.Page.TopBar.title.setImage('<?=SITE_DIR?>MobileTamara/logo.png');
var params = {
	callback:function(){
		app.openNewPage('<?=SITE_DIR?>MobileTamara/personal/cart/');
		},
	type: 'cart'
};
BXMobileApp.UI.Page.TopBar.addRightButton(params);
</script>
<h1 class="title_h1">Наши контакты</h1>
 <br>
<div class=" block-left contact_map" style="height:400px">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:map.yandex.view", 
	".default", 
	array(
		"API_KEY" => "2d1f7e9d-99e1-4c95-ae19-ee73ff188bb6",
		"COMPONENT_TEMPLATE" => ".default",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CONTROLS" => array(
			0 => "ZOOM",
			1 => "MINIMAP",
			2 => "TYPECONTROL",
			3 => "SCALELINE",
		),
		"INIT_MAP_TYPE" => "MAP",
		"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:53.55592999999411;s:10:\"yandex_lon\";d:49.44766600000001;s:12:\"yandex_scale\";i:10;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:3:\"LON\";d:49.447601626975;s:3:\"LAT\";d:53.555993883294;s:4:\"TEXT\";s:0:\"\";}}}",
		"MAP_HEIGHT" => "600",
		"MAP_ID" => "tamara_map",
		"MAP_WIDTH" => "600",
		"OPTIONS" => array(
			0 => "ENABLE_SCROLL_ZOOM",
			1 => "ENABLE_DBLCLICK_ZOOM",
			2 => "ENABLE_DRAGGING",
		)
	),
	false
);?>
</div>
<div class="block-left contact_text">
<p>
 <b>Свяжитесь с нами:</b><br>
	<ul style="margin:0 0 0 10px">
		<li>Телефон: (8482) 56-61-33</li>
		<li>Факс: (8482) 20-93-07</li>
		<li><a style="color:#000" href="mailto:mail@npotamara.ru">mail@npotamara.ru</a></li>
	</ul>
</p>
<p>
 <b>Единый телефон поддержки:</b><br>
 <b style="font-size:24px;color:#ff0000">8-800-700-0630</b>
</p>
<p>
<b>Режим работы:</b><br>
Понедельник-пятница с 8:00 до 16:45, обед с 12:00 до 12:45
</p>
<p>
 <b>Наш адрес:</b><br>
г. Тольятти, ул. Новозаводская, 15а
</p>
<img alt="Схема проезда по г.Тольятти" title="Схема проезда" src="/local/templates/tamara/image/proezd.png"> <img alt="Вид на офис" src="/local/templates/tamara/image/office.png">
</div>
<div style="clear: both"></div><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>