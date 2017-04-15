<?php
$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";

function lastName($Name){
	$Place = strpos($Name, ","); 
	return substr($Name, 0, $Place);  
}
function firstName($Name){
	$Place = strpos($Name, ","); 
	if($Place>0){$Place = $Place+1;}
	return substr($Name, $Place);  
}
function dateFormat($date){
	return substr($date,5,2)  . "/"  . substr($date,8,2) . "/" . substr($date,2,2);
}
function logID($Name){
	$Place = strpos($Name, ","); 
	$lastName = trim(substr($Name, 0, $Place));  
	if($Place>0){$Place = $Place+1;}
	$firstName =  trim(substr($Name, $Place));  
	return  $firstName . " " . $lastName;
}
function reportsHeader($Title = "Archaeolibrary Excavation Management Software",$MCClass = "", $MCYear = "", $Keywords = array("Archaeology","Software","Biblical","ASOR","Analysis","Excavation","Antiquities Authority"),$PageDescrip = "Archaeological Excavation Management and Analysis Software", $Selection = "", $Year = ""){
	$Keys =  implode(",", $Keywords);
	$Header = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">";
	$Header .= "<HTML>";
	$Header .= "<HEAD>";
	$Header .= "<TITLE>$Title</TITLE>";
	$Header .= "<META http-equiv=\"content-Type\" content=\"text/html; charset=iso-8859-1\">";
	$Header .= "<META name=\"description\" content=\"{$PageDescrip}\" >";
	$Header .= "<META name=\"keywords\" content=\"{$Keys}\">";
	$Header .= "<META name=\"Distribution\" content=\"Global\" >";
	$Header .= "<META name=\"Revisit-After\" content=\"14 Days\" >";
	$Header .= "<META name=\"Robots\" content=\"All\" >";
	$Header .= "<link href=\"../includes/Stylesheet.css\" rel=\"stylesheet\" type=\"text/css\" />";
	$Header .= "</HEAD>";
	$Header .= "<BODY lang=EN-US vLink=purple link=blue bgColor=white>";
	$Header .="<p align=center><Table class=\"head\" WIDTH=\"800\" border=0 ><tr><td VALIGN=BOTTOM ALIGN=CENTER WIDTH=\"200\">";
	$Header .= "<br><br>" . logID($_SESSION['Name']);	
	$Header .="</td><td VALIGN=TOP ALIGN=CENTER  WIDTH=\"400\"><h2>";
	$Header .= "<br>" . DIG_NAME . "<br>" . $Title . "<BR>";
	$Header .="$MCClass<br>$MCYear</h2>";
	$Header .="</td><td VALIGN=BOTTOM align=CENTER WIDTH=\"200\"><br><ul class=\"entry\"><li><a href=\"../demo/\">Home</a></li></ul>";
	$Header .="</td></tr></Table>";		
	echo $Header;
}

function sortPop() {
	switch ($_SESSION['sort'])
	{
	case Name:
	  $sortName = " selected=\"selected\"";
	  break;  
	case Square:
	 $sortSquare = " selected=\"selected\"";
	  break;
	case Locus:
	 $sortLocus = " selected=\"selected\"";
	  break;
	case Basket:
	 $sortBasket = " selected=\"selected\"";
	  break;
	case Date:
	 $sortDate = " selected=\"selected\"";
	  break;
	case Description:
	 $sortDescription = " selected=\"selected\"";
	  break;
	default:
	 $sortOrder = " selected=\"selected\"";
	  break;
	}
	$sortPop = "&nbsp; Sort: <select name=\"Order\">";
	$sortPop .="<option" . $sortName . ">Name</option>";
	$sortPop .="<option" . $sortSquare . ">Square</option>";
	$sortPop .="<option" . $sortLocus . ">Locus</option>";
	$sortPop .="<option" . $sortBasket . ">Basket</option>";
	$sortPop .="<option" . $sortDate . ">Date</option>";
	$sortPop .="<option" . $sortDescription . ">Description</option>";
	$sortPop .="</select>";
	echo $sortPop;}

