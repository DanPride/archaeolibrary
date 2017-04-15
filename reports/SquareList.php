<?php 
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
require_once($theRoot . "Connection.php");


function findAll() {
	global $conn, $filter_field, $filter_type;
	$query_recordset = "SELECT Id,Name,Field,Square,Open,Supervisor FROM `Squares` ORDER BY Square";
	$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		array_push($toret, $row_recordset);	}
	return $toret;
	}	
?>
<html><body>
<table align="center" border="2" cellpadding="7" cellspacing="0" >
	<tr><?Php 
		$CodeList =  "<td class=\"userId\">ID</td>";
		$CodeList .= "<td class=\"userName\">Name</td>";
		$CodeList .= "<td class=\"userIP\">Field</td>";
		$CodeList .= "<td class=\"userPage\">Square</td>";
		$CodeList .= "<td class=\"userDate\">Open</td>";
		$CodeList .= "<td class=\"userTime\">Supervisor</td>";
		echo $CodeList ?>
	</tr>
<?php 
	$Table = findALL();
 	foreach ($Table as $record){
		echo "<tr>";
		echo   "<td class=\"userIdData\">" . $record['Id'] . "</td>"; 
		echo   "<td class=\"userNameData\">" . $record['Name'] . "</td>"; 
		echo   "<td class=\"userIPData\">" . $record['Field'] . "</td>"; 
		echo   "<td class=\"userPageData\">" . $record['Square'] . "</td>"; 
		echo   "<td class=\"userDateData\">" . $record['Open'] . "</td>"; 
		echo   "<td class=\"userTimeData\">" . $record['Supervisor'] . "</td>"; 
	echo "</tr>";
	 }?>
</Table>
<BR>

</body>
</html>