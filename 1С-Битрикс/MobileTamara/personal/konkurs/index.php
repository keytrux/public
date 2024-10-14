<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle(""); ?><?php
$password = $_GET['password'];
if(!empty($password) && $password == "tamara"){?>
<script>
function size(b){
	if(b.className == "default"){
		b.classList.add("big");
		b.classList.remove("default");
		b.style.cssText = "height: 800px; max-width: 100%;";
	}else{
		b.classList.add("default");
		b.classList.remove("big");
		b.style.cssText = "";
	}
}
</script>
<table width="100%" id="table1">
<tbody>
<tr>
	<td>
		 <h2 class="title_h2">Бугуруслан 1</h2>
	</td>
</tr>
<tr>
	<td><img src="/upload/konkurs/bug1/1.jpg" height="300" onClick="size(this)" class="default" /></td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">Бугульма</h2>
	</td>
</tr>
<tr>
	<td><img src="/upload/konkurs/bugul/1.jpg" height="300" onClick="size(this)" class="default" /></td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">Отдел бухгалтерии</h2>
	</td>
</tr>
<tr>
	<td>
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
		"HEIGHT" => "300",
		"HIGH_QUALITY" => "Y",
		"MUTE" => "N",
		"PATH" => "/upload/konkurs/buh/1.mp4",
		"PLAYBACK_RATE" => "1",
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
		"SIZE_TYPE" => "absolute",
		"SKIN" => "",
		"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
		"START_ITEM" => "1",
		"START_TIME" => "0",
		"STREAMER" => "",
		"TYPE" => "",
		"USE_PLAYLIST" => "N",
		"USE_PLAYLIST_AS_SOURCES" => "N",
		"VOLUME" => "90",
		"WIDTH" => "",
		"WMODE" => "transparent",
		"WMODE_WMV" => "window"
	)
);?>
	</td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">Бузулук 1</h2>
	</td>
</tr>
<tr>
	<td height="300"><img src="/upload/konkurs/buz1/1.jpg" height="300" onClick="size(this)" class="default" /></td>
</tr>
<tr>
	<td><h2 class="title_h2">Бузулук 2</h2></td>
</tr>
<tr>
	<td>
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
		"HEIGHT" => "300",
		"HIGH_QUALITY" => "Y",
		"MUTE" => "N",
		"PATH" => "/upload/konkurs/buz2/1.mp4",
		"PLAYBACK_RATE" => "1",
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
		"SIZE_TYPE" => "absolute",
		"SKIN" => "",
		"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
		"START_ITEM" => "1",
		"START_TIME" => "0",
		"STREAMER" => "",
		"TYPE" => "",
		"USE_PLAYLIST" => "N",
		"USE_PLAYLIST_AS_SOURCES" => "N",
		"VOLUME" => "90",
		"WIDTH" => "",
		"WMODE" => "transparent",
		"WMODE_WMV" => "window"
	)
);?>
	</td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">Чистополь</h2>
	</td>
</tr>
<tr>
	<td><img src="/upload/konkurs/chist/1.jpg" height="300" onClick="size(this)" class="default" /></td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">Елабуга</h2>
	</td>
</tr>
<tr>
	<td>
	<img src="/upload/konkurs/elabuga/1.jpg" height="300" onClick="size(this)" class="default" /> 
	<img src="/upload/konkurs/elabuga/2.jpg" height="300" onClick="size(this)" class="default" /> 
	<img src="/upload/konkurs/elabuga/3.jpg" height="300" onClick="size(this)" class="default" />
	</td>
</tr>
<tr>
	<td><h2 class="title_h2">Главный склад</h2></td>
</tr>
<tr>
	<td>
 <img src="/upload/konkurs/glsklad/1.jpg" height="300" onClick="size(this)" class="default">
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
		"HEIGHT" => "300",
		"HIGH_QUALITY" => "Y",
		"MUTE" => "N",
		"PATH" => "/upload/konkurs/glsklad/2.mp4",
		"PLAYBACK_RATE" => "1",
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
		"SIZE_TYPE" => "absolute",
		"SKIN" => "",
		"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
		"START_ITEM" => "1",
		"START_TIME" => "0",
		"STREAMER" => "",
		"TYPE" => "",
		"USE_PLAYLIST" => "N",
		"USE_PLAYLIST_AS_SOURCES" => "N",
		"VOLUME" => "90",
		"WIDTH" => "",
		"WMODE" => "transparent",
		"WMODE_WMV" => "window"
	)
);?>
	</td>
</tr>
<tr>
	<td><h2 class="title_h2">Громовой</h2></td>
</tr>
<tr>
	<td>
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
		"HEIGHT" => "300",
		"HIGH_QUALITY" => "Y",
		"MUTE" => "N",
		"PATH" => "/upload/konkurs/grom/1.mp4",
		"PLAYBACK_RATE" => "1",
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
		"SIZE_TYPE" => "absolute",
		"SKIN" => "",
		"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
		"START_ITEM" => "1",
		"START_TIME" => "0",
		"STREAMER" => "",
		"TYPE" => "",
		"USE_PLAYLIST" => "N",
		"USE_PLAYLIST_AS_SOURCES" => "N",
		"VOLUME" => "90",
		"WIDTH" => "",
		"WMODE" => "transparent",
		"WMODE_WMV" => "window"
	)
);?>
	</td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">Жигулевск</h2>
	</td>
