<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Мои заказы");
global $USER;
if(!$USER->IsAuthorized()) header("Location: /personal/");
?><script>
BXMobileApp.UI.Page.TopBar.title.show();
BXMobileApp.UI.Page.TopBar.title.setText('НПО Тамара');
BXMobileApp.UI.Page.TopBar.title.setDetailText('Мои заказы');
BXMobileApp.UI.Page.TopBar.title.setImage('<?=SITE_DIR?>MobileTamara/logo.png');
</script>
<?$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.order",
	"tamara",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ALLOW_INNER" => "N",
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CUSTOM_SELECT_PROPS" => array(""),
		"DETAIL_HIDE_USER_INFO" => array("0"),
		"DISALLOW_CANCEL" => "N",
		"HISTORIC_STATUSES" => array("F"),
		"NAV_TEMPLATE" => "",
		"ONLY_INNER_FULL" => "N",
		"ORDERS_PER_PAGE" => "20",
		"ORDER_DEFAULT_SORT" => "ACCOUNT_NUMBER",
		"PATH_TO_BASKET" => "/MobileTamara/personal/cart",
		"PATH_TO_CATALOG" => "/MobileTamara/catalog/",
		"PATH_TO_PAYMENT" => "/MobileTamara/personal/order/payment/",
		"PROP_1" => array(),
		"PROP_2" => array(),
		"PROP_3" => array(),
		"REFRESH_PRICES" => "N",
		"RESTRICT_CHANGE_PAYSYSTEM" => array("0"),
		"SAVE_IN_SESSION" => "N",
		"SEF_MODE" => "N",
		"SET_TITLE" => "Y",
		"STATUS_COLOR_F" => "gray",
		"STATUS_COLOR_N" => "green",
		"STATUS_COLOR_P" => "yellow",
		"STATUS_COLOR_PSEUDO_CANCELLED" => "red",
		"STATUS_COLOR_R" => "gray",
		"STATUS_COLOR_S" => "gray"
	)
);?><? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>