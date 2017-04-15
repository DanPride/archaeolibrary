<?php
require_once("_Setup.php");
global $conn, $filter_field, $filter_type;
function visitor() 
{
	global $conn;	
	if(isset($_SESSION['Name']))
	{
		$Name = $_SESSION['Name'];	
	} else {
		$Name = "Visitor";	
	}
	$Page = @$_REQUEST['page'];	
	$IP = $_SERVER['REMOTE_ADDR'];
	$Referer = "xx";//$_SERVER['HTTP_REFERER']; 
	$RHost = "ss";//$_SERVER['REMOTE_HOST'];
	$Host = $_SERVER['HTTP_HOST']; 
	$Agent = $_SERVER['HTTP_USER_AGENT'];
	$Server = $_SERVER['SERVER_ADDR'];
	$query_insert = sprintf("INSERT INTO `Logs` (Name,Page,IP,Referer,RHost,Host,Agent,Server,CreateDate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,now())" ,		
			GetSQLValueString($Name, "text"), # 
			GetSQLValueString($Page, "text"), # 
			GetSQLValueString($IP, "text"), # 
			GetSQLValueString($Referer, "text"), # 
			GetSQLValueString($RHost, "text"), # 
			GetSQLValueString($Host, "text"), # 
			GetSQLValueString($Agent, "text"), # 
			GetSQLValueString($Server, "text") # 
	);
	if(($Name == "Pride, Daniel") || ($IP == "174.31.188.159"))
	{	
	} else {
	$ok = mysql_query($query_insert, $conn);
	}
}
function visitorReport($Page) 
{
	global $conn;	
	if(isset($_SESSION['Name']))
	{
		$Name = $_SESSION['Name'];	
	} else {
		$Name = "Visitor";	
	}
	$IP = $_SERVER['REMOTE_ADDR'];
	$Referer = "xx";//$_SERVER['HTTP_REFERER']; 
	$RHost = "ss";//$_SERVER['REMOTE_HOST'];
	$Host = $_SERVER['HTTP_HOST']; 
	$Agent = $_SERVER['HTTP_USER_AGENT'];
	$Server = $_SERVER['SERVER_ADDR'];
	$query_insert = sprintf("INSERT INTO `Logs` (Name,Page,IP,Referer,RHost,Host,Agent,Server,CreateDate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,now())" ,		
			GetSQLValueString($Name, "text"), # 
			GetSQLValueString($Page, "text"), # 
			GetSQLValueString($IP, "text"), # 
			GetSQLValueString($Referer, "text"), # 
			GetSQLValueString($RHost, "text"), # 
			GetSQLValueString($Host, "text"), # 
			GetSQLValueString($Agent, "text"), # 
			GetSQLValueString($Server, "text") # 
	);
	if(($Name == "Pride, Daniel") || ($IP == "174.31.173.114"))
	{	
	} else {
	$ok = mysql_query($query_insert, $conn);
	}
}
function flex_confirmLogin() 
{
	global $conn;
	if(isset($_SESSION['Name']))
	{
		$isLoggedIn = "Success";
		$loginId =	$_SESSION['Id'];
		$loginDFC = $_SESSION['DFC'];
		$loginDLC = $_SESSION['DLC'];
		$loginLogons = $_SESSION['Logons'];
		$loginUser = $_SESSION['User'];
		$loginName = $_SESSION['Name'];
		$loginPermsAdmin = $_SESSION['PermsAdmin'];
		$loginPermsAdd = $_SESSION['PermsAdd'] ;
		$loginPermsMod = $_SESSION['PermsMod'];
		$loginPermsDelete = $_SESSION['PermsDelete'];
	}else{
		$query = "SELECT * ";
		$query .= "FROM Staff ";
		$query .= " WHERE Name = 'View_Only'";
		$result_set = mysql_query($query);
		confirm_query($result_set);
		if(mysql_num_rows($result_set) == 1 )
			{
				$found_user = mysql_fetch_array($result_set);
				$_SESSION['Id'] = $found_user['Id'];
				$_SESSION['DFC'] = dateTimeFormat($found_user['DFC']);
				$_SESSION['DLC'] = dateTimeFormat($found_user['DLC']);
				$_SESSION['Logons'] = $found_user['Logons'];
				$_SESSION['User'] = $found_user['User'];
				$_SESSION['Name'] = $found_user['Name'];
				$_SESSION['PermsAdmin'] = $found_user['PermsAdmin'];
				$_SESSION['PermsAdd'] = $found_user['PermsAdd'];
				$_SESSION['PermsMod'] = $found_user['PermsMod'];
				$_SESSION['PermsDelete'] = $found_user['PermsDelete'];	
					$isLoggedIn = "Success";
					$loginId =	$_SESSION['Id'];
					$loginDFC = $_SESSION['DFC'];
					$loginDLC = $_SESSION['DLC'];
					$loginLogons = $_SESSION['Logons'];
					$loginUser = $_SESSION['User'];
					$loginName = $_SESSION['Name'];
					$loginPermsAdmin = $_SESSION['PermsAdmin'];
					$loginPermsAdd = $_SESSION['PermsAdd'] ;
					$loginPermsMod = $_SESSION['PermsMod'];
					$loginPermsDelete = $_SESSION['PermsDelete'];
	}}
	$toret = array();
	$test = array("Status"=> $isLoggedIn, "ID" =>$loginId, "DFC" =>$loginDFC, "DLC" => $loginDLC, "Logons"=>$loginLogons, "User" => $loginUser, "Name" => $loginName,  "PermsAdmin" => $loginPermsAdmin,  "PermsAdd" => $loginPermsAdd,  "PermsMod" => $loginPermsMod,  "PermsDelete" => $loginPermsDelete, "DigName" => DIG_NAME, "DigCode" => DIG_CODE, "FieldMinLength" => FIELD_MINLENGTH, "FieldMaxLength" => FIELD_MAXLENGTH, "FieldIsNumber" => FIELD_ISNUMBER,  "FieldMinValue" => FIELD_MINVALUE,  "FieldMaxValue" => FIELD_MAXVALUE,  "SquareMinLength" => SQUARE_MINLENGTH, "SquareMaxLength" => SQUARE_MAXLENGTH, "SquareIsNumber" => SQUARE_ISNUMBER,  "SquareMinValue" => SQUARE_MINVALUE, "SquareMaxValue" => SQUARE_MAXVALUE, "LocusMinLength" => LOCUS_MINLENGTH,  "LocusMaxLength" => LOCUS_MAXLENGTH, "LocusIsNumber"=> LOCUS_ISNUMBER, "LocusMinValue" => LOCUS_MINVALUE,  "LocusMaxValue" => LOCUS_MAXVALUE, "BucketMinLength" => BUCKET_MINLENGTH,  "BucketMaxLength" => BUCKET_MAXLENGTH , "BucketIsNumber" => BUCKET_ISNUMBER, "BucketMinValue" => BUCKET_MINVALUE,  "BucketMaxValue" => BUCKET_MAXVALUE);
	array_push($toret, $test);
	$toret = array(
		"data" => $toret
			);
	return $toret;
}