</tr>
<tr>
	<td><img src="/upload/konkurs/jig/1.jpg" height="300" onClick="size(this)" class="default" /></td>
</tr>
<tr>
	<td><h2 class="title_h2">Кинель</h2></td>
</tr>
<tr>
	<td>
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
		"HEIGHT" => "300",
		"HIGH_QUALITY" => "Y",
		"MUTE" => "N",
		"PATH" => "/upload/konkurs/kinel/1.mp4",
		"PLAYBACK_RATE" => "1",
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
		"SIZE_TYPE" => "absolute",
		"SKIN" => "",
		"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
		"START_ITEM" => "1",
		"START_TIME" => "0",
		"STREAMER" => "",
		"TYPE" => "",
		"USE_PLAYLIST" => "N",
		"USE_PLAYLIST_AS_SOURCES" => "N",
		"VOLUME" => "90",
		"WIDTH" => "",
		"WMODE" => "transparent",
		"WMODE_WMV" => "window"
	)
);?>
	</td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">Коммерческий отдел</h2>
	</td>
</tr>
<tr>
	<td>
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
		"HEIGHT" => "300",
		"HIGH_QUALITY" => "Y",
		"MUTE" => "N",
		"PATH" => "/upload/konkurs/komercheskiy/1.mp4",
		"PLAYBACK_RATE" => "1",
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
		"SIZE_TYPE" => "absolute",
		"SKIN" => "",
		"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
		"START_ITEM" => "1",
		"START_TIME" => "0",
		"STREAMER" => "",
		"TYPE" => "",
		"USE_PLAYLIST" => "N",
		"USE_PLAYLIST_AS_SOURCES" => "N",
		"VOLUME" => "90",
		"WIDTH" => "",
		"WMODE" => "transparent",
		"WMODE_WMV" => "window"
	)
);?>
	</td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">Комсомольская</h2>
	</td>
</tr>
<tr>
	<td><img src="/upload/konkurs/koms/1.jpg" height="300" onClick="size(this)" class="default" /></td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">Ковка</h2>
	</td>
</tr>
<tr>
	<td>
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
		"HEIGHT" => "300",
		"HIGH_QUALITY" => "Y",
		"MUTE" => "N",
		"PATH" => "/upload/konkurs/kovka/1.mp4",
		"PLAYBACK_RATE" => "1",
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
		"SIZE_TYPE" => "absolute",
		"SKIN" => "",
		"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
		"START_ITEM" => "1",
		"START_TIME" => "0",
		"STREAMER" => "",
		"TYPE" => "",
		"USE_PLAYLIST" => "N",
		"USE_PLAYLIST_AS_SOURCES" => "N",
		"VOLUME" => "90",
		"WIDTH" => "",
		"WMODE" => "transparent",
		"WMODE_WMV" => "window"
	)
);?>
	</td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">Лениногорск</h2>
	</td>
</tr>
<tr>
	<td><img src="/upload/konkurs/len/1.jpg" height="300" onClick="size(this)" class="default" /></td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">Ленина</h2>
	</td>
</tr>
<tr>
	<td>
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
		"HEIGHT" => "300",
		"HIGH_QUALITY" => "Y",
		"MUTE" => "N",
		"PATH" => "/upload/konkurs/lenina/1.mp4",
		"PLAYBACK_RATE" => "1",
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
		"SIZE_TYPE" => "absolute",
		"SKIN" => "",
		"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
		"START_ITEM" => "1",
		"START_TIME" => "0",
		"STREAMER" => "",
		"TYPE" => "",
		"USE_PLAYLIST" => "N",
		"USE_PLAYLIST_AS_SOURCES" => "N",
		"VOLUME" => "90",
		"WIDTH" => "",
		"WMODE" => "transparent",
		"WMODE_WMV" => "window"
	)
);?>
	</td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">Отдел маркетинга</h2>
	</td>
</tr>
<tr>
	<td>
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
		"HEIGHT" => "300",
		"HIGH_QUALITY" => "Y",
		"MUTE" => "N",
		"PATH" => "/upload/konkurs/mark/1.mp4",
		"PLAYBACK_RATE" => "1",
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
		"SIZE_TYPE" => "absolute",
		"SKIN" => "",
		"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
		"START_ITEM" => "1",
		"START_TIME" => "0",
		"STREAMER" => "",
		"TYPE" => "",
		"USE_PLAYLIST" => "N",
		"USE_PLAYLIST_AS_SOURCES" => "N",
		"VOLUME" => "90",
		"WIDTH" => "",
		"WMODE" => "transparent",
		"WMODE_WMV" => "window"
	)
);?>
	</td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">Мира</h2>
	</td>
