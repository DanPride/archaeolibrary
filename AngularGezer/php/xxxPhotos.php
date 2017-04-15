<?php
ini_set('memory_limit', '128M');
set_time_limit(1000);
//ini_set('display_errors',1);
//error_reporting(E_ALL|E_STRICT);
$thePhpPathLength = strlen(dirname(__FILE__)); //length of path
$theAppPath = substr(dirname(__FILE__), 0 , $thePhpPathLength -4);
$theAppPathLength =strlen($theAppPath);
$st = strlen(strrchr($theAppPath,"/")); //length last folder
$theWebRoot =  substr($theAppPath, 0, $theAppPathLength - $st);
$TheIncludesFolder = $theWebRoot . "/includes/";
$theImageFolder = $theWebRoot . "/images/";
require_once($TheIncludesFolder . "Connection.php");
require_once($TheIncludesFolder . "functions.inc.php");
require_once($TheIncludesFolder . "functions.php");
require_once($TheIncludesFolder . "XmlSerializer.class.php");
function updateName($theField, $theSquare, $theLocus, $theBasket)
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
	
function processWebs($folderFile, $theFolder) //REWRITE AS A STAND ALONE CALL TO A PHP FILE WITH REQUEST PARAMETERS AND COMPLETED DISPLAY????
	{
			global $conn, $filter_field, $filter_type, $theImageFolder;
			$baseImagePath = $theImageFolder . $theFolder . "/";
			$thewebImagePath = $theImageFolder . $theFolder . "/000Pic/";	
			$theImage = $baseImagePath . $folderFile; // 
			$theImageOut = $thewebImagePath . $folderFile; // 	
			$theImageInfo = getimagesize($theImage);
			$theImageX = $theImageInfo[0];
			$theImageY = $theImageInfo[1];
			$theImageType = $theImageInfo[2];
			$theNewX = 1024;
			if(is_numeric($theImageX))
			{
				if($theImageX>0)
				{
				$theImageRatio = "{$theImageY}"/"{$theImageX}";	
				$theNewY = 768 * ($theImageRatio/0.75);
				} else {
					$theNewY = 768;
				}
			} else{
				$theNewY = 768;	
			}
			$theNewImage = imagecreatetruecolor($theNewX, $theNewY);
			switch($theImageType)
				{
					case '1':
					$image = imagecreatefromgif($theImage);
					break;
					case '2':
					$image = imagecreatefromjpeg($theImage);
					break;
					case '3':
					$image = imagecreatefrompng($theImage);
					break;
				}
			//echo $theImageType;
			imagecopyresampled($theNewImage, $image, 0, 0, 0, 0, $theNewX, $theNewY, $theImageX, $theImageY);
			$result = imagejpeg($theNewImage, $theImageOut, 75);
			imagedestroy($theNewImage);
			imagedestroy($image);
			return $result;		
		}
		function processThumbs($folderFile, $theFolder)
			{
					global $conn, $filter_field, $filter_type, $theImageFolder;
					$baseImagePath = $theImageFolder . $theFolder . "/";
					$thethumbImagePath = $theImageFolder . $theFolder . "/000Tmb/";	
					$theImage = $baseImagePath . $folderFile; // 
					$theImageOut = $thethumbImagePath . $folderFile; // 	
					$theImageInfo = getimagesize($theImage);
					$theImageX = $theImageInfo[0];
					$theImageY = $theImageInfo[1];
					$theImageType = $theImageInfo[2];
					$theNewX = 200; 
					if(is_numeric($theImageX))
					{
						if($theImageX>0){
						$theImageRatio = "{$theImageY}"/"{$theImageX}";	
						$theNewY = 150 * ($theImageRatio/0.75);
						} else 
						{
							$theNewY = 150;
						}
					
					} else
					{
						$theNewY = 150;	
					}
					$theNewImage = imagecreatetruecolor($theNewX, $theNewY);
					switch($theImageType)
						{
							case '1':
							$image = imagecreatefromgif($theImage);
							break;
							case '2':
							$image = imagecreatefromjpeg($theImage);
							break;
							case '3':
							$image = imagecreatefrompng($theImage);
							break;
						}
					//echo $theImageType;
					imagecopyresampled($theNewImage, $image, 0, 0, 0, 0, $theNewX, $theNewY, $theImageX, $theImageY);
					$result = imagejpeg($theNewImage, $theImageOut, 75);
					imagedestroy($theNewImage);
					imagedestroy($image);
					return $result;		
				}


