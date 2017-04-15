import mx.collections.ArrayCollection;
import mx.controls.Alert;
import mx.controls.dataGridClasses.DataGridColumn;
import mx.events.CloseEvent;
import mx.events.DataGridEvent;
import mx.events.FlexEvent;
import mx.managers.CursorManager;
import mx.rpc.AsyncToken;
import mx.rpc.events.FaultEvent;
import mx.rpc.events.ResultEvent;
import mx.rpc.http.HTTPService;
import mx.validators.Validator;
public const ENDPOINT_URL:String = "php/Readings.php";
private var gateway:HTTPService = new HTTPService();
[Bindable]public var objectDataArr:ArrayCollection = new ArrayCollection();
private var orderColumn:Number;
private var objectFields:Object = { 'Id':Number, 'DFC':String,'DLC':String,'User':String,'Name':String, 'ShortName':String,'Field':String, 'Square':String, 'Locus':String, 'Basket':String, 'PeriodCode':String, 'Period':String, 'Quantity':Number, 'Saved':Number, 'Disposition':String, 'Description':String, 'Type':String, 'Comments':String };
[Bindable]public var periodDataArr:ArrayCollection = new ArrayCollection();
private var OrderColumn:Number;
private var periodFields:Object = { 'Id':Number, 'ListOrder':Number, 'Visible':String, 'Period':String, 'Code':String, 'SubPeriod':String};
[Bindable]public var arrType:ArrayCollection = new ArrayCollection([ {label:"Pottery"}, {label:"Bones"},{label:"Coins"},  {label:"Ceramic"} , {label:"Culture"}, {label:"Glass"}, {label:"Metal"}, {label:"Mudbrick"}, {label:"Object"}, {label:"Organic"}, {label:"Plaster"}, {label:"Pottery"}, {label:"Shell"}, {label:"Slag"}, {label:"Stone"} ]);

[Bindable]public var partsDataArr:ArrayCollection = new ArrayCollection();
//private var OrderColumn:Number;
private var partsFields:Object = { 'Id':Number, 'Part':String };

[Bindable]public var readDataArr:ArrayCollection = new ArrayCollection();
//private var OrderColumn:Number;
private var readFields:Object = { 'Id':Number,'DFC':String, 'DLC':String, 'User':String, 'Name':String, 'Field':String, 'Square':String, 'Locus':String, 'Basket':String, 'Periods':String, 'Context':String, 'Disposition':String, 'Status':String };

[Bindable]public var vesselsDataArr:ArrayCollection = new ArrayCollection();
//private var OrderColumn:Number;
private var vesselsFields:Object = { 'Id':Number, 'Vessel':String };

[Bindable]public var countDataArr:Array = new Array();
private var countFields:Object = { 'Count':Number };
private var entryGood:Boolean;
[Bindable]private var thePeriod:String;
[Bindable]private var theFolder:String;
[Bindable]private var theFile:String;
private var thePeriodCode:String;
private var partText:String;
private var vesselText:String;
private var desText:String;
private var theLastBasket:String;
private var theSelectedCamera:String;

private function initApp():void 
{
	gateway.url = ENDPOINT_URL;
	gateway.method = "POST";
	gateway.useProxy = false;
	gateway.resultFormat = "e4x";
	objectGrid.addEventListener(DataGridEvent.ITEM_EDIT_BEGINNING, editCellHandler);
	objectGrid.addEventListener(DataGridEvent.ITEM_EDIT_END, editCellEnd);
	gateway.addEventListener(ResultEvent.RESULT, resultHandler);
	gateway.addEventListener(FaultEvent.FAULT, faultHandler); 
	confirm_Login();
	BasketInput.text = " ";
    getReadBuckets();
    getPeriods();
    getCounts();
    getParts();
    getVessels();
    fill();
	fillUsers();
	visitor();
	videoDisplay_listCameras()
}

