<?php
//ini_set('memory_limit', '128M');
//set_time_limit(1000);
//ini_set('display_errors',1);
//error_reporting(E_ALL|E_STRICT);
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "../../includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot . "functions.php");
require_once($theRoot . "functions.inc.php");
require_once($theRoot . "XmlSerializer.class.php");

$filter_field = "Status";
$filter_type = "text";

function findAll() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Status','Position','Name','PermsAdmin','PermsAdd','PermsMod','PermsDelete');
	$where = "";
	if (@$_REQUEST['filter'] != "") {
		$yava = $_REQUEST["filter"];
		$where = " WHERE Status = '{$yava}' ";
	}
	$order = "";
	if (@$_REQUEST["orderField"] != "" && in_array(@$_REQUEST["orderField"], $fields)) {
		$order = "ORDER BY " . @$_REQUEST["orderField"] . " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
	}
	$query_recordset = "SELECT Id,DFC,DLC,User,Status,Position,Password,Name,PermsAdmin,PermsAdd,PermsMod,PermsDelete FROM `Staff` $where";
//$where $order";
	$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		array_push($toret, $row_recordset);
	}
	$toret = array(
		"data" => $toret, 
		"metadata" => array (
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
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Staff` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];
	$toret = array(
		"data" => $totalrows, 
		"metadata" => array()
	);

	return $toret;
}

function setDirectorPassword() {
	global $conn;
	$theSha1Pass = sha1(DIG_DIRECTOR_PASSWORD);
	$query_update = sprintf("UPDATE `Staff` SET Name = %s, Password = %s WHERE Id = %s", 
		GetSQLValueString(DIG_DIRECTOR, "text"), # 	
		GetSQLValueString($theSha1Pass, "text"), # 			
		GetSQLValueString("3", "int") #
		);
		$ok = mysql_query($query_update); //ifOK set viewOnly
		if ($ok) {
			// return the updated entry
			$toret = array(
				"data" => array(
					array("Status" => "Success" )
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
	return $toret;
}
function setPassword() {
	global $conn;
	$thePass = $_REQUEST["Password"];
	$theSha1Pass = sha1($thePass);
	$query_recordset = sprintf("SELECT * FROM `Staff` WHERE Id = %s",
		GetSQLValueString($_REQUEST["Id"], "int")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);
	if ($num_rows > 0) {
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_update = sprintf("UPDATE `Staff` SET Password = %s WHERE Id = %s", 
			GetSQLValueString($theSha1Pass, "text"), # 			
			GetSQLValueString($_REQUEST["Id"], "int") #
		);
		$ok = mysql_query($query_update);
		if ($ok) {
			// return the updated entry
			$toret = array(
				"data" => array(
					array("Status" => "Success" )
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

function update() {
	global $conn;
	$query_recordset = sprintf("SELECT * FROM `Staff` WHERE Id = %s",
		GetSQLValueString($_REQUEST["Id"], "int")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);
	if ($num_rows > 0) {
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_update = sprintf("UPDATE `Staff` SET DLC = %s,User = %s,Status = %s,Position = %s,Name = %s,Password = %s,PermsAdmin = %s,PermsMod = %s,PermsDelete = %s,PermsAdd = %s WHERE Id = %s", 
			GetSQLValueString($_REQUEST["DLC"], "text"), # 			
			GetSQLValueString($_SESSION['Name'], "text"), # 
			GetSQLValueString($_REQUEST["Status"], "text"), # 
			GetSQLValueString($_REQUEST["Position"], "text"), # 
			GetSQLValueString($_REQUEST["Name"], "text"), # 
			GetSQLValueString($_REQUEST["Password"], "text"), # 
			GetSQLValueString($_REQUEST["PermsAdmin"], "text"), # 
			GetSQLValueString($_REQUEST["PermsMod"], "text"), # 
			GetSQLValueString($_REQUEST["PermsDelete"], "text"), # 
			GetSQLValueString($_REQUEST["PermsAdd"], "text"), #
			GetSQLValueString($row_recordset["Id"], "int")
		);
		$ok = mysql_query($query_update);
		if ($ok) {
			// return the updated entry
			$toret = array(
				"data" => array(
					array(
						"Id" => $row_recordset["Id"], 
					//	"RowNum" => $_REQUEST["RowNum"],  #
						"DFC" => $_REQUEST["DFC"], # 
						"User" => $_REQUEST["User"], # 
						"Status" => $_REQUEST["Status"], # 
						"Position" => $_REQUEST["Position"], # 
						"Name" => $_REQUEST["Name"], # 
						"Password" => $_REQUEST["Password"], # 
						"PermsAdmin" => $_REQUEST["PermsAdmin"], # 
						"PermsMod" => $_REQUEST["PermsMod"], # 
						"PermsDelete" => $_REQUEST["PermsDelete"], # 
						"PermsAdd" => $_REQUEST["PermsAdd"] #
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



function insert() {
	global $conn;
$Password = sha1($_REQUEST["Password"]);
	//build and execute the insert query
	$query_insert = sprintf("INSERT INTO `Staff` (DFC,DLC,User,Status,Position,Name,Password,PermsAdmin,PermsAdd,PermsMod,PermsDelete) VALUES (now(),now(),%s,%s,%s,%s,%s,%s,%s,%s,%s)" ,			
			GetSQLValueString($_SESSION['Name'], "text"), # 
			GetSQLValueString($_REQUEST["Status"], "text"), # 
			GetSQLValueString($_REQUEST["Position"], "text"), # 
			GetSQLValueString($_REQUEST["Name"], "text"), # 
			GetSQLValueString($Password, "text"), # 
			GetSQLValueString($_REQUEST["PermsAdmin"], "text"), # 
			GetSQLValueString($_REQUEST["PermsAdd"], "text"), # 
			GetSQLValueString($_REQUEST["PermsMod"], "text"), # 
			GetSQLValueString($_REQUEST["PermsDelete"], "text") # 
	);
	$ok = mysql_query($query_insert);
	
	if ($ok) {
		// return the new entry, using the insert id
		$toret = array(
			"data" => array(
				array(
				
					"Id" => mysql_insert_id(), 
					"DFC" => $_REQUEST["DFC"], # 
					"DLC" => $_REQUEST["DLC"], # 
					"User" => $_REQUEST["User"], # 
					"Status" => $_REQUEST["Status"], # 
					"Position" => $_REQUEST["Position"], # 
					"Name" => $_REQUEST["Name"], # 
					"Password" => $_REQUEST["Password"], # 
					"PermsAdmin" => $_REQUEST["PermsAdmin"], # 
					"PermsMod" => $_REQUEST["PermsMod"], # 
					"PermsDelete" => $_REQUEST["PermsDelete"], # 
					"PermsAdd" => $_REQUEST["PermsAdd"] #
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

function delete() {
	global $conn;
	if($_REQUEST["Id"] > 4)
{
	// check to see if the record actually exists in the database
	$query_recordset = sprintf("SELECT * FROM `Staff` WHERE Id = %s",
		GetSQLValueString($_REQUEST["Id"], "int")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);

	if ($num_rows > 0) 
	{
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_delete = sprintf("DELETE FROM `Staff` WHERE Id = %s", 
			GetSQLValueString($row_recordset["Id"], "int")
		);
		$ok = mysql_query($query_delete);
		if ($ok) 
		{
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
}	else {
		// no row found, return an error
		$toret = array(
			"data" => array("error" => "System, Director and View_Only users can not be deleted but they can be made inactive by the Director. Inactive users will not appear in the list of users."), 
			"metadata" => array()
		);
	}
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
		case "SetPassword":
			$ret = setPassword();
		break;
			case "Set_DirectorPassword":
				$ret = setDirectorPassword();
			break;
		
		
	}
}

$serializer = new XmlSerializer();
echo $serializer->serialize($ret);
die();
?>
