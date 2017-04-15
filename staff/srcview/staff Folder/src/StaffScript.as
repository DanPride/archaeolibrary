public const ENDPOINT_URL:String = "php/Staff.php";
import mx.collections.ArrayCollection;
import mx.controls.Alert;
import mx.controls.DateField;
import mx.controls.dataGridClasses.DataGridColumn;
import mx.controls.dataGridClasses.DataGridListData;
import mx.core.ClassFactory;
import mx.events.CloseEvent;
import mx.events.DropdownEvent;
import mx.events.DataGridEvent;
import mx.managers.CursorManager;
import mx.rpc.AsyncToken;
import mx.rpc.events.FaultEvent;
import mx.rpc.events.ResultEvent;
import mx.rpc.http.HTTPService;
private var theFocus:*;
private var gateway:HTTPService = new HTTPService();
[Bindable]
public var dataArr:ArrayCollection = new ArrayCollection();
		
[Bindable]public var folderArr:ArrayCollection = new ArrayCollection();
private var orderColumn:Number;
public var theIdNumber:Number;
public var theIdString:String;
[Bindable]public var passSetMessage:String;
[Bindable]private var imageEdit:String;
private var selectedFolder:String;
private var fields:Object = {'Id': Number ,'DFC':Date, 'DLC':Date, 'User':String, 'Status':String, 'Position':String, 'Name':String, 'Password':String, 'PermsAdmin':String, 'PermsMod':String, 'PermsDelete':String, 'PermsAdd':String};
public var dsRowIndexGlobal:int;
public var dsColIndexGlobal:int;
[Bindable]public var permList:ArrayCollection = new ArrayCollection(
	[ {label:"", data:1}, 
		{label:"Granted", data:2}, 
		{label:"Denied", data:3} ]);

[Bindable]public var positionList:ArrayCollection = new ArrayCollection(
	[ {label:"", data:1}, 
		{label:"Director", data:2}, 
		{label:"Field Archaeologist", data:3}, 
		{label:"Yard Supervisor", data:4}, 
		{label:"Square Supervisor", data:5}, 
		{label:"Square Assistant", data:6}, 
		{label:"Digger", data:7}, 
		{label:"Sys Admin", data:8}, 
		{label:"Developer ", data:9} ]);

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
 
///*****Buttons and Pops
protected function buttonHome_clickHandler(event:MouseEvent):void
{
	navigateToURL(new URLRequest('../demo/'), '_self');
}
protected function sourceButton_clickHandler(event:MouseEvent):void
{
	navigateToURL(new URLRequest('srcview/index.html'), '_blank')
}

protected function insructionsButton_clickHandler(event:MouseEvent):void
{
	applicationScreens.selectedChild = Instructions;
}
protected function homeButton_clickHandler(event:MouseEvent):void
{
	applicationScreens.selectedChild = home;
}
public function goToHome():void
{
	applicationScreens.selectedChild = home;
}
public function goToUpdate():void
{
	applicationScreens.selectedChild = update;
}
public function goToInstructions():void
{
	applicationScreens.selectedChild = Instructions;
}

protected function cancelSetPassButton_clickHandler(event:MouseEvent):void
{
	applicationScreens.selectedChild = home;
}

public function setPassView(theId:String):void{
	theIdString = theId;
	theIdNumber = Number(theId);
	if(theIdNumber < 3){
		Alert.show("System Passwords are set in programming");
	} else if (theIdNumber == 3) {
		Alert.show("The Director Password is set in the Setup File");
	} else if (theIdNumber == 4) {
		Alert.show("The View_Only User provides public access in line with U.S.A. National Science Foundation transparency guidelines for funding. It requires no Password. However View_Only can be made inactive at the Directors indescretion.");
	} else {
		applicationScreens.selectedChild = setPassword;
		passSetMessage = "Set Password for " + dataGrid.selectedItem.NameCol;
	}
}

public function setPassButton_clickHandler(event:MouseEvent):void
{
 if(PassInput1.text == PassInput2.text){
	 SetPassword();
 }else {
	 Alert.show("The Passwords entered are different. They must be Identical including Capitolization.");
 }
}

private function SetPassword():void {
	var parameters:* =
		{
			"method": "SetPassword",	 "Id": theIdString,   "Password": PassInput1.text };
	doRequest("SetPassword", parameters, insertItemHandler);
}

protected function setDirectorButton_clickHandler(event:MouseEvent):void{
	Alert.show("asdf");
	var parameters:* =
		{};
	doRequest("Set_DirectorPassword", parameters, insertItemHandler);
}
private function insertItem():void {

	var parameters:* =
		{
			"method": "Insert",	"Status": StatusInput.text, "Position": Position.selectedItem.label, "Name": NameInput.text , "Password": PasswordInput.text   ,		"PermsAdmin": AdminInput.selectedItem.label  ,		"PermsAdd": AddInput.selectedItem.label,		"PermsMod": ModInput.selectedItem.label ,		"PermsDelete": DeleteInput.selectedItem.label };
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

protected function filterTxt_closeHandler(event:DropdownEvent):void
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
				"filter":filterTxt.selectedLabel,
				"orderField": orderField,
				"orderDirection": (desc) ? "DESC" : "ASC"
		}
	doRequest("FindAll", parameters, fillHandler);
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
			"filter":filterTxt.selectedLabel,
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


private function editCellHandler(e:DataGridEvent):void
{
    if((e.dataField == "IdCol"))
    {
        e.preventDefault();
        return;
     
    } else{
		dsRowIndexGlobal =e.rowIndex;
		dsColIndexGlobal =e.columnIndex + 1;
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
        "Id": vo.IdCol,   "Name": vo.NameCol,   "Status": vo.StatusCol,      "Position": vo.PositionCol,        "Password": vo.PasswordCol,        "PermsAdmin": vo.PermsAdminCol,        "PermsAdd": vo.PermsAddCol,        "PermsMod": vo.PermsModCol,        "PermsDelete": vo.PermsDeleteCol}

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
//		
//		dataGrid.enabled = false;
//		var theRow:Number = e.data[0].row.RowNum[0];
//		var theCol:Number = e.data[0].row.ColNum[0];
//		dataGrid.enabled = true;
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

private function filterResults():void
{
	fill();
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
	if(dataGrid.selectedItem.IdCol <4){
		Alert.show("System, Director and View_Only Users can not be deleted but they can be made inactive by the Director. Inactive Users to not appear in the user list. Making View_Only user inactive requires all users to have a Password to view records.");
	}
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

public function doRequest(method_name:String, parameters:Object, callback:Function):void
{
    parameters['method'] = method_name;

    gateway.request = parameters;

    var call:AsyncToken = gateway.send();
    call.request_params = gateway.request;

    call.handler = callback;
}

  public function resultHandlerPops(e:ResultEvent):void
{
     var topass:* = e.result;
    e.token.handler.call(null, topass); 
} 


