<?php
session_start(); 
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "../../includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot . "functions.inc.php");
require_once($theRoot . "functions.php");
require_once($theRoot . "XmlSerializer.class.php");

function findAll() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Name','Field','Square','Locus','Basket','Quantity','Disposition','Description','Type','CreateDate','Comments');
	if(@$_REQUEST["year"] == "All"){
		@$_REQUEST["year"] = "20%";
	}
	$where = "";
	if (@$_REQUEST['filter'] != "") {
		$where = "WHERE " . 'Type' . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], "text") . 
		" AND " .  'CreateDate' . " LIKE " .   GetSQLValueStringForSelect(@$_REQUEST["year"], "text");	
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
	$query_recordset = "SELECT Id,Name,Field,Square,Locus,Basket,Quantity,PeriodCode,Disposition,Description,Type,CreateDate,Comments FROM `Objects` $where $order";
	if ($pageNum >= 0 && $pageSize > 0) {	
		$query_recordset = sprintf("%s LIMIT %d, %d", $query_recordset, $start, $pageSize);
	}
	$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		$row_recordset['Name'] = substr($row_recordset['Name'],6);
		array_push($toret, $row_recordset);
	}
	$toret = array(
		"data" => $toret, 
		"metadata" => array (
			"totalRows" => $totalrows,
			"pageNum" => $pageNum));
	return $toret;
}

function findBasketContents() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Name','Field','Square','Locus','Basket','Quantity','Disposition','Description','Type','CreateDate','Comments');
	$where = "";
	if (@$_REQUEST['filter'] != "") {
		$where = "WHERE " . 'Basket' . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], "text");	
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
	$query_recordset = "SELECT Id,Name,Field,Square,Locus,Basket,Quantity,PeriodCode,Disposition,Description,Type,CreateDate,Comments FROM `Objects` $where $order";
	if ($pageNum >= 0 && $pageSize > 0) {	
		$query_recordset = sprintf("%s LIMIT %d, %d", $query_recordset, $start, $pageSize);
	}
	$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		$row_recordset['Name'] = substr($row_recordset['Name'],6);
		array_push($toret, $row_recordset);
	}
	$toret = array(
		"data" => $toret, 
		"metadata" => array (
			"totalRows" => $totalrows,
			"pageNum" => $pageNum));
	return $toret;
}


function searchObjects() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Name','Field','Square','Locus','Basket','Quantity','PeriodCode','Disposition','Description','Type','CreateDate','Comments');

	$where = "";
	if (@$_REQUEST['filter'] != "") {
		$where = "WHERE " . 'Description' . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], "text"); 
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
	$query_recordset = "SELECT Id,Name,Field,Square,Locus,Basket,Quantity,PeriodCode,Disposition,Description,Type,CreateDate,Comments FROM `Objects` $where $order";
	
	//if we use pagination, add the limit clause
	if ($pageNum >= 0 && $pageSize > 0) {	
		$query_recordset = sprintf("%s LIMIT %d, %d", $query_recordset, $start, $pageSize);
	}

	$recordset = mysql_query($query_recordset, $conn);
	
	//if we have rows in the table, loop through them and fill the array
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		$row_recordset['Name'] = substr($row_recordset['Name'],6);
		array_push($toret, $row_recordset);
	}
	
	//create the standard response structure
	$toret = array(
		"data" => $toret, 
		"metadata" => array (
			"totalRows" => $totalrows,
			"pageNum" => $pageNum
		)
	);

	return $toret;
}


function getforms() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Type','Vessel');
	$where = "";	
	$where = "WHERE " . 'Type' . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], "text") . 
	" OR " .  'Type' . " LIKE " .   GetSQLValueStringForSelect("All", "text");
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
	$query_recordset = "SELECT Id,Type,Vessel  FROM `Vessels` $where $order";
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

