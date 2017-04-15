//SquareScript
import flash.events.Event;
import mx.collections.ArrayCollection;
import mx.controls.Alert;
[Bindable]public var squareId:String;
[Bindable]public var squareName:String;
[Bindable]public var squareDFC:String;
[Bindable]public var squareDLC:String;
[Bindable]public var squareUser:String;
[Bindable]public var squareField:String;
[Bindable]public var squareSquare:String;
[Bindable]public var squareOpen:Boolean;
[Bindable]public var squareClosed:Boolean;
[Bindable]public var squareSupervisor:String;
[Bindable]public var squaresDataArr:ArrayCollection = new ArrayCollection();
[Bindable]public var squareFieldsArr:Array =  new Array("A", "B", "C");
private var squaresFields:Object = { 'Id':Number, 'Name':String, 'DFC':String,'DLC':String,'User':String,'Field':String, 'Square':String, 'Supervisor':String, 'Open':String};
public var selectedSquare:int;
[Bindable]public var squareFieldIndex:int;

private function allSquares():void {
    searchDef.text = "";
	fillSquares();
}

private function selectSquare():void {
 	selectedNum = squaresGrid.selectedItem.SquareCol;
	selectedSquare = squaresGrid.selectedItem.SquareCol;
	filterTxt.text = selectedNum;
	searchDef.text = "Square"
	selectedLoca = 0;
	selectedBasket = 0;
	selectedObject = 0;
	fillLoca();
	fillLocus();
	fillBaskets();
	fillObjects();
	fillPhotos();
	fillGPS();
	CursorManager.removeBusyCursor(); 
}

private function newSquare(event:Event):void {
	home.selectedIndex = 2;
    squareId = "0";
    squareName = "";  
    squareField = "";
    squareSquare = "";
    squareOpen = true;
    squareClosed = false;
    squareSupervisor = ""; 
    fillFields();  
}
private function editSquare():void 
{
	squareId = squaresGrid.selectedItem.IdCol;
	squareName = squaresGrid.selectedItem.NameCol;
	
	var DFCFormat:String = squaresGrid.selectedItem.DFCCol;
	squareDFC = DFCFormat.substring(5,7) + "/" + DFCFormat.substring(8,10) + "/" + DFCFormat.substring(2,4);
	var DLCFormat:String = squaresGrid.selectedItem.DLCCol;
	squareDLC = DLCFormat.substring(5,7) + "/" + DLCFormat.substring(8,10) + "/" + DLCFormat.substring(2,4);
	squareUser = squaresGrid.selectedItem.UserCol;
	squareField = squaresGrid.selectedItem.FieldCol;
	squareFieldIndex = squareFieldsArr.indexOf(squareField);
	squareSquare = squaresGrid.selectedItem.SquareCol;
	var squareOpenVar:String = squaresGrid.selectedItem.OpenCol;
	if(squareOpenVar == 'Open'){
		squareOpen = true;
		squareClosed = false;
	}else {
		squareOpen = false;
		squareClosed = true;
	}
	squareSupervisor = squaresGrid.selectedItem.SupervisorCol;	
	home.selectedIndex = 2;
	
}
private function cancelSquare():void {
	SquareIdCol.text = parentDocument.squaresGrid.selectedItem.IdCol;
	SquareNameCol.text = parentDocument.squaresGrid.selectedItem.NameCol;
	SquareFieldCol.text = parentDocument.squaresGrid.selectedItem.FieldCol;
	SquareSquareCol.text = parentDocument.squaresGrid.selectedItem.SquareCol;
	var squareOpenVar:String = parentDocument.squaresGrid.selectedItem.OpenCol;
	if(squareOpenVar == 'Open'){
		squareOpen = true;
		squareClosed = false;
	}else {
		squareClosed = false;
		squareClosed = true;
	}
	SquareSupervisorCol.text = parentDocument.squaresGrid.selectedItem.SupervisorCol;	
	home.selectedIndex = 0;
}
private function saveSquare():void {
	if(squareId == "0") {
		insertSquare();
	} else {
		updateSquare();
}}
private function updateSquare():void {
	var squareOpen:String = OpenRB.selected?'Open':'Closed';
    var parameters:* =
    {
      "method": "updateSquare",		"Id": SquareIdCol.text,    "Name": SquareNameCol.text,		"Field": SquareFieldCol.selectedItem,		"Square": SquareSquareCol.text,		"Open": squareOpen,		"Supervisor": SquareSupervisorCol.text   };

    doRequest("updateSquare", parameters, insertSquareHandler);
}
private function insertSquare():void {
	var squareOpen:String = OpenRB.selected?'Open':'Closed';
    var parameters:* =
    {
      "method": "insertSquare",		"Name": SquareNameCol.text,		"Field": SquareFieldCol.selectedItem,		"Square": SquareSquareCol.text,		"Open": squareOpen,		"Supervisor": SquareSupervisorCol.text   };

    doRequest("insertSquare", parameters, insertSquareHandler);
}

private function insertSquareHandler(e:Object):void
{
    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    }
    else
    {
	home.selectedChild = view;
 	fillSquares();
}}
private function fillSquares():void {   
    squaresGrid.enabled = false;
    CursorManager.setBusyCursor();
    var parameters:* =
    {
        "orderField": "Name",
        "orderDirection": "ASC", 
        "squareDef": squareDefPop.text
    }  
    doRequest("findSquares", parameters, fillSquaresHandler);
}
private function fillSquaresHandler(e:Object):void
	{
	    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    } 
    else
    {
        squaresDataArr.removeAll();
        for each(var row:XML in e.data.row) 
        {
            var temp:* = {};
            for (var key:String in squaresFields) 
            {
                temp[key + 'Col'] = row[key];
            }

            squaresDataArr.addItem(temp);
        }
		squaresGrid.selectedIndex = selectedSquare;
        CursorManager.removeBusyCursor();
        squaresGrid.enabled = true;
}}

private function deleteSquare():void {
        Alert.show("Are you sure you want to delete the selected record?", "Confirm Delete", 3, this, deleteSquareHandler);   
}
 
private function deleteSquareHandler(event:CloseEvent):void
{
    if (event.detail == Alert.YES) 
    {
        var parameters:* =
        {
            "Id": squareId
        }

        doRequest("deleteSquare", parameters, deleteSquareRefresh);

        setTimeout( function():void
        {
          //  dataGrid.destroyItemEditor();
        },
        1);
}}
private function deleteSquareRefresh():void{
	
}
// ActionScript file
