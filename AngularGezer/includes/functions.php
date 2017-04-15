<?php 
//require_once('initialize.php');
function HeadersSent() {
	if (headers_sent ( $filename, $linenum )) {
	//	Log::logit ( "Headers", $filename . " " . $linenum );
		exit ();
	}
}
function redirectGood($url) {
	$params = array (
			'http' => array (
					'method' => 'HEAD',
					'ignore_errors' => true 
			) 
	);
	$context = stream_context_create ( $params );
	$fp = fopen ( $url, 'rb', false, $context );
	$result = stream_get_contents ( $fp );
	if ($result === false) {
		return false;
	} else if (! strstr ( $http_response_header [0], '301' )) {
		return true;
	}
}
function userCancel($Pid) {
	$Rides = Ride::delete_all_Pid ( $Pid );
	$Drives = Drive::delete_all_Pid ( $Pid );
	$Scheds = Schedule::delete_all_Pid ( $Pid );
	$Outs = Output::delete_all_Pid ( $Pid );
}
function getGeoPosition($address) {
	$url = "http://maps.google.com/maps/api/geocode/json?sensor=false" . "&address=" . urlencode ( $address );
	$json = file_get_contents ( $url );
	$data = json_decode ( $json, TRUE );
	if ($data ['status'] == "OK") {
		return $data ['results'];
	} else {
	}
}
function countCellLetters($Pid) {
	global $database;
	$sql = "SELECT COUNT(*) FROM Outputs WHERE `OutputType`='T-Code' AND `Pid`='{$Pid}'";
	$result_set = $database->query ( $sql );
	$row = $database->fetch_array ( $result_set );
	return array_shift ( $row );
}
// Spherical Law of Cosines
function calc_distance($lat1, $lon1, $lat2, $lon2) {
	$earth_radius = 3960.00;
	// $delta_lat = $lat2 - $lat1 ;
	$delta_lon = $lon2 - $lon1;
	$distance = sin ( deg2rad ( $lat1 ) ) * sin ( deg2rad ( $lat2 ) ) + cos ( deg2rad ( $lat1 ) ) * cos ( deg2rad ( $lat2 ) ) * cos ( deg2rad ( $delta_lon ) );
	$distance = acos ( $distance );
	$distance = rad2deg ( $distance );
	$distance = $distance * 60 * 1.1515;
	$distance = round ( $distance, 2 );
	return $distance;
}
function showRunMap($Id) {
	$mapfilename = "runmap" . $Id;
	$FolderName = folder_name ( "runmap", $Id );
	$FolderPath = "images/" . $FolderName;
	(! (file_exists ( $FolderPath ))) ? mkdir ( $FolderPath ) : '';
	$theFileName = $FolderPath . "/runmap{$Id}.jpg";
	//$theImage = "<a href=\"{$theFileName}\"><img src=\"{$theFileName}\"/></a>";
	$theImage = "<input type=\"image\" src=\"{$theFileName}\" name=\"mapbutton\">";
	return $theImage;
}
function liveRunMap($DriveFromId, $DriveToId, $RideFromId, $RideToId) {
	$DF = Place::find_by_id ( $DriveFromId );
	$DFGPS = $DF->Lat . "," . $DF->Lng;
	$DT = Place::find_by_id ( $DriveToId );
	$DTGPS = $DT->Lat . "," . $DT->Lng;
	$RF = Place::find_by_id ( $RideFromId );
	$RFGPS = $RF->Lat . "," . $RF->Lng;
	$RT = Place::find_by_id ( $RideToId );
	$RTGPS = $RT->Lat . "," . $RT->Lng;
	$result = "<iframe  width=\"90%\" height=\"100%\" frameborder=\"0\" style=\"border:\"0\" ";
	$result .= "src=\"https://www.google.com/maps/embed/v1/directions?";
	$result .= "key=AIzaSyA_PVKSFVpcRgfwBd87Rv13iNmo4Fs-4JA";
	$result .= "&origin={$DFGPS}";
	$result .= "&waypoints={$RFGPS}|{$RTGPS}";
	$result .= "&destination={$DTGPS}\">";
	$result .= "</iframe>";
	return $result;
}
function liveDriveMap($DriveFromId, $DriveToId) {
	$DF = Place::find_by_id ( $DriveFromId );
	$DFGPS = $DF->Lat . "," . $DF->Lng;
	$DT = Place::find_by_id ( $DriveToId );
	$DTGPS = $DT->Lat . "," . $DT->Lng;
	$result = "<iframe  width=\"90%\" height=\"100%\" frameborder=\"0\" style=\"border:\"0\" ";
	$result .= "src=\"https://www.google.com/maps/embed/v1/directions?";
	$result .= "key=AIzaSyA_PVKSFVpcRgfwBd87Rv13iNmo4Fs-4JA";
	$result .= "&origin={$DFGPS}";
	$result .= "&destination={$DTGPS}\">";
	$result .= "</iframe>";
	return $result;
}
function startsWith($haystack, $needle) {
	return $needle === "" || strpos ( $haystack, $needle ) === 0;
}
function strip_zeros_from_date($marked_string = "") {
	// first remove the marked zeros
	$no_zeros = str_replace ( '*0', '', $marked_string );
	// then remove any remaining marks
	$cleaned_string = str_replace ( '*', '', $no_zeros );
	return $cleaned_string;
}
function redirect_to($location = NULL) {
	if ($location != NULL) {
		header ( "Location: {$location}" );
		exit ();
	}
}
function folder_name($Type, $Id) {
	$value = floor ( $Id / 1000 );
	$digits = strlen ( $value );
	switch ($digits) {
		case "1" :
			$FolderName = "000" . $value;
			break;
		case "2" :
			$FolderName = "00" . $value;
			break;
		case "3" :
			$FolderName = "0" . $value;
			break;
	}
	return $Type . $FolderName;
}
function SendAllLetters() {
	global $session;
	// ($Did, $Pid="",$PidDFC="",$Lang="",$OutputType="", $TripDate="", $FromName="", $ToName="", $Email="", $CellDom="", $M_Phone="", $MatchPid='0',$RunId='0',$CellValid,$RD = 0,$RDId = 0,$RDDFC = '0000-00-00', $Admin = '0')
	$theId = 1;
	if (isset ( $_SESSION ["Id"] )) {
		$theId = $_SESSION ["Id"];
	}
	$theUser = User::find_by_id ( $theId );
	$thePerson = Person::find_by_id ( $theId );
	$TripDate = '2014-01-01';
	$FromName = "FromName";
	$ToName = "ToName";
	$MatchPid = "2";
	
	$RD = '2';
	$Admin = '1';
	$CellValid = '33';
	$sql = "SELECT * FROM Invoices WHERE `Id` > '0' LIMIT 1";
	$InvoiceArray = Invoice::find_by_sql ( $sql );
	$Invoice = array_shift ( $InvoiceArray );
	$RunId = $Invoice->Id;
	$sql = "SELECT * FROM Events WHERE `Id` > '0' LIMIT 1";
	$EventArray = Event::find_by_sql ( $sql );
	$Event = array_shift ( $EventArray );
	$RDId = $Event->Id;
	$RDDFC = $Event->DFC;
	$Reg = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "EReg", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$Reset = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "EReset", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$NoAct = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "ENoAct", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$ECode = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "ECode", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$TCode = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "TCode", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$ECode2 = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "ECode2", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$TCode2 = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "TCode2", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$EDrive = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "EDrive", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$TDrive = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "TDrive", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$ERide = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "ERide", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$TRide = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "TRide", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$EMonth = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "EMonth", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$EProfile = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "EProfile", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$EAuto = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "EAuto", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$EFace = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "EFace", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$EAbout = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "EAbout", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$EStop = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "EStop", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$ETell = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "ETell", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$EPromo = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "EPromo", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$EHoly = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "EHoly", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$EPlace = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "EPlace", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$EVideo = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "EVideo", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$EHelp = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "EHelp", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$EDRate = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "EDRate", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$ERRate = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "ERRate", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	// ****To Be Tested
	$ECancel = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "ECancel", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$Event = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "EEvent", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$Domain = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "EDomain", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$EInvite = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "EInvite", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$EInviteDB = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "EInviteDB", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
}
function cronSendTexts() {
	global $session;
	// ($Pid="",$PidDFC="",$Lang="",$OutputType="", $TripDate="", $FromName="", $ToName="", $Email="", $CellDom="", $M_Phone="", $MatchPid='0',$RunId='0',$CellValid,$RD = 0,$RDId = 0,$RDDFC = '0000-00-00', $Admin = '0')
	$theId = 1;
	if (isset ( $_SESSION ["Id"] )) {
		$theId = $_SESSION ["Id"];
	}
	$theUser = User::find_by_id ( $theId );
	$thePerson = Person::find_by_id ( $theId );
	$TripDate = '2014-01-01';
	$FromName = "FromName";
	$ToName = "ToName";
	$MatchPid = "2";
	$RunId = "102";
	$RD = '2';
	$RDId = '6131';
	$RDDFC = '2014-03-30';
	$Admin = '1';
	$CellValid = '33';
	$TCode = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "TCode", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$TCode2 = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "TCode2", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$TDrive = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "TDrive", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
	$TRide = Output::schedule_output ( $theUser->Did, $theUser->Id, $theUser->DFC, $theUser->Lang, "TRide", $TripDate, $FromName, $ToName, $theUser->Email, $thePerson->CellDom, $thePerson->M_Phone, $MatchPid, $RunId, $CellValid, $RD, $RDId, $RDDFC, $Admin );
}
function output_message($message = "") {
	if (! empty ( $message )) {
		return "<p class=\"message\">{$message}</p>";
	} else {
		return "";
	}
}
function __autoload($class_name) {
	$class_name = strtolower ( $class_name );
	$path = LIB_PATH . DS . "{$class_name}.php";
	if (file_exists ( $path )) {
		require_once ($path);
	} else {
		die ( "The file {$class_name}.php could not be found." );
	}
}
function include_layout_template($template = "") {
	include (PUBLIC_ROOT . 'layouts' . DS . $template);
}
// function logit($action, $message = "", $LoggingOn = "LoggingOn") {
// 	if ($LoggingOn == "LoggingOn") {
// 		$logfile = SITE_ROOT . DS . 'logs' . DS . 'log.txt';
// 		$new = file_exists ( $logfile ) ? false : true;
// 		if ($handle = fopen ( $logfile, 'a' )) { // append
// 			$timestamp = strftime ( "%Y-%m-%d %H:%M:%S", time () );
// 			$content = "{$timestamp} | {$action}: {$message}\n";
// 			fwrite ( $handle, $content );
// 			fclose ( $handle );
// 			if ($new) {
// 				chmod ( $logfile, 0755 );
// 			}
// 		} else {
// 		}
// 	}
// }
function submit() {
	return "<input type=\"submit\" name=\"submit\" id=\"submito\" value=\"submit\" />";
}
function AscDesc() {
	$record = array (
			'',
			"ASC",
			"DESC" 
	);
	$thePop = "<select name='AscDescPop'>";
	foreach ( $record as $value ) {
		$thePop .= "<option ";
		$thePop .= ($_SESSION ['AscDescPop'] == $value) ? 'selected' : '';
		$thePop .= '>';
		$thePop .= "{$value}</option>";
	}
	$theSubstring = $thePop . "</select>";
	return $theSubstring;
}
function InputQueryText() {
	return "<input type=\"text\" name=\"QueryInput\" size=\"25\" value=\"{$_SESSION['QueryInput']}\" />  &nbsp;";
}
function CreateDateArray() {
	global $lang;
	$DateArray = array ();
	$DateArray [date ( "D M j", strtotime ( "now" ) )] = date ( "Y-m-d", strtotime ( "now" ) );
	$SelectTravelDate = $lang->SelectTravelDate;
	$DateArray ["{$SelectTravelDate}"] = 'Select';
	for($i = 0; $i < 100; $i ++) {
		$DateArray [date ( "D M j", strtotime ( "+{$i} day" ) )] = date ( "Y-m-d", strtotime ( "+{$i} day" ) );
	}
	return $DateArray;
}
function CreateTimeArray($Start = "Start") {
	global $lang;
	if ($Start == "Start") {
		$SelectTimeOfDay = $lang->SelectStartOfTravel;
	} elseif ($Start == "End") {
		$SelectTimeOfDay = $lang->SelectEndOfTravel;
	}
	elseif ($Start == "Open") {
		$SelectTimeOfDay = "Select Start Time";
	}
	elseif ($Start == "Close") {
		$SelectTimeOfDay = "Select End Time";
	}
	$record = array (
			'Midnite' => '00:00:00',
			'12:15 AM' => '00:15:00',
			'12:30 AM' => '00:30:00',
			'12:45 AM' => '00:45:00',
			'1:00 AM' => '01:00:00',
			'1:15 AM' => '01:15:00',
			'1:30 AM' => '01:30:00',
			'1:45 AM' => '01:45:00',
			'2:00 AM' => '02:00:00',
			'2:15 AM' => '02:15:00',
			'2:30 AM' => '02:30:00',
			'2:45 AM' => '02:45:00',
			'3:00 AM' => '03:00:00',
			'3:15 AM' => '03:15:00',
			'3:30 AM' => '03:30:00',
			'3:45 AM' => '03:45:00',
			'4:00 AM' => '04:00:00',
			'4:15 AM' => '04:15:00',
			'4:30 AM' => '04:30:00',
			'4:45 AM' => '04:45:00',
			'5:00 AM' => '05:00:00',
			'5:15 AM' => '05:15:00',
			'5:30 AM' => '05:30:00',
			'5:45 AM' => '05:45:00',
			'6:00 AM' => '06:00:00',
			'6:15 AM' => '06:15:00',
			'6:30 AM' => '06:30:00',
			'6:45 AM' => '06:45:00',
			'7:00 AM' => '07:00:00',
			'7:15 AM' => '07:15:00',
			'7:30 AM' => '07:30:00',
			'7:45 AM' => '07:45:00',
			'8:00 AM' => '08:00:00',
			'8:15 AM' => '08:15:00',
			'8:30 AM' => '08:30:00',
			'8:45 AM' => '08:45:00',
			'9:00 AM' => '09:00:00',
			'9:15 AM' => '09:15:00',
			'9:30 AM' => '09:30:00',
			'9:45 AM' => '09:45:00',
			'10:00 AM' => '10:00:00',
			'10:15 AM' => '10:15:00',
			'10:30 AM' => '10:30:00',
			'10:45 AM' => '10:45:00',
			'11:00 AM' => '11:00:00',
			'11:15 AM' => '11:15:00',
			'11:30 AM' => '11:30:00',
			'11:45 AM' => '11:45:00',
			"{$SelectTimeOfDay}" => 'Select',
			'Noontime' => '12:00:00',
			'12:15 PM' => '12:15:00',
			'12:30 PM' => '12:30:00',
			'12:45 PM' => '12:45:00',
			'1:00 PM' => '13:00:00',
			'1:15 PM' => '13:15:00',
			'1:30 PM' => '13:30:00',
			'1:45 PM' => '13:45:00',
			'2:00 PM' => '14:00:00',
			'2:15 PM' => '14:15:00',
			'2:30 PM' => '14:30:00',
			'2:45 PM' => '14:45:00',
			'3:00 PM' => '15:00:00',
			'3:15 PM' => '15:15:00',
			'3:30 PM' => '15:30:00',
			'3:45 PM' => '15:45:00',
			'4:00 PM' => '16:00:00',
			'4:15 PM' => '16:15:00',
			'4:30 PM' => '16:30:00',
			'4:45 PM' => '16:45:00',
			'5:00 PM' => '17:00:00',
			'5:15 PM' => '17:15:00',
			'5:30 PM' => '17:30:00',
			'5:45 PM' => '17:45:00',
			'6:00 PM' => '18:00:00',
			'6:15 PM' => '18:15:00',
			'6:30 PM' => '18:30:00',
			'6:45 PM' => '18:45:00',
			'7:00 PM' => '19:00:00',
			'7:15 PM' => '19:15:00',
			'7:30 PM' => '19:30:00',
			'7:45 PM' => '19:45:00',
			'8:00 PM' => '20:00:00',
			'8:15 PM' => '20:15:00',
			'8:30 PM' => '20:30:00',
			'8:45 PM' => '20:45:00',
			'9:00 PM' => '21:00:00',
			'9:15 PM' => '21:15:00',
			'9:30 PM' => '21:30:00',
			'9:45 PM' => '21:45:00',
			'10:00 PM' => '22:00:00',
			'10:15 PM' => '22:15:00',
			'10:30 PM' => '22:30:00',
			'10:45 PM' => '22:45:00',
			'11:00 PM' => '23:00:00',
			'11:15 PM' => '23:15:00',
			'11:30 PM' => '23:30:00',
			'11:45 PM' => '23:45:00' 
	);
	return $record;
}
function SchedulerRadios($NoDisplay = "", $Weekly) {
	// global $record;
	global $lang;
	if ($Weekly == true) {
		$Once = false;
	} else {
		$Once = true;
	}
	$Scheduler = "";
	// if(strlen($NoDisplay)==0)
	// {<li . $NoDisplay>
	$Scheduler = "<ol>";
	$Scheduler .= "<li {$NoDisplay}><input type=\"radio\"  name=\"scheduler\" value=\"{$Once}\" id=\"schedulerfalse\" checked  ";
	$Scheduler .= "/>";
	$Scheduler .= "<label for=\"schedulerfalse\">" . $lang->ThisWeek . "</label></li>";
	
	// $NoDisplayRed =
	$Scheduler .= "<li {$NoDisplay}><input type=\"radio\"   name=\"scheduler\" value=\"{$Weekly}\" id=\"schedulertrue\"  ";
	$Scheduler .= "/> ";
	$Scheduler .= "<label style=\"color:red; font-weight:bold\" for=\"schedulertrue\">" . $lang->EveryWeek . "</label>";
	$Scheduler .= "</li></ol>";
	// }
	return $Scheduler;
}
function SchedulerButton() {
	return "<a href=\"cancelschedule.php?Id={$SchedId}\" style=\"height: 30px; text-align:center;\"><button>Delete Schedule</button></a>";
}
function AndOr() {
	$record = array (
			'',
			"AND",
			"OR" 
	);
	$thePop = "<select name='AndOrPop'>";
	foreach ( $record as $value ) {
		$thePop .= "<option ";
		$thePop .= ($_SESSION ['AndOrPop'] == $value) ? 'selected' : '';
		$thePop .= '>';
		$thePop .= "{$value}</option>";
	}
	$theSubstring = $thePop . "</select>";
	return $theSubstring;
}
function MySqlComparisons() { // Could check that $record exists and is an array
	$ComparisonsArray = array (
			"",
			"LIKE",
			"NOT LIKE",
			">",
			"<",
			">=",
			"<=" 
	);
	$thePop = "&nbsp; <select name='ComparisonPop'> &nbsp";
	foreach ( $ComparisonsArray as $value ) {
		$thePop .= "<option ";
		$thePop .= ($_SESSION ['ComparisonPop'] == $value) ? 'selected' : '';
		$thePop .= '>';
		$thePop .= "{$value}</option>";
	}
	$theSubstring = $thePop . "</select>";
	return $theSubstring;
}
function count_sql($sql) {
	global $database;
	$result_set = $database->query ( $sql );
	$row = $database->fetch_array ( $result_set );
	return array_shift ( $row );
}
// function find_Ids_by_sql($sql="") {
// global $database;
// $result_set = $database->query($sql);
// $id_array = array();
// while ($row = $database->fetch_array($result_set)) {
// $id_array[] = $row['Id'];
// }
// return $id_array;
// }
function TablePopup() {
	$thePop = "<select name='TablePop'>";
	$tablelist = array (
			"Bugs",
			"Comments",
			"Domains",
			"Drives",
			"Events",
			"Invoices",
			"Langs",
			"Langs2",
			"LangsErr",
			"LangsList",
			"Letters",
			"LetText",
			"LetVars",
			"LineItems",
			"Logs",
			"Outputs",
			"People",
			"Photos",
			"Places",
			"Popups",
			"Posts",
			"Rides",
			"Runs",
			"Schedules",
			"Sched86s",
			"Users",
			"Zero" 
	);
	foreach ( $tablelist as $value ) {
		$thePop .= "<option ";
		$thePop .= ($_SESSION ['TablePop'] == $value) ? 'selected' : '';
		$thePop .= '>';
		$thePop .= "{$value}</option>";
	}
	$thePop .= "</select>";
	return $thePop;
}
function stylePop() {
	$PhpSelf = $_SERVER ['PHP_SELF'];
	$theForm = "<form id='theform'  action=\"{$PhpSelf}\"  method=\"POST\">";
	$StylesArray = array (
			"list.css",
			"alternate.css" 
	);
	$thePop = "&nbsp; <select name='StylePop'> &nbsp";
	foreach ( $StylesArray as $value ) {
		// $value = $record["Value"];
		$thePop .= "<option ";
		$thePop .= ($_SESSION ['StylePop'] == $value) ? 'selected' : '';
		$thePop .= '>';
		$thePop .= "{$value}</option>";
	}
	$theSubstring = $theForm . $thePop . "</select>";
	return $theSubstring;
}
function todayDate() {
	return date ( "Y" ) . "-" . date ( "m" ) . "-" . date ( "d" );
}
function validationButtonDisplay($DisplayCodeInput) {
	global $lang;
	if ($DisplayCodeInput == TRUE) {
		return "<button type=\"submit\" style=\"float:none;margin-top:20px;\" name=\"sendval\" id=\"submit\" value=\"submit\">$lang->SendNow</button>";
	}
}
function validationcodedisplay($CellValidCode, $DisplayCodeInput, $Message, $FormInputErrors) {
	global $lang;
	$ValInputText = "";
	if ($DisplayCodeInput == TRUE) {
		$ValInputText .= "<li style=\"height: 60px\">";
		$ValInputText .= "<label for=\"CellValid\" class=\"label\">";
		$ValInputText .= "{$Message} </label>";
		$ValInputText .= "<input name=\"CellValid\" class=\"input\" id=\"CellValid\"  type=\"text\" placeholder=\"##\" ";
		$ValInputText .= "value=\"{$CellValidCode}\" >";
		if (array_key_exists ( 'CellValid', $FormInputErrors )) {
			$ValInputText .= "<p class=\"error\">" . $lang2->CellValidation . "</p>";
		}
		
		// $ValInputText .=
	} else {
		$ValInputText .= "<li style=\"display: none;\"><input name=\"CellValid\" style=\"display: none;\" class=\"hidden\" size=\"1\" type=\"text\" value=\"";
		$ValInputText .= "{$CellValidCode}\" >";
	}
	$ValInputText .="</li>";
	return $ValInputText;
}
function textingtext($DisplayCodeInput) {
	global $lang;
	if ($DisplayCodeInput == TRUE) {
		$Buttons = "<button type=\"submit\" name=\"skip\" id=\"skip\" value=\"skip\">{$lang->Skip}</button>";
	} else {
		$Buttons = "<button type=\"submit\" name=\"sendval\" id=\"sendval\" value=\"sendval\">{$lang->Test}</button>";
	}
	return $Buttons;
}
function textingSkipit($DisplayCodeInput) {
	global $lang;
	if ($DisplayCodeInput == TRUE) {
		$Buttons = "<button type=\"submit\" name=\"skip\" id=\"skip\" value=\"skip\">{$lang->Skip}</button>";
	} else {
		$Buttons = "";
	}
	return $Buttons;
}
function validationMessage($DisplayCodeInput) {
	global $lang2;
	$ValInputText = "";
	$ValInputText .= "<p style=\"margin: 10\">";
	if ($DisplayCodeInput == TRUE) {
		$ValInputText .= $lang2->TextingOnFooter;
	} else {
		$ValInputText .= $lang2->TextingOffFooter;
	}
	$ValInputText .= "</p>";
	return $ValInputText;
}
function textingHeader($DisplayCodeInput) {
	global $lang2;
	$ValInputText = "";
	$ValInputText .= "<p style=\"margin: 10\">";
	if ($DisplayCodeInput == TRUE) {
		$ValInputText .= $lang2->RegisterTextingHeader;
	} else {
		$ValInputText .= $lang2->TextingHeader;
	}
	$ValInputText .= "</p>";
	return $ValInputText;
}
function daycheckboxes($RideDateIn, $Hidden = "") {
	$RideDate = date ( $RideDateIn );
	$DayLetterArray = array (
			"M",
			"T",
			"W",
			"Th",
			"F",
			"Sa",
			"Su" 
	);
	$Checkboxes = "";
	for($i = 0; $i <= 6; $i ++) {
		if ($i == 0) {
			$theDay = strtotime ( "now" );
		} else {
			$theDay = strtotime ( "+{$i} day" );
		}
		$theDate = date ( "Y-m-d", $theDay );
		$DayNum = date ( "N", $theDay ) - 1;
		$DayLetter = (strlen ( $Hidden ) > 0) ? "" : $DayLetterArray [$DayNum];
		$LineHeight = (strlen ( $Hidden ) > 0) ? "\"height: .5;\"" : "";
		$Checkboxes .= "<li{$LineHeight}><label for=\"{$theDate}\">{$DayLetter}</label><input type=\"checkbox\" " . $Hidden . "name=\"DateArray[]\" value=\"{$theDate}\" id=\"{$theDate}\" {$Hidden} ";
		$theRecDate = substr ( $RideDate, 0, 10 );
		if ((isset ( $theRecDate )) && ($theRecDate == $theDate)) {
			$Checkboxes .= "checked";
		}
		$Checkboxes .= "/></li>";
	}
	return $Checkboxes;
}
function DriveDayCheckboxes($DriveDateIn) {
	$DriveDate = date ( $DriveDateIn );
	$DayLetterArray = array (
			"M",
			"T",
			"W",
			"Th",
			"F",
			"Sa",
			"Su" 
	);
	$Checkboxes = "";
	for($i = 0; $i <= 6; $i ++) {
		if ($i == 0) {
			$theDay = strtotime ( "now" );
		} else {
			$theDay = strtotime ( "+{$i} day" );
		}
		$theDate = date ( "Y-m-d", $theDay );
		$DayNum = date ( "N", $theDay ) - 1;
		$DayLetter = $DayLetterArray [$DayNum];
		$Checkboxes .= "<li><label for=\"{$theDate}\">{$DayLetter} </label><input type=\"checkbox\" name=\"DateArray[]\" value=\"{$theDate}\" id=\"{$theDate}\" ";
		$theRecDate = substr ( $DriveDate, 0, 10 );
		if ((isset ( $theRecDate )) && ($theRecDate == $theDate)) {
			$Checkboxes .= " checked ";
		}
		$Checkboxes .= "/></li>";
	}
	return $Checkboxes;
}
function displayreportlinks() {
	$Logstext = "<div id=\"reportbar\" style=\"width: 900px;margin-left: auto;margin-right: auto\">";
	$Logstext = "<div class=\"reportmenu\"><a href =\"logs.php\" >Logs</a></div>";
	$Logstext .= "<div class=\"reportmenu\"><a href =\"outputs.php\" >Outputs</a> </div>";
	$Logstext .= "<div class=\"reportmenu\"><a href =\"users.php\" >Users</a> </div>";
	$Logstext .= "<div class=\"reportmenu\"><a href =\"userfails.php\" >Fails</a> </div>";
	$Logstext .= "<div class=\"reportmenu\"><a href =\"people.php\" >People</a> </div>";
	$Logstext .= "<div class=\"reportmenu\"><a href =\"phones.php\" >Phones</a> </div>";
	$Logstext .= "<div class=\"reportmenu\"><a href =\"runmaps.php\" >Runmaps</a> </div>";
	$Logstext .= "<div class=\"reportmenu\"><a href =\"admin.php\" >admin</a></div>";
	$Logstext .= "<div class=\"reportmenu\"><a href =\"lists.php\" >Lists</a></div>";
	$Logstext .= "<div class=\"reportmenu\"><a href =\"feeds.php\" >Feeds</a></div>";
	$Logstext .= "<div class=\"reportmenu\"><a href =\"daily.php\" >Daily</a></div>";
	$Logstext .= "</p></div></div>";
	return $Logstext;
}
function IBG_LogonBar($activeTab) {
	global $lang;
	$NavBar = "<div class=\"nav\">";
	$NavBar .= "<ul><li ";
	
	if ($activeTab == $lang->About) {
		$NavBar .= "id=\"activetab\" ";
	}
	$NavBar .= "title=\"" . $lang->About . "\"><a href=\"about.php\">" . $lang->About . "</a></li><li ";
	
	if ($activeTab == "Example") {
		$NavBar .= "id=\"activetab\" ";
	}
	$NavBar .= "title=\"" . "Example" . "\"><a href=\"minmax.php\">" .  "Example" . "</a></li><li ";
	
	
	if ($activeTab == $lang->Register) {
		$NavBar .= "id=\"activetab\" ";
	}
	$NavBar .= "title=\"" . $lang->Register . "\"><a href=\"register.php\">" . $lang->Register . "</a></li><li ";
	
	if ($activeTab == $lang->Login) {
		$NavBar .= "id=\"activetab\" ";
	}
	$NavBar .= "title=\"" . $lang->Login . "\"><a href=\"login.php\">" . $lang->Login . "</a></li>";
	
	$NavBar .= "</ul>";
	$NavBar .= "</div>";
	return $NavBar;
}
function IBG_NavBar($activeTab) {
	global $lang;
	$NavBar = "<div class=\"nav\">";
	$NavBar .= "<ul><li ";
	if ($activeTab == $lang->Events) {
		$NavBar .= "id=\"activetab\" ";
	}
	$NavBar .= "title=\"" . $lang->Events . "\"><a href=\"index.php\">" . $lang->Events . "</a></li><li ";
	
	if ($activeTab == $lang->Drives) {
		$NavBar .= "id=\"activetab\" ";
	}
	$NavBar .= "title=\"" . $lang->Drives . "\"><a href=\"drives.php\">" . $lang->Drives . "</a></li><li ";
	
	if ($activeTab == $lang->Rides) {
		$NavBar .= "id=\"activetab\"";
	}
	$NavBar .= "title=\"" . $lang->Rides . "\"><a href=\"rides.php\">" . $lang->Rides . "</a></li><li ";
	
	if ($activeTab == $lang->Runs) {
		$NavBar .= "id=\"activetab\"";
	}
	$NavBar .= " title=\"" . $lang->Runs . "\"><a href=\"runs.php\">" . $lang->Runs . "</a></li> ";
	$NavBar .= "</ul>";
	$NavBar .= "</div>";
	return $NavBar;
}

