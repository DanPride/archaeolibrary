<?php
// THIS IS THE SETUP FILE FOR AN ARCHAEOLIBRARY DATABASE
//ENTER THE PROPER VALUES FOR YOUR EXCAVATION AND RESAVE
//TO THE INCLUDES FOLDER IN THE ROOT FOLDER

//ENTRIES UP TO TEN CHARACTERS LONG ARE ALLOWED FOR FIELD,SQUARE,LOCUS, AND BUCKET
//THEY CAN BE TEXT OR NUMERIC, LONG VALUES MAY REQUIRE BOTHERSOME RESIZING OF LIST COLUMNS FOR USERS
//THE RECOMMENDED LENGTHS ARE AS FOLLOWS, 
//FIELD - 1 CAPITOL ALPHABETIC
//SQUARE - 3 ONE ALPHA AND TWO NUMBERIC
//LOCUS - FIVE NUMERIC
//BUCKET - FIVE NUMERIC

//define("DB_SERVER", "localhost"); //Enter the Database Full Path Here
define("DB_SERVER", "gezer.db.3216758.hostedresource.com"); //Enter the Database Full Path Here
define("DB_NAME", "gezer"); //Enter the Database Name Here
define("DB_USER", "gezer"); //Enter the Database User Name Here
define("DB_PASS", "Solomon39"); //Enter the Database Password Here
define("DIG_CODE", "GZ"); //Enter the Two Letter Code Assigned by ArchaeoLibrary for the Excavation
define("DIG_NAME", "Tel Gezer"); //Enter the Name of the Site as you want it to appear in the headers
define("DIG_DIRECTOR", "Director"); //Enter the Directors name, Last,First
define("DIG_DIRECTOR_PASSWORD", "masflow"); //Enter the password for the Director
define("VIEW_ONLY_USER", "View_Only"); //Do Not Change, to eliminate public access make this inactive in the Users screen
define("DIG_WEBSITE", "root"); //Enter the Website for the Site if one exists
define("DIG_EMAIL", "root"); //Enter the primary contact Email for the Site

define("FIELD_MINLENGTH", 1); //Enter the Minimum Number of Characters for Any Field
define("FIELD_MAXLENGTH", 1); //Enter the Maximum Number of Characters for Any Field

define("SQUARE_MINLENGTH", 3); //Enter the Minimum Number of Characters for Any Square
define("SQUARE_MAXLENGTH", 3); //Enter the Maximum Number of Characters for Any Square
define("SQUARE_NAME_IN_ID", 1);//If the Square is Max and Min 3, the Square Name will be in the Id ( "GZAY10" ), if not the ID will be used i.e. "GZA001"

define("LOCUS_MINLENGTH", 5); //Enter the Minimum Number of Characters for Any Locus
define("LOCUS_MAXLENGTH", 5); //Enter the Maximum Number of Characters for Any Locus

define("BUCKET_MINLENGTH", 5); //Enter the Minimum Number of Characters for Any Bucket
define("BUCKET_MAXLENGTH", 5); //Enter the Maximum Number of Characters for Any Bucket

define("FIELD_ISNUMBER", 0); // Enter 1 if Field is a pure number otherwise enter 0
define("FIELD_MINVALUE", 0); //Enter the Minimum Number a field can be if it is a number only otherwise 0
define("FIELD_MAXVALUE", 0); //Enter the Maximum Number a field can be if it is a number only otherwise 0

define("SQUARE_ISNUMBER", false); //Enter 1 if Square is a pure number otherwise false
define("SQUARE_MINVALUE", 0); //Enter the Minimum Number a Square can be if it is a number only otherwise 0
define("SQUARE_MAXVALUE", 0); //Enter the Maximum Number a Square can be if it is a number only otherwise 0

define("LOCUS_ISNUMBER", true); //Enter 1 if Locus is a pure number otherwise false
define("LOCUS_MINVALUE", 10000); //Enter the Minimum Number a Locus can be if it is a number only otherwise blank
define("LOCUS_MAXVALUE", 999999); //Enter the Maximum Number a Locus can be if it is a number only otherwise blank

define("BUCKET_ISNUMBER", true); //TEnter 1 if Bucket is a pure number otherwise false
define("BUCKET_MINVALUE", 10000); //Enter the Minimum Number a Bucket can be if it is a number only otherwise blank
define("BUCKET_MAXVALUE", 99999); //Enter the Maximum Number a Bucket can be if it is a number only otherwise blank


?>