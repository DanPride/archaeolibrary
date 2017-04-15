
import mx.collections.ArrayCollection;
import mx.controls.Alert;
import mx.controls.dataGridClasses.DataGridColumn;
import mx.controls.dataGridClasses.DataGridItemRenderer;
import mx.events.CloseEvent;
import mx.events.DataGridEvent;
import mx.managers.CursorManager;
import mx.rpc.AsyncToken;
import mx.rpc.events.FaultEvent;
import mx.rpc.events.ResultEvent;
import mx.rpc.http.HTTPService;
import mx.validators.NumberValidator;
import mx.validators.StringValidator;
import mx.validators.Validator;
import mx.managers.CursorManager;
import mx.utils.ObjectUtil;
public const ENDPOINT_URL:String = "php/checkin.php";
private var gateway:HTTPService = new HTTPService();
[Bindable]
public var dataArr:ArrayCollection = new ArrayCollection();
[Bindable]
public var fieldsArr:ArrayCollection = new ArrayCollection();

/*[Bindable]
public var logArr:ArrayCollection = new ArrayCollection();*/
[Bindable]
public var squaresArr:ArrayCollection = new ArrayCollection();
private var selectedField:String;
private var orderColumn:Number;
private var entryGood:Boolean;
private var pottery:Object = { 'Id':Number, 'Name':String, 'Field':String, 'Square':String, 'Locus':String, 'Basket':String, 'Periods':String, 'Context':String, 'Disposition':String, 'CreateDate':Date, 'Status':String};
private var fields:Object = { 'Id':Number, 'Code':String, 'Supervisor':String, 'Open':String};

/*private var fieldsLogin:Object = { 'Id':Number, 'Status':String};*/
private var squares:Object = { 'Id':Number, 'Name':String, 'Field':String, 'Square':String, 'Supervisor':String, 'Open':String};

private function initApp():void 
{
	
	gateway.url = ENDPOINT_URL;
	gateway.method = "POST";
	gateway.useProxy = false;
	gateway.resultFormat = "e4x";
	gateway.addEventListener(ResultEvent.RESULT, resultHandler);
	gateway.addEventListener(FaultEvent.FAULT, faultHandler);
	dataGrid.addEventListener(DataGridEvent.ITEM_EDIT_BEGINNING, editCellHandler);
	dataGrid.addEventListener(DataGridEvent.ITEM_EDIT_END, editCellEnd);
	confirm_Login()
	visitor();
	fill();	
	fillFields();
	labelName.text = loginName;
}
	

//************Buttons Pops and Vals
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
private function sourceButton_clickHandler(event:MouseEvent):void
{
	navigateToURL(new URLRequest('srcview/index.html'), '_blank')
}

private function closeFieldPop(event:Event):void {
	selectedField = fieldPop.selectedLabel;
	fillSquares();
}

private function getDateFormat(item:Object,column:DataGridColumn):String{
	//	return dateFormat.format(item[column.dataField]);
	return item[column.dataField].substring(5,7) + "/" + item[column.dataField].substring(8,10) + "/" + item[column.dataField].substring(2,4);
} 

private function getTimeFormat(item:Object,column:DataGridColumn):String{
	//	return dateFormat.format(item[column.dataField]);
	return item[column.dataField].substring(11,16);
} 

private function entryVal():Boolean
{
	var inValidArray:Array = 
		 Validator.validateAll([LocusVal, BasketVal]);
		 if(inValidArray.length == 0)
		 {
		 	return true;
		 }
		 else
		 {
		 	Alert.show("Data Entry Error", "Invalid Data Entry");
		 	return false;
		 }
}
//***********Cell Edits

