<?php //Assign Pottery Names for each Loca record
		$thePath = strlen(dirname(__FILE__)); //length of path
		$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
		$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
		require_once($theRoot . "Connection.php");
		require_once($theRoot . "functions.inc.php");
		require_once($theRoot . "XmlSerializer.class.php");
	function returnNumber($Number)
		{
			if($Number>9) {
				settype($Number, string);
				return $Number;
			} else {
				settype($Number, string);
				return "0" . $Number;
			}
		}		
		
	function AssignPotteryName() {
	   	global $conn;
		$query = sprintf("SELECT * FROM Loca ORDER BY Locus");  //Get List of All Locas
		$result = mysql_query($query, $conn);
		while ($locarow = mysql_fetch_assoc($result)) {
			$Field = $locarow["Field"];
			$Square = $locarow["Square"];
			$Locus = $locarow["Locus"];
			$LocaName = $locarow["Name"];
			$Id = $locarow["Id"];		
			$potQuery = sprintf("SELECT * FROM Pottery WHERE Field = '{$Field}' AND Square = '{$Square}' AND Locus = '{$Locus}' ORDER BY Basket"); //Get Pottery matches for each Loca
			$potresult = mysql_query($potQuery, $conn);
			$PotNumber = 1;
			while ($potteryrow = mysql_fetch_assoc($potresult)) {  //Go through matching Pottery for each Loca and Assign Names
				$Name = $LocaName . "B" . returnNumber($PotNumber);
				echo $Name . "<br>";
				$PotNumber++;
				$potteryId = $potteryrow["Id"];
				$query_update = sprintf("UPDATE `Pottery` SET Name = %s WHERE Id = %s", 
					GetSQLValueString($Name, "text"), 
					GetSQLValueString($potteryId, "int")
					);	
				$ok = mysql_query($query_update);
				if (!$ok) {
					$errno = mysql_errno($conn);
					$error = mysql_error($conn);
					echo "Database Error ($errno): $error" . "<br>";
					}
			}	
		}
	}
	?>
<html>
<head>
<title>Single Locus Basket Report</title>
<body>	
<br><p align =center>
<?Php 	
$ShortArray = assignPotteryName(); 
echo $ShortArray;
?>
</p>
</body>
</html>