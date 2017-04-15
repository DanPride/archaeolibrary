<?php 
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
require_once($theRoot . "Connection.php");
require_once($theRoot .  "functions.inc.php");
require_once($theRoot .  "functions.php");
//require_once($theRoot .  "XmlSerializer.class.php");
session_start();
confirm_logged_in("R_Usage");
	visitorReport("Usage");
function findAll() {
	global $conn, $filter_field, $filter_type;
	$where = "%%";	
	if($_SESSION['User'] == "List All"){$userName = "%%";} else {$userName = $_SESSION['User'];}
	if($_SESSION['Page'] == "List All"){$userPage = "%%";} else {$userPage = $_SESSION['Page'];}
	$where = "WHERE " . 'Name' .  " LIKE " . GetSQLValueStringForSelect($userName, "text") . " AND " . 'Page' . " LIKE " . GetSQLValueStringForSelect($userPage, "text");
	$order = "ORDER BY " . 'CreateDate'  . " " .  'DESC';	
	$query_recordset = "SELECT Id,Name,Page,IP,Agent,CreateDate FROM `Logs` $where $order LIMIT 2000";
	$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		array_push($toret, $row_recordset);	}
	return $toret;
	}	

if(isset($_POST['user'])){
	$_SESSION['User']=$_POST['user'];
	$_SESSION['Page']=$_POST['page'];
	$TheUserDate = firstName($_SESSION['User']) . " " . lastName($_SESSION['User']);
} else {
	$TheUserDate = "<b>Select User and Year</b>";}?>
	
<?Php reportsHeader("Staff Database Usage")?>
<?php echo $TheUserDate; ?></p>
	<form action="R_Usage.php" method="post">
	<p align =center> User: 
	<?Php 
	userusePop("List All");
	pagePop("List All"); ?>
	<input type="submit" name="submit" value="Submit" /> &nbsp;</p>
	</form>
	</p>
<table align="center" border="2" cellpadding="7" cellspacing="0" >
	<tr><?Php 
		$CodeList =  "<td class=\"userId\">ID</td>";
		$CodeList .= "<td class=\"userName\">Name</td>";
		$CodeList .= "<td class=\"userIP\">IP</td>";
		$CodeList .= "<td class=\"userPage\">Page</td>";
		$CodeList .= "<td class=\"userDate\">Date</td>";
		$CodeList .= "<td class=\"userTime\">Time</td>";
		$CodeList .= "<td class=\"userAgent\">Agent</td>";

		echo $CodeList ?>
	</tr>
<?php 
if(isset($_POST['user'])){
	$Table = findALL();
 	foreach ($Table as $record){
		$date = dateFormat($record['CreateDate']);
	//$date = substr($record['Date'],0,11);
	$time = substr($record['CreateDate'],11,5);
	$IP = $record['IP'];
 	switch ($IP) {
 	case "199.203.136.116": $IP = "Rockefeller"; break;
 	case "199.203.136.118": $IP = "Israel IAA"; break;
 	case "173.226.171.2": $IP = "Ft Worth"; break;
 	case "212.25.81.227": $IP = "ramat-hashofet"; break;
 	case "79.183.179.44": $IP = "Tel Aviv-yafo"; break;
 	case "82.80.136.25": $IP = "Talmei Menashe"; break;
 	case "79.177.168.162": $IP = "Rishon Le Zion"; break;
 	case "99.6.35.228": $IP = "Ft Worth"; break;
 	case "75.183.154.197": $IP = "Columbia SC"; break;
 	case "213.57.140.138": $IP = "Gan Yavne"; break;
 	case "77.127.49.239": $IP = "Jerusalem"; break;
 	case "80.230.113.204": $IP = "Netanya"; break;
 	case "87.68.237.80": $IP = "Kfar Saba"; break;
 	case "87.68.164.18": $IP = "Kfar Saba"; break;
 	case "87.68.164.237": $IP = "Kfar Saba"; break;
 	case "62.219.125.233": $IP = "Kfar Saba"; break;
 	case "212.235.88.74": $IP = "Albright"; break;
 	case "84.111.75.175": $IP = "Jerusalem"; break;
 	case "109.148.55.124": $IP = "London"; break;
 	case "69.76.204.145": $IP = "Kansas City"; break;
 	case "70.253.140.159": $IP = "Fort Worth, TX"; break;
	case "79.176.174.127": $IP = "Tel Aviv-yafo"; break;
	case "98.71.155.133": $IP = "Fair Play, SC"; break;
	case "80.230.9.50": $IP = "Neve Shalom"; break;
	case "79.182.96.245": $IP = "Sams House"; break;
	case "75.172.13.67": $IP = "Seattle"; break;
	case "80.230.63.166": $IP = "Tel Aviv"; break;
	case "70.190.223.202": $IP = "Tucson, AZ"; break;
	case "68.18.11.74": $IP = "Shreveport, LA"; break;
	case "24.102.230.195": $IP = "Denver, Pa"; break;
	case "98.80.97.126": $IP = "Bossier City La"; break;
	case "136.167.92.122": $IP = "Boston College"; break;
	case "65.111.127.84": $IP = "Waco, Tx"; break;
	case "66.169.145.168": $IP = "Fort Worth, Tx"; break;
	case "79.183.141.23": $IP = "Tel Aviv"; break;
	case "159.220.74.5": $IP = "Reuters"; break;
	case "173.227.88.147": $IP = "Austin, Tx"; break;
	case "70.171.26.91": $IP = "Gainsville, Fl"; break;
	case "79.183.58.253": $IP = "Tel Aviv"; break;
	case "24.19.110.242": $IP = "Seattle, Wa"; break;
	case "75.141.133.145": $IP = "Dallas Ft Worth"; break;
	case "199.203.136.211": $IP = "Elron Corp Jerusalem"; break;
	case "79.183.114.178": $IP = "Petach Tikva, Israel"; break;
	case "174.31.188.159": $IP = "Seattle, Wa"; break;
	 	
 	}
		echo "<tr>";
		echo   "<td class=\"userIdData\">" . $record['Id'] . "</td>"; 
		echo   "<td class=\"userNameData\">" . $record['Name'] . "</td>"; 
		echo   "<td class=\"userIPData\">" . $IP . "</td>";
		echo   "<td class=\"userPageData\">" . $record['Page'] . "</td>"; 
		echo   "<td class=\"userDateData\">" . $date . "</td>";
		echo   "<td class=\"userTimeData\">" . $time . "</td>";
		echo   "<td class=\"userAgentData\">" . $record['Agent'] . "</td>"; 
	echo "</tr>";
	 }}?>
</Table>
<BR>

</body>
</html>