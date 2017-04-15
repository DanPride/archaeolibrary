// LocaScript
import flash.events.Event;
import flash.events.MouseEvent;

import mx.controls.Alert;
import mx.controls.Menu;
import mx.events.CloseEvent;
import mx.events.MenuEvent;

[Bindable]public var locaId:String;
[Bindable]public var locaDFC:String;
[Bindable]public var locaDLC:String;
[Bindable]public var locaUser:String;
[Bindable]public var locaType:String;
[Bindable]public var locaName:String;
[Bindable]public var locaField:String;
[Bindable]public var locaSquare:String;
[Bindable]public var locaLocus:String;
[Bindable]public var locaRationale:String;
[Bindable]public var locaDefinition:String;
[Bindable]public var locaDescription:String;
[Bindable]public var locaInterpretation:String;
[Bindable]public var locaStratigraphy:String;
[Bindable]public var locaMethodIndex:int;
[Bindable]public var locaSieved:Boolean;
[Bindable]public var locaNotSieved:Boolean;
[Bindable]public var locaQualityIndex:int;
[Bindable]public var locaQualCom:String;
[Bindable]public var locaLength:String;
[Bindable]public var locaWidth:String;
[Bindable]public var locaRemarks:String;
[Bindable]public var locaM_FormationIndex:int;
[Bindable]public var locaM_TypeIndex:int;
[Bindable]public var locaM_CompactionIndex:int;
[Bindable]public var locaM_CompositionIndex:int;
[Bindable]public var locaM_Color:String;
[Bindable]public var locaM_Inclusions:String;
[Bindable]public var locaRelationships:String;
[Bindable]public var locaQualityNum:Number = 3;
[Bindable]public var locaM_InclusionsArray:Array = new Array("Chips", "Pepsi Bottles", "Bedsprings", "Other");
public var locaQualityArray:Array = new Array("", "Clean", "Contam", "Mixed", "Other");
 public var locaM_FormationArray:Array = new Array("","Aeolian", "Carbon_Ash", "Dump", "Debris", "Fill", "Habitation", "Lacustrine", "Organic", "Packing", "Sediment", "Weathering", "Tumble", "Other");
 public var locaMethodArray:Array = new  Array("", "Large Picks", "Small Picks", "Trowel", "Dental Picks", "Other");
 public var locaM_CompactionArray:Array = new Array("", "Very Hard", "Hard", "Soft", "Loose", "Mixed", "Crumbly", "Gravely", "Porous", "Compacted", "Laminated", "Graded", "Other");
 public var locaM_CompositionArray:Array = new Array("", "Course", "Fine", "Porous", "Compacted", "Laminated", "Indurated", "Graded", "Other");
 public var locaM_TypeArray:Array = new Array("", "Clay", "Silt", "Sand Granule", "Pebble", "Cobble", "Boulder", "Ash", "Sand Clay", "Loam", "Gravel", "Bricky", "Rubbly", "Other");

[Bindable]public var locaMethodArr:ArrayCollection = new  ArrayCollection([{label:"", data:1}, {label:"Large Picks", data:2}, {label:"Small Picks", data:3}, {label:"Trowel", data:4}, {label:"Dental Picks", data:5}, {label:"Other", data:6} ]);
//private function closeLocaMethod(event:Event):void{locaMethod = LocaMethodCol.selectedItem.label;}

[Bindable]public var locaFormationArr:ArrayCollection = new ArrayCollection(
	[ {label:"", data:1}, {label:"Aeolian", data:2}, {label:"Carbon_Ash", data:3}, {label:"Dump", data:4}, {label:"Debris", data:5}, {label:"Fill", data:6}, 
		{label:"Habitation", data:7}, {label:"Lacustrine", data:8}, {label:"Organic", data:9}, {label:"Packing", data:10}, {label:"Sediment", data:11}, {label:"Weathering", data:12}, 
		{label:"Tumble", data:13}, {label:"Other", data:14}]);
//private function closeLocaM_Formation(event:Event):void{locaM_Formation = LocaM_FormationCol.selectedItem.label;}

