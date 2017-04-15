// PhotoScript file

[Bindable]
public var photosDataArr:ArrayCollection = new ArrayCollection();
private var photosFields:Object = { 'Id':Number, 'Category':String, 'Thumb':Number, 'Name':String, 'Field':String, 'Square':String, 'Locus':String, 'Basket':String, 'Object':String,'Description':String, 'FolderName':String, 'FileName':String, 'Comments':String, 'CreateDate':Date};
[Bindable]
public var selectedPhoto:String;
[Bindable]
public var selectedPhotoDisplay:String;
[Bindable]
public var photoName:String;
[Bindable]
public var photoField:String;
[Bindable]
public var photoSquare:String;
[Bindable]
public var photoLocus:String;
[Bindable]
public var photoBasket:String;
[Bindable]
public var photoDescription:String;
[Bindable]
public var photoComments:String;
[Bindable]
public var photoCategory:String;
[Bindable]
public var photoCreateDate:String;
[Bindable]
public var photoDisplayNumArr:ArrayCollection = new ArrayCollection([ {label:"25"}, {label:"50"}, {label:"100"}, {label:"200"}, {label:"All"} ]);
[Bindable]
public var arrPhotoDef:ArrayCollection = new ArrayCollection([ {label:"Formal"}, {label:"Library"},{label:"Shards"},{label:"GPS"},{label:"Originals"}, {label:"Extras"}, {label:"Staff"}, {label:"Inform"}, {label:"All"} ]);

private function closePhotoNum(event:Event):void {

	photoDisplayNumPop.text = ComboBox(event.target).selectedItem.label;
	fillPhotos();
}
private function closePhotoDefPop(event:Event):void {
	
	photoDefPop.text = ComboBox(event.target).selectedItem.label;
	if(photoDefPop.text == "GPS"){gpsGrid.visible = true} else {gpsGrid.visible = false};
	fillPhotos();
	
}
private function downloadPhoto():void
{
	//imageDownLoad = selectedPhoto;
	selectedPhotoDisplay = selectedPhoto.replace("/000Pic","");
	navigateToURL(new URLRequest(selectedPhotoDisplay), '_blank')
}
private function allPhotos():void
{
	selectedNum = ""
	searchDef.text = "Locus" 
	fillPhotos();
}
private function fillPhotos():void 
{   
 	photosDataGrid.enabled = false; 
    CursorManager.setBusyCursor();
    var parameters:* =
    {
        "orderField": "Name",
        "orderDirection": "ASC", 
        "filter":  filterTxt.text,
        "searchDef": searchDef.text,
		"photoListNum": photoDisplayNumPop.text,
		"photoDefPop": photoDefPop.text
    }
    doRequest("findPhotos", parameters, fillPhotosHandler);
}

private function fillPhotosHandler(e:Object):void
	{
	    if (e.isError)
    {
        Alert.show("Error: " + e.data.error);
    } 
    else
    {
        photosDataArr.removeAll();
        for each(var row:XML in e.data.row) 
        {
            var temp:* = {};
            for (var key:String in photosFields) 
            {
                temp[key + 'Col'] = row[key];
            }

            photosDataArr.addItem(temp);
        }
		photosDataGrid.selectedIndex = 0;
        CursorManager.removeBusyCursor();
        photosDataGrid.enabled = true;
    }    
}

