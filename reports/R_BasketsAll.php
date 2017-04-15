<?php 
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot .  "functions.inc.php");
require_once($theRoot .  "functions.php");
require_once($theRoot .  "XmlSerializer.class.php");
session_start();
confirm_logged_in("BasketsAll");
visitor();
	function findAll() {
		global $conn, $filter_field, $filter_type;
		$where = "";
		if($_POST['year'] == "All"){
			$Year = GetSQLValueStringForSelect("0", "text");	
		} else {
			$Year = GetSQLValueStringForSelect($_POST['year'], "text");	
		}
		$where = "WHERE " . 'Type' . " LIKE " . GetSQLValueStringForSelect('Pottery', "text") .	" AND " . 'CreateDate' . " LIKE " . $Year;
		$thedate = 	$_POST['Order'] ;
		if($thedate == "Date"){$thedate ="DFC";}	
		$order = "ORDER BY " . $thedate   . " " .  $_POST['UpDown'];
		//calculate the number of rows in this table


		//construct the query, using the where and order condition
		$query_recordset = "SELECT Name,Field,Square,Locus,Basket,Period,Description,Quantity,Saved,Comments,Disposition,CreateDate FROM `Objects` $where $order";

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
			visitorReport("BasketsAll");
		}
		?>
	
	<?Php reportsHeader("Full Basket Read List",$MCYear,"", array("Archaeology","Excavation","Biblical","Software"), "Archaeological Excavation Software"); ?>

	<form action="R_BasketsAll.php" method="post">
		<p align=center>
		<?Php 	yearPop();
				sortPop(); 
			 	updownPop(); 
				groupPop();
				viewPop();
				?>
		 &nbsp;<input type="submit" name="submit" value="Submit" />
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
				<td class="period">Period</td>
				<td class="quan">Qty</td>
				<td class="saved">Svd</td>
				<td class="descr">Description</td>
				<td class="comm">Comments</td>
				<td class="disp">Disp</td>
				<td class="date">Date</td>
		</tr>
	<?php 
	if(isset($_POST['year'])){
	$lastValue = "xx";	
		$Table = findALL();
		visitor();
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

			//	echo   "<td class=\"" . $typeData . "\">" . $record['Type'] . "</td>"; 
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