<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Восстановление пароля");
?><script>
BXMobileApp.UI.Page.TopBar.title.show();
BXMobileApp.UI.Page.TopBar.title.setText('НПО Тамара');
BXMobileApp.UI.Page.TopBar.title.setDetailText('Восстановление пароля');
BXMobileApp.UI.Page.TopBar.title.setImage('<?=SITE_DIR?>MobileTamara/logo.png');
</script> <?$APPLICATION->IncludeComponent(
	"bitrix:main.auth.forgotpasswd",
	"tamara",
	Array(
		"AUTH_AUTH_URL" => "/MobileTamara/personal/",
		"AUTH_REGISTER_URL" => "/MobileTamara/personal/registration/",
		"FORDOT_PASSWORD_URL" => "",
		"PROFILE_URL" => "",
		"REGISTER_URL" => "",
		"SHOW_ERRORS" => "Y"
	)
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>