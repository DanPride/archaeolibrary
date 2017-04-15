<?php
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot . "functions.inc.php");
require_once($theRoot . "functions.php");
require_once($theRoot . "XmlSerializer.class.php");
require_once(dirname(__FILE__) . "/Square.php");
require_once(dirname(__FILE__) . "/Loca.php");
require_once(dirname(__FILE__) . "/Basket.php");
require_once(dirname(__FILE__) . "/Locus.php");
require_once(dirname(__FILE__) . "/Object.php");
require_once(dirname(__FILE__) . "/Photo.php");
require_once(dirname(__FILE__) . "/Period.php");
require_once(dirname(__FILE__) . "/GPS.php");

//*********************New Formats that Toggle Search for Each Table needs debugging perhaps
function rowCount() 
{
$ret = array(
	"data" => array("error" => "No operation"), 
	"metadata" => array()
);
}

if ($conn === false) 
{
	$ret = array(
		"data" => array("error" => "database connection error, please check your settings !"), 
		"metadata" => array()
	);
} else {
	mysql_select_db($database_conn, $conn);
	switch (@$_REQUEST["method"]) 
	{
		case "findBaskets":
			$ret = findBaskets();
		break;
		case "insertBasket":
			$ret = insertBasket();
		break;
		case "updateBasket":
			$ret = updateBasket();
		break;
		case "deleteBasket":
			$ret = deleteBasket();
		break;
		case "findLoca":
			$ret = findLoca();
		break;
		case "insertLoca": 
			$ret = insertLoca();
		break;
		case "updateLoca": 
			$ret = updateLoca();
		break;
		case "deleteLoca": 
			$ret = deleteLoca();
		break;
		case "findLocus":
			$ret = findLocus();
		break;
		case "insertLocus": 
			$ret = insertLocus();
		break;
		case "updateLocus": 
			$ret = updateLocus();
		break;
		case "deleteLocus": 
			$ret = deleteLocus();
		break;
		case "findObjects":
			$ret = findObjects();
		break;
		case "insertObject":
			$ret = insertObject();
		break;
		case "updateObject":
			$ret = updateObject();
		break;
		case "deleteObject":
			$ret = deleteObject();
		break;
		case "findPhotos":
			$ret = findPhotos();
		break;
		case "findPeriods":
			$ret = findPeriods();
		break;
		case "findSquares":
			$ret = findSquares();
		break;
		case "insertSquare":
			$ret = insertSquare();
		break;
		case "updateSquare":
			$ret = updateSquare();
		break;
		case "deleteSquare":
			$ret = deleteSquare();
		break;
		case "findFields":
			$ret = findFields();
		break;
		case "Visitor":
			$ret = visitor();
		break;
		case "Flex_confirmLogin":
			$ret = flex_confirmLogin();
		break;
		case "FindUsers":
			$ret = findUsers();
		break;
		case "Flex_LogIn":
			$ret = flex_LogIn();
		break;
		case "FindGPS": 
			$ret = findGPS();
		break;
		default:
			$ret = findBaskets();
			break;
			
	}
}

$serializer = new XmlSerializer();
echo $serializer->serialize($ret);
die();
?>