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
	$fields = array('Id','DFC','DLC','User','Name','Code','Field','Supervisor','Open');
	$where = "";
	if (@$_REQUEST['filter'] != "") {
		$where = "WHERE " . $filter_field . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], $filter_type);	
	}
	$order = "";
	if (@$_REQUEST["orderField"] != "" && in_array(@$_REQUEST["orderField"], $fields)) {
		$order = "ORDER BY " . @$_REQUEST["orderField"] . " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
	}
		$where = "";
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Fields` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];
	$pageNum = (int)@$_REQUEST["pageNum"];
	$pageSize = (int)@$_REQUEST["pageSize"];
	$start = $pageNum * $pageSize;
	$query_recordset = "SELECT Id,DFC,DLC,User,Name,Code,Field,Supervisor,Open FROM `Fields` $where $order";
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

function returnNewField($Field)
	{
		global $conn;
		$fieldArray = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","ß","∂","ƒ","©");
		if((FIELD_MINLENGTH == 1) && (FIELD_MAXLENGTH == 1)){
			return strtoupper($Field);
		} else {	
				$exit = true;
				$query = sprintf("SELECT MAX(Id) as theId FROM Fields");
				$recordset = mysql_query($query, $conn);
				$num_rows = mysql_num_rows($recordset);
				$row_recordset = mysql_fetch_assoc($recordset);
				$Index = $row_recordset['theId'];
				$theTest = is_null($Index);
				if($theTest == 1){
					$Index = 0;	
				} 
				$newCode = strtoupper($fieldArray[$Index]);
				return 	$newCode;
		}
	}
	
function rowCount() {
	global $conn, $filter_field, $filter_type;

	$where = "";
	if (@$_REQUEST['filter'] != "") {
		$where = "WHERE " . $filter_field . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], $filter_type);	
	}

	//calculate the number of rows in this table
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Fields` $where"); 
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
$Sequence = 1;
$theCode = returnNewField($_REQUEST["Field"]);
$theName = DIG_CODE . $theCode;
	//build and execute the insert query
	$Sequence = 1;
	$query_insert = sprintf("INSERT INTO `Fields` (DFC,DLC,User,Name,Supervisor,Open,Code,Field) VALUES (now(),now(),%s,%s,%s,%s,%s,%s)" ,			
			GetSQLValueString($_SESSION['Name'], "text"), # 
			GetSQLValueString($theName, "text"), # 
			GetSQLValueString($_REQUEST["Supervisor"], "text"), # 
			GetSQLValueString($_REQUEST["Open"], "text"), # 
			GetSQLValueString($theCode, "text"), # 
			GetSQLValueString($_REQUEST["Field"], "text")# 
	);
	$ok = mysql_query($query_insert);
	
	if ($ok) {
		$toret = array(
			"data" => array(
				array(
					"Id" => mysql_insert_id(), 
					"DFC" => $_REQUEST["DFC"], # 
						"DLC" => $_REQUEST["DLC"], # 
							"User" => $_SESSION['Name'], # 
								"Name" => $_REQUEST["Name"], # 
									"Supervisor" => $_REQUEST["Supervisor"], # 
										"Open" => $_REQUEST["Open"] ,# 
											"Code" => $_REQUEST["Code"] ,# 
												"Field" => $_REQUEST["Field"] # 
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
	$query_recordset = sprintf("SELECT * FROM `Fields` WHERE Id = %s",
		GetSQLValueString($_REQUEST["Id"], "int")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);
	
	if ($num_rows > 0) {
		$theCode = returnNewField($_REQUEST["Field"]);
		$theName = DIG_CODE . 	$theCode;
		// build and execute the update query
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_update = sprintf("UPDATE `Fields` SET DLC = now(),User = %s,Name = %s,Code = %s,Field = %s,Supervisor = %s,Open = %s  WHERE Id = %s", 
			GetSQLValueString($_SESSION['Name'], "text"), 
			GetSQLValueString($theName, "text"), 
			GetSQLValueString($theCode, "text"), 
			GetSQLValueString($_REQUEST["Field"], "text"), 
			GetSQLValueString($_REQUEST["Supervisor"], "text"), 
			GetSQLValueString($_REQUEST["Open"], "text"), 
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
								"Code" => $theCode, #
								"Supervisor" => $_REQUEST["Supervisor"], #
									"Open" => $_REQUEST["Open"] #
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
	$query_recordset = sprintf("SELECT * FROM `Fields` WHERE Id = %s",
		GetSQLValueString($_REQUEST["Id"], "int")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);

	if ($num_rows > 0) {
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_delete = sprintf("DELETE FROM `Fields` WHERE Id = %s", 
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
