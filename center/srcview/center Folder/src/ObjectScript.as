// ObjectScript file
import flash.events.Event;
[Bindable]public var objectId:String;
[Bindable]public var objectName:String;
[Bindable]public var objectDFC:String;
[Bindable]public var objectDLC:String;
[Bindable]public var objectUser:String;
[Bindable]public var objectField:String;
[Bindable]public var objectSquare:String;
[Bindable]public var objectLocus:String;
[Bindable]public var objectBasket:String;
[Bindable]public var objectPeriodCode:String;
[Bindable]public var objectPeriod:String;
[Bindable]public var objectQuantity:String;
[Bindable]public var objectSaved:String;
[Bindable]public var objectDisposition:String;
[Bindable]public var objectDescription:String;
[Bindable]public var objectType:String;
[Bindable]public var objectComments:String;
public var selectedObject:int;
[Bindable]public var objectsDataArr:ArrayCollection = new ArrayCollection();
private var objectsFields:Object = { 'Id':Number, 'DFC':String,'DLC':String,'User':String,'Name':String, 'ShortName':String,'Field':String, 'Square':String, 'Locus':String, 'Basket':String, 'PeriodCode':String, 'Period':String, 'Quantity':Number, 'Saved':Number, 'Disposition':String, 'Description':String, 'Type':String, 'Comments':String};

private function allObjects():void
{
	selectedNum = ""
	searchDef.text = "Locus"
	fillObjects();
}
private function selectObjects():void
{
	 filterTxt.text = objectsGrid.selectedItem.NameCol;
	 searchDef.text = "Name"
	 currentState = '';
	 fillPhotos();
	 CursorManager.removeBusyCursor();
}
private function newObject(event:Event):void{	
	navigateToURL(new URLRequest('../readings/'), '_blank')
	/*home.selectedIndex = 6;	
	objectId = "0";
	objectName = "";
	objectField = "";
	objectSquare = "";
	objectLocus = "";
	objectBasket = "";
	objectPeriodCode = "";
	objectPeriod = "";
	objectQuantity = "";
	objectSaved = "";
	objectDisposition = "";
	objectDescription = "";
	objectType = "";
	objectComments = "";	*/
}
private function editObjects(event:Event):void
{
	selectedObject = objectsGrid.selectedIndex;
	objectId = objectsGrid.selectedItem.IdCol;
	var DFCFormat:String = objectsGrid.selectedItem.DFCCol;
	objectDFC = DFCFormat.substring(5,7) + "/" + DFCFormat.substring(8,10) + "/" + DFCFormat.substring(2,4);
	var DLCFormat:String = objectsGrid.selectedItem.DLCCol;
	objectDLC = DLCFormat.substring(5,7) + "/" + DLCFormat.substring(8,10) + "/" + DLCFormat.substring(2,4);
	//objectDFC =objectsGrid.selectedItem.DFCCol;
	//objectDLC =objectsGrid.selectedItem.DLCCol;
	objectUser =objectsGrid.selectedItem.UserCol;
	objectName =objectsGrid.selectedItem.NameCol;
	objectField =objectsGrid.selectedItem.FieldCol;
	objectSquare =objectsGrid.selectedItem.SquareCol;
	objectLocus =objectsGrid.selectedItem.LocusCol;
	objectBasket =objectsGrid.selectedItem.BasketCol;
	objectPeriodCode =objectsGrid.selectedItem.PeriodCodeCol;
	objectPeriod =objectsGrid.selectedItem.PeriodCol;
	objectQuantity =objectsGrid.selectedItem.QuantityCol;
	objectSaved =objectsGrid.selectedItem.SavedCol;
	objectDisposition =objectsGrid.selectedItem.DispositionCol;
	objectDescription = objectsGrid.selectedItem.DescriptionCol;
	objectType = objectsGrid.selectedItem.TypeCol;
	objectComments =objectsGrid.selectedItem.CommentsCol;
	home.selectedIndex = 6;	
}
private function cancelObject():void{
	home.selectedIndex = 0;
	ObjectIdCol.text = parentDocument.objectsGrid.selectedItem.IdCol;
	ObjectNameCol.text = parentDocument.objectsGrid.selectedItem.NameCol;
	ObjectFieldCol.text = parentDocument.objectsGrid.selectedItem.FieldCol;
	ObjectSquareCol.text = parentDocument.objectsGrid.selectedItem.SquareCol;
	ObjectLocusCol.text = parentDocument.objectsGrid.selectedItem.LocusCol;
	ObjectBasketCol.text = parentDocument.objectsGrid.selectedItem.BasketCol;
	//ObjectPeriodCodeCol.text = parentDocument.objectsGrid.selectedItem.PeriodCodeCol;
	ObjectPeriodCol.text = parentDocument.objectsGrid.selectedItem.PeriodCol;
	ObjectQuantityCol.text = parentDocument.objectsGrid.selectedItem.QuantityCol;
	ObjectSavedCol.text = parentDocument.objectsGrid.selectedItem.SavedCol;
	ObjectDispositionCol.text = parentDocument.objectsGrid.selectedItem.DispositionCol;
	ObjectDescriptionCol.text = parentDocument.objectsGrid.selectedItem.DescriptionCol;
	ObjectTypeCol.text = parentDocument.objectsGrid.selectedItem.TypeCol;
	//ObjectCreateDateCol.text = parentDocument.objectsGrid.selectedItem.CreateDateCol;
	ObjectCommentsCol.text = parentDocument.objectsGrid.selectedItem.CommentsCol;

}
private function saveObject():void{
	if(objectId == "0") {
		insertObject();
	} else {
		updateObject();
}}
private function updateObject():void
{
	var parameters:* =
    {
        "method": "updateObject",	"Id": ObjectIdCol.text,	"Name": ObjectNameCol.text,		"Field": ObjectFieldCol.text,		"Square": ObjectSquareCol.text,		"Locus": ObjectLocusCol.text,		"Basket": ObjectBasketCol.text,		"PeriodCode": "",		"Period": ObjectPeriodCol.text,		"Quantity": ObjectQuantityCol.text,		"Saved": ObjectSavedCol.text,		"Disposition": ObjectDispositionCol.text,		"Description": ObjectDescriptionCol.text,		"Type": ObjectTypeCol.text,		"Comments": ObjectCommentsCol.text    };
    doRequest("updateObject", parameters, insertObjectHandler);
}
private function insertObject():void {
    var parameters:* =
    {
        "method": "insertObject",		"Field": ObjectFieldCol.text,		"Square": ObjectSquareCol.text,		"Locus": ObjectLocusCol.text,		"Basket": ObjectBasketCol.text,		"PeriodCode": "",		"Period": ObjectPeriodCol.text,		"Quantity": ObjectQuantityCol.text,		"Saved": ObjectSavedCol.text,		"Disposition": ObjectDispositionCol.text,		"Description": ObjectDescriptionCol.text,		"Type": ObjectTypeCol.text,			"Comments": ObjectCommentsCol.text    };

	/**
	 * execute the server "insert" command
	 */
    doRequest("insertObject", parameters, insertObjectHandler);
}

