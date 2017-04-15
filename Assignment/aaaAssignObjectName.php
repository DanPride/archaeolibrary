<?php //Assign All Object numbers for Each Pottery Record
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
		
	function AssignObjectName() {
	   	global $conn;
		$query = sprintf("SELECT * FROM Pottery"); //Get All Pottery Records
		$result = mysql_query($query, $conn);
		while ($potteryrow = mysql_fetch_assoc($result)) {
			$Field = $potteryrow["Field"];
			$Square = $potteryrow["Square"];
			$Locus = $potteryrow["Locus"];
			$Basket = $potteryrow["Basket"];
			$PotteryName = $potteryrow["Name"];
			$Id = $potteryrow["Id"];		
			$ObjectQuery = sprintf("SELECT * FROM Objects WHERE Field = '{$Field}' 	AND Square = '{$Square}' AND Locus = '{$Locus}' AND Basket = '{$Basket}'");
			$Objectresult = mysql_query($ObjectQuery, $conn);
			$ObjectNumber = 1;
			while ($Objectrow = mysql_fetch_assoc($Objectresult)) { //Run through All Matching Objects and Assign PotteryName + ObjectNumber
				$Name = $PotteryName . "-" . returnNumber($ObjectNumber);
				echo $Name . "<br>";
				$ObjectNumber++;
				$ObjectId = $Objectrow["Id"];
				$query_update = sprintf("UPDATE `Objects` SET Name = %s WHERE Id = %s", 
					GetSQLValueString($Name, "text"), 
					GetSQLValueString($ObjectId, "int")
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
$ShortArray = AssignObjectName(); 
echo $ShortArray;
?>
</p>
</body>
</html>