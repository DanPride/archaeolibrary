<?php 
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot .  "functions.inc.php");
require_once($theRoot .  "functions.php");
require_once($theRoot .  "XmlSerializer.class.php");
session_start();
confirm_logged_in("BasketLoca");
function findAll() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Field','Square','Locus','Basket','Period','Quantity','Saved','Description','Disposition','Type','DFC','Comments');
	$where = "";
		$Switcher = $_REQUEST['Display'];
		switch($Switcher)
		{
		case 'All':
			$where = "WHERE " . 'Square' . " LIKE " . GetSQLValueStringForSelect($_POST['Square'], "text") . " AND "   . 'Locus' . " LIKE " . GetSQLValueStringForSelect($_POST['Locus'], "text");
		break;
		case 'Pottery':
			$where = "WHERE " . 'Square' . " LIKE " . GetSQLValueStringForSelect($_POST['Square'], "text") . " AND " . 'Type' . " LIKE " . 'Pottery'   ." AND "  .  'Locus' . " LIKE " . GetSQLValueStringForSelect($_POST['Locus'], "text");
		break;
		case 'Culture':
			$where = "WHERE " . 'Square' . " LIKE " . GetSQLValueStringForSelect($_POST['Square'], "text") . " AND " . 'Type' . " !=  'Pottery'" .  " AND " . 'Locus' . " LIKE " . GetSQLValueStringForSelect($_POST['Locus'], "text");
		break;
		}
		$order = "ORDER BY " . $_POST['Order']  . " " .  $_POST['UpDown'];
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Objects` $where"); 
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
	$query_recordset = "SELECT Name,Field,Square,Locus,Basket,Type,Period,Quantity,Saved,Description,Comments,Disposition,DFC FROM `Objects` $where $order";
	 break;
	case Sequence:
	$query_recordset = "SELECT Name,Type,Period,Quantity,Saved,Description,Comments,Disposition,DFC FROM `Objects` $where $order";
	 break;  
	case Number:
	$query_recordset = "SELECT Basket,Type,Period,Quantity,Saved,Description,Comments,Disposition,DFC FROM `Objects` $where $order";
	break;
	default:
	$query_recordset = "SELECT Name,Field,Square,Locus,Basket,Type,Period,Quantity,Saved,Description,Comments,Disposition,DFC FROM `Objects` $where $order";
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
}	?>	

<?Php
if(isset($_POST['Locus'])){
$TheSquareLocus = "Square: " . $_POST['Square'] . " Locus: " . $_POST['Locus'];
$_SESSION['year']=$_POST['year'];
$_SESSION['type']=$_POST['type'];
$_SESSION['sort']=$_POST['Order'];
$_SESSION['UpDown']=$_POST['UpDown'];
$_SESSION['view']=$_POST['view'];
} else {
$TheSquareLocus = "<b>Enter a Square and Locus Number</b>";
	visitorReport("Loca Baskets");
}?>
<?Php reportsHeader("Artifact Baskets by Square Locus",$TheSquareLocus); ?>
<form action="RG_BasketLoca.php" method="post">
<p align=center>
Square: &nbsp;<input type="text" name="Square" size="3" value="A03" /> 
Locus: &nbsp;<input type="text" name="Locus" size="5" value="32080" /> 
<?Php 
	typePop(); 
	sortPop();  
	UpDownPop();  
	viewPop(); ?>
 &nbsp; <input type="submit" name="submit" value="Submit" /> &nbsp;
</p>
</form>

<table align="center" border="2" cellpadding="7" cellspacing="0" >
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
				//$CodeList .= "<td class=\"locus\">Locus</td>";
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
		<td class="type">Type</td>
		<td class="period">Period</td>
		<td class="quan">Qty</td>
		<td class="saved">Svd</td>
		<td class="descr">Description</td>
		<td class="comm">Comments</td>
		<td class="disp">Disp</td>
		<td class="date">Date</td>
		
	</tr>
<?php 
if(isset($_POST['Display'])){
	$Table = findALL();
 	foreach ($Table as $record){
	echo "<tr><td>";
	echo  implode("</td><td>", $record) . "</tr>"; 
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