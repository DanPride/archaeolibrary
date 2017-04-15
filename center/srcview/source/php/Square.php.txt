<?php
function findFields() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Code','Supervisor','Open');
	$order = "ORDER BY ASC";
	$query_recordset = "SELECT Id,Code,Supervisor,Open FROM `Fields` WHERE Open Like 'Open'";
	$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
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
	$fields = array('Id','Name','DFC','DLC','User','Field','Square','Supervisor','Open');
	$where = "";
			$Switcher = @$_REQUEST['squareDef'];
			switch($Switcher)
			{
				case 'Open':
					$SearchField = 'Open';
				break;
				case 'Closed':
				$SearchField = 'Closed';
				break;
				case 'List All':
				 	$SearchField = '%';
				break;
			}
			$where = "WHERE " . 'Open' . " LIKE " . GetSQLValueStringForSelect($SearchField, "text");
		
	$order = "";
	if (@$_REQUEST["orderField"] != "" && in_array(@$_REQUEST["orderField"], $fields)) 
	{
		$order = "ORDER BY " . @$_REQUEST["orderField"] . " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
	}
	$query_recordset = "SELECT Id,Name,DFC,DLC,User,Field,Square,Supervisor,Open FROM `Squares` $where $order";
	$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) 
	{
		array_push($toret, $row_recordset);
	} 
	$toret = array(
		"data" => $toret, 
		"metadata" => array (
		)
	);
	return $toret;
}

function insertSquare() {
	global $conn;
	$Name = "GZ" . $_REQUEST["Field"] . $_REQUEST["Square"];
	$query_insert = sprintf("INSERT INTO `Squares` (Name,Field,Square,Open,Supervisor) VALUES (%s,%s,%s,%s,%s)" ,	GetSQLValueString($Name, "text"), # 
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
					"Name" => $_REQUEST["Name"], # 
					"Field" => $_REQUEST["Field"], # 
					"Square" => $_REQUEST["Square"], # 
					"Open" => $_REQUEST["Open"], # 
					"Supervisor" => $_REQUEST["Supervisor"]# 
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


function updateSquare() {
	global $conn;
	$query_recordset = sprintf("SELECT * FROM `Squares` WHERE Id = %s",
		GetSQLValueString($_REQUEST["Id"], "int")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);
	$theName = DIG_CODE . $_REQUEST["Field"] . returnNewSquare($_REQUEST["Square"]);
	if ($num_rows > 0) {
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_update = sprintf("UPDATE `Squares` SET DLC = now(),User = %s,Name = %s,Field = %s,Square = %s,Open = %s,Supervisor = %s WHERE Id = %s", 
			GetSQLValueString($_SESSION["Name"], "text"), 
			GetSQLValueString($theName, "text"), 
			GetSQLValueString($_REQUEST["Field"], "text"), 
			GetSQLValueString($_REQUEST["Square"], "text"), 
			GetSQLValueString($_REQUEST["Open"], "text"), 
			GetSQLValueString($_REQUEST["Supervisor"], "text"), 
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
						"Open" => $_REQUEST["Open"], #
						"Supervisor" => $_REQUEST["Supervisor"]#
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

function deleteSquare() {
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

?>