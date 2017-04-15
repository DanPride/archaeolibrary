<?php
session_start();
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "../../includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot . "functions.inc.php");
require_once($theRoot . "functions.php");
require_once($theRoot . "XmlSerializer.class.php");
$filter_field = "Name";
$filter_type = "text";

//**********************
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
//****Name Assignment Functions	
	
function AssignLocus($Locus) 
{
	   global $conn;
		$User = $_SESSION['Name'];
		$Locus = substr($aLocus,1,-1);
		$query = sprintf("SELECT * FROM Locus WHERE Locus = $Locus");
		$result = mysql_query($query, $conn);
		$Count = mysql_num_rows($result);
		if($Count == 0) 
		{
		$query_insert = sprintf("INSERT INTO `Locus` (User,Locus,DFC,DLC) VALUES ('{$User}','{$Locus}',now(),now())");
		$ok = mysql_query($query_insert, $conn);
		}
}
		
function AssignLoca($aField, $aSquare, $aLocus) {
	global $conn;
	$Field = substr($aField,1,-1);
	$Square = substr($aSquare,1,-1);
	$Locus = substr($aLocus,1,-1);
	$User = $_SESSION['Name'];
	$query = sprintf("SELECT * FROM Loca WHERE Field = '{$Field}' AND Square = '{$Square}' AND Locus = '{$Locus}'");
	$result = mysql_query($query, $conn);
	$Count = mysql_num_rows($result);
	mysql_free_result($result);
	if($Count == 0) 
		{
				$squareQuery = sprintf("SELECT * FROM Loca WHERE Field = '{$Field}' AND Square = '{$Square}'");
				$squareresult = mysql_query($squareQuery, $conn);
				$squareCount = mysql_num_rows($squareresult);
				$duptest = true;
				$squareCount++;
				while($duptest == true){
				$Loca = "L" . returnNumber($squareCount);
				$Name = DIG_CODE . $Field . returnSquare($Square) . $Loca;
				$queryDupTest = sprintf("SELECT * FROM Loca WHERE Name = '{$Name}'"); //test for duplicate
				$dupResult = mysql_query($queryDupTest, $conn);
				$DupCount = mysql_num_rows($dupResult);
				if($DupCount != 0){
				$squareCount++;
				} else {
						$duptest = false;
						$query_insert = sprintf("INSERT INTO `Loca` (User,Field,Square,Locus,Name,DFC,DLC) VALUES (%s,%s,%s,%s,%s,now(),now())" ,
						GetSQLValueString($_SESSION["Name"], "text"), # 
						GetSQLValueString($_REQUEST["Field"], "text"), # 
						GetSQLValueString($_REQUEST["Square"], "text"), # 		
						GetSQLValueString($Locus, "text"), # 
						GetSQLValueString($Name, "text")# 
						);
						$ok = mysql_query($query_insert, $conn);	
				}
			}
		}
}

function AssignBucket($aField,$aSquare,$aLocus,$aBucket) {
   	global $conn;
	$Count = 0;
	$duptest = true;
	$Field = substr($aField,1,-1);
	$Square = substr($aSquare,1,-1);
	$Locus = substr($aLocus,1,-1);
	$Bucket = substr($aBucket,1,-1);
	$query = sprintf("SELECT * FROM Loca WHERE Field = '{$Field}' AND Square = '{$Square}' AND Locus = '{$Locus}'");
	$recordSetLoca = mysql_query($query, $conn);
	while ($row_recordset = mysql_fetch_assoc($recordSetLoca)) {
		$LocaName =	$row_recordset["Name"];
	}	
	$potQuery = sprintf("SELECT * FROM Pottery WHERE Field = '{$Field}' AND Square = '{$Square}' AND Locus = '{$Locus}'"); //Get Pottery matches for each Loca
	$result = mysql_query($potQuery, $conn);
	$Count = mysql_num_rows($result);
	mysql_free_result($result);
	while($duptest == true)
	{
	$Count++;
	$Name = $LocaName . "B" . returnNumber($Count);
	$queryDupTest = sprintf("SELECT * FROM Pottery WHERE Name = '{$Name}'"); //test for duplicate
	$dupResult = mysql_query($queryDupTest, $conn);
	$DupCount = mysql_num_rows($dupResult);
	if($DupCount == 0){
		$duptest = false;
		return $Name;	
		}
	}
}

//*** List Fields and Squares
function findFields() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Code','Supervisor','Open');
	$order = "ORDER BY ASC";
	$query_recordset = "SELECT Id, Name, Code, Supervisor, Open FROM `Fields`  WHERE Open Like 'Open'";
	$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
	$test = array("Id"=> null, "Code"=> null, "Supervisor"=> null, "Open"=> null);
	array_push($toret, $test);
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		array_push($toret, $row_recordset);
	}
	$toret = array(
		"data" => $toret
			);
	return $toret;
}