//************Buttons Pops and Vals
protected function buttonSettings_clickHandler(event:MouseEvent):void
{
	navigateToURL(new URLRequest('http://www.macromedia.com/support/documentation/en/flashplayer/help/settings_manager06.html#117645'), '_blank')
}
public function goToHome():void
{
	applicationScreens.selectedChild = home;
}
public function goToInstructions():void
{
	applicationScreens.selectedChild = instructions;
}
private function sourceButton_clickHandler(event:MouseEvent):void
{
	navigateToURL(new URLRequest('srcview/index.html'), '_blank')
}

private function closeDispositionPop():void {
           dispositionPop.text = dispositionPop.selectedLabel;
        }
            
private function closeObjectTypePop():void {
           objectTypePop.text = objectTypePop.selectedItem.label;
        }

private function clearBasket():void
{
	clearBasketOne();
	BasketInput.text = " ";
    getReadBuckets();
//	initApp();
}

private function clearBasketOne():void
{
	    readGrid.enabled = false;
    	CursorManager.setBusyCursor()
	  	var parameters:* =   
   {  "method": "Update", 
   "Basket": BasketInput.text };
	doRequest("clearBasket", parameters, clearHandler);
}

private function clearHandler(e:Object):void
{
    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    } 
    else
    {
        readDataArr.removeAll();
        for each(var row:XML in e.data.row) 
        {
            var temp:* = {};
            for (var key:String in readFields) 
            {
                temp[key + 'Col'] = row[key];
            }
            readDataArr.addItem(temp);
        }
        CursorManager.removeBusyCursor();
    	readGrid.enabled = true;
    }    
}

private function editCellEnd(e:DataGridEvent):void
{
    var dsRowIndex:int = e.rowIndex;
    var dsFieldName:String = e.dataField;
    var dsColumnIndex:Number = e.columnIndex;

    var vo:* = objectDataArr[dsRowIndex];
    
    var col:DataGridColumn = objectGrid.columns[dsColumnIndex];
    var newvalue:String = objectGrid.itemEditorInstance[col.editorDataField];

    trace("a:" + dsRowIndex + ", " + dsFieldName + ", " + dsColumnIndex);

    var parameters:* =
    {
        "Id": vo.IdCol,   "Name": vo.NameCol,     "Field": vo.FieldCol,        "Square": vo.SquareCol,        "Locus": vo.LocusCol,        "Basket": vo.BasketCol,        "Period": vo.PeriodCol,     "PeriodCode": vo.PeriodCodeCol,       "Quantity": vo.QuantityCol,     "Saved": vo.SavedCol,      "Description": vo.DescriptionCol,        "Type": vo.TypeCol,       "Comments": vo.CommentsCol  }

	parameters[dsFieldName.substr(0,dsFieldName.length-3)] = newvalue;
    doRequest("Update", parameters, saveItemHandler);    
}

private function editCellHandler(e:DataGridEvent):void
{
	if((e.dataField == "IdCol")||(e.dataField == "BasketCol")||(e.dataField == "NameCol")||(e.dataField == "TypeCol")||(e.dataField == "PeriodCol"))
    {
        e.preventDefault();
        return;
    }
}

private function entryVal():Boolean
{
	var inValidArray:Array = 
		Validator.validateAll([basketVal, periodVal]);
		 if(inValidArray.length == 0)
		 {
		 	return true;
		 }
		 else
		 {
		 	Alert.show("Data Entry Error, Select a Basket, a Period and a Disposition.", "Invalid Data Entry");
		 	return false;
		 }
}

private function filterResults():void
{
    fill();
}

private function insertBasket():void 
{	
	entryGood = entryVal();
	theSelectedCamera = cameraSelect.selectedIndex.toString();
	if(theSelectedCamera != "0"){
	img.visible = true;
	var camera:Camera = Camera.getCamera();
	if(camera){
		takeSnapshot();
	}
	}
	if (entryGood == true )
	{
//	Convert OrderColumn to Nan
//	orderColumn = 0/0;
   var parameters:* =   
   {  "method": "Insert", "Camera": theSelectedCamera, "Image": theByteString, "Field": FieldInput.text, "Square": SquareInput.text, "Locus": LocusInput.text, "Basket": BasketInput.text, "PeriodCode": thePeriodCode, "Period": PeriodInput.text, "Type": objectTypePop.text, "Quantity": CountInput.text, "Saved": SavedInput.text, "Disposition": dispositionPop.text, "Description": theDescription.text, "Comments": displayComments.text   }
   doRequest("insertBasket", parameters, insertItemHandler);
   	partsGrid.selectedIndex = -1;
	vesselsGrid.selectedIndex = -1;
	periodGrid.selectedIndex = -1;
	countGrid.selectedIndex = -1;
   }
}