private function insertObjectHandler(e:Object):void
{
    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    }
    else
    {
		home.selectedChild = view;
		filterTxt.text = objectsGrid.selectedItem.NameCol.substring(0,12);
        fillObjects();
		//filterTxt.text = objectsGrid.selectedItem.NameCol.substring(0,9);;
		fillPhotos();
    }     
}

private function fillObjects():void 
{   
 	objectsGrid.enabled = false; 
    CursorManager.setBusyCursor();
    var parameters:* =
    {
        "orderField": "Name",
        "orderDirection": "ASC", 
        "filter": filterTxt.text,
        "searchDef": searchDef.text,
		"searchType": searchType.text,
		"searchPeriod": searchPeriod.text
    }
      
    doRequest("findObjects", parameters, fillObjectsHandler);
}

private function fillObjectsHandler(e:Object):void
	{
	    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    } 
    else
    {
        objectsDataArr.removeAll();
        for each(var row:XML in e.data.row) 
        {
            var temp:* = {};
            for (var key:String in objectsFields) 
            {
                temp[key + 'Col'] = row[key];
            }

            objectsDataArr.addItem(temp);
        }
		objectsGrid.selectedIndex = selectedObject;
        CursorManager.removeBusyCursor();
        objectsGrid.enabled = true;
    }    
}


private function deleteObject():void{
	   Alert.show("Are you sure you want to delete the selected record?",
        "Confirm Delete", 3, this, deleteObjectHandler);
}
	
private function deleteObjectHandler(event:CloseEvent):void{
	Alert.show(ObjectIdCol.text);
    if (event.detail == Alert.YES) 
    {
        var parameters:* =
        {
            "Id": ObjectIdCol.text
        }
        doRequest("deleteObject", parameters, deleteHandler);
    }
	
}
private function deleteObjectRefresh():void{
	
	
}

/* public function deleteHandler(e:*):void
{
    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    }
    else
    {
        var Id:Number = parseInt(e.data.toString(), 10);
        for (var index:Number = 0; index < dataArr.length; index++)
        {
            if (dataArr[index].IdCol == Id)
            {
                dataArr.removeItemAt(index);
                break;
            }
        }
    }     
} */

