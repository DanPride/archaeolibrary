<?php

$thePhpPathLength = strlen(dirname(__FILE__)); //length of path
$theAppPath = substr(dirname(__FILE__), 0 , $thePhpPathLength -4);
$theAppPathLength =strlen($theAppPath);
$st = strlen(strrchr($theAppPath,"/")); //length last folder
$theWebRoot =  substr($theAppPath, 0, $theAppPathLength - $st);
$TheIncludesFolder = $theWebRoot . "/includes/";
$theImageFolder = $theWebRoot . "/images/";
require_once($TheIncludesFolder . "Connection.php");
require_once($TheIncludesFolder . "functions.inc.php");
require_once($TheIncludesFolder . "functions.php");
require_once($TheIncludesFolder . "XmlSerializer.class.php");

$filter_field = "Basket";
$filter_type = "text";

function findObjects() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Name','Field','Square','Locus','Basket','Period','Quantity','Saved','Disposition','Description','Type','Comments');
	$where = "";
	if (@$_REQUEST['filter'] != "") {
		$where = "WHERE " . 'Basket' . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], $filter_type);	
	}
	$order = "";
	if (@$_REQUEST["orderField"] != "" && in_array(@$_REQUEST["orderField"], $fields)) {
		$order = "ORDER BY " . @$_REQUEST["orderField"] . " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
	}	
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Objects` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];
	$pageNum = (int)@$_REQUEST["pageNum"];
	$pageSize = (int)@$_REQUEST["pageSize"];
	$start = $pageNum * $pageSize;
	$query_recordset = "SELECT Id,DFC,DLC,User,Name,Field,Square,Locus,Basket,Period,Quantity,Saved,Disposition,Description,Type,Comments FROM `Objects` $where $order";
	if ($pageNum >= 0 && $pageSize > 0) {	
		$query_recordset = sprintf("%s LIMIT %d, %d", $query_recordset, $start, $pageSize);
	}
	$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		$row_recordset['ShortName'] =  substr($row_recordset['Name'], 9);
		array_push($toret, $row_recordset);
	}
	$toret = array(
		"data" => $toret, 
		"metadata" => array (
			"totalRows" => $totalrows,
			"pageNum" => $pageNum
		)
	);

	return $toret;
}

function findReads() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','DFC','DLC','User','Name','Field','Square','Locus','Basket','Periods','Context','Disposition','Status');
	$searchType = (@$_REQUEST["searchType"]);
	$basketNum = (@$_REQUEST["basketNum"]);
	$where = "";
	if($searchType == "READ")
	{
			$where = "WHERE " . "Status" . " LIKE " . GetSQLValueStringForSelect("READ", $filter_type);	
	} else
	{
			$where = "WHERE " . "Basket" . " LIKE " . GetSQLValueStringForSelect($basketNum, $filter_type);	
	}
	
	$order = "";
	if (@$_REQUEST["orderField"] != "" && in_array(@$_REQUEST["orderField"], $fields)) {
		$order = "ORDER BY " . @$_REQUEST["orderField"] . " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
	}
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Pottery` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];
	$pageNum = (int)@$_REQUEST["pageNum"];
	$pageSize = (int)@$_REQUEST["pageSize"];
	$start = $pageNum * $pageSize;
	$query_recordset = "SELECT Id,DFC,DLC,User,Name,Field,Square,Locus,Basket,Periods,Context,Disposition,Status FROM `Pottery` $where $order";
	if ($pageNum >= 0 && $pageSize > 0) {	
		$query_recordset = sprintf("%s LIMIT %d, %d", $query_recordset, $start, $pageSize);
	}
	$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		array_push($toret, $row_recordset);
	}
	$toret = array(
		"data" => $toret, 
		"metadata" => array (
			"totalRows" => $totalrows,
			"pageNum" => $pageNum
		)
	);
	return $toret;
}

function findParts() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Part');
	$where = "";
