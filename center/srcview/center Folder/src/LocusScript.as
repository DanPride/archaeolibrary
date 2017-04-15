// ActionScript file


[Bindable]public var locusId:String;
[Bindable]public var locusDFC:String;
[Bindable]public var locusDLC:String;
[Bindable]public var locusUser:String;
[Bindable]public var locusType:String;
[Bindable]public var locusName:String;

[Bindable]public var locusDataArr:ArrayCollection = new ArrayCollection();
private var locusFields:Object = { 'Id':Number, 'Name':String, 'DFC':String, 'DLC':String, 'User':String, 'Locus':String };

private function allLocus():void
{
	filterTxt.text = "";
    searchDef.text = "Locus";
	fillLocus();
}

private function editLocus():void {
	home.selectedChild = enterLocus;
	locusId = locusGrid.selectedItem.IdCol;
	var DFCFormat:String = locusGrid.selectedItem.DFCCol;
	locusDFC = DFCFormat.substring(5,7) + "/" + DFCFormat.substring(8,10) + "/" + DFCFormat.substring(2,4);
	var DLCFormat:String = locusGrid.selectedItem.DLCCol;
	locusDLC = DLCFormat.substring(5,7) + "/" + DLCFormat.substring(8,10) + "/" + DLCFormat.substring(2,4);
	locusUser = locusGrid.selectedItem.UserCol;
	locusType = locusGrid.selectedItem.TypeCol;
	locusName = locusGrid.selectedItem.NameCol;

}

private function newLocus():void{
	//home.selectedChild = enterLocus;
	navigateToURL(new URLRequest('../checkin/'), '_blank')
}
private function fillLocus():void 
{   
    locusGrid.enabled = false;
    CursorManager.setBusyCursor();
    var parameters:* =
    {
        "orderField": "Locus",
        "orderDirection": "ASC", 
        "filter": filterTxt.text,
        "searchDef": searchDef.text
    }  
    doRequest("findLocus", parameters, fillLocusHandler);
}

private function fillLocusHandler(e:Object):void
	{
	    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    } 
    else
    {
        locusDataArr.removeAll();
        for each(var row:XML in e.data.row) 
        {
            var temp:* = {};
            for (var key:String in locusFields) 
            {
                temp[key + 'Col'] = row[key];
            }

            locusDataArr.addItem(temp);
        }

        CursorManager.removeBusyCursor();
        locusGrid.enabled = true;
    }    
}

private function selectLocus():void
{	
	 selectedNum = locusGrid.selectedItem.LocusCol;
	 filterTxt.text = selectedNum;
	 searchDef.text = "Locus"
	 currentState = '';
	 fillLoca();
	 fillBaskets();
	 fillObjects();
	 fillPhotos();	 
	 CursorManager.removeBusyCursor();
}