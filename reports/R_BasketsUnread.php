<?php 
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot .  "functions.inc.php");
require_once($theRoot .  "functions.php");
require_once($theRoot .  "XmlSerializer.class.php");
session_start();
confirm_logged_in("BasketsUnread");
function findAll() {
	global $conn, $totalrows ;
	$where = "";
	$where = "WHERE " . 'Status' . " LIKE " . GetSQLValueStringForSelect("READ", "text");
	$order = "ORDER BY " . $_POST['Order'] . " " . $_POST['UpDown'];
	//calculate the number of rows in this table
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Pottery` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];

	$pageNum = (int)@$_REQUEST["pageNum"];
	$pageSize = (int)@$_REQUEST["pageSize"];
	$start = $pageNum * $pageSize;
	if(!isset($_POST['Code'])){
	$query_recordset = "SELECT Name,Field,Square,Locus,Basket,CreateDate,Status FROM `Pottery` $where $order";
	}else {
	$query_recordset = "SELECT Name,Locus,CreateDate,Status FROM `Pottery` $where $order";
	}
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

function countBaskets() {
		global $conn;
		$where = "";
		$where = "WHERE " . 'Status' . " LIKE " . GetSQLValueStringForSelect('READ', "text");

		//calculate the number of rows in this table
		$rscount = mysql_query("SELECT count(*) AS cnt FROM `Pottery` $where"); 
		$row_rscount = mysql_fetch_assoc($rscount);
		$totalrows = (int) $row_rscount["cnt"];
		return 	$totalrows;
	}
	
	if(isset($_POST['Order'])){
	$_SESSION['UpDown']=$_POST['UpDown'];
	$_SESSION['sort']=$_POST['Order'];}
	
	

if(countBaskets() == 1){
	$Basket = "Basket";
} else {
	$Basket = "Baskets";
}

$BasketYard = countBaskets() . " " . $Basket .  " in the Yard at " . date("d/m/y : H:i:s", time());
 reportsHeader("Unread Baskets List"); ?>
	<form action="R_BasketsUnread.php" method="post">
		<p align=center>
		<?Php sortPop(); ?>
		<?Php updownPop(); ?> 
		<input type="submit" name="submit" value="Submit" />
		 &nbsp; Simple View: <input type="checkbox" name="Code" value="True" />
<H3 align=center> <?php echo countBaskets() . " " . $Basket ?>  in the Yard at 
 <?php echo date("d/m/y : H:i:s", time())  ?> </H3>
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
		$CodeList .= "<td class=\"locus\">Locus</td>";
		}
		?>
		<td class="date">CheckIn</td>
		<td class="status">Status</td>
	</tr>
<?php 
	if(isset($_POST['Order'])){
	$Table = findALL();
 	foreach ($Table as $record){
	echo "<tr/><td/>";
	echo  implode("</td><td>", $record) . "</tr>"; 
	}} ?>
</Table>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-1223547-1";
urchinTracker();
</script>
</body>
</html>