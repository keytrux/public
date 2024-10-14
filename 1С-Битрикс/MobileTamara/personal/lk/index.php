<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Персональный раздел");
global $USER; 

?><script>
BXMobileApp.UI.Page.TopBar.title.show();
BXMobileApp.UI.Page.TopBar.title.setText('НПО Тамара');
BXMobileApp.UI.Page.TopBar.title.setDetailText('Личный кабинет');
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
</script> 
<? $APPLICATION->SetAdditionalCSS("/MobileTamara/personal/style.css"); ?> <!-- <td colspan="2" class="personal-kart"><a class="link" href="kart/"><span>Моя карта</span></a></td> -->
<table class="personal-table-menu">
<tbody>
<tr>
	<td class="personal-order">
 <a class="link" href="orders/"><img src="/MobileTamara/personal/1.png"> Мои заказы</a>
	</td>
	<td class="personal-cart">
 <a class="link" href="cart/"><img src="/MobileTamara/personal/2.png"> Моя корзина</a>
	</td>
	<td class="personal-profile">
 <a class="link" href="profile/"><img src="/MobileTamara/personal/3.png"> Профиль</a>
	</td>
</tr>
<tr>
	<td colspan="1">
 <a class="link" href="support/">Поддержка</a>
	</td>
	<td>
 <a class="link" href="/MobileTamara/personal/index.php?logout=yes">Выход</a>
	</td>
</tr>
</tbody>
</table>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>