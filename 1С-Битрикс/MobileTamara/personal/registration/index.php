<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
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
		"REQUIRED_FIELDS" => array(),
		"SET_TITLE" => "Y",
		"SHOW_FIELDS" => array("NAME","SECOND_NAME","LAST_NAME","PERSONAL_PHONE"),
		"SUCCESS_PAGE" => "/MobileTamara/personal/index.php",
		"USER_PROPERTY" => array("UF_LOYALITY_CARD"),
		"USER_PROPERTY_NAME" => "",
		"USE_BACKURL" => "N"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>