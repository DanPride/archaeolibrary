<?php 
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot .  "functions.inc.php");
require_once($theRoot .  "functions.php");
require_once($theRoot .  "XmlSerializer.class.php");
session_start();
confirm_logged_in("PotteryPeriod"); 
//---------------------------
function findAll() {
	global $conn, $filter_field, $filter_type;
	$where = "";
	$Switcher = $_POST['Display'];
	switch($Switcher){
	case 'All':
	$where = "WHERE " . 'Period' . " LIKE " .  GetSQLValueStringForSelect($_POST['Period'] ,"text");
	break;
	case 'Pottery':
	$where = "WHERE " . 'Period' . " LIKE " . GetSQLValueStringForSelect($_POST['Period'], "text") . " AND " . 'Type' . " LIKE 'Pottery'";
	break;
	case 'Culture':
	$where = "WHERE " . 'Period' . " LIKE " . GetSQLValueStringForSelect($_POST['Period'], "text") . " AND " . 'Type' . " !=  'Pottery'";
	break;	}	
	$thedate = 	$_POST['Order'] ;
	if($thedate == "Date"){$thedate ="DFC";}	
	$order = "ORDER BY " . $thedate   . " " .  $_POST['UpDown'];
	//calculate the number of rows in this table


	//construct the query, using the where and order condition
	$query_recordset = "SELECT Name,Field,Square,Locus,Basket,Type,Period,Quantity,Saved,Description,Comments,Disposition,DFC FROM `Objects` $where $order"; 
	//if we use pagination, add the limit clause

	$recordset = mysql_query($query_recordset, $conn);

	//if we have rows in the table, loop through them and fill the array
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		array_push($toret, $row_recordset);
	}
	return $toret;
}
$_SESSION['group'] = 'Locus';
if(isset($_POST['Period'])){
$TheLocus = "Period: " . $_POST['Period'];
$_SESSION['year']=$_POST['year'];
$_SESSION['sort']=$_POST['Order'];
$_SESSION['Display']=$_POST['Display'];
$_SESSION['Period']=$_POST['Period'];
$_SESSION['UpDown']=$_POST['UpDown'];
$_SESSION['group']=$_POST['group'];
$_SESSION['view']=$_POST['view'];
} else {
$TheLocus = "<b>Enter Letters in a Period</b>";
	visitorReport("Period");
}

?>
<?Php reportsHeader("Basket Artifacts by Period",$TheLocus); ?>
<form action="RG_PotteryPeriod.php" method="POST">
<p align=center>Period: <input type="text"  size="9" name="Period" value="Hell" /> 
 <?Php 	typePop(); 
		sortPop(); 
		updownPop(); 
		groupPop();
		viewPop(); ?> 
<input type="submit" name="submit" value="Submit" />
</p>
</form>
</p>	
<table align=center border="2" cellpadding="7" cellspacing="0" >
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
	if(isset($_POST['Period'])){
		$lastValue = "xxx";
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
				echo   "<td class=\"" . $dateData . "\">" . dateFormat($record['DFC']) . "</td>";
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