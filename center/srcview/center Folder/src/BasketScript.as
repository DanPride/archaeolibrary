// BasketScript
import flash.events.Event;

import mx.controls.Alert;

[Bindable]public var basketId:String;
[Bindable]public var basketDFC:String;
[Bindable]public var basketDLC:String;
[Bindable]public var basketUser:String;
[Bindable]public var basketName:String;
[Bindable]public var basketField:String;
[Bindable]public var basketSquare:String;
[Bindable]public var basketLocus:String;
[Bindable]public var basketBasket:String;
[Bindable]public var basketPeriods:String;
[Bindable]public var basketContext:String;
[Bindable]public var basketDisposition:String;
[Bindable]public var basketStatus:String;

public var selectedBasket:int;
public var vBasketName:String = new String();
[Bindable]
public var basketsDataArr:ArrayCollection = new ArrayCollection();
private var basketFields:Object = { 'Id':Number, 'DFC':String,'DLC':String,'User':String,'Name':String,'ShortName':String, 'Field':String, 'Square':String, 'Locus':String, 'Basket':String, 'Periods':String, 'Context':String, 'Disposition':String,  'Status':String};


private function allBaskets():void
{
	selectedNum = ""  //?????
	searchDef.text = "Search Locus"
	fillBaskets();
}
private function selectBaskets():void 
{   
 	filterTxt.text = basketsGrid.selectedItem.NameCol;
	searchDef.text = "Name"
	currentState = '';
	fillObjects();
	fillPhotos();	
	CursorManager.removeBusyCursor();
}
private function newBasket(event:Event):void{
	navigateToURL(new URLRequest('../checkin/'), '_blank')
}
	
private function editBasket(event:Event):void 
{
	selectedBasket = basketsGrid.selectedIndex;
	selectedObject = 0;
	basketId = basketsGrid.selectedItem.IdCol;
	basketName = basketsGrid.selectedItem.NameCol;
	var DFCFormat:String = basketsGrid.selectedItem.DFCCol;
	basketDFC = DFCFormat.substring(5,7) + "/" + DFCFormat.substring(8,10) + "/" + DFCFormat.substring(2,4);
	var DLCFormat:String = basketsGrid.selectedItem.DLCCol;
	basketDLC = DLCFormat.substring(5,7) + "/" + DLCFormat.substring(8,10) + "/" + DLCFormat.substring(2,4);
	basketUser = basketsGrid.selectedItem.UserCol;
	basketField = basketsGrid.selectedItem.FieldCol;
	basketSquare = basketsGrid.selectedItem.SquareCol;
	basketLocus = basketsGrid.selectedItem.LocusCol;
	basketBasket = basketsGrid.selectedItem.BasketCol;
	basketPeriods = basketsGrid.selectedItem.PeriodsCol;
	basketContext = basketsGrid.selectedItem.ContextCol;
	basketDisposition = basketsGrid.selectedItem.DispositionCol;
	basketStatus = basketsGrid.selectedItem.StatusCol;
	home.selectedIndex = 5;
}
private function cancelBasket():void{
	home.selectedIndex = 0;
	BasketIdCol.text = parentDocument.basketsGrid.selectedItem.IdCol;
	BasketNameCol.text = parentDocument.basketsGrid.selectedItem.NameCol;
	BasketFieldCol.text = parentDocument.basketsGrid.selectedItem.FieldCol;
	BasketSquareCol.text = parentDocument.basketsGrid.selectedItem.SquareCol;
	BasketLocusCol.text = parentDocument.basketsGrid.selectedItem.LocusCol;
	BasketBasketCol.text = parentDocument.basketsGrid.selectedItem.BasketCol;
	BasketPeriodsCol.text = parentDocument.basketsGrid.selectedItem.PeriodsCol;
	BasketContextCol.text = parentDocument.basketsGrid.selectedItem.ContextCol;
	BasketDispositionCol.text = parentDocument.basketsGrid.selectedItem.DispositionCol;
	BasketStatusCol.text = parentDocument.basketsGrid.selectedItem.StatusCol;	
	
}
private function saveBasket():void {
	if(basketId == "0") {
		insertBasket();
	} else {
		updateBasket();
}}
private function updateBasket():void{
	var parameters:* =
    {
        "method": "updateBasket",	"Id": BasketIdCol.text,	"Name": BasketNameCol.text,	"Field": BasketFieldCol.text,		"Square": BasketSquareCol.text,		"Locus": BasketLocusCol.text,		"Basket": BasketBasketCol.text,		"Periods": BasketPeriodsCol.text,		"Context": BasketContextCol.text,		"Disposition": BasketDispositionCol.text,			"Status": BasketStatusCol.text    };
    doRequest("updateBasket", parameters, insertBasketHandler);
}
private function insertBasket():void{
    var parameters:* =
    {
        "method": "insertBasket",	"Field": BasketFieldCol.text,		"Square": BasketSquareCol.text,		"Locus": BasketLocusCol.text,		"Basket": BasketBasketCol.text,		"Periods": BasketPeriodsCol.text,		"Context": BasketContextCol.text,		"Disposition": BasketDispositionCol.text,		"Status": BasketStatusCol.text    };
    doRequest("insertBasket", parameters, insertBasketHandler);
}
private function insertBasketHandler(e:Object):void{
	if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    }
    else
    {
	home.selectedChild = view;
    searchDef.text = "Name";
    filterTxt.text = basketsGrid.selectedItem.NameCol.substring(0,9);
 	fillBaskets();
	filterTxt.text = basketsGrid.selectedItem.NameCol.substring(0,12);
 	fillObjects();
 	fillPhotos();

}}
private function fillBaskets():void {   
    basketsGrid.enabled = false;
    CursorManager.setBusyCursor();
    var parameters:* =
    {
        "orderField": "Name",
        "orderDirection": "ASC",     
        "filter":  filterTxt.text,
        "searchDef": searchDef.text    
    }
    doRequest("findBaskets", parameters, fillBasketsHandler);
}

private function fillBasketsHandler(e:Object):void
	{
	    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    } 
    else
    {
        basketsDataArr.removeAll();
        for each(var row:XML in e.data.row) 
        {
            var temp:* = {};
            for (var key:String in basketFields) 
            {
                temp[key + 'Col'] = row[key];
            }

            basketsDataArr.addItem(temp);
        }
		basketsGrid.selectedIndex = selectedBasket;
        CursorManager.removeBusyCursor();
        basketsGrid.enabled = true;
    }    
}

private function deleteBasket():void {
        Alert.show("Are you sure you want to delete the selected record?",
        "Confirm Delete", 3, this, deleteBasketHandler);
}

private function deleteBasketHandler(event:CloseEvent):void
{
    if (event.detail == Alert.YES) 
    {
        var parameters:* =
        {
            "Id": LocaIdCol
        }
        doRequest("deleteBasket", parameters, deleteBasketRefresh);
    }
}

public function deleteBasketRefresh():void
{
    
}