function  findAll(){
		global $conn, $filter_field, $filter_type, $theImageFolder;
	//	$theFolder = '2008';
		$theFolder = @$_REQUEST["filter"];
		$where = "WHERE FolderName  =  '{$theFolder}'";
		$baseImagePath = $theImageFolder . $theFolder . "/";
		$thewebImagePath = $theImageFolder . $theFolder . "/000Pic/";
		$thethumbImagePath = $theImageFolder . $theFolder . "/000Tmb/";
		if (!is_dir($thewebImagePath)) mkdir($thewebImagePath, 0777); 
		if (!is_dir($thethumbImagePath)) mkdir($thethumbImagePath, 0777); 
		$folderFilePaths = glob("$baseImagePath*"); //Raw
		$webFilePaths = glob("$thewebImagePath*"); //Webs
		$thumbFilePaths = glob("$thethumbImagePath*"); //Thumbs
		$query_recordset = "SELECT FileName FROM `Photos` $where";
		$recordset = mysql_query($query_recordset, $conn);
		$databaseFiles = array();
		while ($row_recordset = mysql_fetch_row($recordset)) 
		{
			array_push($databaseFiles, $row_recordset[0]); //datafiles array built
		}
		mysql_free_result($recordset); //Phase 1
		//print_r($databaseFiles);
		$folderFiles = array();
		foreach ($folderFilePaths as $folderFilePath) //build folderFiles Array
		{
			$thePath = strlen($folderFilePath); //length of path
			$st = strlen(strrchr($folderFilePath,"/")) - 1; //length last folder
			$theFolderFileName = substr($folderFilePath, $thePath - $st);
				if(($theFolderFileName != "000Pic") && ($theFolderFileName != "000Tmb")){
					array_push($folderFiles, $theFolderFileName); //folderfiles array built	
				}
		}
		//print_r($folderFiles);
		$webFiles = array();
		foreach ($webFilePaths as $webFilePath) //build webFiles Array
		{
			$thePath = strlen($webFilePath); //length of path
			$st = strlen(strrchr($webFilePath,"/")) - 1; //length last folder
			$theWebFileName = substr($webFilePath, $thePath - $st);
				if(($theWebFileName != "000Pic") && ($theWebFileName != "000Tmb")){
					array_push($webFiles, $theWebFileName); //webfiles array built	
				}
		}
		//print_r($webFiles);
		$thumbFiles = array();
		foreach ($thumbFilePaths as $thumbFilePath) //build thumbFiles Array
		{
			$thePath = strlen($thumbFilePath); //length of path
			$st = strlen(strrchr($thumbFilePath,"/")) - 1; //length last folder
			$thethumbFileName = substr($thumbFilePath, $thePath - $st);
				if(($thethumbFileName != "000Pic") && ($thethumbFileName != "000Tmb")){
					array_push($thumbFiles, $thethumbFileName); //thumbfiles array built	
				}
		}
		//print_r($thumbFiles);
		
		foreach ($folderFiles as $folderFile) //create data records for any unlisted files
		{
			if(($folderFile != "000Pic") && ($folderFile != "000Tmb"))
			{
				if(!in_array($folderFile, $databaseFiles))
				{
					$theImage = $baseImagePath . $folderFile;
					$theImageInfo = getimagesize($theImage);
					$theImageX = $theImageInfo[0];
					$theImageY = $theImageInfo[1];
					$theImageRatio = "{$theImageY}"/"{$theImageX}";
					$theNewY = 150 * ($theImageRatio/0.75);	
					$query_insert = sprintf("INSERT INTO `Photos` (DFC,DLC,User,FolderName,FileName,Thumb) VALUES (now(),now(),%s,%s,%s,%s)" ,			
										GetSQLValueString($_SESSION['Name'], "text"), # 
										GetSQLValueString($theFolder, "text"), # 
										GetSQLValueString($folderFile, "text"), # 
										GetSQLValueString($theNewY, "text") # 
										);
				$ok = mysql_query($query_insert, $conn);
			//	echo $theFolder . " " . $folderFile . " Inserted in Database<br>";	THISWORKS!!!!!!!!!!
				}
				if(!in_array($folderFile, $thumbFiles))
				{
				$result = processThumbs($folderFile, $theFolder);
				}
				if(!in_array($folderFile, $webFiles))
				{
				 $result = processWebs($folderFile, $theFolder);
				}
			
			}
		}
				
		foreach ($databaseFiles as $databaseFile) 	//Check for Deleted Photos, Delete Record, send email
		{
			if(!in_array($databaseFile, $folderFiles))
				{
				$query_delete = sprintf("DELETE FROM `Photos` WHERE FolderName  =  '{$theFolder}' AND 	FileName  =  '{$databaseFile}'");
				$ok = mysql_query($query_delete, $conn);
			//error_log("Big trouble, we're all out of FOOs!", 1,"operator@example.com");
				}
		}
		$order = "";
		$fields = array('Id','Name','Field','Square','Locus','Basket','Object','Description','FolderName','FileName','Comments','CreateDate','Category');
		if (@$_REQUEST["orderField"] != "" && in_array(@$_REQUEST["orderField"], $fields)) {
			$order = "ORDER BY " . @$_REQUEST["orderField"] . " " . (in_array(@$_REQUEST["orderDirection"], array("ASC", "DESC")) ? @$_REQUEST["orderDirection"] : "ASC");
		}
	 	$query_recordset = "SELECT Id,Name,Field,Square,Locus,Basket,Object,Description,FolderName,FileName,Comments,CreateDate,Category FROM `Photos` $where $order";	
		$recordset = mysql_query($query_recordset, $conn);
		$toret = array();
		while ($row_recordset = mysql_fetch_assoc($recordset)) 
		{
				array_push($toret, $row_recordset);
		}
		
		$toret = array("data" => $toret, "metadata" => array ());
		mysql_free_result($recordset); //Phase 1
		return $toret;
}

