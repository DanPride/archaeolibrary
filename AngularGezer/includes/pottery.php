<?php
require_once (LIB_PATH . DS . 'database.php');
class Pottery extends DatabaseObject {
	protected static $table_name = "Pottery";
	protected static $db_fields = array (
			'Id',
			'DFC',
			'DLC',
			'User',
			'Sequence',
			'Name',
			'Field',
			'Square',
			'Locus',
			'Basket',
			'Periods',
			'Context',
			'Disposition',
			'CreateDate',
			'Status'
	);
	public $Id;
	public $DFC;
	public $DLC;
	public $User;
	public $Sequence;
	public $Name;
	public $Field;
	public $Square; 
	public $Locus; 
	public $Basket;
	public $Periods;
	public $Context;
	public $Disposition;
	public $CreateDate;
	public $Status;
	public $VarsToVal = array ();

	// Common Database Methods
	public function save() {
		$recordarray = ( array ) $this;
		if ((isset ( $this->Id )) && (strlen ( $this->Id ) > 0)) {
			return $this->update ();
		} else {
			return $this->create ();
		}
	}
	public static function validate($VarsToVal) {
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
}

?>