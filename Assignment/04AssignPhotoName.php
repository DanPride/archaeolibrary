<?php //Enter Locus Number if it does not already exist in Locus Table
		$thePath = strlen(dirname(__FILE__)); //length of path
		$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
		$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
		require_once($theRoot . "Connection.php");
		require_once($theRoot . "functions.inc.php");
		require_once($theRoot . "XmlSerializer.class.php");
	
		function updatePhotoName($theField, $theSquare, $theLocus, $theBasket, $theObject)
		{
			global $conn;
			
			if(strlen($theBasket) > 0) {
				$where = "WHERE Field = '{$theField}' AND Square = '{$theSquare}' AND Locus = '{$theLocus}' AND Basket = '{$theBasket}'";
				$query_recordset = "SELECT Name FROM `Pottery` $where";
				}
			elseif (strlen($theLocus) > 0) {
				$where = "WHERE Field = '{$theField}' AND Square = '{$theSquare}' AND Locus = '{$theLocus}'";
				$query_recordset = "SELECT Name FROM `Loca` $where";
				}
			elseif (strlen($theSquare) > 0) {
				$where = "WHERE Field = '{$theField}' AND Square = '{$theSquare}'";
				$query_recordset = "SELECT Name FROM `Squares`  $where";
				}
			elseif (strlen($theField) > 0) {
				$where = "WHERE Code = '{$theField}'";
				$query_recordset = "SELECT Name FROM `Fields` $where";
				} else {
				return "No Data";	
				}
			$recordset = mysql_query($query_recordset, $conn);
			$num_rows = mysql_num_rows($recordset);
			if ($num_rows > 0) 
			{
			$databaseFiles = array();
			while ($row_recordset = mysql_fetch_row($recordset)) 
				{
				array_push($databaseFiles, $row_recordset[0]); //datafiles array built
				}
			mysql_free_result($recordset); //Mod Phase 1
			return $databaseFiles[0];
			} else {
			return "No Match";
			}
		} 
			
	function AssignPhotoNames() {
	   global $conn;
			$query = sprintf("SELECT * FROM Photos");
			$result = mysql_query($query, $conn);
			while ($row = mysql_fetch_assoc($result)) {
				$Name = updatePhotoName($row['Field'], $row['Square'], $row['Locus'], $row['Basket'], $row['Object']);
				echo $Name . "<br>";
				$query_update = sprintf("UPDATE `Photos` SET Name = %s WHERE Id = %s", 
						GetSQLValueString($Name, "text"), 
						GetSQLValueString($row["Id"], "int")
						);
				$ok = mysql_query($query_update);
				}
			}
	?>
<html>
<head>
<title>Single Locus Basket Report</title>
<body>	
<br><p align =center>
<?Php 	
$ShortArray = AssignPhotoNames(); 
echo "yaba";
?>
</p>
</body>
</html>