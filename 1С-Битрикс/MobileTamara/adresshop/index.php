<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle("");?><script>
BXMobileApp.UI.Page.TopBar.title.show();
BXMobileApp.UI.Page.TopBar.title.setText('НПО Тамара');
BXMobileApp.UI.Page.TopBar.title.setDetailText('Адреса магазинов');
BXMobileApp.UI.Page.TopBar.title.setImage('<?=SITE_DIR?>MobileTamara/logo.png');
var params = {
	callback:function(){
		app.openNewPage('<?=SITE_DIR?>MobileTamara/personal/cart/');
		},
	type: 'cart'
};
BXMobileApp.UI.Page.TopBar.addRightButton(params);
</script>
<div class="content">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.store",
	"tamara_v2",
	Array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"MAP_TYPE" => "0",
		"PHONE" => "Y",
		"SCHEDULE" => "Y",
		"SEF_FOLDER" => "/MobileTamara/adresshop/",
		"SEF_MODE" => "Y",
		"SEF_URL_TEMPLATES" => Array("element"=>"#store_id#/","liststores"=>"index.php"),
		"SET_TITLE" => "Y",
		"TITLE" => "Список магазинов с подробной информацией"
	)
);?>
</div>
 <br><?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>