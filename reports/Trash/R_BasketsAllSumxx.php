<?php 
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot .  "functions.inc.php");
require_once($theRoot .  "functions.php");
require_once($theRoot .  "XmlSerializer.class.php");
session_start();
confirm_logged_in("BasketsAllSum");

	$Year = "";
	$Table = array();
	if(isset($_POST['year'])){
	if($_POST['year'] == "All"){
		$Year = GetSQLValueStringForSelect("0", "text");	
	} else {
		$Year = $_POST['year'];	
	}}
function findAll() {
	global $conn,$Year;

	$where = "";
	$where = "WHERE " . 'Date' . " LIKE " . GetSQLValueStringForSelect($Year, "text") . " AND " . 'Status' . " LIKE " . GetSQLValueStringForSelect("DONE", "text") ;

	$order = "ORDER BY " . $_POST['Order']  . " " . $_POST['UpDown'];
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
	if(!isset($_POST['Code'])){
	$query_recordset = "SELECT Name,Field,Square,Locus,Basket FROM `Pottery` $where $order";
	}else{
	$query_recordset = "SELECT Name,Locus FROM `Pottery` $where $order";
	}
	//if we use pagination, add the limit clause
	if ($pageNum >= 0 && $pageSize > 0) {	
		$query_recordset = sprintf("%s LIMIT %d, %d", $query_recordset, $start, $pageSize);
	}

	$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		array_push($toret, $row_recordset);
	}
	return $toret;
}

function shortListPeriods($Name) {
	global $conn;
	$where = "";
	$where = "WHERE " . 'Name' . " LIKE " . GetSQLValueStringForSelect($Name, "text");
	$order = "ORDER BY " .  'Name' . " " . "ASC";
	//calculate the number of rows in this table
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Objects` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];

	//get the page number, and the page size
	$pageNum = (int)@$_REQUEST["pageNum"];
	$pageSize = (int)@$_REQUEST["pageSize"];

	//calculate the start row for the limit clause
	$start = $pageNum * $pageSize;

	//construct the query, using the where and order condition
	$query_recordset = "SELECT PeriodCode,Type FROM `Objects` $where $order";

	//if we use pagination, add the limit clause
	if ($pageNum >= 0 && $pageSize > 0) {	
		$query_recordset = sprintf("%s LIMIT %d, %d", $query_recordset, $start, $pageSize);
	}

	$recordset = mysql_query($query_recordset, $conn);
	$toret = array();	
	while ($row_recordset = mysql_fetch_array($recordset)) {
			if($row_recordset[1] == "Pottery"){
			array_push($toret, $row_recordset[0]);
			}else {
			array_push($toret, $row_recordset[1]);	
			}
	}
	return  $toret;
}

 if(isset($_POST['year'])){
		if($_POST['year'] == "All"){ $Season = "Seasons";} else {$Season = "Season";}
		$MCYear = $_POST['year'] . " " . $Season ;
		$_SESSION['year']=$_POST['year'];
		$_SESSION['sort']=$_POST['Order'];
		$_SESSION['UpDown']=$_POST['UpDown'];
	} else {
		$MCYear = "<b>Select Season</b>";
	}?>
	<?Php reportsHeader("Pottery Basket Reading Summary",$MCYear);  ?>

<form method="post" action="R_BasketsAllSum.php">
<p align =center> &nbsp; &nbsp; &nbsp;  &nbsp;
	<?Php yearPop(); ?>
	<?Php sortPop(); ?>
	<?Php updownPop(); ?>
	
<input type="submit" name="submit" value="Submit"/>
 Simple View: <input type="checkbox" name="Code" value="True" />
</p>
</form>
<CENTER>
<table align="center" border="2" cellpadding="7" cellspacing="0" >
	<tr>
	<td class="name">Name</td>
	<?php
	if(!isset($_POST['Code'])){
		$CodeList =	"<td class=\"field\">Fld</td>";
		$CodeList .= "<td class=\"square\">Sq</td>";
		$CodeList .= "<td class=\"locus\">Locus</td>";
		$CodeList .= "<td class=\"basket\">Basket</td>";
	echo $CodeList;} else {
	echo "<td class=\"locus\">Locus</td>";
	}
	?>
	<td class="contents">Basket Contents</td>
	</tr>
<?php 
if(isset($_POST['year'])){
	$Table = findALL();
 	foreach ($Table as $record){
	echo "<tr/><td/>";
	echo  implode("</td><td>", $record) . "</td>"; 
	$ShortArray = shortListPeriods($record['Name']);
	echo "<td>" .   implode(", ", $ShortArray) . "</td></tr>";
	}} ?>
</Table>
</body>
</html>