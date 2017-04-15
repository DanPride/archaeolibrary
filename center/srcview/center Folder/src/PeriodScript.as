


//private var orderColumn:Number;
private var periodsObject:Object = { 'Id':Number, 'ListOrder':Number, 'Visible':String, 'Period':String, 'Code':String, 'SubPeriod':String};
         
 private function closePeriodPop(event:Event):void 
 {
	 //thePeriodPopVal = ComboBox(event.target).selectedItem.label;
	 //fillPeriods();
 }    
            
private function filterResults():void
{
	fillPeriods();
}

/*private function setOrder(event:DataGridEvent):void 
{
   // orderColumn = event.columnIndex;
    var col:DataGridColumn = dataGrid.columns[orderColumn];
    col.sortDescending = !col.sortDescending;
    
    event.preventDefault();
    fill();
}
*/

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
    doRequest("findPeriods", parameters, fillPeriodHandler);
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
   //     dataGrid.enabled = true;
    }    
}


