
import mx.collections.ArrayCollection;
import mx.controls.Alert;
import mx.controls.dataGridClasses.DataGridColumn;
import mx.controls.dataGridClasses.DataGridItemRenderer;
import mx.events.CloseEvent;
import mx.events.DataGridEvent;
import mx.events.DropdownEvent;
import mx.events.FlexEvent;
import mx.managers.CursorManager;
import mx.rpc.AsyncToken;
import mx.rpc.events.FaultEvent;
import mx.rpc.events.ResultEvent;
import mx.rpc.http.HTTPService;
import mx.utils.ObjectUtil;
import mx.validators.Validator;
public const ENDPOINT_URL:String = "php/Culture.php";
private var gateway:HTTPService = new HTTPService();
private var entryGood:Boolean;
[Bindable]protected var thePeriod:String;
[Bindable]public var dataArr:ArrayCollection = new ArrayCollection();
private var orderColumn:Number;
private var fields:Object = { 'Id':Number, 'Name':String, 'Field':String, 'Square':String, 'Locus':String, 'Basket':String, 'PeriodCode':String, 'Period':String, 'Quantity':Number, 'Saved':Number, 'Disposition':String, 'Description':String, 'Type':String, 'CreateDate':Date, 'Comments':String};
[Bindable]public var basketArr:ArrayCollection = new ArrayCollection();
private var basketOrderColumn:Number;
private var basketFields:Object = { 'Id':Number, 'Field':String, 'Square':String, 'Locus':String, 'Basket':String,  'Quantity':Number, 'Disposition':String, 'Description':String, 'Type':String, 'CreateDate':Date, 'Comments':String};
[Bindable]public var formsDataArr:ArrayCollection = new ArrayCollection();
private var formsFields:Object = { 'Id':Number, 'Type':String, 'Vessel':String };
private var theBasketDate:String;
[Bindable]public var arrPeriodPop:ArrayCollection = new ArrayCollection();
private var periodsObject:Object = { 'Id':Number, 'ListOrder':Number, 'Visible':String, 'Period':String, 'Code':String, 'SubPeriod':String};
[Bindable]public var objectTypeArr:ArrayCollection = new ArrayCollection([ {label:"Select Type"}, {label:"Bone"},{label:"Coin"},  {label:"Ceramic"} , {label:"Culture"}, {label:"Glass"}, {label:"Metal"}, {label:"Mudbrick"}, {label:"Object"}, {label:"Organic"}, {label:"Plaster"}, {label:"Pottery"}, {label:"Shell"}, {label:"Slag"}, {label:"Stone"} ]);
[Bindable]public var objectTypeEntryArr:ArrayCollection = new ArrayCollection([{label:""}, {label:"Bone"}, {label:"Coin"}, {label:"Ceramic"} , {label:"Culture"}, {label:"Glass"}, {label:"Metal"}, {label:"Mudbrick"}, {label:"Object"}, {label:"Organic"}, {label:"Plaster"}, {label:"Pottery"}, {label:"Shell"}, {label:"Slag"}, {label:"Stone"} ]);                 
[Bindable]public var dispositionArr:ArrayCollection = new ArrayCollection([ {label:"Save", data:1}, {label:"Discard", data:2}, {label:"Save Diag", data:3},{label:"Save All", data:4}, {label:"Mend", data:5}, {label:"Restore", data:6} ]); 
[Bindable]public var yearArr:ArrayCollection = new ArrayCollection([ {label:"All"},{label:"2012"}, {label:"2011"}, {label:"2010"}, {label:"2009"}, {label:"2008"}, {label:"2007"},{label:"2006"} ]);

private function initApp():void 
{
	gateway.url = ENDPOINT_URL;
	gateway.method = "POST";
	gateway.useProxy = false;
	gateway.resultFormat = "e4x";
	gateway.addEventListener(ResultEvent.RESULT, resultHandler);
	gateway.addEventListener(FaultEvent.FAULT, faultHandler);
	confirm_Login();
	dataGrid.addEventListener(DataGridEvent.ITEM_EDIT_BEGINNING, editCellHandler);
	dataGrid.addEventListener(DataGridEvent.ITEM_EDIT_END, editCellEnd);
	visitor();
	getForms();
	fillPeriods();
	fill();	
}