function VideoBar($activeTab) {
	global $lang;
	$NavBar = "<div class=\"nav\">";
	$NavBar .= "<ul><li ";
	if ($activeTab == "Intro") {
		$NavBar .= "id=\"activetab\" ";
	}
	$NavBar .= "title=\"" . "Intro" . "\"><a href=\"index.php?V=Intro\">" . "Intro" . "</a></li><li ";

	if ($activeTab == "SAS") {
		$NavBar .= "id=\"activetab\" ";
	}
	$NavBar .= "title=\"" . "SAS" . "\"><a href=\"index.php?V=SAS\">" . "SAS" . "</a></li><li ";

	if ($activeTab == "Events") {
		$NavBar .= "id=\"activetab\"";
	}
	$NavBar .= "title=\"" . "Events" . "\"><a href=\"index.php?V=Events\">" . "Events" . "</a></li><li ";

	if ($activeTab == "Register") {
		$NavBar .= "id=\"activetab\"";
	}
	$NavBar .= " title=\"" . "Register" . "\"><a href=\"index.php?V=Register\">" . "Register" . "</a></li> ";
	$NavBar .= "</ul>";
	$NavBar .= "</div>";
	return $NavBar;
}
// function IBG_EvtBar($activeTab){
// global $lang;
// $NavBar = "<div class=\"nav\">";
// $NavBar .= "<ul><li ";
// if($activeTab == $lang->Places){
// $NavBar .= "id=\"activetab\" ";
// }
// $NavBar .= "title=\"".$lang->Places."\"><a href=\"places.php\">".$lang->Places."</a></li><li ";