[Bindable]public var locaQualityArr:ArrayCollection = new ArrayCollection( 
	[ {label:"", data:1}, {label:"Clean", data:2}, {label:"Contam", data:3}, {label:"Mixed", data:4}, {label:"Other", data:5}]);
//private function closeLocaQuality(event:Event):void{locaQuality = LocaQualityCol.selectedItem.label; Alert.show(locaQuality);}

[Bindable]public var locaM_CompactionArr:ArrayCollection = new ArrayCollection(
	[ {label:"", data:1}, {label:"Very Hard", data:2}, {label:"Hard", data:3}, {label:"Soft", data:4}, {label:"Loose", data:5}, {label:"Mixed", data:6}, {label:"Crumbly", data:7}, {label:"Gravely", data:8}, {label:"Porous", data:9}, {label:"Compacted", data:10}, {label:"Laminated", data:11}, {label:"Graded", data:12}, {label:"Other", data:13}]);
//private function closeLocaM_Compaction(event:Event):void{locaM_Compaction = LocaM_CompactionCol.selectedItem.label;}

[Bindable]public var locaM_CompositionArr:ArrayCollection = new ArrayCollection(
	[ {label:"", data:1}, {label:"Course", data:2}, {label:"Fine", data:3}, {label:"Porous", data:4}, {label:"Compacted", data:5}, {label:"Laminated", data:6}, {label:"Indurated", data:7}, {label:"Graded", data:8}, {label:"Other", data:9}]);
//private function closeLocaM_Composition(event:Event):void{locaM_Composition = LocaM_CompositionCol.selectedItem.label;}

[Bindable]public var locaM_TypeArr:ArrayCollection = new ArrayCollection(
	[ {label:"", data:1}, {label:"Clay", data:2}, {label:"Silt", data:3}, {label:"Sand Granule", data:4}, {label:"Pebble", data:5}, {label:"Cobble", data:6}, {label:"Boulder", data:7}, {label:"Ash", data:8}, {label:"Sand Clay", data:9}, {label:"Loam", data:10}, {label:"Gravel", data:11}, {label:"Bricky", data:12}, {label:"Rubbly", data:13}, {label:"Other", data:14}]);

[Bindable]public var inclusionsArray:ArrayCollection = new ArrayCollection(
	[ {label:"Select", data:1}, {label:"Clay", data:2}, {label:"Silt", data:3}, {label:"Sand Granule", data:4}, {label:"Pebble", data:5}, {label:"Cobble", data:6}, {label:"Boulder", data:7}, {label:"Ash", data:8}, {label:"Sand Clay", data:9}, {label:"Loam", data:10}, {label:"Gravel", data:11}, {label:"Bricky", data:12}, {label:"Rubbly", data:13}, {label:"Other", data:14}]);

[Bindable]public var inclusionsDataArray:ArrayCollection = new ArrayCollection(
	[ {label:"Clay", data:2}, {label:"Silt", data:3}]);


 
public var selectedLoca:int;
public var vLocaName:String = new String();
[Bindable]
public var locaDataArr:ArrayCollection = new ArrayCollection();
private var locaFields:Object = { 'Id':Number, 'DFC':String, 'DLC':String, 'User':String, 'Type':String, 'Name':String, 'ShortName':String,'Field':String, 'Square':String, 'Locus':String, 
 'Rationale':String, 'Definition':String, 'Description':String, 'Interpretation':String, 'Stratigraphy':String, 'Method':String, 'Sieved':Number, 
 'Quality':String, 'QualCom':String, 'Length':Number, 'Width':Number, 'Remarks':String, 'M_Formation':String, 'M_Type':String, 'M_Compaction':String, 
 'M_Composition':String, 'Relationships':String  };

[Bindable]
public var locaTypeArr:ArrayCollection = new ArrayCollection([ {label:""}, {label:"Burial"},{label:"Feature"},{label:"Sediment"},{label:"Surface"},{label:"Trench"},{label:"Wall"},{label:"Other"}]);

private function allLoca():void
{
	selectedNum = "";
	fillLoca();
}
private function selectLoca():void
{ 	
	 //selectedNum = locaGrid.selectedItem.NameCol;
	 filterTxt.text = locaGrid.selectedItem.NameCol;
	 searchDef.text = "Name"
	 currentState = '';
	 fillBaskets();
	 fillObjects();
	 fillPhotos();
	 CursorManager.removeBusyCursor();
}
private function newLoca(event:Event):void {
	navigateToURL(new URLRequest('../checkin/'), '_blank')
}

