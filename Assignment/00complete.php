<?php ob_start();
		$thePath = strlen(dirname(__FILE__)); //length of path
		$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
		$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
		echo $theRoot . "Connection.php";
		require_once($theRoot . "Connection.php");
		require_once($theRoot . "functions.inc.php");
		require_once($theRoot . "/functions.php");
session_start();
//confirm_logged_in("Home");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML><HEAD>
<TITLE>Tel Gezer Excavations</TITLE>
<META http-equiv="content-Type" content="text/html; charset=iso-8859-1">
<META name="description" content="" >
<META name="keywords" content="">
<META name="abstract" content="" >
<META name="Distribution" content="Global" >
<META name="Revisit-After" content="14 Days" >
<META name="Robots" content="All" >
<STYLE>
body {
	background-color:#999966;
	color:white;
	margin:auto;
	text-align:center;
}

h1, h2 {text-align: center; margin:0;}
h1{
	margin-top:30px;
}
a{
	color: white;
	text-decoration:none;
	padding:0.4em 2em;
	display: block;
}
a:hover {
	background-color:white;
	color:#716744}

ul {
	list-style-type:none;
	width : 780px;
	margin : 0;
	padding : 0;
	margin-left:auto;
	margin-right:auto;
	text-align:center;
}
li {
border: solid white 0.5px;
border-top: solid #887766;
}

ul.entry{
	width:680px;	
}
ul.entry li {
	text-align: center;
	float: left;
	width: 150px;
	display: block;

	background-color:#716744;
	margin : 0.5em  0.5em;

}
ul.listbuttons {
	margin-left:auto;
	margin-right:auto;
	text-align:center;
	width:800px;
}
ul.listbuttons li{
	text-align: center;
	float: left;
	width: 170px;
	display: block;
	padding: 0.3em;
	background-color:#716744;
	margin : 0.5em;
}

ul.mainButton li {
	text-align: center;
	width: 200px;
	background-color:#716744;
	margin : auto;
	margin-top:50px;
		margin-bottom:20px;
	
}
ul.report {
		float: left;
		width:230px;
	
}
ul.report li{
	text-align: center;
	width: 200px;
	padding: 0.3em;
	background-color:#716744;
	margin-top : 0.5em;
	margin-left:auto;
	margin-right:auto;

}
ul.reporttop {
	width:340px;
	margin-top:20px;
}
ul.reporttop li{
	text-align: center;
	float: left;
	width: 150px;
	display: block;
	background-color:#716744;
	margin : 0.5em  0.5em;

}
div.clear{
	clear:both;
}

div.report {
	width:230px;
	float:left;
}

div.reportcenter {
	width:700px;
	margin-left:auto;
	margin-right:auto;
	text-align:center;
	margin-top:30px;
}
</STYLE>
</HEAD>
<body lang=EN-US vLink=purple link=blue bgColor=white>
<h1>Tel Gezer Process</h1>
<br><br>
<p align=center><a href="01AssignLocaName.php">Assign Loca Names</a> 
<a href="02AssignPotteryName.php">Assign Pottery Names</a> 
<a href="03AssignObjectName.php">Assign Object Names</a> </p>
<a href="04AssignPhotoName.php">Assign Photo Names</a> </p>

<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-1223547-1";
urchinTracker();
</script>
</BODY></HTML>
<?php ob_end_flush(); ?>
