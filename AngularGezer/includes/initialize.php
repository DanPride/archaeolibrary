<?php
ini_set ( 'memory_limit', '256M' );
set_time_limit ( 1000 );
date_default_timezone_set ( 'America/Los_Angeles' );
if (strstr ( __FILE__, 'Sites' )) {
	error_reporting ( E_ALL );
	ini_set ( 'display_errors', '1' );
	defined ( 'ENV' ) ? null : define ( 'ENV', 'LOCAL' );
} elseif (strstr ( __FILE__, 'AngularGezer' )) {
	error_reporting ( E_ALL );
	ini_set ( 'display_errors', '1' );
	defined ( 'ENV' ) ? null : define ( 'ENV', 'DADDY' );
} else {
	error_reporting ( E_ALL );
	ini_set ( 'display_errors', '1' );
	defined ( 'BETA' ) ? null : define ( 'ENV', 'BETA' );
}

switch (ENV) {
	case "LOCAL" :
		defined ( 'SITE_ROOT' ) ? null : define ( 'SITE_ROOT', '/Users/dp/Sites/AngularGezer/' );
		defined ( 'PUBLIC_ROOT' ) ? null : define ( 'PUBLIC_ROOT', '/Users/dp/Sites/AngularGezer/public_html/' );
		break;
	case "DADDY" :
		defined ( 'SITE_ROOT' ) ? null : define ( 'SITE_ROOT', '/home/content/58/3216758/html/_ArchaeoLibrary/AngularGezer' );
		defined ( 'PUBLIC_ROOT' ) ? null : define ( 'PUBLIC_ROOT', '/home/content/58/3216758/html/AngularGezer/public_html' );
		break;
	case "BETA" :
		defined ( 'SITE_ROOT' ) ? null : define ( 'SITE_ROOT', '/home/content/58/3216758/html/_ArchaeoLibrary/a' );
		defined ( 'PUBLIC_ROOT' ) ? null : define ( 'PUBLIC_ROOT', '/home/content/58/3216758/html/a/public_html' );
		break;
}
defined ( 'DS' ) ? null : define ( 'DS', DIRECTORY_SEPARATOR );
defined ( 'LIB_PATH' ) ? null : define ( 'LIB_PATH', SITE_ROOT . DS . 'includes' );
require_once (LIB_PATH . DS . 'config.php');
require_once (LIB_PATH . DS . 'database.php');
require_once (LIB_PATH . DS . 'database_object.php');
mysql_query ( "SET NAMES 'utf8'" );
mysql_query ( "SET CHARACTER SET 'utf8'" );
require_once (LIB_PATH . DS . 'functions.php');
require_once (LIB_PATH . DS . 'field.php');
require_once (LIB_PATH . DS . 'gps.php');
require_once (LIB_PATH . DS . 'knownips.php');
require_once (LIB_PATH . DS . 'loca.php');
require_once (LIB_PATH . DS . 'locus.php');
require_once (LIB_PATH . DS . 'log.php');
require_once (LIB_PATH . DS . 'mc_class.php');
require_once (LIB_PATH . DS . 'object.php');
require_once (LIB_PATH . DS . 'page.php');
require_once (LIB_PATH . DS . 'pagination.php');
require_once (LIB_PATH . DS . 'part.php');
require_once (LIB_PATH . DS . 'period.php');
require_once (LIB_PATH . DS . 'photo.php');
require_once (LIB_PATH . DS . 'pottery.php');
require_once (LIB_PATH . DS . 'square.php');
require_once (LIB_PATH . DS . 'staff.php');
require_once (LIB_PATH . DS . 'staffassign.php');
require_once (LIB_PATH . DS . 'strata.php');
require_once (LIB_PATH . DS . 'vessel.php');
// require_once (LIB_PATH . DS . 'session.php');

// require_once (LIB_PATH . DS . 'session.php');
?>