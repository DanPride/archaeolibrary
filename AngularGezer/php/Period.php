<?php
//ini_set('display_errors',1);
//error_reporting(E_ALL|E_STRICT);
function findPeriods() {
	global $conn, $filter_field, $filter_type;
	$filter_field = "Visible";
	$filter_type = "text";
	/**
	 * the list of fields in the table. We need this to check that the sent value for the ordering is indeed correct.
	 */
	$fields = array('Id','ListOrder','Visible','Period','Code','SubPeriod');

	$where = "";
	if (@$_REQUEST['filter'] != "") {
		$where = "WHERE " . $filter_field . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], $filter_type);	
	}
	if (@$_REQUEST['filter'] == "List All") {
		$where = "";	
	}

	$order = "";
	if (@$_REQUEST["orderField"] != "" && in_array(@$_REQUEST["orderField"], $fields)) {
		$order = "ORDER BY " . @$_REQUEST["orderField"] . " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
	}
	//calculate the number of rows in this table
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Periods` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];
	
	//get the page number, and the page size
	$pageNum = (int)@$_REQUEST["pageNum"];
	$pageSize = (int)@$_REQUEST["pageSize"];
	
	//calculate the start row for the limit clause
	$start = $pageNum * $pageSize;

	//construct the query, using the where and order condition
	$query_recordset = "SELECT Id,ListOrder,Visible,Period,Code,SubPeriod FROM `Periods`";
	
	//if we use pagination, add the limit clause
	if ($pageNum >= 0 && $pageSize > 0) {	
		$query_recordset = sprintf("%s LIMIT %d, %d", $query_recordset, $start, $pageSize);
	}

	$recordset = mysql_query($query_recordset, $conn);
	
	//if we have rows in the table, loop through them and fill the array
	$toret = array();
	$arrayBase = array("Id"=> null, "ListOrder"=> null, "Visible"=> null, "Period"=> null, "Code"=> "All Periods", "SubPeriod"=> null);
	array_push($toret, $arrayBase);
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


function rowCountPeriod() {
	global $conn, $filter_field, $filter_type;

	$where = "";
	if (@$_REQUEST['filter'] != "") {
		$where = "WHERE " . $filter_field . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], $filter_type);	
	}

	//calculate the number of rows in this table
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Periods` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];
	
	//create the standard response structure
	$toret = array(
		"data" => $totalrows, 
		"metadata" => array()
	);

	return $toret;
}

?>