//	$where = "WHERE " . "Part" . " LIKE " . GetSQLValueStringForSelect("%", $filter_type);	
	$order = "";
	if (@$_REQUEST["orderField"] != "" && in_array(@$_REQUEST["orderField"], $fields)) 
	{
		$order = "ORDER BY " . @$_REQUEST["orderField"] . " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
	}
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Parts` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];
	$pageNum = (int)@$_REQUEST["pageNum"];
	$pageSize = (int)@$_REQUEST["pageSize"];
	$start = $pageNum * $pageSize;
	$query_recordset = "SELECT Id,Part  FROM `Parts` $where $order";
	if ($pageNum >= 0 && $pageSize > 0) {	
		$query_recordset = sprintf("%s LIMIT %d, %d", $query_recordset, $start, $pageSize);
	}
	$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		array_push($toret, $row_recordset);
	}
	$toret = array(
		"data" => $toret, 
		"metadata" => array (
			"totalRows" => $totalrows,
			"pageNum" => $pageNum
		)
	);
	return $toret;
}

function findPeriods() {
	global $conn, $filter_field, $filter_type;
	$fields = array('ListOrder','Visible','Period','Code','SubPeriod');
	$where = "";
	$where = "WHERE " . "Visible" . " LIKE " . GetSQLValueStringForSelect("Visible", $filter_type);	
	$order = "";
	if (@$_REQUEST["orderField"] != "" && in_array(@$_REQUEST["orderField"], $fields)) 
	{
		$order = "ORDER BY " . @$_REQUEST["orderField"] . " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
	}
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Periods` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];
	$pageNum = (int)@$_REQUEST["pageNum"];
	$pageSize = (int)@$_REQUEST["pageSize"];
	$start = $pageNum * $pageSize;
	$query_recordset = "SELECT ListOrder,Visible,Period,Code,SubPeriod FROM `Periods` $where $order";
	if ($pageNum >= 0 && $pageSize > 0) {	
		$query_recordset = sprintf("%s LIMIT %d, %d", $query_recordset, $start, $pageSize);
	}
	$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		array_push($toret, $row_recordset);
	}
	$toret = array(
		"data" => $toret, 
		"metadata" => array (
			"totalRows" => $totalrows,
			"pageNum" => $pageNum
		)
	);
	return $toret;
}


function findVessels() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Vessel','Type');
	$where = "";
	$where = "WHERE " . "Type" . " LIKE " . GetSQLValueStringForSelect("Pottery", $filter_type);	
	$order = "";
	if (@$_REQUEST["orderField"] != "" && in_array(@$_REQUEST["orderField"], $fields)) 
	{
		$order = "ORDER BY " . @$_REQUEST["orderField"] . " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
	}
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Vessels` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];
	$pageNum = (int)@$_REQUEST["pageNum"];
	$pageSize = (int)@$_REQUEST["pageSize"];
	$start = $pageNum * $pageSize;
	$query_recordset = "SELECT Id,Vessel  FROM `Vessels` $where $order";
	if ($pageNum >= 0 && $pageSize > 0) {	
		$query_recordset = sprintf("%s LIMIT %d, %d", $query_recordset, $start, $pageSize);
	}
	$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		array_push($toret, $row_recordset);
	}
	$toret = array(
		"data" => $toret, 
		"metadata" => array (
			"totalRows" => $totalrows,
			"pageNum" => $pageNum
		)
	);
	return $toret;
}

function rowCount() {
	global $conn, $filter_field, $filter_type;

	$where = "";
	if (@$_REQUEST['filter'] != "") {
		$where = "WHERE " . $filter_field . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], $filter_type);	
	}

	//calculate the number of rows in this table
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Objects` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];
	
	//create the standard response structure
	$toret = array(
		"data" => $totalrows, 
		"metadata" => array()
	);

	return $toret;
}

function returnNumber($Number)
	{
		if($Number>9) {
			settype($Number, string);
			return $Number;
		} else {
			settype($Number, string);
			return "0" . $Number;
		}
	}
	
