<?php 
//Update Pottery Names based on matching Loca records xxxxxx
	$thePath = strlen(dirname(__FILE__)); //length of path
	$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
	$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
	require_once($theRoot . "Connection.php");
	require_once($theRoot . "functions.inc.php");
	require_once($theRoot . "XmlSerializer.class.php");
	global $conn;
	
	function returnNumber($Number)
	{
		$Number++;
		if($Number>9) {
			settype($Number, string);
			return $Number;
		} else {
			settype($Number, string);
			return "0" . $Number;
		}
	}		
		
	function AssignPotteryName($Field, $Square, $Locus, $Basket) 
	{
		global $conn;
		$query = sprintf("SELECT * FROM Loca WHERE Field = '{$Field}' AND Square = '{$Square}' AND Locus = '{$Locus}'");
		$result = mysql_query($query, $conn);
		$Count = mysql_num_rows($result);
		if($Count == 1)
			{
				$locaName = substr(mysql_result($result,0,2),0);
				$potQuery = sprintf("SELECT * FROM Pottery WHERE Field = '{$Field}' AND Square = '{$Square}' AND Locus = '{$Locus}'");
				$potresult = mysql_query($potQuery, $conn);
				$PotNumber = 1;
				while ($row = mysql_fetch_assoc($potresult)) 
				{ //Assign Pottery Names based on pre-existing Loca
					$Name = $locaName . "P" . returnNumber($PotNumber);
					echo $Name;
					$PotNumber++;
					$Id = $row['Id'];
					$query_update = sprintf("UPDATE `Pottery` SET Name = %s WHERE Id = %s", GetSQLValueString($Name, "text"), GetSQLValueString($Id, "int"));
					$ok = mysql_query($query_update);
					if (!$ok) {
						$errno = mysql_errno($conn);
						$error = mysql_error($conn);
						echo "Database Error ({$errno}): {$error}" . "<br>";
					}
				}
		}
	}
	function findAll() {
		global $conn, $filter_field, $filter_type;
		$where = "";
		$order = "ORDER BY " . "Id ASC";
		$query_recordset = "SELECT Id,Name,Field,Square,Locus,Basket,Periods,Context,Disposition,Date,Status FROM `Pottery` $where $order";
		$recordset = mysql_query($query_recordset, $conn);
		while ($row = mysql_fetch_assoc($recordset)) {
			$Field = $row["Field"];
		    $Square = $row["Square"];
		    $Locus = $row["Locus"];
		  	$Basket = $row["Basket"];
			$Id = $row["Id"];
			AssignPotteryName($Field, $Square, $Locus, $Basket, $Id);
		}
	}
?>
<html>
<head>
<title>Single Locus Basket Report</title>
<body>	
<br><p align =center>
<?php 
$ShortArray = findAll(); 
echo $ShortArray; 
?>
</p>
</body>
</html>
