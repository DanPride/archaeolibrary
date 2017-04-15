<?php 
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot .  "functions.inc.php");
require_once($theRoot .  "functions.php");
require_once($theRoot .  "XmlSerializer.class.php");
session_start();
confirm_logged_in("BasketsDaily"); 
function findDates() {
	global $conn;
	if($_POST['year'] == "All"){
		$Year = GetSQLValueStringForSelect("0", "text");	
	} else {
		$Year = GetSQLValueStringForSelect($_POST['year'] ."-%%-%%", "text");
	}
	$where = "";
	$where = "WHERE " . 'CreateDate' . " LIKE " . $Year;
	$order = "ORDER BY " . 'CreateDate' .  " " . "ASC";
	//calculate the number of rows in this table
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Pottery` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];

	//get the page number, and the page size
	$pageNum = (int)@$_REQUEST["pageNum"];
	$pageSize = (int)@$_REQUEST["pageSize"];

	//calculate the start row for the limit clause
	$start = $pageNum * $pageSize;

	//construct the query, using the where and order condition
	$query_recordset = "SELECT DISTINCT substr(CreateDate, 1, 10) FROM `Pottery` $where $order";


	//if we use pagination, add the limit clause
	if ($pageNum >= 0 && $pageSize > 0) {	
		$query_recordset = sprintf("%s LIMIT %d, %d", $query_recordset, $start, $pageSize);
	}

	$recordset = mysql_query($query_recordset, $conn);

	//if we have rows in the table, loop through them and fill the array
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		array_push($toret, $row_recordset);
	}
	return $toret;
}	

function countRecords($record) {
	global $conn;
	$where = "";
	$where = "WHERE " . 'CreateDate' . " LIKE " . GetSQLValueStringForSelect(implode("*", $record), "text");
	$order = "ORDER BY " . 'CreateDate' .  " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
	//calculate the number of rows in this table
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Pottery` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];
	return 	$totalrows;
}?>
 <?Php if(isset($_POST['year'])){
	if($_POST['year'] == "All"){ $Season = "Seasons";} else {$Season = "Season";}
	$Year = $_POST['year'] . " " . $Season ;
	$_SESSION['year']=$_POST['year'];
} else {
	$Year = "<b>Select Season</b>";
}?>
<?Php reportsHeader("Daily Pottery Baskets Checkins",$Year); ?>

<form method="post" action="R_BasketsDaily.php">
<p align =center> &nbsp;&nbsp;&nbsp;	
<?Php yearPop(); ?> &nbsp; 
<input type="submit" name="submit" value="Submit"/> &nbsp; 
</p>
</form>
<table align="center" border="2" cellpadding="7" cellspacing="0" >
	<tr>
	<td class="date">BucketDate</td>
	<td class="count">Count</td>
	</tr>
<?php 
	if(isset($_POST['year'])){
	$Table = findDates();
 	foreach ($Table as $record){
	echo "<tr/><td/>";
	echo  implode("</td><td>", $record) . "</td>"; 
	$theCount = countRecords($record);
	echo  "<td>", $theCount . "</tr>"; 
	}} ?>
</Table><br><br><br>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-1223547-1";
urchinTracker();
</script>
</body>
</html>