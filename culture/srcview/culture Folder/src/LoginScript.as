// ActionScript file
import mx.events.FlexEvent;
[Bindable]protected var loginId:Number;
[Bindable]protected var loginName:String;
[Bindable]protected var loginStatus:String;
[Bindable]protected var permsAdmin:String;
[Bindable]protected var loginDFC:String;
[Bindable]protected var loginDLC:String;
[Bindable]protected var loginUser:String;
[Bindable]protected var permsAdd:String;
[Bindable]protected var permsMod:String;
[Bindable]protected var permsDelete:String;
[Bindable]protected var digName:String;
[Bindable]protected var permsAdminValue:Boolean;
[Bindable]protected var permsAddValue:Boolean;
[Bindable]protected var permsModValue:Boolean;
[Bindable]protected var permsDeleteValue:Boolean;
[Bindable]protected var theVisible:Boolean = false;
[Bindable]public var usersArr:ArrayCollection = new ArrayCollection();
private var users:Object = { 'Id':Number, 'Name':String};

private function confirm_Login():void
{ var parameters:* ={"method": "flexLogIn"};
	doRequest("Flex_confirmLogin", parameters, logInHandler);
}

private function logInHandler(e:Object):void
{
	if (e.isError){Alert.show("Error: " + e.data.error);
	}else{
		loginStatus = e.data[0].row.Status.toString();
		if(loginStatus != "Success"){
			theVisible = false;
			navigateToURL(new URLRequest('../demo/'), '_self' );
		} else {
			theVisible = true;
			loginId = e.data[0].row.ID.toString();
			loginName = e.data[0].row.Name.toString();
			loginDFC = e.data[0].row.DFC.toString();
			loginDLC = e.data[0].row.DLC.toString();
			permsAdmin = e.data[0].row.PermsAdmin.toString();
			permsAdd = e.data[0].row.PermsAdd.toString();
			permsMod = e.data[0].row.PermsMod.toString();
			permsDelete = e.data[0].row.PermsDelete.toString();
			digName = e.data[0].row.DigName.toString();
			if(permsAdd.indexOf("Granted") >=0) { permsAddValue = true; }else { permsAddValue = false;}
			if(permsMod.indexOf("Granted") >=0) { permsModValue = true; }else { permsModValue = false;}
			if(permsDelete.indexOf("Granted") >=0) { permsDeleteValue = true; }else { permsDeleteValue = false;}
			if(loginId < 4) { permsAdminValue = true; }else { permsAdminValue = false;}
			labelName.text = loginName;
		}}
}

private function fillUsers():void 
{
	var desc:Boolean = false;
	var orderField:String = '';
	var parameters:* =
		{
			
		}
		doRequest("FindUsers", parameters, fillUsersHandler);
}

private function fillUsersHandler(e:Object):void
{
	if (e.isError)
	{
		Alert.show("Error: " + e.data.error);
	} 
	else
	{
		usersArr.removeAll();
		for each(var row:XML in e.data.row) 
		{
			var temp:* = {};
			for (var key:String in users) 
			{
				temp[key + 'Col'] = row[key];
			}
			
			usersArr.addItem(temp);
		}
		
		CursorManager.removeBusyCursor();
		dataGrid.enabled = true;
	}    
}

private function visitor():void{
	var parameters:* = {
		"method": "Visitor", 
		"page": "Culture"
	};
	doRequest("Visitor", parameters, visitorItemHandler);
}
private function visitorItemHandler(e:Object):void{
	if (e.isError)
	{
		Alert.show("Error: " + e.data.error);
	}
	else
	{
	} 
}