<?php
/**
 * Created by PhpStorm.
 * User: heinz
 * Date: 18/12/2018
 * Time: 22.48
 */

class toolbox {
	private $db;

	public function __construct() {
		global $db;
		$this->db = $db;
	}

	/**
	 * Includes system header for admin pages
	 */
	public static function sysHeader() {
		require_once $_SERVER["DOCUMENT_ROOT"]  . "/assets/incl/header.php";
	}

	/**
	 * Includes system footer for admin pages
	 */
	public static function sysFooter() {
		require_once $_SERVER["DOCUMENT_ROOT"]  . "/assets/incl/footer.php";
	}

	/**
	 * @param $title Navn på admin modul side
	 * @param $subtitle Navn på side mode
	 * @param $arr_buttons Panel af knapper
	 *
	 * @return string Samlet html
	 */
	public static function getAdminHeader($title, $subtitle, $arr_buttons) {
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
	public static function getButton($buttontext, $reference, $type = "button") {
		$txt = "<button type=\"".$type."\" onclick=\"document.location.href='".$reference."'\">" .
		       $buttontext . "</button>\n";
		return $txt;
	}

	/**
	 * @param $link
	 * @param $icon
	 * @param array $attr
	 * @return string
	 */
	static function getIcon($ref, $icon, $attr = array()) {
		$class = isset($attr["class"]) ? $attr["class"] : "icon";
		return "<a class=\"".$class."\" href=\"".$ref."\"><i class=\"fas fa-".$icon."\"></i></a>\n";
	}
}