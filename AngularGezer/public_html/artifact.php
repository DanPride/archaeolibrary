<?php require_once("../includes/initialize.php");
// if (!$session->is_logged_in()) {
// 	redirect_to("login.php");
// }

if (($_SERVER ['REQUEST_METHOD'] == 'GET') && (isset ( $_GET ['Name'] ))) {
$FullList = array();
$PhotoPaths = "";
$PhotoNames = "";
$PhotoDescrips = "";
foreach ($ObjectList as $Object){
	$sql = "SELECT * FROM Photos WHERE `Name` LIKE '{$_GET ['Name']}%'";
	$Photos = Photo::find_by_sql($sql);
	foreach ($Photos as $Photo){
		$PhotoPaths .= $Photo->FolderName . ", ";
		$PhotoNames .= $Photo->FileName . ", ";
		$PhotoDescrips .= $Photo->Description . ", ";
	}
	$PhotoCount = sizeof($Photos);
	$Object->PhotoCount = $PhotoCount;
 	$Object->PhotoFolder = $PhotoPaths;
 	$Object->PhotoFile = $PhotoNames;
 	$Object->Description = $PhotoDescrips;
 	 	array_push($FullList, $Object);
}
$thejson = json_encode($ObjectList);
echo $thejson;
}