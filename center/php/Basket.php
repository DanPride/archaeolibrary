<?php
function findBaskets() {
		global $conn, $filter_field, $filter_type;
		$fields = array('Id','DFC','DLC','User','Name','Field','Square','Locus','Basket','Periods','Context','Disposition');

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
				}
					$where = "WHERE " . $SearchField . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], "text");
			}
		$order = "";
		if (@$_REQUEST["orderField"] != "" && in_array(@$_REQUEST["orderField"], $fields)) {
			$order = "ORDER BY " . @$_REQUEST["orderField"] . " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
		}	
		$query_recordset = "SELECT Id,DFC,DLC,User,Name,Field,Square,Locus,Basket,Status,Periods,Context,Disposition FROM `Pottery` $where $order";
		$recordset = mysql_query($query_recordset, $conn);
		$toret = array();
		while ($row_recordset = mysql_fetch_assoc($recordset)) {
			$row_recordset["ShortName"] = substr($row_recordset["Name"],3);
			array_push($toret, $row_recordset);
		}
		$toret = array(
			"data" => $toret, 
			"metadata" => array (
			)
		);
		return $toret;
	}
	function insertBasket() {
		global $conn;
		$query_insert = sprintf("INSERT INTO `Pottery` (Name,Field,Square,Locus,Basket,Periods,Context,Disposition,Status) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)" ,	
				GetSQLValueString($_SESSION["Name"], "text"), # 		
				GetSQLValueString($_REQUEST["Name"], "text"), # 
				GetSQLValueString($_REQUEST["Field"], "text"), # 
				GetSQLValueString($_REQUEST["Square"], "text"), # 
				GetSQLValueString($_REQUEST["Locus"], "text"), # 
				GetSQLValueString($_REQUEST["Basket"], "text"), # 
				GetSQLValueString($_REQUEST["Periods"], "text"), # 
				GetSQLValueString($_REQUEST["Context"], "text"), # 
				GetSQLValueString($_REQUEST["Disposition"], "text"), # 
				GetSQLValueString($_REQUEST["Status"], "text")# 
		);
		$ok = mysql_query($query_insert);

		if ($ok) {
			// return the new entry, using the insert id
			$toret = array(
				"data" => array(
					array(
						"Id" => mysql_insert_id(),
						"Name" => $_REQUEST["Name"], # 
						"Field" => $_REQUEST["Field"], # 
						"Square" => $_REQUEST["Square"], # 
						"Locus" => $_REQUEST["Locus"], # 
						"Basket" => $_REQUEST["Basket"], # 
						"Periods" => $_REQUEST["Periods"], # 
						"Context" => $_REQUEST["Context"], # 
						"Disposition" => $_REQUEST["Disposition"], # 
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

	function updateBasket() {
		global $conn;
		$query_recordset = sprintf("SELECT * FROM `Pottery` WHERE Id = %s",
			GetSQLValueString($_REQUEST["Id"], "int")
		);
		$recordset = mysql_query($query_recordset, $conn);
		$num_rows = mysql_num_rows($recordset);

		if ($num_rows > 0) {
			$row_recordset = mysql_fetch_assoc($recordset);
			$query_update = sprintf("UPDATE `Pottery` SET DLC = now(),User = %s,Field = %s,Square = %s,Locus = %s,Basket = %s,Periods = %s,Context = %s,Disposition = %s,Status = %s WHERE Id = %s", 
				GetSQLValueString($_SESSION["Name"], "text"), 
				GetSQLValueString($_REQUEST["Field"], "text"), 
				GetSQLValueString($_REQUEST["Square"], "text"), 
				GetSQLValueString($_REQUEST["Locus"], "text"), 
				GetSQLValueString($_REQUEST["Basket"], "text"), 
				GetSQLValueString($_REQUEST["Periods"], "text"), 
				GetSQLValueString($_REQUEST["Context"], "text"), 
				GetSQLValueString($_REQUEST["Disposition"], "text"), 
				GetSQLValueString($_REQUEST["Status"], "text"), 
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
							"Periods" => $_REQUEST["Periods"], #
							"Context" => $_REQUEST["Context"], #
							"Disposition" => $_REQUEST["Disposition"], #
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

function deleteBasket() {
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
			$toret = array(
				"data" => array("error" => "No row found"), 
				"metadata" => array()
			);
		}
		return $toret;
	}	
	
?>