private function insertItemHandler(e:Object):void
{
    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    }
    else
    {
	img.visible = false;
   fill();
   if(processMethod.selectedLabel == "Readables"){
	   PeriodInput.text = "";
   } else {
	   PeriodInput.text = "Unknown";
   }
   theDescription.text = "";
   displayComments.text = "";
   CountInput.text = "1";
   SavedInput.text = "1";
   dispositionPop.selectedIndex = 0;
    }     
}

private function fill():void 
{
    var desc:Boolean = true;
    var orderField:String = 'Name';
    
    if(!isNaN(orderColumn))
    {
        var col:DataGridColumn = objectGrid.columns[orderColumn];
        desc = col.sortDescending;
		//remove the 'Col' particle
        orderField = col.dataField.substr(0,col.dataField.length-3);
    }

    objectGrid.enabled = false;
    CursorManager.setBusyCursor();

    var parameters:* =
    {
        "orderField": orderField,
        "orderDirection": (desc) ? "DESC" : "ASC" ,
        "filter": BasketInput.text
    }
    doRequest("findObjects", parameters, fillHandler);
}

private function fillHandler(e:Object):void
{
    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    } 
    else
    {
        objectDataArr.removeAll();
        for each(var row:XML in e.data.row) 
        {
            var temp:* = {};
            for (var key:String in objectFields) 
            {
                temp[key + 'Col'] = row[key];
            }

            objectDataArr.addItem(temp);
        }

        CursorManager.removeBusyCursor();
        objectGrid.enabled = true;
    }    
}

private function getReadBuckets():void 
{
    var desc:Boolean = false;
    var orderField:String = '';    
    if(!isNaN(orderColumn))
    {
        var col:DataGridColumn = readGrid.columns[orderColumn];
        desc = col.sortDescending;
		//remove the 'Col' particle
        orderField = col.dataField.substr(0,col.dataField.length-3);
    } else {
    	orderField = 'Basket';
    	desc = false;
    }
    readGrid.enabled = false;
    CursorManager.setBusyCursor();

    var parameters:* =
    {
        "orderField": orderField,
        "orderDirection": (desc) ? "DESC" : "ASC",
        "searchType": "READ"
    }
    doRequest("findReads", parameters, readHandler);
}

private function readHandler(e:Object):void
{
    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    } 
    else
    {
        readDataArr.removeAll();
        for each(var row:XML in e.data.row) 
        {
            var temp:* = {};
            for (var key:String in readFields) 
            {
                temp[key + 'Col'] = row[key];
            }
            readDataArr.addItem(temp);
        }
        CursorManager.removeBusyCursor();
        readGrid.enabled = true;
//        if(readDataArr.length ==1) {
//        	//Alert.show("One Selected");
//        	readGrid.selectedIndex = 1;
//        	setBucket();
//        }
        
      
    }    
}

private function saveItemHandler(e:Object):void
{
    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    }
    else
    {
    }     
}

private function searchBasket():void 
{
    var desc:Boolean = false;
    var orderField:String = '';    
    if(!isNaN(orderColumn))
    {
        var col:DataGridColumn = readGrid.columns[orderColumn];
        desc = col.sortDescending;
		//remove the 'Col' particle
        orderField = col.dataField.substr(0,col.dataField.length-3);
    } else {
    	orderField = 'Name';
    	desc = true;
    }
    readGrid.enabled = false;
    CursorManager.setBusyCursor();

    var parameters:* =
    {
        "orderField": orderField,
        "orderDirection": (desc) ? "DESC" : "ASC",
        "searchType": "Basket",
        "basketNum": searchBasketNum.text
    }
    doRequest("findReads", parameters, readHandler);
}