private function editLoca(event:Event):void {
	selectedLoca = locaGrid.selectedIndex;
	selectedBasket = 0;
	selectedObject = 0;
	locaId = locaGrid.selectedItem.IdCol;
	var DFCFormat:String = locaGrid.selectedItem.DFCCol;
	locaDFC = DFCFormat.substring(5,7) + "/" + DFCFormat.substring(8,10) + "/" + DFCFormat.substring(2,4);
	var DLCFormat:String = locaGrid.selectedItem.DLCCol;
	locaDLC = DLCFormat.substring(5,7) + "/" + DLCFormat.substring(8,10) + "/" + DLCFormat.substring(2,4);
	locaUser = locaGrid.selectedItem.UserCol;
	locaType = locaGrid.selectedItem.TypeCol;
	locaName = locaGrid.selectedItem.NameCol;
	locaField = locaGrid.selectedItem.FieldCol;
	locaSquare = locaGrid.selectedItem.SquareCol;
	locaLocus = locaGrid.selectedItem.LocusCol;
	locaRationale = locaGrid.selectedItem.RationaleCol;
	locaDefinition = locaGrid.selectedItem.DefinitionCol;
	locaDescription = locaGrid.selectedItem.DescriptionCol;
	locaInterpretation = locaGrid.selectedItem.InterpretationCol;
	locaStratigraphy = locaGrid.selectedItem.StratigraphyCol;
	locaMethodIndex = locaMethodArray.indexOf(locaGrid.selectedItem.MethodCol.toString());
	var locaSievedVar:String = locaGrid.selectedItem.SievedCol;
	if(locaSievedVar == 'Sieved'){
		locaSieved = true;
		locaNotSieved = false;
	}else {
		locaSieved = false;
		locaNotSieved = true;
	} 
	locaQualityIndex = locaQualityArray.indexOf(locaGrid.selectedItem.QualityCol.toString());	
	locaQualCom = locaGrid.selectedItem.QualComCol;
	locaLength = locaGrid.selectedItem.LengthCol;
	locaWidth = locaGrid.selectedItem.WidthCol;
	locaRemarks = locaGrid.selectedItem.RemarksCol;
	locaM_FormationIndex = locaM_FormationArray.indexOf(locaGrid.selectedItem.M_FormationCol.toString());
	locaM_TypeIndex = locaM_TypeArray.indexOf(locaGrid.selectedItem.M_TypeCol.toString());
	locaM_CompactionIndex = locaM_CompactionArray.indexOf(locaGrid.selectedItem.M_CompactionCol.toString());
	locaM_CompositionIndex = locaM_CompositionArray.indexOf(locaGrid.selectedItem.M_CompositionCol.toString());
	locaRelationships = locaGrid.selectedItem.M_RelationshipsCol;
	home.selectedIndex = 3;

	
}
private function cancelLoca():void{
	home.selectedChild = view;
	LocaIdCol.text = parentDocument.locaGrid.selectedItem.IdCol;
//	LocaDFCCol.text = parentDocument.locaGrid.selectedItem.DFCCol;
//	LocaDLCCol.text = parentDocument.locaGrid.selectedItem.DLCCol;
//	LocaUserCol.text = parentDocument.locaGrid.selectedItem.UserCol;
	//LocaCreateDateCol.text = parentDocument.locaGrid.selectedItem.CreateDateCol;
	LocaTypeCol.text = parentDocument.locaGrid.selectedItem.TypeCol;
	LocaNameCol.text = parentDocument.locaGrid.selectedItem.NameCol;
	LocaFieldCol.text = parentDocument.locaGrid.selectedItem.FieldCol;
	LocaSquareCol.text = parentDocument.locaGrid.selectedItem.SquareCol;
	LocaLocusCol.text = parentDocument.locaGrid.selectedItem.LocusCol;
	LocaRationaleCol.text = parentDocument.locaGrid.selectedItem.RationaleCol;
	LocaDefinitionCol.text = parentDocument.locaGrid.selectedItem.DefinitionCol;
	LocaDescriptionCol.text = parentDocument.locaGrid.selectedItem.DescriptionCol;
	LocaInterpretationCol.text = parentDocument.locaGrid.selectedItem.InterpretationCol;
	LocaStratigraphyCol.text = parentDocument.locaGrid.selectedItem.StratigraphyCol;
	//LocaMethodCol.text = parentDocument.locaGrid.selectedItem.MethodCol;
	LocaMethodCol.selectedIndex = locaMethodArray.indexOf(parentDocument.locaGrid.selectedItem.MethodCol.toString());
	var locaSievedVar:String = parentDocument.locaGrid.selectedItem.SievedCol;;
	if(locaSievedVar == 'Sieved'){
		locaSieved = true;
		locaNotSieved = false;
	}else {
		locaSieved = false;
		locaNotSieved = true;
	}
	LocaQualityCol.selectedIndex = locaQualityArray.indexOf(parentDocument.locaGrid.selectedItem.QualityCol.toString());	
	//parentDocument.locaGrid.selectedItem.QualityCol;
	LocaQualComCol.text = parentDocument.locaGrid.selectedItem.QualComCol;
	LocaLengthCol.text = parentDocument.locaGrid.selectedItem.LengthCol;
	LocaWidthCol.text = parentDocument.locaGrid.selectedItem.WidthCol;
	LocaRemarksCol.text = parentDocument.locaGrid.selectedItem.RemarksCol;
	LocaM_FormationCol.selectedIndex = locaM_FormationArray.indexOf(parentDocument.locaGrid.selectedItem.M_FormationCol.toString());	
	//LocaM_FormationCol.text = parentDocument.locaGrid.selectedItem.M_FormationCol;
	LocaM_TypeCol.selectedIndex = locaM_TypeArray.indexOf(parentDocument.locaGrid.selectedItem.M_TypeCol.toString());	
	//LocaM_TypeCol.text = parentDocument.locaGrid.selectedItem.M_TypeCol;
	LocaM_CompactionCol.selectedIndex = locaM_CompactionArray.indexOf(parentDocument.locaGrid.selectedItem.M_CompactionCol.toString());
	//LocaM_CompactionCol.text = parentDocument.locaGrid.selectedItem.M_CompactionCol;
	LocaM_CompositionCol.selectedIndex = locaM_CompositionArray.indexOf(parentDocument.locaGrid.selectedItem.M_CompositionCol.toString());
	//LocaM_CompositionCol.text = parentDocument.locaGrid.selectedItem.M_CompositionCol;	
	LocaRelationshipsCol.text = parentDocument.locaGrid.selectedItem.M_RelationshipsCol;

}

