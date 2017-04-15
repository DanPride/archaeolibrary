<?php
function findPhotos() {
			global $conn, $filter_field, $filter_type;
		$fields = array('Id','DFC','DLC','User','Category','Name','Field','Square','Locus','Basket','Object','Description','FolderName','FileName','Comments','CreateDate');
		$where = "";
		if(@$_REQUEST['photoListNum']=="All"){
				$Limit = "";
		} else{
			$Limit = " Limit " . @$_REQUEST['photoListNum'];	
		}	
		if(@$_REQUEST['photoDefPop']=="Originals"){@$_REQUEST['photoDefPop']="Original";}
		if(@$_REQUEST['photoDefPop']=="All"){
				$PhotoCat = "";
		} else{
				$PhotoCat = " AND " . 'Category'  . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST['photoDefPop'], "text") ;	
		}
		if (@$_REQUEST['filter'] != "") 
		{
				$Switcher = @$_REQUEST['searchDef'];
				switch($Switcher)
				{
					case 'Square':
						$SearchField = 'Square';
					break;
					case 'Locus':
					$SearchField = 'Locus';
					break;
					case 'Basket':
					 	$SearchField = 'Basket';
					break;
					case 'Period':
					 	$SearchField = 'Period';
					break;
					case 'Name':
						$SearchField = 'Name';
					break;
			}
			$where = "WHERE " . $SearchField . " LIKE " . GetSQLValueStringForSelect(@$_REQUEST["filter"], "text") . " AND " . 'Name' . "!=''";
			}
		$order = " ORDER BY " .  'NAME' . ", " . 'CreateDate' ;
		$query_recordset = "SELECT Id,DFC,DLC,User,Category,Name,Field,Square,Locus,Basket,Object,Description,FolderName,FileName,Comments,Thumb,CreateDate  FROM `Photos` $where $PhotoCat $order $Limit";
		$recordset = mysql_query($query_recordset, $conn);
	$toret = array();
	while ($row_recordset = mysql_fetch_assoc($recordset)) {
		$row_recordset["Name"] = substr($row_recordset["Name"],3);
		array_push($toret, $row_recordset);
		}

		$toret = array(
			"data" => $toret, 
			"metadata" => array (
			)
		);

		return $toret;
}
?>