function findUsers() {
	global $conn, $filter_field, $filter_type;
	$fields = array('Id','Name');
	$order = "ORDER BY ASC";
	$query_recordset = "SELECT Id, Name,DLC FROM `Staff`  WHERE Status Like 'Active' ORDER BY ID ASC";
	$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) 
	{
		array_push($toret, $row_recordset);			
	}
	$toret = array(
		"data" => $toret
			);
	return $toret;
}

function flex_LogIn()
{
	global $conn;
	$Nametrim = @$_REQUEST["Name"];
	$PassTrim = @$_REQUEST["Password"];
	$Name = trim(mysql_prep($Nametrim));
	$Password = trim(mysql_prep($PassTrim));
	$hashed_Password = sha1($Password);
	if(($Nametrim == "View_Only") || ($Nametrim == "")){
	$query = "SELECT * ";
	$query .= "FROM Staff ";
	$query .= " WHERE Name = 'View_Only'";
	} else {
	$query = "SELECT * ";
	$query .= "FROM Staff ";
	$query .= " WHERE Name = '{$Name}' ";
	$query .= " AND Password = '{$hashed_Password}' ";
	}
	$result_set = mysql_query($query);
	confirm_query($result_set);
	if(mysql_num_rows($result_set) == 1 )
		{
			$found_user = mysql_fetch_array($result_set);
			$_SESSION['Id'] = $found_user['Id'];
			$_SESSION['DFC'] = dateTimeFormat($found_user['DFC']);
			$_SESSION['DLC'] = dateTimeFormat($found_user['DLC']);
			$_SESSION['Logons'] = $found_user['Logons'];
			$_SESSION['User'] = $found_user['User'];
			$_SESSION['Name'] = $found_user['Name'];
			$_SESSION['PermsAdmin'] = $found_user['PermsAdmin'];
			$_SESSION['PermsAdd'] = $found_user['PermsAdd'];
			$_SESSION['PermsMod'] = $found_user['PermsMod'];
			$_SESSION['PermsDelete'] = $found_user['PermsDelete'];
			$test = array("Id"=> $_SESSION['Id'], "Status"=> "Success", "Logons" => $_SESSION['Logons'], "Name"=> $found_user['Name'], "DFC"=> 	$_SESSION['DFC'], "DLC" => $_SESSION['DLC'], "User" => $found_user['User'], "PermsAdmin" => $found_user['PermsAdmin'], "PermsAdd" => $found_user['PermsAdd'], "PermsMod" => $found_user['PermsMod'], "PermsDelete" => $found_user['PermsDelete'], "DigName" => DIG_NAME, "DigCode" => DIG_CODE, "FieldMinLength" => FIELD_MINLENGTH, "FieldMaxLength" => FIELD_MAXLENGTH, "SquareNameLength" => SQUARE_MAXLENGTH, "FieldIsNumber" => FIELD_ISNUMBER,  "FieldMinValue" => FIELD_MINVALUE,  "FieldMaxValue" => FIELD_MAXVALUE,  "SquareMinLength" => SQUARE_MINLENGTH, "SquareMaxLength" => SQUARE_MAXLENGTH, "SquareIsNumber" => SQUARE_ISNUMBER,  "SquareMinValue" => SQUARE_MINVALUE,  "SquareMaxValue" => SQUARE_MAXVALUE, "LocusMinLength" => LOCUS_MINLENGTH,  "LocusMaxLength" => LOCUS_MAXLENGTH, "LocusIsNumber"=> LOCUS_ISNUMBER, "LocusMinValue" => LOCUS_MINVALUE,  "LocusMaxValue" => LOCUS_MAXVALUE, "BucketMinLength" => BUCKET_MINLENGTH,  "BucketMaxLength" => BUCKET_MAXLENGTH , "BucketIsNumber" => BUCKET_ISNUMBER, "BucketMinValue" => BUCKET_MINVALUE,  "BucketMaxValue" => BUCKET_MAXVALUE);
		} else {
				$test = array("Id"=> null, "Status"=> "Fail");
				session_destroy();
		}
		$toret = array();
		array_push($toret, $test);
		$Logons = (int)$_SESSION['Logons'] + 1;
		$SessID = $_SESSION['Id'];
	//	$row_recordset = mysql_fetch_assoc($recordset);
		$query_update = sprintf("UPDATE `Staff` SET Logons = %s,DLC = now() WHERE Id = %s", 
			GetSQLValueString($Logons, "int"), 
			GetSQLValueString($SessID, "int")
		);
		$ok = mysql_query($query_update);
		
	$toret = array("data" => $toret);
	return $toret; 		
	header("Location: {'../../center/index.html'}");
	exit;
}

function dateTimeFormat($theDate){
	return substr($theDate,5,2) .  "/" . substr($theDate,8,2) .   "/" . substr($theDate,2,2) . " " .   substr($theDate,11,5);
}
session_start();
//$hostname_conn = "GezerData.db.3216758.hostedresource.com";
//ini_set('display_errors',1);
//error_reporting(E_ALL|E_STRICT);
//date_default_timezone_set('Europe/Minsk');
//$hostname_conn = "localhost";
//$database_conn = "archaeodemodata";
//$username_conn = "archaeodemodata";
//$password_conn = "ArchaeoDemo39";
//$conn = mysql_pconnect($hostname_conn, $username_conn, $password_conn) or trigger_error(mysql_error(),E_USER_ERROR);
$conn = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db(DB_USER, $conn);
//mysql_query("SET NAMES 'utf8'");
?>
