<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/mobileapp/public/.mobile_menu.php");

$arMobileMenuItems = array(
	array(
		"type" => "section",
		"text" => 'Личный кабинет',
		"sort" => "100",
		"items" => array(
			array(
				"text" => 'Личный кабинет',
				"data-url" => SITE_DIR . "MobileTamara/personal/index.php",
				"class" => "",
				"id" => "",

			)
		)
	),
	array(
		"type" => "section",
		"text" => 'Главная страница',
		"sort" => "200",
		"items" => array(
			array(
				"text" => 'Главная страница',
				"data-url" => SITE_DIR . "MobileTamara/index.php",
				"class" => "",
				"id" => "",

			),
			array(
				"text" => 'О компании',
				"data-url" => SITE_DIR . "MobileTamara/about/",
				"class" => "",
				"id" => "",

			),
			array(
				"text" => 'Контакты',
				"data-url" => SITE_DIR . "MobileTamara/kontakty/",
				"class" => "",
				"id" => "",

			)
		)
	),
	array(
		"type" => "section",
		"text" => 'Интернет магазин',
		"sort" => "300",
		"items" => array(
			array(
				"text" => 'Каталог товаров',
				"data-url" => SITE_DIR . "MobileTamara/catalog/",
				"class" => "",
				"id" => "",

			),
			array(
				"text" => 'Акции',
				"data-url" => SITE_DIR . "MobileTamara/action/",
				"class" => "",
				"id" => "",

			),
			array(
				"text" => 'Новости',
				"data-url" => SITE_DIR . "MobileTamara/news/",
				"class" => "",
				"id" => "",

			)
		)
	),
	array(
		"type" => "section",
		"text" => 'Розничные магазины',
		"sort" => "400",
		"items" => array(
			array(
				"text" => 'Адреса магазинов',
				"data-url" => SITE_DIR . "MobileTamara/adresshop/",
				"class" => "",
				"id" => "",

			)
		)
	)
);
?>