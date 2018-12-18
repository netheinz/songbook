<?php
define("DOCROOT", $_SERVER["DOCUMENT_ROOT"]);
define("COREROOT", substr(DOCROOT, 0, strrpos(DOCROOT,"/")) . "/core/");

require_once COREROOT . "functions.php";
require_once COREROOT . "autoload.php";

/**
 * Created by PhpStorm.
 * User: heinz
 * Date: 06/12/2018
 * Time: 20.46
 */
$host = "127.0.0.1";
$user = "heka";
$passwd = "password";
$dbname = "songbook";

try {
	$db  = new PDO( 'mysql:host=' . $host . ';dbname='.$dbname.';charset=utf8;', $user, $passwd);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br />";
	exit();
}