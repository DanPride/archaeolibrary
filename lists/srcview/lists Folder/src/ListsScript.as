
include "ListsConfig.as";
import mx.collections.ArrayCollection;
import mx.controls.Alert;
import mx.controls.dataGridClasses.DataGridColumn;
import mx.controls.dataGridClasses.DataGridItemRenderer;
import mx.events.CloseEvent;
import mx.events.DataGridEvent;
import mx.rpc.events.ResultEvent;
import mx.managers.CursorManager;
import mx.utils.ObjectUtil;
import mx.rpc.http.HTTPService;
import mx.rpc.events.FaultEvent;
import mx.rpc.AsyncToken;

private var gateway:HTTPService = new HTTPService();
[Bindable]public var dataArr:ArrayCollection = new ArrayCollection();
private var orderColumn:Number;
private var fields:Object = { 'Id':Number, 'Type':String, 'Vessel':String};
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
	fill();	
	labelName.text = loginName;
}

private function sourceButton_clickHandler(event:MouseEvent):void
{
	navigateToURL(new URLRequest('srcview/index.html'), '_blank')
}
public function goToHome():void
{
	applicationScreens.selectedChild = home;
}
public function goToInstructions():void
{
	applicationScreens.selectedChild = instructions;
}

private function editCellHandler(e:DataGridEvent):void
{
    if(e.dataField == "IdCol")
    {
        e.preventDefault();
        return;
    }
}

private function filterResults():void
{
    fill();
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
        "Id": vo.IdCol,        "Type": vo.TypeCol,        "Vessel": vo.VesselCol    }

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

private function setOrder(event:DataGridEvent):void 
{
    orderColumn = event.columnIndex;
    var col:DataGridColumn = dataGrid.columns[orderColumn];
    col.sortDescending = !col.sortDescending;
    
    event.preventDefault();
    fill();
}


private function insertItem():void {
    var parameters:* =
    {
        "method": "Insert",		"Type": TypeCol.text,		"Vessel": VesselCol.text   };
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
        goToHome();
        fill();
    }     
}

private function fill():void 
{
    /**
     * find the order parameters
     */
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
        "filter": filterTxt.text
    }
	/**
	 * execute the server "select" command
	 */
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


/**
 * Click handler when the user click the "Create" button
 * Load the "Update" canvas.
 */
public function goToUpdate():void
{
	applicationScreens.selectedChild = update;
}