//*****ButtonsAndPops
private function getDateFormat(item:Object,column:DataGridColumn):String{
	//	return dateFormat.format(item[column.dataField]);
	return item[column.dataField].substring(5,7) + "/" + item[column.dataField].substring(8,10) + "/" + item[column.dataField].substring(2,4);
}

protected function buttonHome_clickHandler(event:MouseEvent):void
{
	navigateToURL(new URLRequest('../demo/'), '_self');
}
public function goToHome():void
{
	applicationScreens.selectedChild = home;
}
public function goToInstructions():void
{
	applicationScreens.selectedChild = instructions;
}
protected function sourceButton_clickHandler(event:MouseEvent):void
{
	navigateToURL(new URLRequest('srcview/index.html'), '_blank')
}
private function closeYearPop():void {
           //yearPop.text = yearPop.selectedItem.data;
            }
private function closeObjectTypePop():void {
           //objectTypePop.text = objectTypePop.selectedItem.label;
            }
private function closeDispositionPop():void {
           //dispositionPop.text = dispositionPop.selectedItem.label;
            }
private function editCellHandler(e:DataGridEvent):void
{
// if the user clicked on the primary key column, stop editing
    if((e.dataField == "IdCol")||(e.dataField == "FieldCol")||(e.dataField == "SquareCol")||(e.dataField == "LocusCol")||(e.dataField == "BasketCol")||(e.dataField == "PeriodCodeCol")||(e.dataField == "TypeCol"))
    {
        e.preventDefault();
        return;
    }
}

private function filterResults():void
{
    fill();
}

private function fillPeriods():void 
{
	CursorManager.setBusyCursor();
	
	var parameters:* =
		{
			"orderField": "ListOrder",
			"orderDirection": "ASC", 
			"filter": "Visible",
			"filter_field": "Visible"
		}
	doRequest("FindPeriods", parameters, fillPeriodHandler);
}

private function fillPeriodHandler(e:Object):void
{
	if (e.isError)
	{
		Alert.show("Error: " + e.data.error);
	} 
	else
	{
		arrPeriodPop.removeAll();
		for each(var row:XML in e.data.row) 
		{
			var temp:* = {};
			for (var key:String in periodsObject) 
			{
				temp[key + 'Col'] = row[key];
			}
			
			arrPeriodPop.addItem(temp);
		}
		
		CursorManager.removeBusyCursor();
	}    
}


protected function objectPeriodEntryPop_closeHandler(event:DropdownEvent):void
{
	thePeriod = arrPeriodPop[objectPeriodEntryPop.selectedIndex].SubPeriodCol;
}

private function editCellEnd(e:DataGridEvent):void
{
    var dsRowIndex:int = e.rowIndex;
    var dsFieldName:String = e.dataField;
    var dsColumnIndex:Number = e.columnIndex;

    var vo:* = dataArr[dsRowIndex];
    
    var col:DataGridColumn = dataGrid.columns[dsColumnIndex];
    var newvalue:String = dataGrid.itemEditorInstance[col.editorDataField];

    trace("a:" + dsRowIndex + ", " + dsFieldName + ", " + dsColumnIndex);

    var parameters:* =
    {
        "Id": vo.IdCol,        "Name": vo.NameCol,        "Field": vo.FieldCol,        "Square": vo.SquareCol,        "Locus": vo.LocusCol,        "Basket": vo.BasketCol,        "PeriodCode": vo.PeriodCodeCol,        "Period": vo.PeriodCol,        "Quantity": vo.QuantityCol,        "Saved": vo.SavedCol,        "Disposition": vo.DispositionCol,        "Description": vo.DescriptionCol,        "Type": vo.TypeCol,        "CreateDate": vo.CreateDateCol,        "Comments": vo.CommentsCol    }

	parameters[dsFieldName.substr(0,dsFieldName.length-3)] = newvalue;
    doRequest("Update", parameters, saveItemHandler);    
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


private function getForms():void 
{
    var desc:Boolean = false;
    var orderField:String = '';
    
    if(!isNaN(orderColumn))
    {
        var col:DataGridColumn = formsGrid.columns[orderColumn];
        desc = col.sortDescending;
        orderField = col.dataField.substr(0,col.dataField.length-3);
    } else {
    	orderField = 'Vessels';
    	desc = false;
    }

    formsGrid.enabled = false;
    CursorManager.setBusyCursor();

    var parameters:* =
    {
        "orderField": "Vessel",
        "orderDirection": (desc) ? "DESC" : "ASC",
        "filter": objectTypeEntryPop.selectedItem.label
    }
    doRequest("getForms", parameters, formsHandler);
}


private function formsHandler(e:Object):void
{
    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    } 
    else
    {
        formsDataArr.removeAll();
        for each(var row:XML in e.data.row) 
        {
            var temp:* = {};
            for (var key:String in formsFields) 
            {
                temp[key + 'Col'] = row[key];
            }

            formsDataArr.addItem(temp);
        }

        CursorManager.removeBusyCursor();
        formsGrid.enabled = true;
    }    
}

