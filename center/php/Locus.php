<?php
function findLocus() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Locus');
	$where = "";
		if (@$_REQUEST['filter'] != "") 
		{
			$where = "WHERE " . 'Square' . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], "text");
		}
	$order = "";
	if (@$_REQUEST["orderField"] != "" && in_array(@$_REQUEST["orderField"], $fields)) {
		$order = "ORDER BY " . @$_REQUEST["orderField"] . " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
	}
	$query_recordset = "SELECT Id,Name,DFC,DLC,User,Locus FROM `Loca` $where $order";
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

function findLocusold() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Locus' );
	$where = "";
		if (@$_REQUEST['filter'] != "") 
		{
			$Switcher = @$_REQUEST['searchDef'];
			switch($Switcher)
			{
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
	if (@$_REQUEST["orderField"] != "" && in_array(@$_REQUEST["orderField"], $fields)) 
	{
		$order = "ORDER BY " . @$_REQUEST["orderField"] . " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
	}
	$query_recordset = "SELECT Id,Locus FROM `Locus` $where $order";
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
function newLocus() {
	
}
?>