protected function locaSaveButton_clickHandler(event:MouseEvent):void
{
	updateLoca();
	/*if(LocaIdCol.text == "0") {
		insertLoca();
	} else {
		
	}*/
}

protected function updateLoca():void {
	var locaSieved:String = SievedRB.selected?'Sieved':'Not Sieved';
    var parameters:* =
    {
      "method": "updateLoca", 
	  "Id": LocaIdCol.text, 
		  "Type": LocaTypeCol.text, 	
		  "Field": LocaFieldCol.text,	
		  "Square": LocaSquareCol.text, 
		  "Locus": LocaLocusCol.text, 
		  "Rationale": LocaRationaleCol.text, 
		  "Definition": LocaDefinitionCol.text,	
		  "Description": LocaDescriptionCol.text, 
		  "Interpretation": LocaInterpretationCol.text,	
		  "Stratigraphy": LocaStratigraphyCol.text, 
		  "LocaMethod": LocaMethodCol.selectedItem.label, 
		  "Sieved": locaSieved, 
		  "Quality": LocaQualityCol.selectedItem.label,	
		  "QualCom": LocaQualComCol.text, 
		  "Length": LocaLengthCol.text,	
		  "Width": LocaWidthCol.text, 
		  "Remarks": LocaRemarksCol.text, 
		  "M_Formation": LocaM_FormationCol.selectedItem.label,	
		  "M_Compaction": LocaM_CompactionCol.selectedItem.label, 
		  "M_Type": LocaM_TypeCol.selectedItem.label, 
		  "M_Composition": LocaM_CompositionCol.selectedItem.label,	
		  "M_Color": LocaM_ColorCol.text,		
		 "M_Inclusions": LocaM_InclusionsText,
		  "Relationships": LocaRelationshipsCol.text };

    doRequest("updateLoca", parameters, insertLocaHandler);
}
private function insertLoca():void {
	var locaSieved:String = SievedRB.selected?'Sieved':'Not Sieved';
    var parameters:* =
    {
        "method": "insertLoca",	  "Type": LocaTypeCol.text, "Name": LocaNameCol.text, "Field": LocaFieldCol.text,		"Square": LocaSquareCol.text,		"Locus": LocaLocusCol.text, 	"Rationale": LocaRationaleCol.text,		"Definition": LocaDefinitionCol.text,		"Description": LocaDescriptionCol.text,		"Interpretation": LocaInterpretationCol.text,		"Stratigraphy": LocaStratigraphyCol.text,		"Method": LocaMethodCol.selectedItem.label,		"Sieved": locaSieved,		"Quality": LocaQualityCol.selectedItem.label,		"QualCom": LocaQualComCol.text,		"Length": LocaLengthCol.text,		"Width": LocaWidthCol.text,		"Remarks": LocaRemarksCol.text,		"M_Formation": LocaM_FormationCol.selectedItem.label,		"M_Compaction": LocaM_CompactionCol.selectedItem.label,		"M_Type": LocaM_TypeCol.selectedItem.label,		"M_Composition": LocaM_CompositionCol.selectedItem.label,		"M_Color": LocaM_ColorCol.text,	
		"M_Inclusions": LocaM_InclusionsText,	
			"Relationships": LocaRelationshipsCol.text    };

    doRequest("insertLoca", parameters, insertLocaHandler);
}

