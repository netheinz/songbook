<?php

function sysHeader() {
	require_once $_SERVER["DOCUMENT_ROOT"]  . "/assets/incl/header.php";
}

function sysFooter() {
	require_once $_SERVER["DOCUMENT_ROOT"]  . "/assets/incl/footer.php";
}

/**
 * @param $title Navn på admin modul side
 * @param $subtitle Navn på side mode
 * @param $arr_buttons Panel af knapper
 *
 * @return string Samlet html
 */
function getAdminHeader($title, $subtitle, $arr_buttons) {
	$txt = "<section class=\"adminpanel\">\n" .
	       "   <div>\n" .
	       "      <h2>" . $title . "</h2>\n" .
	       "      <h4>" . $subtitle . "</h4>\n" .
	       "   </div>\n" .
	       "   <div>\n";
	foreach ($arr_buttons as $item) {
		$txt .= $item;
	}
	$txt .= "   <div>\n</section>\n";

	return $txt;
}

/**
 * @param $buttontext
 * @param $reference
 * @param string $type
 *
 * @return string
 */
function getButton($buttontext, $reference, $type = "button") {
	$txt = "<button type=\"".$type."\" onclick=\"document.location.href='".$reference."'\">" .
	            $buttontext . "</button>\n";
	return $txt;
}

