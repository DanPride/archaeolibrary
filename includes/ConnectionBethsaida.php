<?php
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
	if($Name == "Pride, Daniel") 
	{	
	} else {
	$ok = mysql_query($query_insert, $conn);
	}
}
function digname() {
	return "Bethsaida";
}
function dignamecaps() {
	return "BETHSAIDA";
}
function digcode() {
	return "BT";
}
function digurl() {
	return "\"http://www.Bethsaida.org/\"";
}
function digemail() {
	return "\"mailto:csavage@drew.edu\"";
}
/**
 * connection settings
 * replace with your own hostname, database, username and password 
 */
session_start();
$UserNameDefault = "Savage, Carl";
$UserPassDefault = "";
$hostname_conn = "bethsaidadata.db.3216758.hostedresource.com";
//$hostname_conn = "localhost";
$database_conn = "bethsaidadata";
$username_conn = "bethsaidadata";
$password_conn = "BethSaida39";
$conn = mysql_pconnect($hostname_conn, $username_conn, $password_conn) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db($database_conn, $conn);
mysql_query("SET NAMES 'utf8'");
?>
