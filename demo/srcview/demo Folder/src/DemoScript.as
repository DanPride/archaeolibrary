
import mx.collections.ArrayCollection;
import mx.controls.Alert;
import mx.controls.dataGridClasses.DataGridColumn;
import mx.controls.dataGridClasses.DataGridItemRenderer;
import mx.events.CloseEvent;
import mx.events.DataGridEvent;
import mx.events.FlexEvent;
import mx.managers.CursorManager;
import mx.rpc.AsyncToken;
import mx.rpc.events.FaultEvent;
import mx.rpc.events.ResultEvent;
import mx.rpc.http.HTTPService;
import mx.utils.ObjectUtil;
import mx.validators.Validator;
//include the constant definition of the server endpoint URL
public const ENDPOINT_URL:String = "php/Demo35.php";
private var gateway:HTTPService = new HTTPService();
private var entryGood:Boolean;

private var orderColumn:Number;
private function initApp():void 
{
	gateway.url = ENDPOINT_URL;
	gateway.method = "POST";
	gateway.useProxy = false;
	gateway.resultFormat = "e4x";
	gateway.addEventListener(ResultEvent.RESULT, resultHandler);
	gateway.addEventListener(FaultEvent.FAULT, faultHandler)
	confirm_Login();
	fillUsers();
    visitor();
}

//*****ButtonsAndPops
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

