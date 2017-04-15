<?php 
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot .  "functions.inc.php");
require_once($theRoot .  "functions.php");
require_once($theRoot .  "XmlSerializer.class.php");
session_start();
confirm_logged_in("BasketSquare");
function findAll() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Field','Square','Locus','Basket','Period','Quantity','Saved','Description','Disposition','Type','CreateDate','Comments');
	$where = "";
		$Switcher = $_SESSION['Display'];
		switch($Switcher)
		{
		case 'All':
			$where = "WHERE " . 'Square' . " LIKE " . GetSQLValueStringForSelect($_POST['Square'], "text");
		break;
		case 'Pottery':
			$where = "WHERE " . 'Square' . " LIKE " . GetSQLValueStringForSelect($_POST['Square'], "text") . " AND " . 'Type' . " LIKE 'Pottery'";
		break;
		case 'Culture':
			$where = "WHERE " . 'Square' . " LIKE " . GetSQLValueStringForSelect($_POST['Square'], "text") . " AND " . 'Type' . " !=  'Pottery'";
		break;
		}
		$order = "ORDER BY " . $_POST['Order']  . " " .  $_POST['UpDown'];
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
	$query_recordset = "SELECT Name,Field,Square,Locus,Basket,Type,Period,Quantity,Saved,Description,Comments,Disposition,CreateDate FROM `Objects` $where $order";
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
}	?>	
<?php 
$_SESSION['square']="A03";
if(isset($_POST['Square'])){
$Square =  "Square " . $_POST['Square']; 
$_SESSION['square']=$_POST['Square'];
$_SESSION['group']=$_POST['group'];
$_SESSION['year']=$_POST['year'];
$_SESSION['sort']=$_POST['Order'];
$_SESSION['Display']=$_POST['Display'];
$_SESSION['Period']=$_POST['Period'];
$_SESSION['UpDown']=$_POST['UpDown'];
$_SESSION['view']=$_POST['view'];
} else {$Square = "<b>Enter a Square (A03)</b>";
	visitorReport("Sq Baskets");}?>

<?Php reportsHeader("Square Basket Contents",$Square); ?>
<form method="post" action="RG_BasketSquare.php" >
<p align=center> &nbsp; Square:  &nbsp;<input type="text" size="3" name="Square" value=<?Php echo $_SESSION['square'];?> />  &nbsp;
	 <?Php 
	typePop();
	sortPop();
	UpDownPop();
	groupPop();
	viewPop();
	?>
 &nbsp;<input type="submit" name="submit" value="Submit" /> &nbsp;  