private function setOrder(event:DataGridEvent):void 
{
    orderColumn = event.columnIndex;
    var col:DataGridColumn = readGrid.columns[orderColumn];
    col.sortDescending = !col.sortDescending;
    
    event.preventDefault();
    fill();
}
private function getDateFormat(item:Object,column:DataGridColumn):String{
	//	return dateFormat.format(item[column.dataField]);
	return item[column.dataField].substring(5,7) + "/" + item[column.dataField].substring(8,10) + "/" + item[column.dataField].substring(2,4);
} 

private function setBucket():void
{
	img.visible = false;
	FieldInput.text = readGrid.selectedItem.FieldCol;
	SquareInput.text = readGrid.selectedItem.SquareCol;
	LocusInput.text = readGrid.selectedItem.LocusCol;
	BasketInput.text = readGrid.selectedItem.BasketCol;
	var thewholeDate:String = readGrid.selectedItem.DFCCol;
	DFCInput.text = thewholeDate.substring(5,7) + "/" + thewholeDate.substring(8,10) + "/" + thewholeDate.substring(2,4);
	PeriodInput.text = "";
	thePeriodCode = "";
	fill();
}
protected function buttonHome_clickHandler(event:MouseEvent):void
{
	navigateToURL(new URLRequest('../demo/'), '_self');
}
private function setCount():void
{
	CountInput.text = countGrid.selectedItem.valueOf();
}

private function setSavedCount():void
{
	SavedInput.text = savedGrid.selectedItem.valueOf();
	dispositionPop.text = "Save";
}

private function setPart():void
{
	partText = partsGrid.selectedItem.PartCol;
	desText = theDescription.text
	if(vesselText != null)
	{
	theDescription.text = vesselText +"-"+ partText +", " + desText ;
	} else {
	Alert.show("Select a Vessel then a Part");
	}
	vesselText = null;
	partText = null;
	partsGrid.selectedIndex = -1;
	vesselsGrid.selectedIndex = -1;
}

private function setPredom():void
{
	
	desText = displayComments.text
	displayComments.text = desText + "Predom" ;
}

private function setPeriod():void
{
	img.visible = false;
	PeriodInput.text = periodGrid.selectedItem.SubPeriodCol;
	thePeriodCode = periodGrid.selectedItem.CodeCol;
}

private function setVessel():void
{
	img.visible = false;
	vesselText = vesselsGrid.selectedItem.VesselCol;
}

private function getCounts():void 
{		
countDataArr = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,25,30,35,40,45,50]
}


private function getParts():void 
{
    var desc:Boolean = false;
    var orderField:String = '';
    
    if(!isNaN(OrderColumn))
    {
        var col:DataGridColumn = partsGrid.columns[OrderColumn];
        desc = col.sortDescending;
		//remove the 'Col' particle
        orderField = col.dataField.substr(0,col.dataField.length-3);
    } else {
    	orderField = 'Part';
    	desc = false;
    }

    partsGrid.enabled = false;
    CursorManager.setBusyCursor();

    var parameters:* =
    {
        "orderField": "Part",
        "orderDirection": (desc) ? "DESC" : "ASC"
    }
    doRequest("findParts", parameters, partsHandler);
}

private function getPeriods():void 
{
    var desc:Boolean = false;
    var orderField:String = '';
    
    if(!isNaN(OrderColumn))
    {
        var col:DataGridColumn = periodGrid.columns[OrderColumn];
        desc = col.sortDescending;
		//remove the 'Col' particle
        orderField = col.dataField.substr(0,col.dataField.length-3);
    } else {
    	orderField = 'ListOrder';
    	desc = true;
    }

    periodGrid.enabled = false;
    CursorManager.setBusyCursor();

    var parameters:* =
    {
        "orderField": "ListOrder",
        "orderDirection": (desc) ? "DESC" : "ASC"
    }
    doRequest("findPeriods", parameters, periodHandler);
}