function getDatabaseFolders()
	{
	global $conn;
	$query_recordset = "SELECT DISTINCT FolderName FROM `Photos`";
	$recordset = mysql_query($query_recordset, $conn);
	$databaseFolders = array();
	while ($row_recordset = mysql_fetch_row($recordset)) 
	{
		array_push($databaseFolders, $row_recordset[0]); //datafiles array built
	}
	mysql_free_result($recordset); //Phase 1
	return $databaseFolders;
	}
	
function getDiskFolders()
		{
			$diskFolderPaths = glob("../../images/*", GLOB_ONLYDIR);
			$theDiskFolders = array();
			foreach($diskFolderPaths as $theDiskFolderPath)
			{
				$theDiskFolder = substr($theDiskFolderPath, 13);
				array_push($theDiskFolders, $theDiskFolder);
			}
			return $theDiskFolders;
		}
	
function findFolders() { //Array of Disk Folders returned
		global $conn;
		$diskFolders = getDiskFolders();
	//	print_r($diskFolders);
		$databaseFolders = getDatabaseFolders();
	//	print_r($databaseFolders);
		foreach ($databaseFolders as $thedatabaseFolder)
			{
				if(!in_array($thedatabaseFolder, $diskFolders))
				{
				$query_delete = sprintf("DELETE FROM `Photos` WHERE FolderName  =  '{$thedatabaseFolder}'");
				$ok = mysql_query($query_delete, $conn);
				}
			}
		$diskFoldersAssocArray = array(array("FolderName"=> null));
		foreach ($diskFolders as $thediskFolder)
			{
			$theTempFolderNameArray = array("FolderName"=> $thediskFolder);
			array_push($diskFoldersAssocArray, $theTempFolderNameArray); //folderfiles array built
			}
		$toret = array(
			"data" => $diskFoldersAssocArray, 
			"metadata" => array (
			)
		);
		return $toret;
	}

