<?php
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot . "functions.inc.php");
require_once($theRoot . "XmlSerializer.class.php");
global $conn, $filter_field, $filter_type;

$filter_field = "Basket";
$filter_type = "text";

function findObjects() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Name','Field','Square','Locus','Basket','Period','Quantity','Saved','Disposition','Description','Type','CreateDate','Comments');
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
	$query_recordset = "SELECT Id,Name,Field,Square,Locus,Basket,Period,Quantity,Saved,Disposition,Description,Type,CreateDate,Comments FROM `Objects` $where $order";
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

function findReads() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Name','Field','Square','Locus','Basket','Periods','Context','Disposition','CreateDate','Status');
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
	$query_recordset = "SELECT Id,Name,Field,Square,Locus,Basket,Periods,Context,Disposition,CreateDate,Status FROM `Pottery` $where $order";
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

function getPeriod($PeriodCode= "IR2")
{
	global $conn, $filter_field, $filter_type;
	$where = "WHERE " . `Code` . " LIKE " . "{$PeriodCode}" . " LIMIT 1";	
	$query_recordset = "SELECT SubPeriod FROM `Periods` $where";
	$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) 
	{
		array_push($toret, $row_recordset);
	}
	print_r($toret);
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

function insert() {
	global $conn;

	//build and execute the insert query
	$query_insert = sprintf("INSERT INTO `Objects` (Id,Name,Field,Square,Locus,Basket,Period,Quantity,Description,Type,CreateDate,Comments) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)" ,			
			GetSQLValueString($_REQUEST["Id"], "int"), # 
			GetSQLValueString($_REQUEST["Name"], "text"), # 
			GetSQLValueString($_REQUEST["Field"], "text"), # 
			GetSQLValueString($_REQUEST["Square"], "text"), # 
			GetSQLValueString($_REQUEST["Locus"], "text"), # 
			GetSQLValueString($_REQUEST["Basket"], "text"), # 
			GetSQLValueString($_REQUEST["PeriodCode"], "text"), # 
			GetSQLValueString($_REQUEST["Period"], "text"), # 
			GetSQLValueString($_REQUEST["Quantity"], "int"), # 
			GetSQLValueString($_REQUEST["Saved"], "int"), # 
			GetSQLValueString($_REQUEST["Description"], "text"), # 
			GetSQLValueString($_REQUEST["Type"], "text"), # 
			GetSQLValueString($_REQUEST["CreateDate"], "text"), # 
			GetSQLValueString($_REQUEST["Comments"], "text")# 
	);
	$ok = mysql_query($query_insert);
	
	if ($ok) {
		// return the new entry, using the insert id
		$toret = array(
			"data" => array(
				array(
					"Id" => $_REQUEST["Id"], 
					"Name" => $_REQUEST["Name"], # 
					"Field" => $_REQUEST["Field"], # 
					"Square" => $_REQUEST["Square"], # 
					"Locus" => $_REQUEST["Locus"], # 
					"Basket" => $_REQUEST["Basket"], # 
					"PeriodCode" => $_REQUEST["PeriodCode"], # 
					"Period" => $_REQUEST["Period"], # 
					"Quantity" => $_REQUEST["Quantity"], # 
					"Saved" => $_REQUEST["Saved"], # 
					"Description" => $_REQUEST["Description"], # 
					"Type" => $_REQUEST["Type"], # 
					"CreateDate" => $_REQUEST["CreateDate"], # 
					"Comments" => $_REQUEST["Comments"]# 
				)
			), 
			"metadata" => array()
		);
	} else {
		// we had an error, return it
		$toret = array(
			"data" => array("error" => mysql_error()), 
			"metadata" => array()
		);
	}
	return $toret;
}

function insertBasket() {
	global $conn;

	//build and execute the insert query
	$query_insert = sprintf("INSERT INTO `Objects` (Field,Square,Locus,Basket,PeriodCode,Period,Quantity,Saved,Disposition,Description,Type,CreateDate,Comments) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,now(),%s)" ,			
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
//			GetSQLValueString($_REQUEST["CreateDate"], "text"), # 
			GetSQLValueString($_REQUEST["Comments"], "text") # 				
	);
	$ok = mysql_query($query_insert);
	
	if ($ok) {
		// return the new entry, using the insert id
		$toret = array(
			"data" => array(
				array(
					"Id" => $_REQUEST["Id"], 
					"Name" => $_REQUEST["Name"], # 
					"Field" => $_REQUEST["Field"], # 
					"Square" => $_REQUEST["Square"], # 
					"Locus" => $_REQUEST["Locus"], # 
					"Basket" => $_REQUEST["Basket"], # 
					"PeriodCode" => $_REQUEST["PeriodCode"], # 
					"Period" => $_REQUEST["Period"], # 
					"Quantity" => $_REQUEST["Quantity"], # 
					"Saved" => $_REQUEST["Saved"], # 
					"Disposition" => $_REQUEST["Disposition"], # 
					"Description" => $_REQUEST["Description"], # 
					"Type" => $_REQUEST["Type"], # 
					"CreateDate" => $_REQUEST["CreateDate"], # 
					"Comments" => $_REQUEST["Comments"] # 
				)
			), 
			"metadata" => array()
		);
	} else {
		// we had an error, return it
		$toret = array(
			"data" => array("error" => mysql_error()), 
			"metadata" => array()
		);
	}
	return $toret;
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
		$query_update = sprintf("UPDATE `Objects` SET Name = %s,Field = %s,Square = %s,Locus = %s,Basket = %s,Period = %s,Quantity = %s,Description = %s,Type = %s,CreateDate = %s,Comments = %s WHERE Id = %s", 
			GetSQLValueString($_REQUEST["Name"], "text"), 
			GetSQLValueString($_REQUEST["Field"], "text"), 
			GetSQLValueString($_REQUEST["Square"], "text"), 
			GetSQLValueString($_REQUEST["Locus"], "text"), 
			GetSQLValueString($_REQUEST["Basket"], "text"), 
			GetSQLValueString($_REQUEST["Period"], "text"), 
			GetSQLValueString($_REQUEST["Quantity"], "int"), 
			GetSQLValueString($_REQUEST["Description"], "text"), 
			GetSQLValueString($_REQUEST["Type"], "text"), 
			GetSQLValueString($_REQUEST["CreateDate"], "text"), 
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
						"CreateDate" => $_REQUEST["CreateDate"], #
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
		case "Insert": 
			$ret = insert();
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
		default;
		$ret = getPeriod();
		break;
		
	}
}


$serializer = new XmlSerializer();
echo $serializer->serialize($ret);
die();
?>
