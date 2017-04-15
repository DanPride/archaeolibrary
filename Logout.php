<?php ob_start();
session_start() ; 
require_once("includes/Connection.php");
require_once("includes/functions.inc.php");
require_once("includes/functions.php");?>

<?php
	//4 STEPS TO LOGGING OUT
	
	//1 Find the Session
	session_start();
	
	//2 Unset all session variables - set to empty array
	$_SESSION = array();
	
	//3 Destroy session cookie
	if(isset($COOKIE[session_name()])) {
		setcookie(session_name(), '', time()-42000, '/');
	}
	
	//4 Destroy session
	session_destroy();	
	redirect_to("Login.php");
?>
<?php ob_end_flush(); ?>