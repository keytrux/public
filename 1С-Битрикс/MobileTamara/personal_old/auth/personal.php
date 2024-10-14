<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
?><script>
BXMobileApp.UI.Page.TopBar.title.show();
BXMobileApp.UI.Page.TopBar.title.setText('НПО Тамара');
BXMobileApp.UI.Page.TopBar.title.setDetailText('Персональная информация');
BXMobileApp.UI.Page.TopBar.title.setImage('<?=SITE_DIR?>MobileTamara/logo.png');

</script>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.profile",
	"tamara",
	Array(
		"CHECK_RIGHTS" => "N",
		"SEND_INFO" => "N",
		"SET_TITLE" => "Y",
		"USER_PROPERTY" => array("UF_LOYALITY_CARD"),
		"USER_PROPERTY_NAME" => ""
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>