</p>
</form>
</p>
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
				$CodeList .= "<td class=\"locus\">Locus</td>";
				 break;  
				case Number:
				$CodeList =	"<td class=\"field\">Fld</td>";
				$CodeList .= "<td class=\"square\">Sq</td>";
				$CodeList .= "<td class=\"locus\">Locus</td>";
				$CodeList .= "<td class=\"basket\">Basket</td>";
				break;
				default:
				$CodeList = "<td class=\"name\">Name</td>";
				$CodeList .=	"<td class=\"field\">Fld</td>";
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
	$lastValue = "xx";
	$Table = findALL();
 	foreach ($Table as $record){
		$NameDisplay = substr($record['Name'],0,6) . "<b>" . substr($record['Name'],6,6) . "</b>" .  substr($record['Name'],12,6);
			echo "<tr>";
			switch ($_SESSION['group'])
				{
				case None:
				$shadeValue = "x";
				break;
				case Basket:
				$shadeValue = $record['Basket'];
				break;
				case Locus:
				$shadeValue = $record['Locus'];
				break;
				case Square:
				$shadeValue = $record['Square'];
				break;
			}
			if ($shadeValue !== $lastValue){
				if ($Back == true){
					$Back = false;
				} else {
					$Back = true;
				}}
				$lastValue = $shadeValue;
		  if($_POST['Order'] !== 'Name'){$Back = true;}
			if($Back == true){
			$nameData = "namedata";
			$fieldData = "fielddata";
			$squareData = "squaredata";
			$locusData = "locusdata";
			$basketData = "basketdata";
			$typeData = "typedata";
			$periodData = "perioddata";
			$quanData = "quandata";
			$savedData = "saveddata";
			$descrData = "descrdata";
			$commData = "commdata";
			$dispData = "dispdata";
			$dateData = "datedata";

			} else {
			$nameData = "namedata shaded";
			$fieldData = "fielddata shaded";
			$squareData = "squaredata shaded";
			$locusData = "locusdata shaded";
			$basketData = "basketdata shaded";
			$typeData = "typedata shaded";
			$periodData = "perioddata shaded";
			$quanData = "quandata shaded";
			$savedData = "saveddata shaded";
			$descrData = "descrdata shaded";
			$commData = "commdata shaded";
			$dispData = "dispdata shaded";
			$dateData = "datedata shaded";
			}

		switch ($_SESSION['view'])
						{
						case Full:
							echo  "<td class=\"" . $nameData . "\">" . $NameDisplay . "</td>";
							echo  "<td class=\"" . $fieldData . "\">" . $record['Field'] . "</td>"; 
							echo  "<td class=\"" . $squareData . "\">" . $record['Square'] . "</td>"; 
							echo  "<td class=\"" . $locusData . "\">" . $record['Locus'] . "</td>"; 
							echo  "<td class=\"" . $basketData . "\">" . $record['Basket'] . "</td>"; 
						 break;
						case Sequence:
								echo  "<td class=\"" . $nameData . "\">" . $NameDisplay . "</td>";
								//echo  "<td class=\"" . $fieldData . "\">" . $record['Field'] . "</td>"; 
								//echo  "<td class=\"" . $squareData . "\">" . $record['Square'] . "</td>"; 
								echo  "<td class=\"" . $locusData . "\">" . $record['Locus'] . "</td>"; 
								//echo  "<td class=\"" . $basketData . "\">" . $record['Basket'] . "</td>";
						 break;  
						case Number:
							//	echo  "<td class=\"" . $nameData . "\">" . $NameDisplay . "</td>";
								echo  "<td class=\"" . $fieldData . "\">" . $record['Field'] . "</td>"; 
								echo  "<td class=\"" . $squareData . "\">" . $record['Square'] . "</td>"; 
								echo  "<td class=\"" . $locusData . "\">" . $record['Locus'] . "</td>"; 
								echo  "<td class=\"" . $basketData . "\">" . $record['Basket'] . "</td>";
						break;
						default:
								echo  "<td class=\"" . $nameData . "\">" . $NameDisplay . "</td>";
								echo  "<td class=\"" . $fieldData . "\">" . $record['Field'] . "</td>"; 
								echo  "<td class=\"" . $squareData . "\">" . $record['Square'] . "</td>"; 
								echo  "<td class=\"" . $locusData . "\">" . $record['Locus'] . "</td>"; 
								echo  "<td class=\"" . $basketData . "\">" . $record['Basket'] . "</td>";
						break;
					}

			echo   "<td class=\"" . $typeData . "\">" . $record['Type'] . "</td>"; 
			echo   "<td class=\"" . $periodData . "\">" . $record['Period'] . "</td>"; 
			echo   "<td class=\"" . $quanData . "\">" . $record['Quanity'] . "</td>"; 
			echo   "<td class=\"" . $savedData . "\">" . $record['Saved'] . "</td>"; 
			echo   "<td class=\"" . $descrData . "\">" . $record['Description'] . "</td>"; 
			echo   "<td class=\"" . $commData . "\">" . $record['Comments'] . "</td>"; ;
			echo   "<td class=\"" . $dispData . "\">" . $record['Disposition'] . "</td>"; 
			echo   "<td class=\"" . $dateData . "\">" . dateFormat($record['CreateDate']) . "</td>";
			echo "</tr>";
			} }?>
		</Table>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-1223547-1";
urchinTracker();
</script>
</body>
</html>