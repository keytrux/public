<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?><script>
BXMobileApp.UI.Page.TopBar.title.show();
BXMobileApp.UI.Page.TopBar.title.setText('НПО Тамара');
BXMobileApp.UI.Page.TopBar.title.setDetailText('Личный кабинет');
BXMobileApp.UI.Page.TopBar.title.setImage('<?=SITE_DIR?>MobileTamara/logo.png');

</script> <br>
 <br>
<div>
</div>
 <?if($USER->IsAuthorized()):?> <? $APPLICATION->SetAdditionalCSS("/personal/style.css"); ?>
<table class="personal-table-menu">
<tbody>
<tr>
	<td class="personal-order">
 <a class="link" href="orders/"><img src="1.png"> Мои заказы</a>
	</td>
	<td class="personal-cart">
		<a class="link" href="/MobileTamara/personal/cart/"><img src="2.png"> Моя корзина</a>
	</td>
	<td class="personal-profile">
 <a class="link" href="personal.php"><img src="3.png"> Профиль</a>
	</td>
</tr>
<tr>
	<td colspan="2" class="personal-kart">
 <a class="link" href="dc.php">Моя карта</a>
	</td>
	<td>
 <a class="link" href="support/">Поддержка</a>
	</td>
</tr>
<tr>
	<td>
	</td>
	<td>
	</td>
	<td>
		<a class="link" href="/MobileTamara/personal/auth/lk.php?logout=yes">Выход</a>
	</td>
</tr>
</tbody>
</table>
 <?else:?> <?$APPLICATION->IncludeComponent(
	"bitrix:main.auth.form",
	"tamara",
	Array(
		"AUTH_FORGOT_PASSWORD_URL" => "forget.php",
		"AUTH_REGISTER_URL" => "registration.php",
		"AUTH_SUCCESS_URL" => "index.php",
		"FORGOT_PASSWORD_URL" => "forget.php",
		"PROFILE_URL" => "personal.php",
		"REGISTER_URL" => "registration.php",
		"SHOW_ERRORS" => "Y"
	)
);?><br>
 <?endif;?> <br>

 <br>
 <br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>