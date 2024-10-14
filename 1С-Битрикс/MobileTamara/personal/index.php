<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Персональный раздел");
global $USER; 
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @var string $strElementEdit */
/** @var string $strElementDelete */
/** @var array $arElementDeleteParams */
/** @var array $arSkuTemplate */

?><script>
BXMobileApp.UI.Page.TopBar.title.show();
BXMobileApp.UI.Page.TopBar.title.setText('НПО Тамара');
BXMobileApp.UI.Page.TopBar.title.setDetailText('Личный кабинет');
BXMobileApp.UI.Page.TopBar.title.setImage('<?=SITE_DIR?>MobileTamara/logo.png');

</script> <?
if($USER->IsAuthorized()):?> <? $APPLICATION->SetAdditionalCSS("/MobileTamara/personal/style.css"); ?> <!-- <td colspan="2" class="personal-kart"><a class="link" href="kart/"><span>Моя карта</span></a></td> --> <!--<td colspan="1">
 <a class="link" href="support/">Поддержка</a> 
	</td> -->
<table class="personal-table-menu">
<tbody>
<tr>
	<td class="personal-profile">
 		<a class="link" href="profile/"><img src="3.png"> Профиль</a>
	</td>
</tr>
<tr>
	<td class="personal-cart">
		<a class="link" href="cart/"><img src="2.png"> Моя корзина</a>
	</td>
</tr>
<tr>
	<td class="personal-order">
 <a class="link" href="orders/"><img src="1.png"> Мои заказы</a>
	</td>


</tr>
<tr>

	<td>
 		<input class="link" type="button" onclick="location.href='/MobileTamara/personal/index.php?logout=yes';" value="Выход">
		
	</td>
</tr>

</tbody>
</table>



 <?else:?> <? ?> <script>
BXMobileApp.UI.Page.TopBar.title.show();
BXMobileApp.UI.Page.TopBar.title.setText('НПО Тамара');
BXMobileApp.UI.Page.TopBar.title.setDetailText('Авторизация');
BXMobileApp.UI.Page.TopBar.title.setImage('<?=SITE_DIR?>MobileTamara/logo.png');

BXMobileApp.UI.Page.TopBar.show({
    hidden_sliding_panel: true,
    scroll_backlash: 20.0,
    buttons: {
        back_button: {
            name: "",
            type: "menu",
            callback: function (){                            
                BXMobileApp.UI.Page.close();
            }
        }
    }
});

</script> <?$APPLICATION->IncludeComponent(
	"bitrix:main.auth.form",
	"tamara",
	Array(
		"AUTH_FORGOT_PASSWORD_URL" => "password_recovery/",
		"AUTH_REGISTER_URL" => "registration/",
		"AUTH_SUCCESS_URL" => "/MobileTamara/personal/index.php",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	)
);?> <?endif;?> <br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>