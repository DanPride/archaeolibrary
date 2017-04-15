<?php //Enter Locus Number if it does not already exist in Locus Table
		$thePath = strlen(dirname(__FILE__)); //length of path
		$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
		$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
		require_once($theRoot . "Connection.php");
		require_once($theRoot . "functions.inc.php");
		require_once($theRoot . "XmlSerializer.class.php");
				
	function AssignLocus($Locus) {
	   global $conn;
			$query = sprintf("SELECT * FROM Locus WHERE Locus = '{$Locus}'");
			$result = mysql_query($query, $conn);
			$Count = mysql_num_rows($result);
			mysql_free_result($result);
			if($Count == 0) 
			{
					$query_insert = sprintf("INSERT INTO `Locus` (Locus) VALUES (%s)" ,
					GetSQLValueString($Locus, "text")# 
					);
					$ok = mysql_query($query_insert, $conn);
			}
		}
	?>
<html>
<head>
<title>Single Locus Basket Report</title>
<body>	
<br><p align =center>
<?Php 	
$ShortArray = AssignLocus("31003"); 
echo "yaba";
?>
</p>
</body>
</html>