function findSquares() {
	global $conn, $filter_field, $filter_type;
	$field = @$_REQUEST["selectedField"];
	$order = " ORDER BY " . "Square" . " ASC";
	$where = "WHERE " . "Field" . " LIKE " . GetSQLValueStringForSelect($field, "text") . " AND " . 
	  "Open" . " LIKE " . GetSQLValueStringForSelect("Open", "text");
	$query_recordset = "SELECT Id,Name,Field,Square,Supervisor,Open FROM Squares $where $order";
	$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
	$test = array("Id"=> null, "Name"=> null, "Field"=> null, "Square"=> null, "Supervisor"=> null,  "Open"=> null);
	array_push($toret, $test);
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		array_push($toret, $row_recordset);
	}
	$toret = array(
		"data" => $toret
			);
	return $toret;
}

function findAll() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Name','Field','Square','Locus','Basket','Periods','Context','Disposition','CreateDate','Status');
	$where = "";
	$where = "WHERE " . "Status" . " LIKE " . GetSQLValueStringForSelect("READ", $filter_type);	
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


//****INSERT, UPDATE, DELETE
function insert() {
	global $conn;
	$Sequence = 1;
	AssignLocus(GetSQLValueString($_REQUEST["Locus"]));
	AssignLoca(GetSQLValueString($_REQUEST["Field"], "text"), GetSQLValueString($_REQUEST["Square"],  "text"), GetSQLValueString($_REQUEST["Locus"], "text"));
	$theName = AssignBucket(GetSQLValueString($_REQUEST["Field"], "text"), GetSQLValueString($_REQUEST["Square"],  "text"),GetSQLValueString($_REQUEST["Locus"], "text"), GetSQLValueString($_REQUEST["Basket"], "text"));
	$query_insert = sprintf("INSERT INTO `Pottery` (User,Sequence,Name,Field,Square,Locus,Basket,Status,CreateDate,DFC,DLC) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,now(),now(),now())" ,			
			GetSQLValueString($_SESSION['Name'], "text"), # 
			GetSQLValueString($Sequence, "int"), # 
			GetSQLValueString($theName, "text"), # 
			GetSQLValueString($_REQUEST["Field"], "text"), # 
			GetSQLValueString($_REQUEST["Square"], "text"), # 
			GetSQLValueString($_REQUEST["Locus"], "text"), # 
			GetSQLValueString($_REQUEST["Basket"], "text"), # 
			GetSQLValueString($_REQUEST["Status"], "text")# 
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
					"Periods" => $_REQUEST["Periods"], # 
					"Context" => $_REQUEST["Context"], # 
					"Disposition" => $_REQUEST["Disposition"], # 
					"CreateDate" => $_REQUEST["CreateDate"], # 
					"Status" => $_REQUEST["Status"]# 
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
	$query_recordset = sprintf("SELECT * FROM `Pottery` WHERE Id = %s",
		GetSQLValueString($_REQUEST["Id"], "int")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);
	
	if ($num_rows > 0) {

		// build and execute the update query
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_update = sprintf("UPDATE `Pottery` SET User = %s, Name = %s,Field = %s,Square = %s,Locus = %s,Basket = %s,Periods = %s,Context = %s,Disposition = %s,CreateDate = %s,Status = %s,DLC = now() WHERE Id = %s", 
			GetSQLValueString($_SESSION["Name"], "text"), 
			GetSQLValueString($_REQUEST["Name"], "text"), 
			GetSQLValueString($_REQUEST["Field"], "text"), 
			GetSQLValueString($_REQUEST["Square"], "text"), 
			GetSQLValueString($_REQUEST["Locus"], "text"), 
			GetSQLValueString($_REQUEST["Basket"], "text"), 
			GetSQLValueString($_REQUEST["Periods"], "text"), 
			GetSQLValueString($_REQUEST["Context"], "text"), 
			GetSQLValueString($_REQUEST["Disposition"], "text"), 
			GetSQLValueString($_REQUEST["CreateDate"], "text"), 
			GetSQLValueString($_REQUEST["Status"], "text"), 
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
						"Periods" => $_REQUEST["Periods"], #
						"Context" => $_REQUEST["Context"], #
						"Disposition" => $_REQUEST["Disposition"], #
						"CreateDate" => $_REQUEST["CreateDate"], #
						"Status" => $_REQUEST["Status"]#
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
	$query_recordset = sprintf("SELECT * FROM `Pottery` WHERE Id = %s",
		GetSQLValueString($_REQUEST["Id"], "int")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);

	if ($num_rows > 0) {
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_delete = sprintf("DELETE FROM `Pottery` WHERE Id = %s", 
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

//**** RowCount and Master Switch
function rowCount() {
	global $conn, $filter_field, $filter_type;

	$where = "";
	if (@$_REQUEST['filter'] != "") {
		$where = "WHERE " . 'Status' . " LIKE " . GetSQLValueStringForSelect("READ", $filter_type);	
	}

	//calculate the number of rows in this table
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Pottery` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];
	
	//create the standard response structure
	$toret = array(
		"data" => $totalrows, 
		"metadata" => array()
	);
	return $toret;
}


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
		case "FindAll":
			$ret = findAll();
		break;
		case "FindFields":
			$ret = findFields();
		break;
		case "FindSquares":
			$ret = findSquares();
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
			$ret = fieldList();
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
		case "AssignNumbers":
			$ret = assignNumbers();
		break;
	}
}
$serializer = new XmlSerializer();
echo $serializer->serialize($ret);
die();
?>
