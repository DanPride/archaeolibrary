<?php
function findObjects() {
			global $conn, $filter_field, $filter_type;
		$fields = array('Id','DFC','DLC','User','Name', 'Field','Square','Locus','Basket','Description','Type','Disposition','Period','Comments');
		$where = "";
		$whereType = "";
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
			
			if (@$_REQUEST['filter'] != "") 
			{
				$Switcher = @$_REQUEST['searchType'];
				switch($Switcher)
				{
					case 'All Types':
						$whereType = '';
					break;
					default:
						$whereType = "AND " . 'Type'  . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["searchType"], "text");
					break;
				}
			}
			
			if (@$_REQUEST['filter'] != "") 
			{
				$Switcher = @$_REQUEST['searchPeriod'];
				switch($Switcher)
				{
					case 'All Periods':
						$wherePeriod = '';
					break;
					default:
						$wherePeriod = "AND " . 'PeriodCode'  . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["searchPeriod"], "text");
					break;
				}
			}
			
		$order = "";
		if (@$_REQUEST["orderField"] != "" && in_array(@$_REQUEST["orderField"], $fields)) 
		{
			$order = "ORDER BY " . @$_REQUEST["orderField"] . " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
		}
		$query_recordset = "SELECT Id,DFC,DLC,User,Name,Field,Square,Locus,Basket,Description,Type,Disposition,Period,Comments FROM `Objects` $where $whereType $wherePeriod $order";
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
function insertObject() {
	global $conn;
	$query_insert = sprintf("INSERT INTO `Objects` (Name,Field,Square,Locus,Basket,PeriodCode,Period,Quantity,Saved,Disposition,Description,Type,CreateDate,Comments) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)" ,			
			GetSQLValueString($_REQUEST["Name"], "text"), # 
			GetSQLValueString($_REQUEST["Field"], "text"), # 
			GetSQLValueString($_REQUEST["Square"], "text"), # 
			GetSQLValueString($_REQUEST["Locus"], "text"), # 
			GetSQLValueString($_REQUEST["Basket"], "text"), # 
			GetSQLValueString($_REQUEST["PeriodCode"], "text"), # 
			GetSQLValueString($_REQUEST["Period"], "text"), # 
			GetSQLValueString($_REQUEST["Quantity"], "int"), # 
			GetSQLValueString($_REQUEST["Saved"], "int"), # 
			GetSQLValueString($_REQUEST["Disposition"], "text"), # 
			GetSQLValueString($_REQUEST["Description"], "text"), # 
			GetSQLValueString($_REQUEST["Type"], "text"), # 
			GetSQLValueString($_REQUEST["CreateDate"], "text"), # 
			GetSQLValueString($_REQUEST["Comments"], "text")# 
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
					"PeriodCode" => $_REQUEST["PeriodCode"], # 
					"Period" => $_REQUEST["Period"], # 
					"Quantity" => $_REQUEST["Quantity"], # 
					"Saved" => $_REQUEST["Saved"], # 
					"Disposition" => $_REQUEST["Disposition"], # 
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
function updateObject() {
	global $conn;
	$query_recordset = sprintf("SELECT * FROM `Objects` WHERE Id = %s",
		GetSQLValueString($_REQUEST["Id"], "int")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);
	
	if ($num_rows > 0) {
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_update = sprintf("UPDATE `Objects` SET DLC = now(),User = %s,Field = %s,Square = %s,Locus = %s,Basket = %s,PeriodCode = %s,Period = %s,Quantity = %s,Saved = %s,Disposition = %s,Description = %s,Type = %s,CreateDate = %s,Comments = %s WHERE Id = %s", 
			GetSQLValueString($_SESSION["Name"], "text"), 
			GetSQLValueString($_REQUEST["Field"], "text"), 
			GetSQLValueString($_REQUEST["Square"], "text"), 
			GetSQLValueString($_REQUEST["Locus"], "text"), 
			GetSQLValueString($_REQUEST["Basket"], "text"), 
			GetSQLValueString($_REQUEST["PeriodCode"], "text"), 
			GetSQLValueString($_REQUEST["Period"], "text"), 
			GetSQLValueString($_REQUEST["Quantity"], "int"), 
			GetSQLValueString($_REQUEST["Saved"], "int"), 
			GetSQLValueString($_REQUEST["Disposition"], "text"), 
			GetSQLValueString($_REQUEST["Description"], "text"), 
			GetSQLValueString($_REQUEST["Type"], "text"), 
			GetSQLValueString($_REQUEST["CreateDate"], "text"), 
			GetSQLValueString($_REQUEST["Comments"], "text"), 
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
						"PeriodCode" => $_REQUEST["PeriodCode"], #
						"Period" => $_REQUEST["Period"], #
						"Quantity" => $_REQUEST["Quantity"], #
						"Saved" => $_REQUEST["Saved"], #
						"Disposition" => $_REQUEST["Disposition"], #
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
function deleteObject() {
	global $conn;
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