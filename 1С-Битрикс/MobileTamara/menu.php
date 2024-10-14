<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");?>
<script type="text/javascript">
	app.enableSliderMenu(true);
</script>
<?
$APPLICATION->IncludeComponent(
	'bitrix:mobileapp.menu',
	'mobile',
	array("MENU_FILE_PATH"=>"/MobileTamara/.mobile_menu.php"),
	false,
	Array('HIDE_ICONS' => 'Y'));
?>
</body>
</html>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>