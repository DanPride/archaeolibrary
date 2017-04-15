<?php
if (ENV == 'LOCAL') {
	defined ( 'DB_SERVER' ) ? null : define ( "DB_SERVER", "localhost" );
	defined ( 'DB_USER' ) ? null : define ( "DB_USER", "root" );
	defined ( 'DB_PASS' ) ? null : define ( "DB_PASS", "root" );
	defined ( 'DB_NAME' ) ? null : define ( "DB_NAME", "Gezer" );
} elseif (ENV == 'DADDY') {
	defined ( 'DB_SERVER' ) ? null : define ( "DB_SERVER", "gezer.db.3216758.hostedresource.com" );
	defined ( 'DB_USER' ) ? null : define ( "DB_USER", "gezer" );
	defined ( 'DB_PASS' ) ? null : define ( "DB_PASS", "Solomon3!" );
	defined ( 'DB_NAME' ) ? null : define ( "DB_NAME", "gezer" );
} elseif (ENV == 'BETA') {
		defined ( 'DB_SERVER' ) ? null : define ( "DB_SERVER", "gezer.db.3216758.hostedresource.com" );
		defined ( 'DB_USER' ) ? null : define ( "DB_USER", "gezer" );
		defined ( 'DB_PASS' ) ? null : define ( "DB_PASS", "Solomon3!" );
		defined ( 'DB_NAME' ) ? null : define ( "DB_NAME", "gezer" );
	}
?>