function rowCount() {
	global $conn, $filter_field, $filter_type;

	$where = "";
	if (@$_REQUEST['filter'] != "") {
		$where = "WHERE " . $filter_field . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], $filter_type);	
	}
	$rscount = mysql_query("SELECT count(*) AS cnt FROM `Photos` $where"); 
	$row_rscount = mysql_fetch_assoc($rscount);
	$totalrows = (int) $row_rscount["cnt"];
	$toret = array(
		"data" => $totalrows, 
		"metadata" => array()
	);

	return $toret;
}

function insert() {
	global $conn;
	$query_insert = sprintf("INSERT INTO `Photos` (DFC,DLC,User,Name,Field,Square,Locus,Basket,Object,Description,FolderName,FileName,Comments,CreateDate,Category) VALUES (now(),now(),%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)" ,			
			GetSQLValueString($_SESSION['Name'], "text"), # 
			GetSQLValueString($_REQUEST["Name"], "text"), # 
			GetSQLValueString($_REQUEST["Field"], "text"), # 
			GetSQLValueString($_REQUEST["Square"], "text"), # 
			GetSQLValueString($_REQUEST["Locus"], "text"), # 
			GetSQLValueString($_REQUEST["Basket"], "text"), # 
			GetSQLValueString($_REQUEST["Object"], "text"), # 
			GetSQLValueString($_REQUEST["Description"], "text"), # 
			GetSQLValueString($_REQUEST["FolderName"], "text"), # 
			GetSQLValueString($_REQUEST["FileName"], "text"), # 
			GetSQLValueString($_REQUEST["Comments"], "text"), # 
			GetSQLValueString($_REQUEST["CreateDate"], "text"), # 
			GetSQLValueString($_REQUEST["Category"], "text")# 
	);
	$ok = mysql_query($query_insert);
	
	if ($ok) {
		// return the new entry, using the insert id
		$toret = array(
			"data" => array(
				array(
					"Id" => mysql_insert_id(), 
					"Name" => $_REQUEST["Name"], # 
					"Field" => $_REQUEST["Field"], # 
					"Square" => $_REQUEST["Square"], # 
					"Locus" => $_REQUEST["Locus"], # 
					"Basket" => $_REQUEST["Basket"], # 
					"Object" => $_REQUEST["Object"], # 
					"Description" => $_REQUEST["Description"], # 
					"FolderName" => $_REQUEST["FolderName"], # 
					"FileName" => $_REQUEST["FileName"], # 
					"Comments" => $_REQUEST["Comments"], # 
					"CreateDate" => $_REQUEST["CreateDate"], # 
					"Category" => $_REQUEST["Category"]# 
				)
			), 
			"metadata" => array()
		);
	} else {
		// we had an error, return it
		$toret = array(
			"data" => array("error" => mysql_error()), 
			"metadata" => array()
		);
	}
	return $toret;
}

