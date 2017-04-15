<?php //Create Loca Records from Pottery Basket CheckIn Entries
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot . "functions.inc.php");
require_once($theRoot . "XmlSerializer.class.php");


	function FindErrors() {  //create Loca from Pottery records
		echo  "Id      DFC      DLC      User      FIELD    SQUARE   LOCUS   Basket <br>";
			$Count = 1;
			$tab = "";
	   		global $conn;
			$query = sprintf("SELECT * FROM Photos WHERE Name = 'No Match' AND Field = 'A' ORDER BY Locus ASC");
			$recordset = mysql_query($query, $conn);
			while ($row = mysql_fetch_assoc($recordset)) {
					$ID = $row["Id"];
					$DFC = $row["DFC"];
					$DLC = $row["DLC"];
					$User = $row["User"];
					$Name = $row["Name"];
					$FIELD = $row["Field"];
					$SQUARE = $row["Square"];
					$LOCUS = $row["Locus"];
					$Basket = $row["Basket"];	
					echo  $FIELD . "    " . $SQUARE . "    " . $LOCUS . "     " . $Basket ."<br>";
			}}


	?>
<html>
<head>
<title>Data Entry Errors Report</title>
<body>	
<br><p align =center>
<?Php 	
FindErrors(); 

?>
</p>
</body>
</html>