function AssignObjectName($Bucket) {
   	global $conn;
	$Count = 0;
	$duptest = true;
	$query = sprintf("SELECT Name FROM Pottery WHERE Basket = '{$Bucket}'");
	$recordSetBucket = mysql_query($query, $conn);
	while ($row_recordset = mysql_fetch_assoc($recordSetBucket)) {
		$BucketName = $row_recordset["Name"];
	}	
	$potQuery = sprintf("SELECT * FROM Objects WHERE Basket = '{$Bucket}'"); //Get Pottery matches for each Loca
	$result = mysql_query($potQuery, $conn);
	$Count = mysql_num_rows($result);
	mysql_free_result($result);
	while($duptest == true)
	{
	$Count = $Count + 1;
	$Name = $BucketName . "-" . returnNumber($Count);
	$queryDupTest = sprintf("SELECT * FROM Objects WHERE Name = '{$Name}'"); //test for duplicate
	$dupResult = mysql_query($queryDupTest, $conn);
	$DupCount = mysql_num_rows($dupResult);
	if($DupCount == 0){
		$duptest = false;
		return $Name;	
		}
	}
}

function insertBasket() {
	global $conn, $theImageFolder;
	$Sequence = 1;
	$theSelectedCamera = $_REQUEST["Camera"];
	$theName = AssignObjectName($_REQUEST["Basket"]);
	$Sequenceplace = strpos($theName, '-')+1;
	$Sequence = substr($theName, $Sequenceplace);
	$theFileName = $theName . ".JPG";
	$ShortName = substr($theName,9);	
	$theFolder = substr($theName,0,6);
	$baseImagePath = $theImageFolder . $theFolder . "/";
	$thewebImagePath = $theImageFolder . $theFolder . "/000Pic/";
	$thethumbImagePath = $theImageFolder . $theFolder . "/000Tmb/";
	if (!is_dir($baseImagePath)) mkdir($baseImagePath, 0777); 
	if (!is_dir($thewebImagePath)) mkdir($thewebImagePath, 0777); 
	if (!is_dir($thethumbImagePath)) mkdir($thethumbImagePath, 0777);
	$theImagePathName = $baseImagePath . $theFileName;

	//build and execute the insert query
	$query_insert = sprintf("INSERT INTO `Objects` (DFC,DLC,User,Sequence,Name,Field,Square,Locus,Basket,PeriodCode,Period,Quantity,Saved,Disposition,Description,Type,Comments) VALUES (now(),now(),%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)" ,
			GetSQLValueString($_SESSION['Name'], "text"), # 
			GetSQLValueString($Sequence, "text"), # 	
			GetSQLValueString($theName, "text"), # 		
			GetSQLValueString($_REQUEST["Field"], "text"), # 
			GetSQLValueString($_REQUEST["Square"], "text"), # 
			GetSQLValueString($_REQUEST["Locus"], "text"), # 
			GetSQLValueString($_REQUEST["Basket"], "text"), # 
			GetSQLValueString($_REQUEST["PeriodCode"], "text"), # 
			GetSQLValueString($_REQUEST["Period"], "text"), # 
			GetSQLValueString($_REQUEST["Quantity"], "text"), # 
			GetSQLValueString($_REQUEST["Saved"], "text"), # 
			GetSQLValueString($_REQUEST["Disposition"], "text"), # 
			GetSQLValueString($_REQUEST["Description"], "text"), # 
			GetSQLValueString($_REQUEST["Type"], "text"), # 
			GetSQLValueString($_REQUEST["Comments"], "text") # 				
	);
	$ok = mysql_query($query_insert);
	if (!$ok){
		// we had an error, return it
		$toret = array(
			"data" => array("error" => mysql_error()), 
			"metadata" => array()
		);
		return $toret;
	}
	//If Text was ok then insert photo
	if($theSelectedCamera > 0){
	$fp = fopen("{$theImagePathName}","w");
	$yava = $_REQUEST["Image"];
	$data = base64_decode($yava);
    $im = imagecreatefromstring($data);
 	fwrite( $fp, $data);
   	fclose($fp);
	$query_insert = sprintf("INSERT INTO `Photos` (DFC,DLC,User,Name,Field,Square,Locus,Basket,Object,Description,FolderName,FileName,Comments,Category) VALUES 	(now(),now(),%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)" ,			
		GetSQLValueString($_SESSION['Name'], "text"), # 
		GetSQLValueString($theName, "text"), # 
		GetSQLValueString($_REQUEST["Field"], "text"), # 
		GetSQLValueString($_REQUEST["Square"], "text"), # 
		GetSQLValueString($_REQUEST["Locus"], "text"), # 
		GetSQLValueString($_REQUEST["Basket"], "text"), # 
		GetSQLValueString($_REQUEST["Object"], "text"), # 
		GetSQLValueString($_REQUEST["Description"], "text"), # 
		GetSQLValueString($theFolder, "text"), # 
		GetSQLValueString($theFileName, "text"), # 
		GetSQLValueString($_REQUEST["Comments"], "text"), # 
		GetSQLValueString("Formal", "text")# 
		);
	$ok = mysql_query($query_insert);
	if (!$ok) {
		// we had an error, return it
		$toret = array(
			"data" => array("error" => mysql_error()), 
			"metadata" => array()
			);
		return $toret;
	}

	$theImage = $baseImagePath . $theFileName; // 
	$theImageOut = $thethumbImagePath . $theFileName; // 
	$theBigImageOut = $thewebImagePath . $theFileName; // 	
	$theImageInfo = getimagesize($theImage);
	$theImageX = $theImageInfo[0];
	$theImageY = $theImageInfo[1];
	$theNewX = 200; 
	$theBigX = 1024;
	if(is_numeric($theImageX))
	{
		if($theImageX>0){
		$theImageRatio = "{$theImageY}"/"{$theImageX}";	
		$theNewY = 150 * ($theImageRatio/0.75);
		$theBigY = 768 * ($theImageRatio/0.75);
		} else 
		{
			$theNewY = 150;
			$theBigY = 768;
		}
	
	} else
	{
		$theNewY = 150;	
		$theBigY = 768;
	}
	$theNewImage = imagecreatetruecolor($theNewX, $theNewY);
	$image = imagecreatefromjpeg($theImage);
	imagecopyresampled($theNewImage, $image, 0, 0, 0, 0, $theNewX, $theNewY, $theImageX, $theImageY);
	$result = imagejpeg($theNewImage, $theImageOut, 75);
	//thebig one
	$theBigImage = imagecreatetruecolor($theBigX, $theBigY);
	$bigimage = imagecreatefromjpeg($theImage);
	imagecopyresampled($theBigImage, $bigimage, 0, 0, 0, 0, $theBigX, $theBigY, $theImageX, $theImageY);
	$result = imagejpeg($theBigImage, $theBigImageOut, 75);
	imagedestroy($theNewImage);
	imagedestroy($image);
	imagedestroy($theBigImage);
	imagedestroy($bigimage);
	}
}