function yearPop(){
	//<option selected="selected">Saab</option>
	switch ($_SESSION['year'])
	{
	case 2011:
	 $Select11 = " selected=\"selected\"";
	  break;  
	case 2010:
	  $Select10 = " selected=\"selected\"";
	  break;  
	case 2009:
	  $Select9 = " selected=\"selected\"";
	  break;  
	case 2008:
	 $Select8 = " selected=\"selected\"";
	  break;
	case 2007:
	 $Select7 = " selected=\"selected\"";
	  break;
	case 2006:
	 $Select6 = " selected=\"selected\"";
	  break;
	default:
	 $SelectAll = " selected=\"selected\"";
	  break;
	}
	
	$YearPop = " Year: <select name=\"year\" size=\"1\">";
	$YearPop .= "<option" . $Select11 . ">2011</option>";
	$YearPop .= "<option" . $Select10 . ">2010</option>";
	$YearPop .= "<option" . $Select9 . ">2009</option>";
	$YearPop .= "<option" . $Select8 . ">2008</option>";
	$YearPop .= "<option" . $Select7 . ">2007</option>";
	$YearPop .= "<option" . $Select6 . ">2006</option>";
	$YearPop .= "<option" . $SelectAll . ">All</option>";
	$YearPop .= "</select>";
	echo $YearPop;
}

function classPop() {
	$query = "SELECT Class FROM MC_Class";
	$result = mysql_query($query);
	confirm_query($result);
	$classPop = " Class: <select name=\"MC_Class\" size=\"1\">";
	$classPop .= "<option>Select Class</option>";
	while ($row = mysql_fetch_array($result)) {
			if($row['Class'] == $_SESSION['class']){ $ClassSelect = " selected=\"selected\"";} else { $ClassSelect = ""; }
			$classPop .= "<option" . $ClassSelect . ">" . $row['Class'] . "</option>"; }		
			$classPop .= "</select>";
			echo $classPop; }
			
			
function userPop($headerInsert = ""){
	$query = "SELECT Name FROM Staff";
	$result = mysql_query($query);
	confirm_query($result);
	$userPop = "<select name=\"Name\" size=\"1\">";
	if($headerInsert == $_SESSION['Name']){$userPop .= "<option" . " selected=\"selected\"" . ">" . $headerInsert . "</option>";} else {
	$userPop .= "<option>" . $headerInsert . "</option>" ;	
	}
	while ($row = mysql_fetch_array($result)) {
	if($row['Name'] == $_SESSION['Name']){ $UserSelect = " selected=\"selected\"";} else { $UserSelect = ""; }
	$userPop .= "<option" . $UserSelect . ">" . $row['Name'] . "</option>";}
	$userPop .= "</select>";
	echo $userPop;
}

function userusePop($headerInsert = ""){
	$query = "SELECT Name FROM Staff";
	$result = mysql_query($query);
	confirm_query($result);
	$userusePop = "<select name=\"user\" size=\"1\">";
	if($headerInsert == $_SESSION['User']){$userusePop .= "<option" . " selected=\"selected\"" . ">" . $headerInsert . "</option>";} else {
	$userusePop .= "<option>" . $headerInsert . "</option>" ;	
	}
	while ($row = mysql_fetch_array($result)) {
	if($row['Name'] == $_SESSION['User']){ $UserSelect = " selected=\"selected\"";} else { $UserSelect = ""; }
	$userusePop .= "<option" . $UserSelect . ">" . $row['Name'] . "</option>";}
	$userusePop .= "</select>";
	echo $userusePop;
}

function pagePop($headerInsert = ""){
	$query = "SELECT Name FROM Pages";
	$result = mysql_query($query);
	confirm_query($result);
	$pagePop = " Page &nbsp;<select name=\"page\" size=\"1\">";
	if($headerInsert == $_SESSION['Page']){$pagePop .= "<option" . " selected=\"selected\"" . ">" . $headerInsert . "</option>";} else {
	$pagePop .= "<option>" . $headerInsert . "</option>" ;	
	}
	while ($row = mysql_fetch_array($result)) {
	if($row['Name'] == $_SESSION['Page']){ $pageSelect = " selected=\"selected\"";} else { $pageSelect = ""; }
	$pagePop .= "<option" . $pageSelect . ">" . $row['Name'] . "</option>";}
	$pagePop .= "</select>";
	echo $pagePop;
}

function typePop(){
	switch ($_SESSION['Display'])
	{
	case All:
	  $DisplayAll = " selected=\"selected\"";
	  break;  
	case Pottery:
	 $DisplayPottery = " selected=\"selected\"";
	  break;
	case Culture:
	 $DisplayCulture = " selected=\"selected\"";
	  break;
	default:
	 $SelectAll = " selected=\"selected\"";
	  break;
	}
		$typePop = " Show: <select name=\"Display\" size=\"1\">";
		$typePop .= "<option" . $DisplayAll . ">All</option>";
		$typePop .= "<option" . $DisplayPottery . ">Pottery</option>";
		$typePop .= "<option" . $DisplayCulture . ">Culture</option>";
		$typePop .= "</select>";
		echo 	$typePop;
}