function update() {
	global $conn;
	$query_recordset = sprintf("SELECT * FROM `Photos` WHERE Id = %s",
		GetSQLValueString($_REQUEST["Id"], "int")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);
	if ($num_rows > 0) {
		$theObject = $_REQUEST["Object"];
		if (strlen($theObject > 0))
			{$theObject = "-" . $theObject;
			} 
		$Name =  updateName($_REQUEST["Field"], $_REQUEST["Square"], $_REQUEST["Locus"], $_REQUEST["Basket"]) . $theObject;
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_update = sprintf("UPDATE `Photos` SET DLC = now(),User = %s,Name = %s,Field = %s,Square = %s,Locus = %s,Basket = %s,Object = %s,Description = %s,FolderName = %s,FileName = %s,Comments = %s,CreateDate = %s,Category = %s WHERE Id = %s", 
			GetSQLValueString($_SESSION['Name'], "text"), 
			GetSQLValueString($Name, "text"), 
			GetSQLValueString($_REQUEST["Field"], "text"), 
			GetSQLValueString($_REQUEST["Square"], "text"), 
			GetSQLValueString($_REQUEST["Locus"], "text"), 
			GetSQLValueString($_REQUEST["Basket"], "text"), 
			GetSQLValueString($_REQUEST["Object"], "text"), 
			GetSQLValueString($_REQUEST["Description"], "text"), 
			GetSQLValueString($_REQUEST["FolderName"], "text"), 
			GetSQLValueString($_REQUEST["FileName"], "text"), 
			GetSQLValueString($_REQUEST["Comments"], "text"), 
			GetSQLValueString($_REQUEST["CreateDate"], "text"), 
			GetSQLValueString($_REQUEST["Category"], "text"), 
			GetSQLValueString($row_recordset["Id"], "int")
		);
		$ok = mysql_query($query_update);
		if ($ok) {
			// return the updated entry
			$toret = array(
				"data" => array(
					array(
						"Id" => $row_recordset["Id"], 
						"RowNum" => $_REQUEST["RowNum"],  #
						"ColNum" => $_REQUEST["ColNum"],  #
						"Name" => $Name, #
						"Field" => $_REQUEST["Field"], #
						"Square" => $_REQUEST["Square"], #
						"Locus" => $_REQUEST["Locus"], #
						"Basket" => $_REQUEST["Basket"], #
						"Object" => $_REQUEST["Object"], #
						"Description" => $_REQUEST["Description"], #
						"FolderName" => $_REQUEST["FolderName"], #
						"FileName" => $_REQUEST["FileName"], #
						"Comments" => $_REQUEST["Comments"], #
						"CreateDate" => $_REQUEST["CreateDate"], #
						"Category" => $_REQUEST["Category"]#
					)
				), 
				"metadata" => array()
			);
		} else {
			// an update error, return it
			$toret = array(
				"data" => array("error" => mysql_error()), 
				"metadata" => array()
			);
		}
	} else {
		$toret = array(
			"data" => array("error" => "No row found"), 
			"metadata" => array()
		);
	}
	return $toret;
}

function delete() {
	global $conn;

	// check to see if the record actually exists in the database
	$query_recordset = sprintf("SELECT * FROM `Photos` WHERE Id = %s",
		GetSQLValueString($_REQUEST["Id"], "int")
	);
	$recordset = mysql_query($query_recordset, $conn);
	$num_rows = mysql_num_rows($recordset);

	if ($num_rows > 0) {
		$row_recordset = mysql_fetch_assoc($recordset);
		$query_delete = sprintf("DELETE FROM `Photos` WHERE Id = %s", 
			GetSQLValueString($row_recordset["Id"], "int")
		);
		$ok = mysql_query($query_delete);
		if ($ok) {
			// delete went through ok, return OK
			$toret = array(
				"data" => $row_recordset["Id"], 
				"metadata" => array()
			);
		} else {
			$toret = array(
				"data" => array("error" => mysql_error()), 
				"metadata" => array()
			);
		}

	} else {
		// no row found, return an error
		$toret = array(
			"data" => array("error" => "No row found"), 
			"metadata" => array()
		);
	}
	return $toret;
}

$ret = array(
	"data" => array("error" => "No operation"), 
	"metadata" => array()
);


if ($conn === false) {
	$ret = array(
		"data" => array("error" => "database connection error, please check your settings !"), 
		"metadata" => array()
	);
} else {
	mysql_select_db($database_conn, $conn);
	switch (@$_REQUEST["method"]) {
		case "FindAll":
			$ret = findAll();
		break;
		case "Insert": 
			$ret = insert();
		break;
		case "GetSources": 
			$ret = getSources();
		break;
		case "FindFolders": 
			$ret = findFolders();
		break;
		case "Update": 
			$ret = update();
		break;
		case "Delete": 
			$ret = delete();
		break;
		case "Count":
			$ret = rowCount();
		break;
		case "Visitor":
			$ret = visitor();
		break;
		case "Flex_confirmLogin":
			$ret = flex_confirmLogin();
		break;
		case "FindUsers":
			$ret = findUsers();
		break;
		case "Flex_LogIn":
			$ret = flex_LogIn();
		break;
		case "FindFolders":
		$ret = 	findFolders();
		break;
	}
}

$serializer = new XmlSerializer();
echo $serializer->serialize($ret);
die();
?>
