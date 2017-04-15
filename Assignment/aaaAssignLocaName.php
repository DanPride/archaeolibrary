<?php //Create Loca Records from Pottery Basket CheckIn Entries
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot . "functions.inc.php");
require_once($theRoot . "XmlSerializer.class.php");
	
	function returnNumber($Number)
		{
			$Number++;
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
			$order = "ORDER BY " ."Id ASC";
			$query_recordset = "SELECT Id,Name,Field,Square,Locus,Basket,Periods,Context,Disposition,CreateDate,Status FROM `Pottery` $where $order";
			$recordset = mysql_query($query_recordset, $conn);
			while ($row = mysql_fetch_assoc($recordset)) {
				$Field = $row["Field"];
			    $Square = $row["Square"];
			    $Locus = $row["Locus"];
	
				AssignLoca($Field, $Square, $Locus);
			}}
			
	function AssignLoca($Field, $Square, $Locus) {  //create Loca from Pottery records
	   global $conn;
			echo $Field . " " .  $Square . " " . $Locus . "xxx<br>";
			$query = sprintf("SELECT * FROM Loca WHERE Field = '{$Field}' AND Square = '{$Square}' AND Locus = '{$Locus}' ORDER BY Id ASC");
			$result = mysql_query($query, $conn);
			$Count = mysql_num_rows($result);
			mysql_free_result($result);
			if($Count == 0) {  //No Loca exists so create one
					$squareQuery = sprintf("SELECT * FROM Loca WHERE Field = '{$Field}' AND Square = '{$Square}' ORDER BY Id ASC");
					$squareresult = mysql_query($squareQuery, $conn);
					$squareCount = mysql_num_rows($squareresult);
					$Loca = "L" . returnNumber($squareCount);  //Loca number is square's loca plus one
					$Name = "GZ" . $Field . $Square . $Loca;
					echo $Name . "<br>";
					$Insertquery = sprintf("INSERT INTO `Loca` (Field,Square,Locus,Name) VALUES (%s,%s,%s,%s)" ,			
							GetSQLValueString($Field, "text"), # 
							GetSQLValueString($Square, "text"), # 
							GetSQLValueString($Locus, "text"), #
							GetSQLValueString($Name, "text")# 
					);
					$ok = mysql_query($Insertquery, $conn);
					if (!$ok) {
						$errno = mysql_errno($conn);
						$error = mysql_error($conn);
						echo "Database Error ($errno): $error";
			}}}
	

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