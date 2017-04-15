<?php
require_once (LIB_PATH . DS . 'database.php');
class Photo extends DatabaseObject {
	protected static $table_name = "Photos";
	protected static $db_fields = array (
			'Id',
			'DFC',
			'DLC',
			'User',
			'Category',
			'Name',
			'Field',
			'Square',
			'Locus',
			'Basket',
			'Object',
			'Description',
			'FolderName',
			'FileName',
			'Comments',
			'CreateDate',
			'Thumb'
	);
	public $Id;
	public $DFC;
	public $DLC;
	public $User;
	public $Category;
	public $Name;
	public $Field;
	public $Square; 
	public $Locus; 
	public $Basket;
	public $Object;
	public $Description;
	public $FolderName;
	public $FileName;
	public $Comments;
	public $CreateDate;
	public $Type;
	public $VarsToVal = array ();
	public $ValErrorsArray = array ();
	
	// **************
	// Common Database Methods
	public function save() {
		$recordarray = ( array ) $this;
		if ((isset ( $this->Id )) && (strlen ( $this->Id ) > 0)) {
			return $this->update ();
		} else {
			return $this->create ();
		}
	}
	public static function validatemanual($VarsToVal) {
		$FormInputErrors = array ();
// 		if (strlen ( $VarsToVal ['Name'] ) < 4) {
// 			$FormInputErrors ['Name'] = 'Name Must be at least four letters long';
// 		}
		
// 		if (strlen ( $VarsToVal ['City'] ) < 2) {
// 			$FormInputErrors ['City'] = 'City Must be at least 2 letters long';
// 		}
// 		if (strlen ( $VarsToVal ['State'] ) < 2) {
// 			$FormInputErrors ['State'] = 'State Must be at least 2 letters long';
// 		}
		return $FormInputErrors;
	}
	public static function validatenamethis($VarsToVal) {
		$FormInputErrors = array ();
// 		if (strlen ( $VarsToVal ['Name'] ) < 4) {
// 			$FormInputErrors ['Name'] = 'Name Must be at least four letters long';
// 		}
		
// 		if (! (preg_match ( '/^(\-?\d+(\.\d+)?)/', $VarsToVal ['Lat'] ))) {
// 			$FormInputErrors ['Lat'] = 'Share Location - Wait for Geodata.';
// 		}
		
// 		if (is_numeric ( $VarsToVal ['Lng'] )) {
// 			// $FormInputErrors['Lng'] = 'Longitude Error';
// 		}
		
		return $FormInputErrors;
	}
}

?>