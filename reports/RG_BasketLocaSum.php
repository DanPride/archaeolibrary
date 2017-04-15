<?php 
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot .  "functions.inc.php");
require_once($theRoot .  "functions.php");
require_once($theRoot .  "XmlSerializer.class.php");
session_start();
confirm_logged_in("BasketLocaSum");		
function findAll() {
	global $conn;
	$where = "";
	$where = "WHERE " . 'Square' . " LIKE " . GetSQLValueStringForSelect($_REQUEST["Square"], "text") . 
	" AND " . 'Locus' . " LIKE " . GetSQLValueStringForSelect($_REQUEST["Locus"], "text");
	$order = "ORDER BY " . 'Name' . " "  . "ASC";
	//calculate the number of rows in this table
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Pottery` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];

	//get the page number, and the page size
	$pageNum = (int)@$_REQUEST["pageNum"];
	$pageSize = (int)@$_REQUEST["pageSize"];

	//calculate the start row for the limit clause
	$start = $pageNum * $pageSize;

	switch ($_SESSION['view'])
	{
	case Full:
	$query_recordset = "SELECT Name,Field,Square,Locus,Basket,Status FROM `Pottery` $where $order";
	 break;
	case Sequence:
	$query_recordset = "SELECT Name,Status FROM `Pottery` $where $order";
	 break;  
	case Number:
	$query_recordset = "SELECT Name,Basket,Status FROM `Pottery` $where $order";
	break;
	default:
	$query_recordset = "SELECT Name,Field,Square,Locus,Basket,Status FROM `Pottery` $where $order";
	 break;
}
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
	if(isset($_POST['Locus'])){
	$TheSquareLocus = "Square: " . $_POST['Square'] . " Locus: " . $_POST['Locus'];
//	$_SESSION['year']=$_POST['year'];
//	$_SESSION['type']=$_POST['type'];
	$_SESSION['sort']=$_POST['Order'];
	$_SESSION['UpDown']=$_POST['UpDown'];
	$_SESSION['view']=$_POST['view'];
	} else {
	$TheSquareLocus = "<b>Enter a Square and Locus Number</b>";
		visitorReport("Loca BSum");
	}
	reportsHeader("Basket Summary by Square locus", $TheSquareLocus); ?>
	<form action="RG_BasketLocaSum.php" method="post">
	<p align=center>
	Square: <input type="text" name="Square" size="3" value="A03" /> 
	Locus: <input type="text" name="Locus" size="5" value="32080" /> 
	<?php
	sortPop();  
	UpDownPop();  
	viewPop();
	?>
	&nbsp; <input type="submit" name="submit" value="Submit" />	&nbsp; 
	</p>
	</form>

<table align="center" border="1" cellpadding="7" cellspacing="0" >
	<tr>
			<?php 
					switch ($_SESSION['view'])
					{
					case Full:
					$CodeList = "<td class=\"name\">Name</td>";
					$CodeList .=	"<td class=\"field\">Fld</td>";
					$CodeList .= "<td class=\"square\">Sq</td>";
					$CodeList .= "<td class=\"locus\">Locus</td>";
					$CodeList .= "<td class=\"basket\">Basket</td>";
					 break;
					case Sequence:
					$CodeList = "<td class=\"name\">Name</td>";
					//$CodeList .= "<td class=\"locus\">Locus</td>";
					 break;  
					case Number:
					$CodeList = "<td class=\"name\">Name</td>";
				//	$CodeList .=	"<td class=\"field\">Fld</td>";
				//	$CodeList .= "<td class=\"square\">Sq</td>";
				//	$CodeList .= "<td class=\"locus\">Locus</td>";
					$CodeList .= "<td class=\"basket\">Basket</td>";
					break;
					default:
					$CodeList = "<td class=\"name\">Name</td>";
					$CodeList .= "<td class=\"field\">Fld</td>";
					$CodeList .= "<td class=\"square\">Sq</td>";
					$CodeList .= "<td class=\"locus\">Locus</td>";
					$CodeList .= "<td class=\"basket\">Basket</td>";
					break;
				}
			echo $CodeList;
			?>
		<td class="status">Status</td>
		<td class="contents">Basket Contents</td>
	</tr>
<?php 
if(isset($_POST['Square'])){
	$Table = findALL();
 	foreach ($Table as $record){
	echo "<tr><td>";
	echo  implode("</td><td>", $record) . "</td>"; 
	$ShortArray = shortListPeriods($record['Name']);
	echo "<td>" .   implode(", ", $ShortArray) . "</td></tr>";
	} }?>
</Table><br><br><br>
</body>
</html>