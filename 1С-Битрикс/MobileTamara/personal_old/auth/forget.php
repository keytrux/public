<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Если забыли пароль");
?><script>
BXMobileApp.UI.Page.TopBar.title.show();
BXMobileApp.UI.Page.TopBar.title.setText('НПО Тамара');
BXMobileApp.UI.Page.TopBar.title.setDetailText('Восстановление пароля');
BXMobileApp.UI.Page.TopBar.title.setImage('<?=SITE_DIR?>MobileTamara/logo.png');
</script>
<?$APPLICATION->IncludeComponent("bitrix:system.auth.forgotpasswd", "", Array(
	"FORGOT_PASSWORD_URL" => "",
		"PROFILE_URL" => "",
		"REGISTER_URL" => "",
		"SHOW_ERRORS" => "N"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>