private function setVessel():void
{
	DescriptionInput.text = formsGrid.selectedItem.VesselCol;
}

private function setOrder(event:DataGridEvent):void 
{
    orderColumn = event.columnIndex;
    var col:DataGridColumn = dataGrid.columns[orderColumn];
    col.sortDescending = !col.sortDescending;
    
    event.preventDefault();
    fill();
}


private function entryVal():Boolean
{
	var inValidArray:Array = 
		 Validator.validateAll([basketVal]);
		 
		 if(inValidArray.length == 0)
		 {
		 	return true;
		 }
		 else
		 {
		 	Alert.show("Data Entry Error, Enter a valid Basket number.", "Invalid Data Entry");
		 	return false;
		 }
}

 
private function insertItem():void {
	
    var parameters:* =
    {
       "method": "Insert",	 "Field": FieldInput.text,	"Square": SquareInput.text,		"Locus": LocusInput.text, "Basket": BasketInput.text,		"Quantity": QuantityInput.text,		
		   "Disposition": dispositionPop.selectedItem.label, "Description": DescriptionInput.text, "PeriodCode": objectPeriodEntryPop.selectedItem.CodeCol,	"Period":thePeriod,	"Type": objectTypeEntryPop.selectedItem.label,		"CreateDate": theBasketDate,		"Comments": CommentsInput.text };
    doRequest("Insert", parameters, insertItemHandler);
}

private function insertItemHandler(e:Object):void
{
    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    }
    else
    {
        findBasketContents();
    }     
}

/** 
 * general utility function for refreshing the data 
 * gets the filtering and ordering, then dispatches a new server call
 *
 */
 private function searchObjects():void 
{
    var desc:Boolean = false;
    var orderField:String = '';
    
    if(!isNaN(orderColumn))
    {
        var col:DataGridColumn = dataGrid.columns[orderColumn];
        desc = col.sortDescending;
		//remove the 'Col' particle
        orderField = col.dataField.substr(0,col.dataField.length-3);
    }

    dataGrid.enabled = false;
    CursorManager.setBusyCursor();

    var parameters:* =
    {
        "orderField": orderField,
        "orderDirection": (desc) ? "DESC" : "ASC",
        "filter": searchTerm.text
    }
	/**
	 * execute the server "select" command
	 */
    doRequest("searchObjects", parameters, fillHandler);
}

private function fill():void 
{
    var desc:Boolean = false;
    var orderField:String = '';
    if(!isNaN(orderColumn))
    {
        var col:DataGridColumn = dataGrid.columns[orderColumn];
        desc = col.sortDescending;
		//remove the 'Col' particle
        orderField = col.dataField.substr(0,col.dataField.length-3);
    }
    dataGrid.enabled = false;
    CursorManager.setBusyCursor();
    var parameters:* =
    {
        "orderField": orderField,
        "orderDirection": (desc) ? "DESC" : "ASC",
        "filter": objectTypePop.selectedItem.label,
        "year": yearPop.selectedItem.label
    }
    doRequest("FindAll", parameters, fillHandler);
}

private function fillHandler(e:Object):void
{
	if (e.isError)
	{
		Alert.show("Error: " + e.data.error);
	} 
	else
	{
		dataArr.removeAll();
		for each(var row:XML in e.data.row) 
		{
			var temp:* = {};
			for (var key:String in fields) 
			{
				temp[key + 'Col'] = row[key];
			}
			
			dataArr.addItem(temp);
		}
		CursorManager.removeBusyCursor();
		dataGrid.enabled = true;
	}    
}
private function insertButton():void{
	insertItem();
	objectTypeEntryPop.selectedIndex = 0;
	objectPeriodEntryPop.selectedIndex = 0;
	QuantityInput.text = "";
}