function clearBasket() 
{
	global $conn;
		$query_update = sprintf("UPDATE `Pottery` SET Status = 'Done' WHERE Basket = %s", 
			GetSQLValueString($_REQUEST["Basket"], "text") 
		);
		$ok = mysql_query($query_update);
		if ($ok) 
		{
			findReads();
		}
}


function update() {
	global $conn;

	// check to see if the record actually exists in the database
	$query_recordset = sprintf("SELECT * FROM `Objects` WHERE Id = %s",
		GetSQLValueString($_REQUEST["Id"], "int")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);
	
	if ($num_rows > 0) {

		// build and execute the update query
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_update = sprintf("UPDATE `Objects` SET DLC = now(),User = %s,Sequence = %s,Name = %s,Field = %s,Square = %s,Locus = %s,Basket = %s,Period = %s,Quantity = %s,Description = %s,Type = %s,Comments = %s WHERE Id = %s", 
			GetSQLValueString($_SESSION['Name'], "text"), 
			GetSQLValueString($Sequence, "int"), 
			GetSQLValueString($_REQUEST["Name"], "text"), 
			GetSQLValueString($_REQUEST["Field"], "text"), 
			GetSQLValueString($_REQUEST["Square"], "text"), 
			GetSQLValueString($_REQUEST["Locus"], "text"), 
			GetSQLValueString($_REQUEST["Basket"], "text"), 
			GetSQLValueString($_REQUEST["Period"], "text"), 
			GetSQLValueString($_REQUEST["Quantity"], "int"), 
			GetSQLValueString($_REQUEST["Description"], "text"), 
			GetSQLValueString($_REQUEST["Type"], "text"), 
			GetSQLValueString($_REQUEST["Comments"], "text"), 
			GetSQLValueString($row_recordset["Id"], "int")
		);
		$ok = mysql_query($query_update);
		if ($ok) {
			// return the updated entry
			$toret = array(
				"data" => array(
					array(
						"Id" => $row_recordset["Id"], 
						"Name" => $_REQUEST["Name"], #
						"Field" => $_REQUEST["Field"], #
						"Square" => $_REQUEST["Square"], #
						"Locus" => $_REQUEST["Locus"], #
						"Basket" => $_REQUEST["Basket"], #
						"Period" => $_REQUEST["Period"], #
						"Quantity" => $_REQUEST["Quantity"], #
						"Description" => $_REQUEST["Description"], #
						"Type" => $_REQUEST["Type"], #
						"Comments" => $_REQUEST["Comments"]#
					)
				), 
				"metadata" => array()
			);
		} else {
			// an update error, return it
			$toret = array(
				"data" => array("error" => mysql_error()), 
				"metadata" => array()
			);
		}
	} else {
		$toret = array(
			"data" => array("error" => "No row found"), 
			"metadata" => array()
		);
	}
	return $toret;
}

