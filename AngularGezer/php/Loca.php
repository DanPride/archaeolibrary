<?php
function findLoca() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','DFC','DLC','User','Name','Field','Square','Locus','Type','M_Composition','Quality','M_Formation');
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
				case 'Name':
					$SearchField = 'Name';
				break;
			}
			$where = "WHERE " . $SearchField . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], "text");
		}
	$order = "ORDER BY Locus";
	if (@$_REQUEST["orderField"] != "" && in_array(@$_REQUEST["orderField"], $fields)) {
		$order = "ORDER BY " . @$_REQUEST["orderField"] . " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
	}	
$query_recordset = "SELECT Id,DFC,DLC,User,Name,Type,Field,Square,Locus,Length,Width,Quality,M_Formation,Stratigraphy,Method,Sieved,Remarks,Rationale,Definition,Description,Interpretation,M_Type,M_Compaction,M_Composition,Relationships FROM `Loca` $where $order";
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

function insertLoca() {
	global $conn;
	$Name = "GZ" . $_REQUEST["Field"] . $_REQUEST["Square"] . "XXX";
	$query_insert = sprintf("INSERT INTO `Loca` (Name,Type,Field,Square,Locus,Rationale,Definition,Description,Interpretation,Stratigraphy,Method,Sieved,Quality,QualCom,Length,Width,Remarks,M_Formation,M_Compaction,M_Type,M_Composition,M_Color,M_Inclusions,Relationships,CreateDate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,now())" ,			
			GetSQLValueString($Name, "text"), # 
			GetSQLValueString($_REQUEST["Type"], "text"), # 
			GetSQLValueString($_REQUEST["Field"], "text"), # 
			GetSQLValueString($_REQUEST["Square"], "text"), # 
			GetSQLValueString($_REQUEST["Locus"], "text"), # 
			GetSQLValueString($_REQUEST["Rationale"], "text"), # 
			GetSQLValueString($_REQUEST["Definition"], "text"), # 
			GetSQLValueString($_REQUEST["Description"], "text"), # 
			GetSQLValueString($_REQUEST["Interpretation"], "text"), # 
			GetSQLValueString($_REQUEST["Stratigraphy"], "text"), # 
			GetSQLValueString($_REQUEST["LocaMethod"], "text"), # 
			GetSQLValueString($_REQUEST["Sieved"], "text"), # 
			GetSQLValueString($_REQUEST["Quality"], "text"), # 
			GetSQLValueString($_REQUEST["QualCom"], "text"), # 
			GetSQLValueString($_REQUEST["Length"], "int"), # 
			GetSQLValueString($_REQUEST["Width"], "int"), # 
			GetSQLValueString($_REQUEST["Remarks"], "text"), # 
			GetSQLValueString($_REQUEST["M_Formation"], "text"), # 
			GetSQLValueString($_REQUEST["M_Compaction"], "text"), # 
			GetSQLValueString($_REQUEST["M_Type"], "text"), # 
			GetSQLValueString($_REQUEST["M_Composition"], "text"), # 
			GetSQLValueString($_REQUEST["M_Color"], "text"), # 
			GetSQLValueString($_REQUEST["M_Inclusions"], "text"), # 
			GetSQLValueString($_REQUEST["Relationships"], "text"), # 
			GetSQLValueString($_REQUEST["CreateDate"], "text")# 
	);
	$ok = mysql_query($query_insert);
	
	if ($ok) {
		// return the new entry, using the insert id
		$toret = array(
			"data" => array(
				array(
					"Id" => mysql_insert_id(), 
					"CreateDate" => $_REQUEST["CreateDate"], # 
					"Type" => $_REQUEST["Type"], # 
					"Name" => $_REQUEST["Name"], # 
					"Field" => $_REQUEST["Field"], # 
					"Square" => $_REQUEST["Square"], # 
					"Locus" => $_REQUEST["Locus"], # 
					"Rationale" => $_REQUEST["Rationale"], # 
					"Definition" => $_REQUEST["Definition"], # 
					"Description" => $_REQUEST["Description"], # 
					"Interpretation" => $_REQUEST["Interpretation"], # 
					"Stratigraphy" => $_REQUEST["Stratigraphy"], # 
					"Method" => $_REQUEST["LocaMethod"], # 
					"Sieved" => $_REQUEST["Sieved"], # 
					"Quality" => $_REQUEST["Quality"], # 
					"QualCom" => $_REQUEST["QualCom"], # 
					"Length" => $_REQUEST["Length"], # 
					"Width" => $_REQUEST["Width"], # 
					"Remarks" => $_REQUEST["Remarks"], # 
					"M_Formation" => $_REQUEST["M_Formation"], # 
					"M_Compaction" => $_REQUEST["M_Compaction"], # 
					"M_Type" => $_REQUEST["M_Type"], # 
					"M_Composition" => $_REQUEST["M_Composition"], # 
					"M_Color" => $_REQUEST["M_Color"], # 
					"M_Inclusions" => $_REQUEST["M_Inclusions"], # 
					"Relationships" => $_REQUEST["Relationships"]# 
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


function updateLoca() {
	global $conn;

	// check to see if the record actually exists in the database
	$query_recordset = sprintf("SELECT * FROM `Loca` WHERE Id = %s",
		GetSQLValueString($_REQUEST["Id"], "int")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);
	
	if ($num_rows > 0) {

		// build and execute the update query
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_update = sprintf("UPDATE `Loca` SET DLC = now(),User = %s,Type = %s,Field = %s,Square = %s,Locus = %s,Rationale = %s,Definition = %s,Description = %s,Interpretation = %s,Stratigraphy = %s,Method = %s,Sieved = %s,Quality = %s,QualCom = %s,Length = %s,Width = %s,Remarks = %s,M_Formation = %s,M_Compaction = %s,M_Type = %s,M_Composition = %s,M_Color = %s,M_Inclusions = %s,Relationships = %s WHERE Id = %s", 
			GetSQLValueString($_SESSION["Name"], "text"), 
			GetSQLValueString($_REQUEST["Type"], "text"), 
			GetSQLValueString($_REQUEST["Field"], "text"), 
			GetSQLValueString($_REQUEST["Square"], "text"), 
			GetSQLValueString($_REQUEST["Locus"], "text"), 
			GetSQLValueString($_REQUEST["Rationale"], "text"), 
			GetSQLValueString($_REQUEST["Definition"], "text"), 
			GetSQLValueString($_REQUEST["Description"], "text"), 
			GetSQLValueString($_REQUEST["Interpretation"], "text"), 
			GetSQLValueString($_REQUEST["Stratigraphy"], "text"), 
			GetSQLValueString($_REQUEST["LocaMethod"], "text"), 
			GetSQLValueString($_REQUEST["Sieved"], "text"), 
			GetSQLValueString($_REQUEST["Quality"], "text"), 
			GetSQLValueString($_REQUEST["QualCom"], "text"), 
			GetSQLValueString($_REQUEST["Length"], "int"), 
			GetSQLValueString($_REQUEST["Width"], "int"), 
			GetSQLValueString($_REQUEST["Remarks"], "text"), 
			GetSQLValueString($_REQUEST["M_Formation"], "text"), 
			GetSQLValueString($_REQUEST["M_Compaction"], "text"), 
			GetSQLValueString($_REQUEST["M_Type"], "text"), 
			GetSQLValueString($_REQUEST["M_Composition"], "text"), 
			GetSQLValueString($_REQUEST["M_Color"], "text"), 
			GetSQLValueString($_REQUEST["M_Inclusions"], "text"), 
			GetSQLValueString($_REQUEST["Relationships"], "text"), 
			GetSQLValueString($row_recordset["Id"], "int")
		);
		$ok = mysql_query($query_update);
		if ($ok) {
			// return the updated entry
			$toret = array(
				"data" => array(
					array(
						"Id" => $row_recordset["Id"], 
						"CreateDate" => $_REQUEST["CreateDate"], #
						"Type" => $_REQUEST["Type"], #
						"Name" => $_REQUEST["Name"], #
						"Field" => $_REQUEST["Field"], #
						"Square" => $_REQUEST["Square"], #
						"Locus" => $_REQUEST["Locus"], #
						"Rationale" => $_REQUEST["Rationale"], #
						"Definition" => $_REQUEST["Definition"], #
						"Description" => $_REQUEST["Description"], #
						"Interpretation" => $_REQUEST["Interpretation"], #
						"Stratigraphy" => $_REQUEST["Stratigraphy"], #
						"Method" => $_REQUEST["Method"], #
						"Sieved" => $_REQUEST["Sieved"], #
						"Quality" => $_REQUEST["Quality"], #
						"QualCom" => $_REQUEST["QualCom"], #
						"Length" => $_REQUEST["Length"], #
						"Width" => $_REQUEST["Width"], #
						"Remarks" => $_REQUEST["Remarks"], #
						"M_Formation" => $_REQUEST["M_Formation"], #
						"M_Compaction" => $_REQUEST["M_Compaction"], #
						"M_Type" => $_REQUEST["M_Type"], #
						"M_Composition" => $_REQUEST["M_Composition"], #
						"M_Color" => $_REQUEST["M_Color"], #
						"M_Inclusions" => $_REQUEST["M_Inclusions"], #
						"Relationships" => $_REQUEST["Relationships"]#
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

function deleteLoca() {
	global $conn;
	$query_recordset = sprintf("SELECT * FROM `Loca` WHERE Id = %s",
		GetSQLValueString($_REQUEST["Id"], "int")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);
	if ($num_rows > 0) {
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_delete = sprintf("DELETE FROM `Loca` WHERE Id = %s", 
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