private function formDoubleClick():void{
	searchTerm.text = formsGrid.selectedItem.VesselCol;
	searchObjects();
}
private function searchBasketButton():void{
	findBasket();
	findBasketContents();
}
private function findBasketContents():void 
{
	var desc:Boolean = false;
	var orderField:String = '';
	if(!isNaN(orderColumn))
	{
		var col:DataGridColumn = dataGrid.columns[orderColumn];
		desc = col.sortDescending;
		orderField = col.dataField.substr(0,col.dataField.length-3);
	}
	dataGrid.enabled = false;
	CursorManager.setBusyCursor();
	var parameters:* =
		{
			"orderField": orderField,
			"orderDirection": (desc) ? "DESC" : "ASC",
			"filter": basketInput.text
		}
	doRequest("findBasketContents", parameters, fillHandler);
}

private function findBasket():void {
	entryGood = entryVal();
	if (entryGood == true )
	{
    var desc:Boolean = false;
    var orderField:String = '';
    
    if(!isNaN(orderColumn))
    {
        var col:DataGridColumn = dataGrid.columns[orderColumn];
        desc = col.sortDescending;
        orderField = col.dataField.substr(0,col.dataField.length-3);
    }
    CursorManager.setBusyCursor();
    var parameters:* =
    {
        "orderField": orderField,
        "orderDirection": (desc) ? "DESC" : "ASC",
        "filter": basketInput.text
    }
	/**
	 * execute the server "select" command
	 */
    doRequest("findBasket", parameters, findBasketHandler);
}}

private function findBasketHandler(e:Object):void {
	
	 if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    } 
    else
    {
        basketArr.removeAll();
        for each(var row:XML in e.data.row) 
        {
            var temp:* = {};
            for (var key:String in fields) 
            {
                temp[key + 'Col'] = row[key];
            }

            basketArr.addItem(temp);
        }
       CursorManager.removeBusyCursor();
if(basketArr.length >0){
	FieldInput.text = basketArr[0].FieldCol;
	SquareInput.text = basketArr[0].SquareCol;
	LocusInput.text = basketArr[0].LocusCol;
	BasketInput.text = basketArr[0].BasketCol;
	theBasketDate = basketArr[0].CreateDateCol;
	CreateDateInput.text = theBasketDate.substring(5,7) + "/" + theBasketDate.substring(8,10) + "/" + theBasketDate.substring(2,4);
	}else{
		Alert.show("No Basket Found");
	}
   }    
}

private function deleteItem():void {
    
    if (dataGrid.selectedItem)
    {
        Alert.show("Are you sure you want to delete the selected record?",
        "Confirm Delete", 3, this, deleteClickHandler);
    }
    
}


private function deleteClickHandler(event:CloseEvent):void
{
    if (event.detail == Alert.YES) 
    {
        var vo:* = dataGrid.selectedItem;

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
            dataGrid.destroyItemEditor();
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
        for (var index:Number = 0; index < dataArr.length; index++)
        {
            if (dataArr[index].IdCol == Id)
            {
                dataArr.removeItemAt(index);
                break;
            }
        }
    }     
}

/**
 * deserializes the xml response
 * handles error cases
 *
 * @param e ResultEvent the server response and details about the connection
 */
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

/**
 * result handler for the gateway
 * deserializes the result, and then calls the REAL event handler
 * (set when making a request in the doRequest function)
 *
 * @param e ResultEvent the server response and details about the connection
 */
public function resultHandler(e:ResultEvent):void
{
    var topass:* = deserialize(e.result, e);
    e.token.handler.call(null, topass);
}

/**
 * fault handler for this connection
 *
 * @param e FaultEvent the error object
 */
public function faultHandler(e:FaultEvent):void
{
	var errorMessage:String = "Connection error: " + e.fault.faultString; 
    if (e.fault.faultDetail) 
    { 
        errorMessage += "\n\nAdditional detail: " + e.fault.faultDetail; 
    } 
    Alert.show(errorMessage);
}

/**
 * makes a request to the server using the gateway instance
 *
 * @param method_name String the method name used in the server dispathcer
 * @param parameters Object name value pairs for sending in post
 * @param callback Function function to be called when the call completes
 */
public function doRequest(method_name:String, parameters:Object, callback:Function):void
{
    // add the method to the parameters list
    parameters['method'] = method_name;

    gateway.request = parameters;

    var call:AsyncToken = gateway.send();
    call.request_params = gateway.request;

    call.handler = callback;
}

