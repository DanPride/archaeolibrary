<?php //Get LocaName with Field Square and Locus  xxx not real useful except for the getlocaname
	$thePath = strlen(dirname(__FILE__)); //length of path
	$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
	$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
	require_once($theRoot . "Connection.php");
	require_once($theRoot . "functions.inc.php");
	require_once($theRoot . "XmlSerializer.class.php");
	
	function returnNumber($Number)
		{	$Number++;
			if($Number>9) {
				settype($Number, string);
				return $Number;
			} else {
				settype($Number, string);
				return "0" . $Number;
		}}	
		
	function getLocaName($Field, $Square, $Locus) {
	   		global $conn;
			$query = sprintf("SELECT * FROM Loca WHERE Field = '{$Field}' AND Square = '{$Square}' AND Locus = '{$Locus}'");
			$result = mysql_query($query, $conn);
			$Count = mysql_num_rows($result);
			if($Count == 0) 
			{		$squareQuery = sprintf("SELECT * FROM Loca WHERE Field = '{$Field}' AND Square = '{$Square}'");
					$squareresult = mysql_query($squareQuery, $conn);
					$squareCount = mysql_num_rows($squareresult);
					$Loca = "L" . returnNumber($squareCount);
					$Name = "GZ" . $Field . $Square . $Loca;
					echo $Name . " zero <br>";	
			} else {
					echo mysql_result($result,0,2) . "<br>";
			}}
	
	function findAll() {
		global $conn, $filter_field, $filter_type;
		$where = "";
		$order = "ORDER BY Name ASC";
		$query_recordset = "SELECT Id,Name,Field,Square,Locus,Basket,Periods,Context,Disposition,Date,Status FROM `Pottery` $where $order";
		$recordset = mysql_query($query_recordset, $conn);
		while ($row = mysql_fetch_assoc($recordset)) {
			$Field = $row["Field"];
		    $Square = $row["Square"];
		    $Locus = $row["Locus"];
			getLocaName($Field, $Square, $Locus);
		}}
	?>
<html>
<head>
<title>Single Locus Basket Report</title>
<body>	
<br><p align =center>
<?Php 	
$ShortArray = getLocaName("A", "W08", "21016"); 
echo $ShortArray;
?>
</p>
</body>
</html>