private function insertLocaHandler(e:Object):void
{
    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    }
    else
    {
	home.selectedChild = view;
    searchDef.text = "Name";
    filterTxt.text = locaGrid.selectedItem.NameCol.substring(0,6);
 	fillLoca();
	filterTxt.text = locaGrid.selectedItem.NameCol;
 	fillBaskets();
 	fillObjects();
}}
private function fillLoca():void 
{   
    locaGrid.enabled = false;
    CursorManager.setBusyCursor();
    var parameters:* =
    {
        "orderField": "Name",
        "orderDirection": "ASC", 
        "filter": filterTxt.text,
        "searchDef": searchDef.text
    }  
    doRequest("findLoca", parameters, fillLocaHandler);
}
private function fillLocaHandler(e:Object):void
	{
	    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    } 
    else
    {
        locaDataArr.removeAll();
        for each(var row:XML in e.data.row) 
        {
            var temp:* = {};
            for (var key:String in locaFields) 
            {
                temp[key + 'Col'] = row[key];
            }

            locaDataArr.addItem(temp);
        }
		locaGrid.selectedIndex = selectedLoca;
        CursorManager.removeBusyCursor();
        locaGrid.enabled = true;
}}

private function deleteLoca():void {
        Alert.show("Are you sure you want to delete the selected record?",
        "Confirm Delete", 3, this, deleteLocaHandler);
}

private function deleteLocaHandler(event:CloseEvent):void
{
    if (event.detail == Alert.YES) 
    {
        var parameters:* =
        {
            "Id": locaId
        }
        doRequest("deleteLoca", parameters, deleteHandlerRefresh);
    }
}

private function deleteHandlerRefresh():void{
	
}

public var typeArr:Array = new Array("Surface", "Floor", "Road","Wall", "Burial", "Trench", "Pit");
private var typeMenu:Menu;
private function showTypeMenu():void {
                typeMenu = Menu.createMenu(null,typeArr, false);
                typeMenu.addEventListener("itemClick", typeMenuHandler);
                typeMenu.show(LocaTypeCol.x + 100, LocaTypeCol.y - 50);
            }
private function typeMenuHandler(event:MenuEvent):void  {
        LocaTypeCol.text = typeArr[typeMenu.selectedIndex];

            }