</tr>
<tr>
	<td><img src="/upload/konkurs/mira/1.jpg" height="300" onClick="size(this)" class="default" /></td>
</tr>
<tr>
	<td><h2 class="title_h2">Нижникамск</h2></td>
</tr>
<tr>
	<td>
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
		"HEIGHT" => "300",
		"HIGH_QUALITY" => "Y",
		"MUTE" => "N",
		"PATH" => "/upload/konkurs/nij/1.mp4",
		"PLAYBACK_RATE" => "1",
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
		"SIZE_TYPE" => "absolute",
		"SKIN" => "",
		"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
		"START_ITEM" => "1",
		"START_TIME" => "0",
		"STREAMER" => "",
		"TYPE" => "",
		"USE_PLAYLIST" => "N",
		"USE_PLAYLIST_AS_SOURCES" => "N",
		"VOLUME" => "90",
		"WIDTH" => "",
		"WMODE" => "transparent",
		"WMODE_WMV" => "window"
	)
);?>
	</td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">Новотроицк</h2>
	</td>
</tr>
<tr>
	<td><img src="/upload/konkurs/nov/1.jpg" height="300" onClick="size(this)" class="default" /></td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">Оренбург 1</h2>
	</td>
</tr>
<tr>
	<td><img src="/upload/konkurs/or1/1.jpg" height="300" onClick="size(this)" class="default" />
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
		"HEIGHT" => "300",
		"HIGH_QUALITY" => "Y",
		"MUTE" => "N",
		"PATH" => "/upload/konkurs/or1/2.mp4",
		"PLAYBACK_RATE" => "1",
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
		"SIZE_TYPE" => "absolute",
		"SKIN" => "",
		"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
		"START_ITEM" => "1",
		"START_TIME" => "0",
		"STREAMER" => "",
		"TYPE" => "",
		"USE_PLAYLIST" => "N",
		"USE_PLAYLIST_AS_SOURCES" => "N",
		"VOLUME" => "90",
		"WIDTH" => "",
		"WMODE" => "transparent",
		"WMODE_WMV" => "window"
	)
);?>
	</td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">Орск</h2>
	</td>
</tr>
<tr>
	<td><img src="/upload/konkurs/orsk/1.jpg" height="300" onClick="size(this)" class="default" /></td>
</tr>
<tr>
	<td><h2 class="title_h2">Сызрань 1</h2></td>
</tr>
<tr>
	<td><img src="/upload/konkurs/siz1/1.jpg" height="300" onClick="size(this)" class="default" /></td>
</tr>
<tr>
	<td><h2 class="title_h2">Сызрань 2</h2></td>
</tr>
<tr>
	<td><img src="/upload/konkurs/siz2/1.jpg" height="300" onClick="size(this)" class="default" /></td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">Склад брака</h2>
	</td>
</tr>
<tr>
	<td><img src="/upload/konkurs/skldbrak/1.jpg" height="300" onClick="size(this)" class="default" /></td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">Сорочинск</h2>
	</td>
</tr>
<tr>
	<td><img src="/upload/konkurs/sor/1.jpg" height="300" onClick="size(this)" class="default" />
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
		"HEIGHT" => "300",
		"HIGH_QUALITY" => "Y",
		"MUTE" => "N",
		"PATH" => "/upload/konkurs/sor/2.mp4",
		"PLAYBACK_RATE" => "1",
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
		"SIZE_TYPE" => "absolute",
		"SKIN" => "",
		"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
		"START_ITEM" => "1",
		"START_TIME" => "0",
		"STREAMER" => "",
		"TYPE" => "",
		"USE_PLAYLIST" => "N",
		"USE_PLAYLIST_AS_SOURCES" => "N",
		"VOLUME" => "90",
		"WIDTH" => "",
		"WMODE" => "transparent",
		"WMODE_WMV" => "window"
	)
);?>
	</td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">ТСЗ</h2>
	</td>
</tr>
<tr>
	<td><img src="/upload/konkurs/tsz/1.jpg" height="300" onClick="size(this)" class="default" /></td>
</tr>
<tr>
	<td>
		 <h2 class="title_h2">Юбилейная</h2>
	</td>
</tr>
<tr>
	<td>
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
		"HEIGHT" => "300",
		"HIGH_QUALITY" => "Y",
		"MUTE" => "N",
		"PATH" => "/upload/konkurs/ub/1.mp4",
		"PLAYBACK_RATE" => "1",
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
		"SIZE_TYPE" => "absolute",
		"SKIN" => "",
		"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
		"START_ITEM" => "1",
		"START_TIME" => "0",
		"STREAMER" => "",
		"TYPE" => "",
		"USE_PLAYLIST" => "N",
		"USE_PLAYLIST_AS_SOURCES" => "N",
		"VOLUME" => "90",
		"WIDTH" => "",
		"WMODE" => "transparent",
		"WMODE_WMV" => "window"
	)
);?>
	</td>
</tr>
</tbody>
</table>
	<?php
}
?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>