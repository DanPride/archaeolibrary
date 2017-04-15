<?php
require_once (LIB_PATH . DS . 'database.php');
class Loca extends DatabaseObject {
	protected static $table_name = "Loca";
	protected static $db_fields = array (
			'Id',
			'DFC',
			'DLC',
			'User',
			'Sequence',
			'Type',
			'Name',
			'Field',
			'Square',
			'Locus',
			'Strata',
			'Supervisor',
			'Rationale',
			'Definition',
			'Description',
			'Interpretation',
			'Stratigraphy',
			'Method',
			'Sieved',
			'Quality',
			'QualCom',
			'Length',
			'Width',
			'Remarks',
			'M_Formation',
			'M_Compaction',
			'M_Type',
			'M_Composition',
			'M_Color',
			'M_Inclusions',
			'M_Relationships' 
	);
	public $Id;
	public $DFC;
	public $DLC;
	public $User;
	public $Sequence;
	public $Type;
	public $Name;
	public $Field;
	public $Square; 
	public $Locus; 
	public $Strata;
	public $Supervisor;
	public $Rationale;
	public $Definition;
	public $Description;
	public $Interpretation;
	public $Stratigraphy;
	public $Method;
	public $Sieved;
	public $Quality;
	public $QualCom;
	public $Length;
	public $Width;
	public $Remarks;
	public $M_Formation;
	public $M_Compaction;
	public $M_Type;
	public $M_Composition;
	public $M_Color;
	public $M_Inclusions;
	public $M_Relationships;
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