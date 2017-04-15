// ActionScript file

// ObjectScript file
import flash.events.Event;
[Bindable]public var gpsId:String;
[Bindable]public var gpsDFC:String;
[Bindable]public var gpsDLC:String;
[Bindable]public var gpsUser:String;
[Bindable]public var gpsName:String;
[Bindable]public var gpsX:String;
[Bindable]public var gpsY:String;
[Bindable]public var gpsZ:String;
[Bindable]public var gpsField:String;
[Bindable]public var gpsSquare:String;
[Bindable]public var gpsLocus:String;
[Bindable]public var gpsBasket:String;
[Bindable]public var gpsObject:int;
[Bindable]public var gpsDataArr:ArrayCollection = new ArrayCollection();
public var gpsFields:Object = { 'Id':Number, 'DFC':String,'DLC':String,'User':String,'Name':String, 'Field':String, 'Square':String, 'Locus':String, 'Basket':String, 'Object':Number,  'X':String, 'Y':String, 'Z':String };
public var selectedGPS:int;
private function allGPSs():void
{
	selectedNum = ""
	searchDef.text = "Name"
	fillGPS();
}
private function selectGPSs():void
{
	filterTxt.text = gpsName;
	searchDef.text = "Name"
	currentState = '';
	fillPhotos();
	CursorManager.removeBusyCursor();
}
private function newGPS(event:Event):void{	
	home.selectedIndex = 6;	
	gpsId = "0";
	gpsDFC = "";
	gpsDLC = "";
	gpsUser = "";
	gpsName = "";
	gpsField = "";
	gpsSquare = "";
	gpsLocus = "";
	gpsBasket = "";
	gpsX = "";
	gpsY = "";
	gpsZ = "";
		
}
private function editGPS(event:Event):void
{
	selectedGPS = gpsGrid.selectedIndex;
	gpsId = gpsGrid.selectedItem.IdCol;
	var DFCFormat:String = gpsGrid.selectedItem.DFCCol;
	gpsDFC = DFCFormat.substring(5,7) + "/" + DFCFormat.substring(8,10) + "/" + DFCFormat.substring(2,4);
	var DLCFormat:String = gpsGrid.selectedItem.DLCCol;
	gpsDLC = DLCFormat.substring(5,7) + "/" + DLCFormat.substring(8,10) + "/" + DLCFormat.substring(2,4);
	//gpsDFC =gpsGrid.selectedItem.DFCCol;
	//gpsDLC =gpsGrid.selectedItem.DLCCol;
	gpsUser =gpsGrid.selectedItem.UserCol;
	gpsName =gpsGrid.selectedItem.NameCol;
	gpsField =gpsGrid.selectedItem.FieldCol;
	gpsSquare =gpsGrid.selectedItem.SquareCol;
	gpsLocus =gpsGrid.selectedItem.LocusCol;
	gpsBasket =gpsGrid.selectedItem.BasketCol;
	gpsObject =gpsGrid.selectedItem.ObjectCol;
	gpsX =gpsGrid.selectedItem.PeriodCol;
	gpsY =gpsGrid.selectedItem.QuantityCol;
	gpsZ =gpsGrid.selectedItem.SavedCol;
	home.selectedIndex = 6;	
}
private function cancelGPS():void{
	home.selectedIndex = 0;
	/*GPSIdCol.text = parentDocument.gpsGrid.selectedItem.IdCol;
	GPSNameCol.text = parentDocument.gpsGrid.selectedItem.NameCol;
	GPSFieldCol.text = parentDocument.gpsGrid.selectedItem.FieldCol;
	gpsquareCol.text = parentDocument.gpsGrid.selectedItem.SquareCol;
	GPSLocusCol.text = parentDocument.gpsGrid.selectedItem.LocusCol;
	GPSBasketCol.text = parentDocument.gpsGrid.selectedItem.BasketCol;
	//GPSPeriodCodeCol.text = parentDocument.gpsGrid.selectedItem.PeriodCodeCol;
	GPSPeriodCol.text = parentDocument.gpsGrid.selectedItem.PeriodCol;
	GPSQuantityCol.text = parentDocument.gpsGrid.selectedItem.QuantityCol;
	gpsavedCol.text = parentDocument.gpsGrid.selectedItem.SavedCol;
	GPSDispositionCol.text = parentDocument.gpsGrid.selectedItem.DispositionCol;
	GPSDescriptionCol.text = parentDocument.gpsGrid.selectedItem.DescriptionCol;
	GPSTypeCol.text = parentDocument.gpsGrid.selectedItem.TypeCol;
	//GPSCreateDateCol.text = parentDocument.gpsGrid.selectedItem.CreateDateCol;
	GPSCommentsCol.text = parentDocument.gpsGrid.selectedItem.CommentsCol;
	*/
}
private function saveGPS():void{
	if(gpsId == "0") {
		insertGPS();
	} else {
		updateGPS();
	}}