private function partsHandler(e:Object):void
{
    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    } 
    else
    {
        partsDataArr.removeAll();
        for each(var row:XML in e.data.row) 
        {
            var temp:* = {};
            for (var key:String in partsFields) 
            {
                temp[key + 'Col'] = row[key];
            }

            partsDataArr.addItem(temp);
        }

        CursorManager.removeBusyCursor();
        partsGrid.enabled = true;
    }    
}


private function periodHandler(e:Object):void
{
    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    } 
    else
    {
        periodDataArr.removeAll();
        for each(var row:XML in e.data.row) 
        {
            var temp:* = {};
            for (var key:String in periodFields) 
            {
                temp[key + 'Col'] = row[key];
            }

            periodDataArr.addItem(temp);
        }

        CursorManager.removeBusyCursor();
        periodGrid.enabled = true;
        periodGrid.verticalScrollPosition = 20;
    }    
}

private function getVessels():void 
{
    var desc:Boolean = false;
    var orderField:String = '';
    
    if(!isNaN(OrderColumn))
    {
        var col:DataGridColumn = vesselsGrid.columns[OrderColumn];
        desc = col.sortDescending;
		//remove the 'Col' particle
        orderField = col.dataField.substr(0,col.dataField.length-3);
    } else {
    	orderField = 'Vessel';
    	desc = false;
    }

    vesselsGrid.enabled = false;
    CursorManager.setBusyCursor();

    var parameters:* =
    {
        "orderField": "Vessel",
        "orderDirection": (desc) ? "DESC" : "ASC"
    }
    doRequest("findVessels", parameters, vesselsHandler);
}


private function vesselsHandler(e:Object):void
{
    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    } 
    else
    {
        vesselsDataArr.removeAll();
        for each(var row:XML in e.data.row) 
        {
            var temp:* = {};
            for (var key:String in vesselsFields) 
            {
                temp[key + 'Col'] = row[key];
            }

            vesselsDataArr.addItem(temp);
        }

        CursorManager.removeBusyCursor();
        vesselsGrid.enabled = true;
    }    
}

private function deleteItem():void {
    
    if (objectGrid.selectedItem)
    {
        Alert.show("Are you sure you want to delete the selected record?",
        "Confirm Delete", 3, this, deleteClickHandler);
    }
    
} 
private function deleteClickHandler(event:CloseEvent):void
{
    if (event.detail == Alert.YES) 
    {
        var vo:* = objectGrid.selectedItem;

        var parameters:* =
        {
            "Id": vo.IdCol
        }

		/**
		 * execute the server "delete" command
		 */
        doRequest("Delete", parameters, deleteHandler);

        setTimeout( function():void
        {
            objectGrid.destroyItemEditor();
        },
        1);
    }
}

public function deleteHandler(e:*):void
{
    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    }
    else
    {
        var Id:Number = parseInt(e.data.toString(), 10);
        for (var index:Number = 0; index < objectDataArr.length; index++)
        {
            if (objectDataArr[index].IdCol == Id)
            {
                objectDataArr.removeItemAt(index);
                break;
            }
        }
    }     
}

public function deserialize(obj:*, e:*):*
{
    var toret:Object = {};
    
    toret.originalEvent = e;

    if (obj.data.elements("error").length() > 0)
    {
        toret.isError = true;
        toret.data = obj.data;
    }
    else
    {
        toret.isError = false;
        toret.metadata = obj.metadata;
        toret.data = obj.data;
    }

    return toret;
}

public function resultHandler(e:ResultEvent):void
{
    var topass:* = deserialize(e.result, e);
    e.token.handler.call(null, topass);
}

public function faultHandler(e:FaultEvent):void
{
	var errorMessage:String = "Connection error: " + e.fault.faultString; 
    if (e.fault.faultDetail) 
    { 
        errorMessage += "\n\nAdditional detail: " + e.fault.faultDetail; 
    } 
    Alert.show(errorMessage);
}

public function doRequest(method_name:String, parameters:Object, callback:Function):void
{
    // add the method to the parameters list
    parameters['method'] = method_name;

    gateway.request = parameters;

    var call:AsyncToken = gateway.send();
    call.request_params = gateway.request;

    call.handler = callback;
}