function findBasket() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Field','Square','Locus','CreateDate','Basket');

	$where = "";
	if (@$_REQUEST['filter'] != "") {
		$where = "WHERE " . 'Basket' . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], "text");	
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

	//construct the query, using the where and order condition
	$query_recordset = "SELECT Id,Field,Square,Locus,CreateDate,Basket FROM `Pottery` $where $order";
	
	//if we use pagination, add the limit clause
	if ($pageNum >= 0 && $pageSize > 0) {	
		$query_recordset = sprintf("%s LIMIT %d, %d", $query_recordset, $start, $pageSize);
	}

	$recordset = mysql_query($query_recordset, $conn);
	
	//if we have rows in the table, loop through them and fill the array
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		array_push($toret, $row_recordset);
	}
	
	//create the standard response structure
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

/**
 * constructs and executes a sql insert query against the selected database
 * can take the following parameters:
 * $_REQUEST["field_name"] - the list of fields which appear here will be used as values for insert. 
 * If a field does not appear, null will be used.  
 * returns : an array of the form
 * array (
 * 		data => array(
 * 			"primary key" => primary_key_value, 
 * 			"field1" => "value1"
 * 			...
 * 		), 
 * 		metadata => array()
 * ) 
 */
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


function AssignObjectName($Bucket) {//*******************************unfinished
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
	$Count++;
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

function insert() {
	global $conn;
	$Sequence = 1;
	$theName = AssignObjectName($_REQUEST["Basket"]);
	$Sequenceplace = strpos($theName, '-')+1;
	$Sequence = substr($theName, $Sequenceplace);
	$ShortName = substr($Name,6);
	//build and execute the insert query
	$query_insert = sprintf("INSERT INTO `Objects` (User,Sequence,Name,Field,Square,Locus,Basket,Quantity,Disposition,Description,Type,Period,PeriodCode,CreateDate,Comments,DFC,DLC) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,now(),now())" ,					
			GetSQLValueString($_SESSION['Name'], "text"), # 
			GetSQLValueString($Sequence, "text"), # 
			GetSQLValueString($theName, "text"), # 
			GetSQLValueString($_REQUEST["Field"], "text"), # 
			GetSQLValueString($_REQUEST["Square"], "text"), # 
			GetSQLValueString($_REQUEST["Locus"], "text"), # 
			GetSQLValueString($_REQUEST["Basket"], "text"), # 
			GetSQLValueString($_REQUEST["Quantity"], "int"), # 
			GetSQLValueString($_REQUEST["Disposition"], "text"), # 
			GetSQLValueString($_REQUEST["Description"], "text"), # 
			GetSQLValueString($_REQUEST["Type"], "text"), # 
			GetSQLValueString($_REQUEST["Period"], "text"), # 
			GetSQLValueString($_REQUEST["PeriodCode"], "text"), # 
			GetSQLValueString($_REQUEST["CreateDate"], "text"), # 
			GetSQLValueString($_REQUEST["Comments"], "text")# 
	);
	$ok = mysql_query($query_insert);
	
	if ($ok) {
		// return the new entry, using the insert id
		$toret = array(
			"data" => array(
				array(
//					"Id" => mysql_insert_id(), 
					"Field" => $_REQUEST["Field"], # 
					"Square" => $_REQUEST["Square"], # 
					"Locus" => $_REQUEST["Locus"], # 
					"Basket" => $_REQUEST["Basket"], # 
					"Quantity" => $_REQUEST["Quantity"], # 
					"Disposition" => $_REQUEST["Disposition"], # 
					"Description" => $_REQUEST["Description"], # 
					"Type" => $_REQUEST["Type"], # 
					"Period" => $_REQUEST["Period"], # 
					"PeriodCode" => $_REQUEST["PeriodCode"], # 
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
		$query_update = sprintf("UPDATE `Objects` SET User = %s,Field = %s,Square = %s,Locus = %s,Basket = %s,Quantity = %s,Description = %s,Type = %s,CreateDate = %s,Comments = %s,DLC = now() WHERE Id = %s", 
			GetSQLValueString($_SESSION['Name'], "text"), 
			GetSQLValueString($_REQUEST["Field"], "text"), 
			GetSQLValueString($_REQUEST["Square"], "text"), 
			GetSQLValueString($_REQUEST["Locus"], "text"), 
			GetSQLValueString($_REQUEST["Basket"], "text"), 
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
						"Field" => $_REQUEST["Field"], #
						"Square" => $_REQUEST["Square"], #
						"Locus" => $_REQUEST["Locus"], #
						"Basket" => $_REQUEST["Basket"], #
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

function findPeriods() {
	global $conn, $filter_field, $filter_type;
	$filter_field = "Visible";
	$filter_type = "text";
	/**
	 * the list of fields in the table. We need this to check that the sent value for the ordering is indeed correct.
	 */
	$fields = array('Id','ListOrder','Visible','Period','Code','SubPeriod');

	$where = "";
	if (@$_REQUEST['filter'] != "") {
		$where = "WHERE " . $filter_field . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], $filter_type);	
	}
	if (@$_REQUEST['filter'] == "List All") {
		$where = "";	
	}

	$order = "";
	if (@$_REQUEST["orderField"] != "" && in_array(@$_REQUEST["orderField"], $fields)) {
		$order = "ORDER BY " . @$_REQUEST["orderField"] . " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
	}
	//calculate the number of rows in this table
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Periods` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];
	
	//get the page number, and the page size
	$pageNum = (int)@$_REQUEST["pageNum"];
	$pageSize = (int)@$_REQUEST["pageSize"];
	
	//calculate the start row for the limit clause
	$start = $pageNum * $pageSize;

	//construct the query, using the where and order condition
	$query_recordset = "SELECT Id,ListOrder,Visible,Period,Code,SubPeriod FROM `Periods`";
	
	//if we use pagination, add the limit clause
	if ($pageNum >= 0 && $pageSize > 0) {	
		$query_recordset = sprintf("%s LIMIT %d, %d", $query_recordset, $start, $pageSize);
	}

	$recordset = mysql_query($query_recordset, $conn);
	
	//if we have rows in the table, loop through them and fill the array
	$toret = array();
	$arrayBase = array("Id"=> null, "ListOrder"=> null, "Visible"=> null, "Period"=> null, "Code"=> null, "SubPeriod"=> null);
	array_push($toret, $arrayBase);
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		array_push($toret, $row_recordset);
	}
	
	//create the standard response structure
	$toret = array(
		"data" => $toret, 
		"metadata" => array (
			"totalRows" => $totalrows,
			"pageNum" => $pageNum
		)
	);
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

/**
 * we use this as an error response, if we do not receive a correct method
 * 
 */
$ret = array(
	"data" => array("error" => "No operation"), 
	"metadata" => array()
);

/**
 * check for the database connection 
 * 
 * 
 */
if ($conn === false) {
	$ret = array(
		"data" => array("error" => "database connection error, please check your settings !"), 
		"metadata" => array()
	);
} else {
	mysql_select_db($database_conn, $conn);
	/**
	 * simple dispatcher. The $_REQUEST["method"] parameter selects the operation to execute. 
	 * must be one of the values findAll, insert, update, delete, Count
	 */
	// execute the necessary function, according to the operation code in the post variables
	switch (@$_REQUEST["method"])
	{
		case "FindAll":
			$ret = findAll();
		break;
		case "findBasket":
			$ret = findBasket();
		break;
		case "findBasketContents":
			$ret = findBasketContents();
		break;
		case "FindPeriods":
			$ret = findPeriods();
		break;
		case "getForms":
			$ret = getForms();
		break;
		case "searchObjects":
			$ret = searchObjects();
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
		case "Flex_confirmLogin":
			$ret = flex_confirmLogin();
		break;
		case "FindUsers":
			$ret = findUsers();
		break;
		case "Flex_LogIn":
			$ret = flex_LogIn();
		break;
	}
}


$serializer = new XmlSerializer();
echo $serializer->serialize($ret);
die();
?>
