<?php //Create Loca Records from Pottery Basket CheckIn Entries
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot . "functions.inc.php");
require_once($theRoot . "XmlSerializer.class.php");
	
	function returnNumber($Number)
		{
		//	$Number++;
			if($Number>9) {
				settype($Number, string);
				return $Number;
			} else {
				settype($Number, string);
				return "0" . $Number;
			}}	
	
		function findAll() {
			global $conn, $filter_field, $filter_type;
			$where = "";
			//$order = "ORDER BY Id ASC";
			$query_recordset = "SELECT Id,Name,Field,Square,Locus,Basket,Periods,Context,Disposition,CreateDate,Status FROM `Pottery` $where ORDER BY Id ASC";
			$recordset = mysql_query($query_recordset, $conn);
			while ($row = mysql_fetch_assoc($recordset)) {
				$Field = $row["Field"];
			    $Square = $row["Square"];
			    $Locus = $row["Locus"];
				AssignLoca($Field, $Square, $Locus);
			}}
			
	function AssignLoca($Field, $Square, $Locus) {  //create Loca from Pottery records
			$Count = 1;
	   		global $conn;
			$query = sprintf("SELECT * FROM Loca WHERE Field = '{$Field}' AND Square = '{$Square}' AND Locus = '{$Locus}' ORDER BY Id ASC");
			$recordset = mysql_query($query, $conn);
				while ($row = mysql_fetch_assoc($recordset)) {
					$squareQuery = sprintf("SELECT * FROM Loca WHERE Field = '{$Field}' AND Square = '{$Square}' ORDER BY Id ASC");
					$squareresult = mysql_query($squareQuery, $conn);
					while ($Squarerow = mysql_fetch_assoc($squareresult)) {
					$LocaId = $row["Id"];
				//	$squareCount = mysql_num_rows($squareresult);
					$Loca = "L" . returnNumber($Count);  //Loca number is square's loca plus one
					$Name = "GZ" . $Field . $Square . $Loca;
					echo $Name . "<br>";
					$Count = $Count + 1;
					$Updatequery = sprintf("UPDATE `Loca` SET Name =  %s WHERE ID = $LocaId", GetSQLValueString($Name, "text"));
					$ok = mysql_query($Updatequery, $conn);
					if (!$ok) {
						$errno = mysql_errno($conn);
						$error = mysql_error($conn);
						echo "Database Error ($errno): $error";
			}}}}


	?>
<html>
<head>
<title>Single Locus Basket Report</title>
<body>	
<br><p align =center>
<?Php 	
$ShortArray = findAll(); 
echo $ShortArray;
?>
</p>
</body>
</html>