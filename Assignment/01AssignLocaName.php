<?php //Create Loca Records from Pottery Basket CheckIn Entries
//Delete all loca entries then run this to renumber
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
				settype($Number, "string");
				return $Number;
			} else {
				settype($Number, "string");
				return "0" . $Number;
			}}	
	
		function findAll() {
			global $conn, $filter_field, $filter_type;
			$query_delete = "DELETE FROM `Loca`";
			$recordset = mysql_query($query_delete, $conn);
			$where = "";
			$order = "ORDER BY " ."Basket ASC";
			$query_recordset = "SELECT Id,Name,Field,Square,Locus,Basket,Periods,Context,Disposition,CreateDate,Status FROM `Pottery` $where $order";
			$recordset = mysql_query($query_recordset, $conn);
			while ($row = mysql_fetch_assoc($recordset)) {
				$Field = $row["Field"];
			    $Square = $row["Square"];
			    $Locus = $row["Locus"];
				AssignLoca($Field, $Square, $Locus);
			}}
	//Add a loca if itdoes not already exist
	function AssignLoca($Field, $Square, $Locus) {  //create Loca from Pottery records
	   global $conn;
		
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
					echo $Name . " Inserted for " . $Field . " " .  $Square . " " . $Locus . "<br>";
					$Insertquery = sprintf("INSERT INTO `Loca` (DFC, DLC, Field,Square,Locus,Name) VALUES (now(),now(),%s,%s,%s,%s)" ,			
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