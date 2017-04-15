<?php
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot .  "functions.inc.php");
require_once($theRoot .  "functions.php");
require_once($theRoot .  "XmlSerializer.class.php");
session_start();
confirm_logged_in("BasketsAllBone");
	$Year = "";
	$Table = array();
	if(isset($_POST['year'])){
	if($_POST['year'] == "All"){
	//	$Year = GetSQLValueStringForSelect("0", "text");
	$Year = "0";	
	} else {
		$Year = $_POST['year'];	
	}}
function findAll() {
	global $conn,$Year;
	if(isset($_POST['year'])){
	if($_POST['year'] == "All"){
		$Year = "0";	
	} else {
		$Year = $_POST['year'];	
	}}
	$where = "";
	$where = "WHERE " . 'CreateDate' . " LIKE " . GetSQLValueStringForSelect($Year, "text") . " AND " . 'Status' . " LIKE " . GetSQLValueStringForSelect("DONE", "text") ;

	$thedate = 	$_POST['Order'] ;
	if($thedate == "Date"){$thedate ="DFC";}	
	$order = "ORDER BY " . $thedate   . " " .  $_POST['UpDown'];
	//calculate the number of rows in this table
	
	if(!isset($_POST['Code'])){
	$query_recordset = "SELECT Name,Field,Square,Locus,Basket,CreateDate FROM `Pottery` $where $order";
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
		$_SESSION['group']=$_POST['group'];
		$_SESSION['view']=$_POST['view'];
	} else {
		$MCYear = "<b>Select Season</b>";
		visitorReport("BasketsAllBone");
	}?>
	<?Php reportsHeader("Pottery Basket's with Bone Reading Summary",$MCYear);  ?>

<form method="post" action="R_BasketsAllBone.php">
<p align =center> &nbsp; &nbsp; &nbsp;  &nbsp;
	<?Php 
	yearPop();
	sortPop();
	updownPop(); 
	groupPop();
	viewPop();
	?>
<input type="submit" name="submit" value="Submit"/>
</p>
</form>
<CENTER>
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
				$CodeList .= "<td class=\"field\">Fld</td>";
				$CodeList .= "<td class=\"square\">Sq</td>";
				$CodeList .= "<td class=\"locus\">Locus</td>";
				$CodeList .= "<td class=\"basket\">Basket</td>";
				break;
			}
			echo $CodeList;
		?>
	<td class="contents">Basket Contents</td>
	<td class="date">Date</td>
	</tr>
	<?php 
	if(isset($_POST['year'])){
	$lastValue = "xx";	
		$Table = findALL();
	 	foreach ($Table as $record){
				$ShortArray = shortListPeriods($record['Name']);
				if(in_array("Bone",$ShortArray)){
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
					$dateData = "datedata";
				
				} else {
				$nameData = "namedata shaded";
				$fieldData = "fielddata shaded";
				$squareData = "squaredata shaded";
				$locusData = "locusdata shaded";
				$basketData = "basketdata shaded";
				$typeData = "typedata shaded";
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
					
				echo   "<td class=\"" . $typeData . "\">" . implode(", ", $ShortArray) . "</td>"; 
				echo   "<td class=\"" . $dateData . "\">" . dateFormat($record['CreateDate']) . "</td>"; 
			}
				} }?>
</Table>
</body>
</html>