function groupPop(){
	switch ($_SESSION['group'])
	{
	case None:
	  $goupNone = " selected=\"selected\"";
	  break;  
	case Basket:
	 $groupBasket = " selected=\"selected\"";
	  break;
	case Locus:
	 $groupLocus = " selected=\"selected\"";
	  break;
	case Square:
	 $groupSquare = " selected=\"selected\"";
	  break;
	default:
	 $groupNone = " selected=\"selected\"";
	  break;
	}
		$groupPop = "&nbsp; Group: <select name=\"group\" size=\"1\">";
		$groupPop .= "<option" . $groupNone . ">None</option>";
		$groupPop .= "<option" . $groupBasket . ">Basket</option>";
		$groupPop .= "<option" . $groupLocus . ">Locus</option>";
		$groupPop .= "<option" . $groupSquare . ">Square</option>";
		$groupPop .= "</select>";
		echo 	$groupPop;
}

function updownPop(){
	switch ($_SESSION['UpDown'])
	{
	case ASC:
	  $ASC = " selected=\"selected\"";
	  break;  
	case DESC:
	 $DESC = " selected=\"selected\"";
	  break;
	}
		$updownPop = " <select name=\"UpDown\">";
		$updownPop .= "<option" . $ASC . ">ASC</option>";
		$updownPop .= "<option" . $DESC . ">DESC</option>";
		$updownPop .= "</select>";
		echo $updownPop;
		}

function viewPop(){
	switch ($_SESSION['view'])
	{
		case Full:
		 $Full = " selected=\"selected\"";
		  break;
		case Sequence:
		  $Sequence = " selected=\"selected\"";
		  break;  
		case Number:
		 $Number = " selected=\"selected\"";
		  break;
		case Supers:
		 $Supers = " selected=\"selected\"";
		  break;
	}
	$viewPop = "&nbsp; View: <select name=\"view\">";
	$viewPop .= "<option" . $Full . ">Full</option>";
	$viewPop .= "<option" . $Sequence . ">Sequence</option>";
	$viewPop .= "<option" . $Number . ">Number</option>";
	$viewPop .= "<option" . $Supers . ">Supers</option>";
	$viewPop .= "</select>";
	echo $viewPop;
	}
	
function redirect_to($location = NULL) {
	if($location != NULL)
	{
			header("Location: {$location}");
			exit;
	}
}

function logged_in() {
	return isset($_SESSION['Name']);
}
global $conn;
function confirm_logged_in($PageTrack = "Unknown") {
	global $conn;
	if(!logged_in()) {
		redirect_to("../demo/");
	} else {	
		$Page = $PageTrack;	
	$Name = $_SESSION['Name'];
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


	if(GetSQLValueString($Name, "text") == "Pride, Daniel") {	
	} else {
//	$ok = mysql_query($query_insert, $conn);
	}
	
	
	}
}

function listVisit() {
	global $conn;	
	$Page = "ListVisit";	
	$Name = "ListVisit";
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
//	$ok = mysql_query($query_insert, $conn);
}


function check_required_fields($required_array) {
		$field_errors = array();
		foreach($required_array as $fieldname) {
			if(!isset($_POST[$fieldname]) || (empty($_POST[$fieldname]) &&
			!is_int($_POST[$fieldname])))
			{
				$field_errors[] = $fieldname;
			}
		}
		return $field_errors;
}

function check_max_field_lengths($field_Length_array) 
{
	$field_errors = array();
	foreach($field_Length_array as $fieldname => $maxlength) 
	{
		if (strlen(trim(mysql_prep($_POST[$fieldname]))) > $maxlength) 
		{
			$field_errors[] = $fieldname; 
		}
	}
	return $field_errors;
}

function display_errors($error_array) 
{
	echo "<p class=\"errors\">";
	echo "Please review the following fields:<br>";
	foreach($error_array as $error) 
	{
		echo " - " . $error . "<br>";
	}
	echo "</p>";
}

function mysql_prep($value) {
		$Magic_quotes_active = get_magic_quotes_gpc();
		$new_enough_php = function_exists("mysql_real_escape_string");
		if($new_enough_php) {
			if($Magic_quotes_active) {$value = stripslashes($value);}
			$value = mysql_real_escape_string($value);
			} else {
				if (!$Magic_quotes_active){$value = addslashes($value); }
			}
			return $value;
		}
		
function confirm_query($result_set) {
		if (!$result_set) {
		die("Database query failed" . mysql_error());	
		}
	}
?>