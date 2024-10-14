<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle(""); ?><?php
$password = $_GET['password'];
if(!empty($password) && $password == "tamara"){?>
<h1 class="title_h1">Новогоднее поздравление директора</h1>
 <br>
<div style="width: 100%; max-width: 720px;">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:player",
	"",
	Array(
		"ADDITIONAL_FLASHVARS" => "",
		"ADDITIONAL_WMVVARS" => "",
		"ADVANCED_MODE_SETTINGS" => "N",
		"ALLOW_SWF" => "Y",
		"AUTOSTART" => "N",
		"AUTOSTART_ON_SCROLL" => "N",
		"BUFFER_LENGTH" => "10",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CONTROLBAR" => "bottom",
		"CONTROLS_BGCOLOR" => "FFFFFF",
		"CONTROLS_COLOR" => "000000",
		"CONTROLS_OVER_COLOR" => "000000",
		"HEIGHT" => "410",
		"HIGH_QUALITY" => "Y",
		"MUTE" => "N",
		"PATH" => "/upload/1.mp4",
		"PLAYBACK_RATE" => "1",
		"PLAYER_ID" => "",
		"PLAYER_TYPE" => "videojs",
		"PLAYLIST" => "right",
		"PLAYLIST_DIALOG" => "",
		"PLAYLIST_HIDE" => "N",
		"PLAYLIST_NUMBER" => "3",
		"PLAYLIST_PREVIEW_HEIGHT" => "48",
		"PLAYLIST_PREVIEW_WIDTH" => "64",
		"PLAYLIST_SIZE" => "180",
		"PLAYLIST_TYPE" => "xspf",
		"PLUGINS" => array("tweetit-1","fbit-1"),
		"PRELOAD" => "N",
		"PREVIEW" => "",
		"PROVIDER" => "video",
		"REPEAT" => "none",
		"SCREEN_COLOR" => "000000",
		"SHOW_CONTROLS" => "Y",
		"SHOW_DIGITS" => "Y",
		"SHUFFLE" => "N",
		"SIZE_TYPE" => "fluid",
		"SKIN" => "",
		"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
		"START_ITEM" => "1",
		"START_TIME" => "0",
		"STREAMER" => "",
		"TYPE" => "",
		"USE_PLAYLIST" => "N",
		"USE_PLAYLIST_AS_SOURCES" => "N",
		"VOLUME" => "90",
		"WIDTH" => "%",
		"WMODE" => "transparent",
		"WMODE_WMV" => "window"
	)
);?>
</div>
<?
} ?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>