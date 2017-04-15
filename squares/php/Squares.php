<?php
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "../../includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot . "functions.inc.php");
require_once($theRoot . "functions.php");
require_once($theRoot . "XmlSerializer.class.php");

$filter_field = "Type";
$filter_type = "text";
function findAll() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Name','Field','Square','Open','Supervisor');
	$where = "";
	if (@$_REQUEST['filter'] != "") {
		$where = "WHERE " . $filter_field . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], $filter_type);	
	}
	$order = "";
	if (@$_REQUEST["orderField"] != "" && in_array(@$_REQUEST["orderField"], $fields)) {
		$order = "ORDER BY " . @$_REQUEST["orderField"] . " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
	}
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Squares` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];
	$pageNum = (int)@$_REQUEST["pageNum"];
	$pageSize = (int)@$_REQUEST["pageSize"];
	$start = $pageNum * $pageSize;
	$where = "";
	$query_recordset = "SELECT Id,Name,Field,Square,Open,Supervisor FROM `Squares` $where $order";
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

function findFields() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Code','Supervisor','Open');
	$order = "ORDER BY ASC";
	$query_recordset = "SELECT Id,Code,Supervisor,Open FROM `Fields` WHERE Open Like 'Open'";
	$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
	$blankLine = array("Id"=>null, "Code"=>null,"Supervisor"=>null,"Open"=>null);
		array_push($toret, $blankLine);
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		array_push($toret, $row_recordset);
	}
	$toret = array(
		"data" => $toret
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
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Squares` $where"); 
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
function returnNewSquare($Square)
	{
		global $conn;
		if((SQUARE_MINLENGTH == 3) && (SQUARE_MAXLENGTH == 3)){
			return $Square;
		} else {	
				$query = "SELECT type, MAX(price) FROM products GROUP BY type"; 
				$query = sprintf("SELECT MAX(Id) FROM Squares");
				$result = mysql_query($query, $conn);
				while ($row_recordset = mysql_fetch_assoc($result)) {
					$Id = $row_recordset['MAX(Id)'];
				}
				//settype($Id, int);
				if($Id > 99) {
					settype($Id, string);
					return $Id;
				} else if($Id > 9) {
					settype($Id, string);
					return "0" . $Id;
				} else {
					settype($Id, string);
					return "00" . $Id;	
				}	
		}
	}
	
function insert() {
	global $conn;
$Sequence = 1;
$Name = DIG_CODE . $_REQUEST["Field"] . returnNewSquare($_REQUEST["Square"]);
	//build and execute the insert query
	$Sequence = 1;
	$query_insert = sprintf("INSERT INTO `Squares` (DFC,DLC,User,Sequence,Name,Field,Square,Open,Supervisor) VALUES (now(),now(),%s,%s,%s,%s,%s,%s,%s)" ,			
			GetSQLValueString($_SESSION['Name'], "text"), # 
			GetSQLValueString($Sequence, "text"), # 
			GetSQLValueString($Name, "text"), # 
			GetSQLValueString($_REQUEST["Field"], "text"), # 
			GetSQLValueString($_REQUEST["Square"], "text"), # 
			GetSQLValueString($_REQUEST["Open"], "text"), # 
			GetSQLValueString($_REQUEST["Supervisor"], "text")# 
	);
	$ok = mysql_query($query_insert);
	
	if ($ok) {
		$toret = array(
			"data" => array(
				array(
					"Id" => mysql_insert_id(), 
					"Name" => $_REQUEST["Type"], # 
					"Field" => $_REQUEST["Type"], # 
					"Square" => $_REQUEST["Type"], # 
					"Open" => $_REQUEST["Type"], # 
					"Supervisor" => $_REQUEST["Vessel"]# 
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
	$query_recordset = sprintf("SELECT * FROM `Squares` WHERE Id = %s",
		GetSQLValueString($_REQUEST["Id"], "int")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);
	
	if ($num_rows > 0) {
		$theName = DIG_CODE . $_REQUEST["Field"];
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_update = sprintf("UPDATE `Squares` SET DLC = now(),Name = %s,User = %s,Name = %s,Field = %s WHERE Id = %s", 
			GetSQLValueString($theName, "text"), 
			GetSQLValueString($_SESSION["Name"], "text"), 
			GetSQLValueString($_REQUEST["Name"], "text"), 
			GetSQLValueString($_REQUEST["Field"], "text"), 
			GetSQLValueString($row_recordset["Id"], "int")
		);
		$ok = mysql_query($query_update);
		if ($ok) {
			// return the updated entry
			$toret = array(
				"data" => array(
					array(
						"Id" => $row_recordset["Id"], 
						"Type" => $_REQUEST["Type"], #
						"Vessel" => $_REQUEST["Vessel"]#
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
	$query_recordset = sprintf("SELECT * FROM `Squares` WHERE Id = %s",
		GetSQLValueString($_REQUEST["Id"], "int")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);

	if ($num_rows > 0) {
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_delete = sprintf("DELETE FROM `Squares` WHERE Id = %s", 
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
