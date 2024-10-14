<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
?><script>
BXMobileApp.UI.Page.TopBar.title.show();
BXMobileApp.UI.Page.TopBar.title.setText('НПО Тамара');
BXMobileApp.UI.Page.TopBar.title.setDetailText('Регистрация');
BXMobileApp.UI.Page.TopBar.title.setImage('<?=SITE_DIR?>MobileTamara/logo.png');

</script>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.register",
	"tamara",
	Array(
		"AUTH" => "Y",
		"REQUIRED_FIELDS" => array("EMAIL"),
		"SET_TITLE" => "Y",
		"SHOW_FIELDS" => array("EMAIL","NAME","SECOND_NAME","LAST_NAME"),
		"SUCCESS_PAGE" => "personal.php",
		"USER_PROPERTY" => array("UF_LOYALITY_CARD"),
		"USER_PROPERTY_NAME" => "",
		"USE_BACKURL" => "Y"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>