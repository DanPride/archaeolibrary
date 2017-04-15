<?php
require_once(LIB_PATH.DS.'database.php');
class DatabaseObject {
	public static $tablelist = array('Fields','GPS','KnownIPs', 'Loca','Locus','Logs','MC_Class','Objects','Pages','Parts','Periods','Photos','Pottery','Squares','Staff','StaffAssigns','Strata','Vessels');
	public static function db_fields(){
		return static::$db_fields;
	}
	public static function count_all() {
		global $database;
		$sql = "SELECT COUNT(*) FROM ".static::$table_name;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
	public static function delete_all_Pid($Pid) {
		$result_array = static::delete_by_sql("DELETE FROM ".static::$table_name." WHERE `Pid`='{$Pid}' ");
		return $result_array;
	}
	public static function find_all() {
		return static::find_by_sql("SELECT * FROM ".static::$table_name);
	}
	public static function find_by_id($id) {
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE `Id`='{$id}' LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	public static function delete_by_id($id) {
		$result_array = static::delete_by_sql("DELETE FROM ".static::$table_name." WHERE `Id`='{$id}' LIMIT 1");
		return $result_array;
	}
	public static function count_by_id($Id) {
		global $database;
		$sql = "SELECT COUNT(*) FROM ".static::$table_name." WHERE `Id`='{$Id}' LIMIT 1";
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
	public static function count_by_DFC($DFC, $Did="0") {
		global $database;
		if($Did=="0"){
			$sql = "SELECT COUNT(*) FROM ".static::$table_name." WHERE DATE(`DFC`) ='{$DFC}' LIMIT 1";
		}else {
			$sql = "SELECT COUNT(*) FROM ".static::$table_name." WHERE DATE(`DFC`)='{$DFC}' AND `Did` ='{$Did}' LIMIT 1";
		}
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
	public static function count_by_DLC($DLC, $Did="0") {
		global $database;
		if($Did=="0"){
			$sql = "SELECT COUNT(*) FROM ".static::$table_name." WHERE DATE(`DFC`)='{$DLC}' LIMIT 1";
		}else {
			$sql = "SELECT COUNT(*) FROM ".static::$table_name." WHERE DATE(`DFC`)='{$DLC}' AND `Did` ='{$Did}' LIMIT 1";
		}
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
	public static function count_by_User($User) {
		global $database;
		$sql = "SELECT COUNT(*) FROM ".static::$table_name." WHERE `User`='{$User}' LIMIT 1";
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
	public static function count_by_Pid($Pid) {
		global $database;
		$sql = "SELECT COUNT(*) FROM ".static::$table_name." WHERE `Pid`='{$Pid}' LIMIT 1";
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
// 	public static function find_by_codes($A, $B) {
// 		$Ba = date("Y-m-d H:i:s",$B);
// 		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE Id='{$id}' AND DFC='{$Ba}' LIMIT 1");
// 		return !empty($result_array) ? array_shift($result_array) : false;
// 	}
	public static function find_by_sql($sql="") {
		global $database;
		$result_set = $database->query($sql);
		$object_array = array();
		while ($row = $database->fetch_array($result_set)) {
			$object_array[] = static::instantiate($row);
		}
		return $object_array;
	}
	
	
	public static function record_exists_by_sql($sql="") {
		$result_array = static::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function delete_by_sql($sql="") {
		global $database;
		$result_set = $database->query($sql);
		return $result_set;
	}
	public static function get_record_by_codes($A, $B){
		global $database;
		$CodeDFC = date("Y-m-d H:i:s",$B);
		$sql = "SELECT * FROM ".static::$table_name." WHERE `Id`='{$A}' AND `DFC`='{$CodeDFC}' LIMIT 1";
		$result_set = $database->query($sql);
		$object_array = array();
		while ($row = $database->fetch_array($result_set)) {
			$object_array[] = static::instantiate($row);
		}
		$theRecord =  !empty($object_array) ? array_shift($object_array) : false;
		return $theRecord;
	}
	public function get_record_codes(){
		$A = $this->Id;
		$B = strtotime($this->DFC);
		return "A={$A}&B={$B}";
	}
	public function get_B(){
		$B = strtotime($this->DFC);
		return $B;
	}

	public static function find_Ids_by_sql($sql="") {
		global $database;
		$result_set = $database->query($sql);
		$IdArray = array();
		while ($row = $database->fetch_array($result_set)) {
			$IdArray[] = $row['Id'];
		}
		return $IdArray;
	}
	public static function search_box(){
		$SelectDiv = "<div id=\"theformbox\">";
		$TablePop = "Table: " . TablePopup();
		$SearchPopup = static::AllFieldsSelect("Search");
		$ComparisonPopup = MySqlComparisons();
		$InputT =  InputQueryText();
		$SortPopup = static::AllFieldsSelect("Sort");
		$AscDescPop = AscDesc();
		$AndOrPop = AndOr();
		$SubmitButton = submit();
		return  $SelectDiv . "<br>"  . $TablePop . " " . $SearchPopup . " " . $ComparisonPopup . " " . $InputT . " " . $SortPopup . " " . $AscDescPop  . " " .  $AndOrPop . " " . $SubmitButton . "</div></form>";

	}
	public static function form_by_Codes($Id){
		$row = static::find_by_Id($Id);
		$SelectDiv = "<div id=\"theformbox\">";
		$TablePop = "Table: " . TablePopup();
		$SearchPopup = static::AllFieldsSelect("Search");
		$ComparisonPopup = MySqlComparisons();
		$InputT =  InputQueryText();
		$SortPopup = static::AllFieldsSelect("Sort");
		$AscDescPop = AscDesc();
		$AndOrPop = AndOr();
		$SubmitButton = submit();
		$theForm =  $SelectDiv . "<br>"  . $TablePop . " " . $SearchPopup . " " . $ComparisonPopup . " " . $InputT . " " . $SortPopup . " " . $AscDescPop  . " " .  $AndOrPop . " " . $SubmitButton . "</div></form>";
		$theForm .= "<form Id=\"forminputs\" action=\"$_SERVER[PHP_SELF]\" method=\"POST\">";
		$theForm .=  static::instantiateTableInput($row);
		$theForm .=  "<br><div class=\"field\"/><div id=\"formsubmit\"><input type=\"submit\" name=\"submit\" id=\"submito\" value=\"submit\" /></div></div>";
		$theForm .="</form>";
		return $theForm;
	}
	
	public static function form_by_Id($Id){
		$row = static::find_by_Id($Id);
		$SelectDiv = "<div id=\"theformbox\">";
		$TablePop = "Table: " . TablePopup();
		$SearchPopup = static::AllFieldsSelect("Search");
		$ComparisonPopup = MySqlComparisons();
		$InputT =  InputQueryText();
		$SortPopup = static::AllFieldsSelect("Sort");
		$AscDescPop = AscDesc();
		$AndOrPop = AndOr();
		$SubmitButton = submit();
		$theForm =  $SelectDiv . "<br>"  . $TablePop . " " . $SearchPopup . " " . $ComparisonPopup . " " . $InputT . " " . $SortPopup . " " . $AscDescPop  . " " .  $AndOrPop . " " . $SubmitButton . "</div></form>";
		$theForm .= "<form Id=\"forminputs\" action=\"$_SERVER[PHP_SELF]\" method=\"POST\">";
		$theForm .=  static::instantiateTableInput($row);
		$theForm .=  "<br><div class=\"field\"/><div id=\"formsubmit\"><input type=\"submit\" name=\"submit\" id=\"submito\" value=\"submit\" /></div></div>";
		$theForm .="</form>";
		return $theForm;
	}
	public static function table_by_sql($sql="") {
		global $database;
		$result_set = $database->query($sql);
		$rowString = "";
		$HeadersRun = "";
		$SelectDiv = "<div id=\"theformbox\">";
		$TablePop = "Table: " . TablePopup();
		$SearchPopup = static::AllFieldsSelect("Search");
		$ComparisonPopup = MySqlComparisons();
		$InputT =  InputQueryText();
		$SortPopup = static::AllFieldsSelect("Sort");
		$AscDescPop = AscDesc();
		$AndOrPop = AndOr();
		$SubmitButton = submit();
		$HeadersRun = "<table id='dataTable'><tr class=\"tablerow\">" . static::instantiateTableHeader() . "</tr>";
		$rowString =  $SelectDiv . "<br>"  . $TablePop . " " . $SearchPopup . " " . $ComparisonPopup . " " . $InputT . " " . $SortPopup . " " . $AscDescPop  . " " .  $AndOrPop . " " . $SubmitButton . "</div></form>" . $HeadersRun;
		while ($row = $database->fetch_array($result_set)) {
			// 			if(	$HeadersRun == ""){
			// 		}
			$Object = static::instantiate($row);
			$Codes = $Object->get_record_codes();
			$rowString .= "<tr>" . static::instantiateTable($row,$Codes) . "</tr>";
		}
		return $rowString . "</table>";
	}
	public static function instantiate($record) { // Could check that $record exists and is an array
		$className = get_called_class();
		$object = new $className;
		foreach($record as $attribute=>$value){
			if($object->has_attribute($attribute)) {
				$object->$attribute = $value;
			}
		}
		return $object;
	}
	private static function instantiateTableHeader() { // Could check that $record exists and is an array
		$className = get_called_class();
		$object = new $className;
		$theField = "";
		$record = static::$db_fields;
		foreach($record as $attribute){
			if($object->has_attribute($attribute)) {
				$theField .= "<th class=\"{$attribute}\">" . $attribute . "</th>";
			}
		}
		$theSubstring = $theField;
		return $theSubstring;
	}


	public static function instantiateTable($record,$Codes) { // Could check that $record exists and is an array
		$className = get_called_class();
		$object = new $className;
		$theResult = "";
		foreach($record as $attribute=>$value){
			if($object->has_attribute($attribute)) {
				$IdValue = "<a href=\"inputs.php?TablePop={$_SESSION['TablePop']}{$Codes}\">{$value}</a>";
				$value = ($attribute == 'Id') ? $IdValue : $value;
				$theResult .= "<td class='" . $attribute . "'>" . $value . "</td>" ;
					
			}
		}
		return $theResult;
	}
	public static function instantiateTableInput($record) { // Could check that $record exists and is an array
		$className = get_called_class();
		$object = new $className;
		$theField = "";
		$theResult ="";
		foreach($record as $attribute=>$value){
			if($object->has_attribute($attribute)) {
			
				//$IdValue = "<a href=\"Inputs.php?table={$_SESSION['TablePop']}&Id={$value}\">{$value}</a>";
				//$value = ($attribute == 'Id') ? $IdValue : $value;
				//$theResult .= "<td class='" . $attribute . "'>" . $value . "</td>" ;
				//$theResult .= "<div class=\"field\"><div class=\"label\" id=\"{$attribute}\">" .$attribute . "</div><div class=\"input\" id=\"{$attribute}\"><input name=\"{$attribute}\" type=\"text\" size=\"25\" value=\"{$value}\"></div></div>";
				$theResult .= "<div class=\"field\"><label for=\"{$attribute}\" class=\"label\">{$attribute}</label><input name=\"{$attribute}\" class=\"input\"  id=\"{$attribute}\" type=\"text\" size=\"25\" value=\"{$value}\"></div>";

			}
		}
		return $theResult;
	}
	
	public static function EditPageVars($record,$Fields,$Button) //record is table object
	{ // Could check that $record exists and is an array
		$theResult= "";
		$className = get_called_class();
		$object = new $className;
		foreach($record as $attribute=>$value)
		{
			if($object->has_attribute($attribute)) 
			{
				if(in_array($attribute,$Fields))
				{
					$theResult .= "<li >{$attribute}<br><textarea rows=\"5\" cols=\"30\" input name=\"{$attribute}\" class=\"input\"  size=\"25\" id=\"{$attribute}\" type=\"textarea\" >{$value}</textarea></li>";
				}else{
					$theResult .= "<li style=\"display:none;\"><textarea rows=\"0\" cols=\"0\" input name=\"{$attribute}\" class=\"input\"  size=\"0\" id=\"{$attribute}\" type=\"textarea\" >{$value}</textarea></li>";	
				}
			}
		}	
		$theResult .="<li style=\"height: 50px;\"><button type=\"submit\" name=\"{$Button}\" id=\"{$Button}\" value=\"{$Button}\">{$Button}</button></li>";
		return $theResult;
	}
	
	public static function instantiateLangTables($record) //record is table object
	{ // Could check that $record exists and is an array
	$FieldsUsedArray = array ($FieldsUsed);
	$className = get_called_class();
	$object = new $className;
	$theResult = "<form><table>";
	foreach($record as $attribute=>$value)
	{
		if($object->has_attribute($attribute))
		{
			if(in_array($attribute,$FieldsUsed))
			{
				$theResult .= "<tr><td>{$attribute}<br><textarea rows=\"5\" cols=\"30\" input name=\"{$attribute}\" class=\"input\"  size=\"25\" id=\"{$attribute}\" type=\"textarea\" >{$value}</textarea></td></tr>";
			}else{
				$theResult .= "<input name=\"{$attribute}\" class=\"hidden\" id=\"{$attribute}\" type=\"text\" size=\"1\" value=\"{$value}\"></div>";
			}
		}
	}
	$theResult .= "</table></form>";
	return $theResult;
	}

	private static function AllFieldsSelect( $action="Sort") { // Could check that $record exists and is an array
		$className = get_called_class();
		$object = new $className;
		$thePop = "&nbsp; {$action}: <select name=\"{$action}Pop\">";
		$record = static::$db_fields;
		foreach($record as $attribute){
			if($object->has_attribute($attribute)) {
				$thePop .= "<option ";
			//	$thevar = "{$action}Pop";
			//	$thePop .= ($_SESSION['{$thevar}'] == $attribute) ? selected:'';
				$thePop .='>';
				$thePop .= "{$attribute}</option>";
			}
		}
		$theSubstring = $thePop . "</select> &nbsp; ";
		return $theSubstring;
	}

	//**************************************************End Static
	public function GetB(){
		$theDate = $this->DFC;
		return strtotime($theDate);
	}

	public function display_meta(){
		$Display = "<div id=\"meta\"><div class=\"metalabel\">Id:</div><div class=\"metadata\">" . $this->Id . "</div>";
		$Display .= "<div class=\"metalabel\">DFC:</div><div class=\"metadata\">" .$this->DFC . "</div>";
		$Display .= "<div class=\"metalabel\">DLC:</div><div class=\"metadata\">" . $this->DLC  . "</div>";
		$Display .= "<div class=\"metalabel\">User:</div><div class=\"metadata\">" . $this->User . "</div></div>";
		return  $Display;
	}

	private function has_attribute($attribute) {
		// We don't care about the value, we just want to know if the key exists  Will return true or false
		return array_key_exists($attribute, $this->attributes());
	}

	protected function attributes() {
		// return an array of attribute names and their values
		$attributes = array();
		foreach(static::$db_fields as $field) {
			if(property_exists($this, $field)) {
				$attributes[$field] = $this->$field;
			}
		}
		return $attributes;
	}

	protected function sanitized_attributes() { // sanitize the values before submitting
		global $database;
		$clean_attributes = array();
		foreach($this->attributes() as $key => $value){
			if($key=="DFC"){
				if((strlen($value)==0) || ($value == '0000-00-00 00:00:00')){
					$value = date("Y-m-d H:i:s");
				}
			}
			if($key=="DLC"){
				$value = date("Y-m-d H:i:s");
			}
			if($key=="User"){
				$value = (Isset($_SESSION['UserName']))?$_SESSION['UserName']:'System';
			}
			$clean_attributes[$key] = $database->escape_value($value);
		}
		return $clean_attributes;
	}
	protected function archive_attributes() { // sanitize the values before submitting
		global $database;
		$clean_attributes = array();
		foreach($this->attributes() as $key => $value){
			$clean_attributes[$key] = $database->escape_value($value);
		}
		return $clean_attributes;
	}

	public function save() {
		// A new record won't have an id yet.
		return ($this->Id>0) ? $this->update() : $this->create();
	}

	public function create() {
		global $database;
		$attributes = $this->sanitized_attributes();
		$sql = "INSERT INTO ".static::$table_name." (";
		$sql .= join(", ", array_keys($attributes));
		$sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
		if($database->query($sql)) {
			$this->Id = $database->insert_id();
			return true;
		} else {
			return false;
		}
	}
	public function archive() {
		global $database;
		$attributes = $this->archive_attributes();
		$sql = "INSERT INTO ".static::$table_name." (";
		$sql .= join(", ", array_keys($attributes));
		$sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
		if($database->query($sql)) {
			$this->Id = $database->insert_id();
			return true;
		} else {
			return false;
		}
	}

	public function update() {
		global $database;
		$attributes = $this->sanitized_attributes();
		$attribute_pairs = array();
		foreach($attributes as $key => $value) {
			$attribute_pairs[] = "{$key}='{$value}'";
		}
		$sql = "UPDATE ".static::$table_name." SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE Id=". $database->escape_value($this->Id);
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}

	public function delete() {
		global $database;
		$sql = "DELETE FROM ".static::$table_name;
		$sql .= " WHERE Id=". $database->escape_value($this->Id);
		$sql .= " LIMIT 1";
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;

		// NB: After deleting, the instance of User still exists, even though the database entry does not.
		// This can be useful, as in:    $user->first_name . " was deleted";
		// but, for example, we can't call $user->update() after calling $user->delete().
	}

}