<?php
function findGPS() {
			global $conn, $filter_field, $filter_type;
		$fields = array('Id','DFC','DLC','User','Name', 'Field','Square','Locus','Basket','Object','X','Y','Z');
		$where = "";
			
			if (@$_REQUEST['filter'] != "") 
			{
				$Switcher = @$_REQUEST['searchDef'];
				switch($Switcher)
				{
					case 'Square':
						$SearchField = 'Square';
					break;
					case 'Locus':
					$SearchField = 'Locus';
					break;
					case 'Basket':
					 	$SearchField = 'Basket';
					break;
					case 'Period':
					 	$SearchField = 'Period';
					break;
					case 'Name':
						$SearchField = 'Name';
					break;
					case 'Description':
						$SearchField = 'Description';
					break;
				}
				$where = "WHERE " . $SearchField . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], "text");
			}
			
		
			
			//$where $order
			
		$order = "";
		if (@$_REQUEST["orderField"] != "" && in_array(@$_REQUEST["orderField"], $fields)) 
		{
			$order = "ORDER BY " . @$_REQUEST["orderField"] . " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
		}
		$query_recordset = "SELECT * FROM `GPS` ";
		$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		$row_recordset["ShortName"] = substr($row_recordset["Name"],6);
		array_push($toret, $row_recordset);
		}
		$toret = array(
			"data" => $toret, 
			"metadata" => array (
			)
		);
		return $toret;
}
function insertGPS() {
	global $conn;
	$query_insert = sprintf("INSERT INTO `GPS` (Name,Field,Square,Locus,Basket,Object,X,Y,Z) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)" ,			
			GetSQLValueString($_REQUEST["Name"], "text"), # 
			GetSQLValueString($_REQUEST["Field"], "text"), # 
			GetSQLValueString($_REQUEST["Square"], "text"), # 
			GetSQLValueString($_REQUEST["Locus"], "text"), # 
			GetSQLValueString($_REQUEST["Basket"], "text"), # 
			GetSQLValueString($_REQUEST["Object"], "text"), # 
			GetSQLValueString($_REQUEST["X"], "text"), # 
			GetSQLValueString($_REQUEST["Y"], "text"), # 
			GetSQLValueString($_REQUEST["Z"], "text") # 
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
					"Locus" => $_REQUEST["Locus"], # 
					"Basket" => $_REQUEST["Basket"], # 
					"PeriodCode" => $_REQUEST["Object"], # 
					"Period" => $_REQUEST["X"], # 
					"Quantity" => $_REQUEST["Y"], # 
					"Saved" => $_REQUEST["Saved"], # 
					"Disposition" => $_REQUEST["Z"] # 
				
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
function updateGPS() {
	global $conn;
	$query_recordset = sprintf("SELECT * FROM `GPS` WHERE Id = %s",
		GetSQLValueString($_REQUEST["Id"], "int")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);
	
	if ($num_rows > 0) {
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_update = sprintf("UPDATE `GPS` SET DLC = now(),User = %s,Name = %s,Field = %s,Square = %s,Locus = %s,Basket = %s,Object = %s,X = %s,Y = %s,X = %s WHERE Id = %s", 
			GetSQLValueString($_SESSION["Name"], "text"), 
			GetSQLValueString($_REQUEST["Name"], "text"), 
			GetSQLValueString($_REQUEST["Field"], "text"), 
			GetSQLValueString($_REQUEST["Square"], "text"), 
			GetSQLValueString($_REQUEST["Locus"], "text"), 
			GetSQLValueString($_REQUEST["Basket"], "text"), 
			GetSQLValueString($_REQUEST["Object"], "text"), 
			GetSQLValueString($_REQUEST["X"], "text"), 
			GetSQLValueString($_REQUEST["Y"], "int"), 
			GetSQLValueString($_REQUEST["Z"], "int"), 
			GetSQLValueString($row_recordset["Id"], "int")
		);
		$ok = mysql_query($query_update);
		if ($ok) {
			$toret = array(
				"data" => array(
					array(
						"Id" => $row_recordset["Id"], 
						"Name" => $_REQUEST["Name"], #
						"Field" => $_REQUEST["Field"], #
						"Square" => $_REQUEST["Square"], #
						"Locus" => $_REQUEST["Locus"], #
						"Basket" => $_REQUEST["Basket"], #
						"PeriodCode" => $_REQUEST["Object"], #
						"Period" => $_REQUEST["X"], #
						"Quantity" => $_REQUEST["Y"], #
						"Saved" => $_REQUEST["Z"] #
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
function deleteGPS() {
	global $conn;
	$query_recordset = sprintf("SELECT * FROM `GPS` WHERE Id = %s",
		GetSQLValueString($_REQUEST["Id"], "int")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);

	if ($num_rows > 0) {
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_delete = sprintf("DELETE FROM `GPS` WHERE Id = %s", 
			GetSQLValueString($row_recordset["Id"], "int")
		);
		$ok = mysql_query($query_delete);
		if ($ok) {
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
		$toret = array(
			"data" => array("error" => "No row found"), 
			"metadata" => array()
		);
	}
	return $toret;
}
?>