// if($activeTab == $lang->Drives){
// $NavBar .= "id=\"activetab\" ";
// }
// $NavBar .= "title=\"".$lang->Drives."\"><a href=\"drives.php\">".$lang->Drives."</a></li><li ";

// if($activeTab == $lang->Rides){
// $NavBar .= "id=\"activetab\"";
// }
// $NavBar .= "title=\"".$lang->Rides."\"><a href=\"rides.php\">".$lang->Rides."</a></li><li ";

// if($activeTab == $lang->Runs){
// $NavBar .= "id=\"activetab\"";
// }
// $NavBar .= " title=\"".$lang->Runs."\"><a href=\"runs.php\">".$lang->Runs."</a></li> ";
// $NavBar .= "</ul>";
// $NavBar .= "</div>";
// return $NavBar;
// }
function IBG_LowerNavBar($activeTab = "Profile") {
	global $lang;
	$LowerNavBar = "<div class=\"lowernav\">";
	$LowerNavBar .= "<ul><li ";
	if ($activeTab == $lang->Profile) {
		$LowerNavBar .= "id=\"activetab\"";
	}
	$LowerNavBar .= " title=\"" . $lang->Profile . "\"><a href=\"profile.php\">" . $lang->Profile . "</a></li><li ";
	if ($activeTab == $lang->Places) {
		$LowerNavBar .= "id=\"activetab\"";
	}
	$LowerNavBar .= "title=\"" . $lang->Places . "\"><a href=\"places.php\">" . $lang->Places . "</a></li><li ";
	if ($activeTab == $lang->Here) {
		$LowerNavBar .= "id=\"activetab\"";
	}
	$LowerNavBar .= "title=\"" . $lang->Here . "\"><a href=\"namethisplace.php\">" . $lang->Here . "</a></li><li ";
	if ($activeTab == $lang->LogOut) {
		$LowerNavBar .= "id=\"activetab\"";
	}
	$LowerNavBar .= "title=\"" . $lang->LogOut . "\"><a href=\"logout.php\">" . $lang->LogOut . "</a></li>";
	$LowerNavBar .= "</ul>";
	$LowerNavBar .= "</div>";
	return $LowerNavBar;
}
function IBG_Header($Geo = "GeoOff") {
	global $lang;
	global $lang2;
	global $session;
	$Domain = Domain::find_by_id ( $_SESSION ['Did'] );
	$Header = "<!DOCTYPE html>";
	$Header .= "<html><head>";
	$Header .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	$Header .= "<title>" . $Domain->Title . "</title>";
	$Header .= "<meta name=\"description\" content=\"" . $Domain->Descrip . "\" />";
	$Header .= "<meta name=\"keywords\" content=\"" . $Domain->Keywords . "\" />";
	$Header .= "<meta name=\"Distribution\" content=\"Global\" />";
	$Header .= "<meta name=\"Revisit-After\" content=\"14 Days\" />";
	// $Header .= "<meta name=\"Robots\" content=\"All\" />";//File does not exist creates error
	$Header .= "<meta name=\"msvalidate-01\" content=\"C6BA74AC5E1EF405A64466E0C1253252\" />";
	$Header .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\">";
	$Header .= "<style>{$Domain->Styles}</style>";
	$Header .= "<script src=\"//code.jquery.com/jquery-1.11.0.min.js\"></script>";
	$Header .= "<script src=\"//code.jquery.com/jquery-migrate-1.2.1.min.js\"></script>";
	$Header .= ($Geo == "GeoOn") ? "<script src=\"javascripts/geolocation.js\"></script>" : '';
	$Header .= ($Geo == "GeoPlace") ? "<script src=\"javascripts/geoplace.js\"></script>" : '';
	//$Header .= ($Geo == "GeoPlace") ? "<script src=\"http://maps.google.com/maps/api/js?sensor=false\"></script>" : '';
	$Header .= "";//<link rel=\"icon\" href=\"images/icon.jpg\">";
	$Header .= "</head>";
	$Header .= "<body><CENTER>";
	if (isset ( $_SESSION ['Id'] )) {
		if (($Domain->Id == $_SESSION ['Id']) || ($_SESSION ['Id'] == '1') || ($Domain->Id == '9') || ($_SESSION ['Admin'] == '1')) {
			$Value = "window.location.href='admin.php'";
			$Valuecss = "window.location.href='css.php'";
			$ValueLogs = "window.location.href='logs.php'";
			$Header .= "<div style=\"margin-top:20px;\">";
			$Header .= "<input type=\"button\" value=\"Css\" onClick=\"{$Valuecss}\"> ";
			
			$Header .= "<input type=\"button\" value=\"Admin {$_SESSION ['Domain']}\" onClick=\"{$Value}\"> ";
			
			$Header .= "<input type=\"button\" value=\"Logs\" onClick=\"{$ValueLogs}\"> ";
			$Header .= "</div>";
		}
	}
	
	return $Header;
}
function reportsHeader($stylesheet = "list.css", $RTitle = "List", $RPageDescrip = "Gas4Rides Free Gas Cheap Exact Rides", $RKeywords = "GasRides, CarPool, RideShare") {
	$Header = "<!DOCTYPE html lang=\"en\">";
	$Header .= "<html><head>";
	$Header .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	$Header .= "<title>" . $RTitle . "</title>";
	$Header .= "<meta name=\"description\" content=\"" . $RPageDescrip . "\" />";
	$Header .= "<meta name=\"keywords\" content=\"" . $RKeywords . "/>";
	$Header .= "<meta name=\"Distribution\" content=\"Global\" />";
	$Header .= "<meta name=\"Revisit-After\" content=\"14 Days\" />";
	// $Header .= "<meta name=\"Robots\" content=\"All\" />";File does not exist creates error
	$Header .= "<link rel=\"stylesheet\" href=\"stylesheets/main.css\" />";
	$Header .= "<link rel=\"stylesheet\" href=\"stylesheets/personinput.css\"  />";
	
	if (ENV == "LOCAL") {
		$Header .= "<script src=\"http://code.jquery.com/jquery-1.9.1.min.js\"></script>";
		// $Header .= "<script src=\"http://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.1/jquery.validate.min.js\" ></script>";
	} else {
		$Header .= "<script src=\"http://code.jquery.com/jquery-1.9.1.min.js\"></script>";
		// $Header .= "<script src=\"http://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.1/jquery.validate.min.js\" ></script>";
	}
	$Header .= "<link rel=\"icon\" href=\"images/icon.jpg\">";
	$Header .= "</head>";
	$Header .= "<body lang=EN-US vLink=purple link=blue ><CENTER>";
	return $Header;
}
function reportsHeaderbootstrap($stylesheet = "list.css", $RTitle = "List", $RPageDescrip = "Gas4Rides Free Gas Cheap Exact Rides", $RKeywords = "GasRides, CarPool, RideShare") {
	$Header = "<!DOCTYPE html lang=\"en\">";
	$Header .= "<html><head>";
	$Header .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	$Header .= "<title>" . $RTitle . "</title>";
	$Header .= "<meta name=\"description\" content=\"" . $RPageDescrip . "\" />";
	$Header .= "<meta name=\"keywords\" content=\"" . $RKeywords . "/>";
	$Header .= "<meta name=\"Distribution\" content=\"Global\" />";
	$Header .= "<meta name=\"Revisit-After\" content=\"14 Days\" />";
	// $Header .= "<meta name=\"Robots\" content=\"All\" />";File does not exist creates error
	$Header .= "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css\">";
	//$Header .= "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css\">";
	$Header .= "<script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js\"></script>";

	if (ENV == "LOCAL") {
		$Header .= "<script src=\"http://code.jquery.com/jquery-1.9.1.min.js\"></script>";
		// $Header .= "<script src=\"http://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.1/jquery.validate.min.js\" ></script>";
	} else {
		$Header .= "<script src=\"http://code.jquery.com/jquery-1.9.1.min.js\"></script>";
		// $Header .= "<script src=\"http://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.1/jquery.validate.min.js\" ></script>";
	}
	$Header .= "<link rel=\"icon\" href=\"images/icon.jpg\">";
	$Header .= "</head>";
	$Header .= "<body lang=EN-US vLink=purple link=blue ><CENTER>";
	return $Header;
}
function reportsTitleBar($Title = "Ride Share Heaven") {
	$StylePop = StylePop ( $_SERVER ['PHP_SELF'] );
	$UserName = displayUserName ();
	$TitleBar = "<div id=\"fullheader\">";
	$TitleBar .= "<div id=\"tablelist\"><p>{$StylePop}</br>{$UserName}</p></div>";
	$TitleBar .= "<div id=\"tabletitle\"><p>{$Title}</p></div>";
	$TitleBar .= "<div id=\"tablehome\"><p><a href=\"lists.php\">Home</a></p></div></div>";
	return $TitleBar;
}
function titleBar($Title = "Ride Share Heaven") {
	$StylePop = StylePop ( $_SERVER ['PHP_SELF'] );
	$UserName = displayUserName ();
	$TitleBar = "<div id=\"fullheader\">";
	$TitleBar .= "<div id=\"tablelist\"><p>{$StylePop}</br>{$UserName}</p></div>";
	$TitleBar .= "<div id=\"tabletitle\"><p>{$Title}</p></div>";
	$TitleBar .= "<div id=\"tablehome\"><p><a href=\"lists.php\">Home</a></p></div></div>";
	return $TitleBar;
}
function displayUserName() {
	if (isset ( $_SESSION ['Name'] )) {
		return $_SESSION ['Name'];
	} else {
		return 'Please Log In';
	}
}

?>