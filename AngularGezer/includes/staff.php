<?php
require_once (LIB_PATH . DS . 'database.php');
class Staff extends DatabaseObject {
	protected static $table_name = "Staff";
	protected static $db_fields = array (
			'Id',
			'DFC',
			'DLC',
			'User',
			'Logons',
			'Status',
			'Position',
			'Name',
			'Password',
			'Addr1',
			'Addr2',
			'City',
			'State',
			'Zip',
			'Tel',
			'Cell',
			'Email',
			'Affiliation',
			'PermsAdmin', 
			'PermsMod',
			'PermsDelete',
			'PermsAdd',
	);
	public $Id;
	public $DFC;
	public $DLC;
	public $User;
	public $Logons;
	public $Status;
	public $Position;
	public $Name; 
	public $Password; 
	public $Addr1;
	public $Addr2;
	public $City;
	public $State;
	public $Zip;
	public $Tel;
	public $Cell;
	public $Email;
	public $Affiliation;
	public $PermsAdmin;
	public $PermsMod;
	public $PermsDelete;
	public $PermsAdd;
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