private function editCellHandler(e:DataGridEvent):void
{
    if(e.dataField == "IdCol")
    {
        e.preventDefault();
        return;
    }
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
        "Id": vo.IdCol,        "Name": vo.NameCol,        "Field": vo.FieldCol,        "Square": vo.SquareCol,        "Locus": vo.LocusCol,        "Basket": vo.BasketCol,        "Periods": vo.PeriodsCol,        "Context": vo.ContextCol,        "Disposition": vo.DispositionCol,        "CreateDate": vo.CreateDateCol,        "Status": vo.StatusCol    }

	parameters[dsFieldName.substr(0,dsFieldName.length-3)] = newvalue;

    doRequest("Update", parameters, saveItemHandler);    

}

protected function completeCheckinButton_clickHandler(event:MouseEvent):void
{ var parameters:* ={"method": "AssignNumbers"};
	doRequest("AssignNumbers", parameters, checkinHandler);
}

private function filterResults():void
{
	fill();
}

private function checkinHandler(e:Object):void
{
	if (e.isError)
	{
		Alert.show("Error: " + e.data.error);
	}
	else
	{
		Alert.show("Checkin completed");
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


private function setOrder(event:DataGridEvent):void 
{
    orderColumn = event.columnIndex;
    var col:DataGridColumn = dataGrid.columns[orderColumn];
    col.sortDescending = !col.sortDescending;
    
    event.preventDefault();
    fill();
}

private function insertItem():void {	
	entryGood = entryVal();
	if (entryGood == true )
	{
	orderColumn = 0;
    var parameters:* =
    {
        "method": "Insert", "Field": fieldPop.selectedLabel,	"Square": squarePop.selectedLabel, "Locus": LocusInput.text, "Basket": BasketInput.text, "Status": "READ" };
    doRequest("Insert", parameters, insertItemHandler);
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
        fill();

    }     
}

 private function fillFields():void 
{
    var desc:Boolean = false;
    var orderField:String = '';
    var parameters:* =
    {

    }
    doRequest("FindFields", parameters, fillFieldsHandler);
}

private function fillFieldsHandler(e:Object):void
{
	if (e.isError)
	{
		Alert.show("Error: " + e.data.error);
	} 
	else
	{
		fieldsArr.removeAll();
		for each(var row:XML in e.data.row) 
		{
			var temp:* = {};
			for (var key:String in fields) 
			{
				temp[key + 'Col'] = row[key];
			}
			
			fieldsArr.addItem(temp);
		}
	
		CursorManager.removeBusyCursor();
		dataGrid.enabled = true;
	}    
}

 private function fillSquares(): void 
{
    var parameters:* =
    {
        "selectedField": selectedField
    }
    doRequest("FindSquares", parameters, fillSquaresHandler);
}

private function fillSquaresHandler(e:Object):void
{
	if (e.isError)
	{
		Alert.show("Error: " + e.data.error);
	} 
	else
	{
		squaresArr.removeAll();
		for each(var row:XML in e.data.row) 
		{
			var temp:* = {};
			for (var key:String in squares) 
			{
				temp[key + 'Col'] = row[key];
			}
			
			squaresArr.addItem(temp);
		}
		CursorManager.removeBusyCursor();
		dataGrid.enabled = true;
	}    
}

private function fill():void
{
    var desc:Boolean = false;
    var orderField:String = '';
    
    if(!isNaN(orderColumn))
    {
        var col:DataGridColumn = dataGrid.columns[orderColumn];
        desc = col.sortDescending;
        orderField = col.dataField.substr(0,col.dataField.length-3);
    } else {
    	orderField = 'CreateDate';
    	desc = false;
    }

    dataGrid.enabled = false;
    CursorManager.setBusyCursor();

    var parameters:* =
    {
        "orderField": orderField,
        "orderDirection": (desc) ? "DESC" : "ASC"
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
            for (var key:String in pottery) 
            {
                temp[key + 'Col'] = row[key];
            }

            dataArr.addItem(temp);
        }

        CursorManager.removeBusyCursor();
        dataGrid.enabled = true;
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

public function doRequest(method_name:String, parameters:Object, callback:Function):void
{
    // add the method to the parameters list
    parameters['method'] = method_name;

    gateway.request = parameters;

    var call:AsyncToken = gateway.send();
    call.request_params = gateway.request;

    call.handler = callback;
}
