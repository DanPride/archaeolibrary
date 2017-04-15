<?php require_once("../includes/initialize.php");
// if (!$session->is_logged_in()) {
// 	redirect_to("login.php");
// }

	$sql = "SELECT * FROM Photos";
	$PhotoList = Photo::find_by_sql($sql );
	$thejson = json_encode($PhotoList);
	echo $thejson;
?>