function delete() {
	global $conn;

	// check to see if the record actually exists in the database
	$query_recordset = sprintf("SELECT * FROM `Objects` WHERE Id = %s",
		GetSQLValueString($_REQUEST["Id"], "int")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);

	if ($num_rows > 0) {
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_delete = sprintf("DELETE FROM `Objects` WHERE Id = %s", 
			GetSQLValueString($row_recordset["Id"], "int")
		);
		$ok = mysql_query($query_delete);
		if ($ok) {
			// delete went through ok, return OK
			$toret = array(
				"data" => $row_recordset["Id"], 
				"metadata" => array()
			);
		} else {
			$toret = array(
				"data" => array("error" => mysql_error()), 
				"metadata" => array()
			);
		}

	} else {
		// no row found, return an error
		$toret = array(
			"data" => array("error" => "No row found"), 
			"metadata" => array()
		);
	}
	return $toret;
}

// error response, if we do not receive a correct method
$ret = array(
	"data" => array("error" => "No operation"), 
	"metadata" => array()
);

if ($conn === false) {
	$ret = array(
		"data" => array("error" => "database connection error, please check your settings !"), 
		"metadata" => array()
	);
} else {
	mysql_select_db($database_conn, $conn);
	switch (@$_REQUEST["method"]) {
		case "findObjects":
			$ret = findObjects();
		break;
		case "findReads":
			$ret = findReads();
		break;
		case "findParts":
			$ret = findParts();
		break;
		case "findPeriods":
			$ret = findPeriods();
		break;	
		case "findVessels":
			$ret = findVessels();
		break;
		case "clearBasket":
			$ret = clearBasket();
		break;
		case "insertBasket":
			$ret = insertBasket();
		break;			
		case "Update": 
			$ret = update();
		break;
		case "Delete": 
			$ret = delete();
		break;
		case "Count":
			$ret = rowCount();
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
	//	case "Insert": 
	//		$ret = insert();
	//	break;
		
	}
}


$serializer = new XmlSerializer();
echo $serializer->serialize($ret);
die();
?>
