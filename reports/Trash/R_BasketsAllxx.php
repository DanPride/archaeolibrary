<?php 
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot .  "functions.inc.php");
require_once($theRoot .  "functions.php");
require_once($theRoot .  "Stylesheet.php");
require_once($theRoot .  "XmlSerializer.class.php");
session_start();
confirm_logged_in("BasketsAll");

	function findAll() {
		global $conn, $filter_field, $filter_type;
		$where = "";
		if($_POST['year'] == "All"){
			$Year = GetSQLValueStringForSelect("0", "text");	
		} else {
			$Year = GetSQLValueStringForSelect($_POST['year'], "text");	
		}
		$where = "WHERE " . 'Type' . " LIKE " . GetSQLValueStringForSelect('Pottery', "text") .	" AND " . 'Date' . " LIKE " . $Year;
		$order = "ORDER BY " . $_POST['Order'] . " " . $_POST['UpDown'];
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
	switch ($_SESSION['view'])
			{
			case Full:
		$query_recordset = "SELECT Name,Field,Square,Locus,Basket,Period,Description,Quantity,Saved,Comments,Disposition,Date FROM `Objects` $where $order";
			 break;
			case Sequence:
			$query_recordset = "SELECT Name,Locus,Period,Description,Quantity,Saved,Comments,Disposition,Date FROM `Objects` $where $order";
			 break;  
			case Number:
$query_recordset = "SELECT Field,Square,Locus,Basket,Period,Description,Quantity,Saved,Comments,Disposition,Date FROM `Objects` $where $order";
			break;
			default:
$query_recordset = "SELECT Name,Field,Square,Locus,Basket,Period,Description,Quantity,Saved,Comments,Disposition,Date FROM `Objects` $where $order";
			 break;
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

	if(isset($_POST['year'])){
			if($_POST['year'] == "All"){ $Season = "Seasons";} else {$Season = "Season";}
			$MCYear = $_POST['year'] . " " . $Season ;
			$_SESSION['year']=$_POST['year'];
			$_SESSION['sort']=$_POST['Order'];
			$_SESSION['UpDown']=$_POST['UpDown'];
			$_SESSION['view']=$_POST['view'];
		} else {
			$MCYear = "<b>Select Season</b>";
		}
		?>
	
	<?Php reportsHeader("Gezer Full Basket Read List",$MCYear,"", array("Archaeology","Excavation","Biblical","Software"), "Archaeological Excavation Software"); ?>

	<form action="R_BasketsAll.php" method="post">
		<p align=center>
			<?Php yearPop(); ?>
			<?Php sortPop(); ?>
			<?Php updownPop(); ?>
<?Php viewPop(); ?>
			<input type="submit" name="submit" value="Submit" />
		 	&nbsp; Simple View: <input type="checkbox" name="Code" value="True" />
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
					$CodeList = "<td class=\"name\">SupersLine#</td>";
					$CodeList .=	"<td class=\"field\">Fld</td>";
					$CodeList .= "<td class=\"square\">Sq</td>";
					$CodeList .= "<td class=\"locus\">Locus</td>";
					$CodeList .= "<td class=\"basket\">Basket</td>";
					break;
				}
			echo $CodeList;
			?>
			<td class="period">Period</td>
			<td class="descr">Description</td>
			<td class="quan">Qty</td>
			<td class="saved">Svd</td>
			<td class="comm">Comments</td>
			<td class="disp">Disp</td>
			<td class="date">Date</td>
		</tr>
	<?php 
	if(isset($_POST['year'])){
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