private function updateGPS():void
{
	/*var parameters:* =
		{
			"method": "updateGPS",	"Id": GPSIdCol.text, "Field": GPSFieldCol.text,		"Square": GPSSquareCol.text,		"Locus": GPSLocusCol.text,		"Basket": GPSBasketCol.text,		"Object": "",		"X": gpsGrid.selectedItem.X,		"Y": gpsGrid.selectedItem.Y,		"Z": gpsGrid.selectedItem.Z  };
	doRequest("updateGPS", parameters, insertGPSHandler);*/
}
private function insertGPS():void {
	/*var parameters:* =
		{
			"method": "insertGPS",		"Field": GPSFieldCol.text,		"Square": gpsquareCol.text,		"Locus": GPSLocusCol.text,		"Basket": GPSBasketCol.text,		"Object": "",		"X": XCol.text,		"Y": YCol.text,		"Z": ZCol.text    };
	
	/**
	 * execute the server "insert" command
	 
	doRequest("insertGPS", parameters, insertGPSHandler);*/
}

private function insertGPSHandler(e:Object):void
{
	if (e.isError)
	{
		Alert.show("Error: " + e.data.error);
	}
	else
	{
		home.selectedChild = view;
		filterTxt.text = gpsGrid.selectedItem.NameCol.substring(0,12);
		fillGPS();
		//filterTxt.text = gpssGrid.selectedItem.NameCol.substring(0,9);;
		fillPhotos();
	}     
}

private function fillGPS():void 
{   
	gpsGrid.enabled = false; 
	CursorManager.setBusyCursor();
	var parameters:* =
		{
			"orderField": "Name",
			"orderDirection": "ASC", 
			"filter":  filterTxt.text,
			"searchDef": searchDef.text
		}
	
	doRequest("FindGPS", parameters, fillGPSHandler);
}

private function fillGPSHandler(e:Object):void
{
	if (e.isError)
	{
		Alert.show("Error: " + e.data.error);
	} 
	else
	{
		gpsDataArr.removeAll();
		for each(var row:XML in e.data.row) 
		{
			var temp:* = {};
			for (var key:String in gpsFields) 
			{
				temp[key + 'Col'] = row[key];
			}
			
			gpsDataArr.addItem(temp);
		}
		gpsGrid.selectedIndex = selectedGPS;
		CursorManager.removeBusyCursor();
		gpsGrid.enabled = true;
	}    
}


private function deleteGPS():void{
	Alert.show("Are you sure you want to delete the selected record?",
		"Confirm Delete", 3, this, deleteGPSHandler);
}

private function deleteGPSHandler(event:CloseEvent):void{
	
	if (event.detail == Alert.YES) 
	{
		var parameters:* =
			{
				"Id": "X"
			}
		doRequest("deleteGPS", parameters, deleteHandler);
	}
	
}
private function deleteGPSRefresh():void{
	
	
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

