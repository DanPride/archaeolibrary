
import flash.events.Event;
import mx.collections.ArrayCollection;
import mx.controls.Alert;
import mx.events.CloseEvent;
import mx.events.FlexEvent;
import mx.managers.CursorManager;
import mx.rpc.AsyncToken;
import mx.rpc.events.FaultEvent;
import mx.rpc.events.ResultEvent;
import mx.rpc.http.HTTPService;
import flash.net.navigateToURL;
import flash.net.URLRequest;
public const ENDPOINT_URL:String = "php/Center.php";
private var gateway:HTTPService = new HTTPService();
private var pops:HTTPService = new HTTPService();

//Begin of Popup Arrays
[Bindable]
public var fieldsArrayCollection:ArrayCollection = new ArrayCollection();
[Bindable]
public var arrSquareStatus:ArrayCollection = new ArrayCollection([ {label:"Open", data:1},  {label:"Closed", data:2}, {label:"List All", data:3} ]);
[Bindable]
 public var arrSearchDef:ArrayCollection = new ArrayCollection([ {label:"Name"}, {label:"Square"}, {label:"Locus"}, {label:"Basket"}, {label:"Description"} ]);
[Bindable]
public var arrSearchType:ArrayCollection = new ArrayCollection([ {label:"All Types"}, {label:"Bones"},{label:"Coins"},  {label:"Ceramic"} , {label:"Culture"}, {label:"Glass"}, {label:"Metal"}, {label:"Mudbrick"}, {label:"Object"}, {label:"Organic"}, {label:"Plaster"}, {label:"Pottery"}, {label:"Shell"}, {label:"Slag"}, {label:"Stone"} ]);
[Bindable]
public var arrPeriodPop:ArrayCollection = new ArrayCollection();

private var fieldsFields:Object = { 'Id':Number, 'Name':String, 'Code':String, 'Supervisor':String, 'Open':String };
private var orderColumn:Number;
public var selectedNum:String;
public var selectedName:String;
public var selectedType:String;  
[Bindable]public var theName:String;
public var selectedField:String;

private function initApp():void 
{  
	gateway.url = ENDPOINT_URL;
	gateway.method = "POST";
	gateway.useProxy = false;
	gateway.resultFormat = "e4x";
	gateway.addEventListener(ResultEvent.RESULT, resultHandler);
	gateway.addEventListener(FaultEvent.FAULT, faultHandler);
	confirm_Login();
	pops.url = ENDPOINT_URL;
	pops.method = "POST";
	pops.useProxy = false;
	pops.addEventListener(ResultEvent.RESULT, resultHandlerPops);
	pops.addEventListener(FaultEvent.FAULT, faultHandler); 
	visitor();
	fillPeriods();
    fillFields(); 
    selectedSquare = 0;
    fillSquares();	
	filterTxt.text  = 'GZA';
    searchDef.text = 'Name';
  	fillPhotos();
	fillGPS();
}

//navigateToURL(new URLRequest('../demo/'))
protected function buttonHome_clickHandler(event:MouseEvent):void
{
	navigateToURL(new URLRequest('../demo/'), '_self');
}

public function goToLibraryGrid():void
{
	home.selectedChild = libraryGrid;
}
public function goToPhotosGrid():void
{
	home.selectedChild = photosGrid;
}
protected function sourceButton_clickHandler(event:MouseEvent):void
{
	navigateToURL(new URLRequest('srcview/index.html'), '_blank')
}

public function goToHome():void
{
	home.selectedChild = view;
}
public function goToInstructions():void
{
	home.selectedChild = instructions;
}

private function getDateFormat(item:Object,column:DataGridColumn):String{
	return item[column.dataField].substring(5,7) + "/" + item[column.dataField].substring(8,10) + "/" + item[column.dataField].substring(2,4);
} 

private function getTimeFormat(item:Object,column:DataGridColumn):String{
	return item[column.dataField].substring(11,16);
} 

 private function fillFields():void 
{

    var desc:Boolean = false;
    var orderField:String = '';
    var parameters:* =
    {

    }
	doRequest("findFields", parameters, fillFieldsHandler);
}


private function fillFieldsHandler(e:Object):void
{
	if (e.isError)
	{
		Alert.show("Error: " + e.data.error);
	} 
	else
	{
		fieldsArrayCollection.removeAll();
		for each(var row:XML in e.data.row) 
		{
			var temp:* = {};
			for (var key:String in fieldsFields) 
			{
				temp[key + 'Col'] = row[key];
			}
			
			fieldsArrayCollection.addItem(temp);
		}
		CursorManager.removeBusyCursor();
	}}


 private function closeFieldPop(event:Event):void {
                     
      
  }
  
private function closeSearchDef(event:Event):void {
        searchDef.text = ComboBox(event.target).selectedItem.label;
		if(searchDef.text == "Description"){
			searchPeriod.text = "All Periods";
			searchType.text = "All Types";
		}
    }

private function closeSearchType(event:Event):void{
	searchPeriod.text = "All Periods";
	fillObjects();
}
private function closeSearchPeriod(event:Event):void{
	searchType.text = "All Types";
	fillObjects();
}

private function buttonLocus():void
{
	 selectedNum = filterTxt.text;
	 if((searchDef.text == "Name")||(searchDef.text == "Square")||(searchDef.text == "Locus")){
	 fillLoca();
	 }
	if((searchDef.text == "Name")||(searchDef.text == "Square")||(searchDef.text == "Locus")||(searchDef.text == "Basket"))
	{
	 fillBaskets();
	 }
	 fillObjects();	 
}




public function deleteHandler(e:*):void
{
    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    }
    else
    {
      home.selectedIndex = 0;
       	fillSquares();
  		fillPhotos();
     
    }     
}

private function goHome():void {
	home.selectedIndex = 0;
}

public function deserialize(obj:*, e:*):*
//deserializes the xml response handles error cases @param e ResultEvent the server response and details about the connection
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
// fault handler for this connection* @param e FaultEvent the error object
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


 public function doPops(method_name:String, parameters:Object, callback:Function):void
{
    parameters['method'] = method_name;
    pops.request = parameters;
    var call:AsyncToken = pops.send();
    call.request_params = pops.request;
    call.handler = callback;
}
 
  public function resultHandlerPops(e:ResultEvent):void
{
	//popsArr = 
     var topass:* = e.result;
    e.token.handler.call(null, topass); 
} 

private function returnTest():String
{
	var thePath:String = "images/test.jpg";
	return thePath ;
}

private function selectType():void //??????????????????????????
{
	currentState = '';
	 var selectedNum:String = locusGrid.selectedItem.NumberCol;
	 filterTxt.text = selectedNum; 
	 fillBaskets();
	 fillObjects();
	 fillPhotos();
	 currentState = '';
	  CursorManager.removeBusyCursor();
}

