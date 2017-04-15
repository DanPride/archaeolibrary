import flash.display.Bitmap;
import flash.net.FileReference;
import flash.utils.ByteArray;

import mx.collections.ArrayCollection;
import mx.controls.Alert;
import mx.controls.Image;
import mx.events.DropdownEvent;
import mx.graphics.ImageSnapshot;
import mx.graphics.codec.JPEGEncoder;
import mx.graphics.codec.PNGEncoder;
import mx.utils.Base64Encoder;
public var theImageByteArray:ByteArray;
[Bindable]protected var cameraNames:Array;
[Bindable]public var bitmap:Bitmap;
[Bindable]public var bitmapPath:String;
//[Bindable]protected var jpegSnapshot:im
protected var theimage:Image;
private var theCameraIndex:int = 1;

private function videoDisplay_listCameras():void 
{
	var cams:Array = new Array("Select Camera");
	var camsList:Array = Camera.names;
	cameraNames = cams.concat(camsList);
}

protected function cameraSelect_closeHandler(event:DropdownEvent):void
{
	theCameraIndex = cameraSelect.selectedIndex;
	var camera:Camera = Camera.getCamera(Camera.names[theCameraIndex]);
	if (camera) {
		videoDisplay.attachCamera(camera);
	} 
}
private function videoDisplay_creationComplete():void 
{
	theCameraIndex = cameraSelect.selectedIndex;
	var camera:Camera = Camera.getCamera(Camera.names[theCameraIndex]);
	if (camera) {
		videoDisplay.attachCamera(camera);
	} 
}

protected function onLoad():void {}//Security.loadPolicyFile("http://88.208.202.35/crossdomain.xml");
public var theByteString:String;
public function takeSnapshot():void
{
	var snapshot:BitmapData = ImageSnapshot.captureBitmapData(videoDisplay);
	var JpgEnc:JPEGEncoder = new JPEGEncoder(100);
	theImageByteArray = JpgEnc.encode(snapshot);
	var BaseE:Base64Encoder = new Base64Encoder();
	BaseE.encodeBytes(theImageByteArray);
	theByteString = BaseE.toString();
	bitmap= new Bitmap(snapshot);
	img.source=bitmap;
	//var jpegSnapshot:ImageSnapshot = ImageSnapshot.captureImage(img, 0, jPEGEncoder);
}

protected function objectGrid_clickHandler():void
{
	var theClickName:String;
	img.visible = true;
	theClickName = objectGrid.selectedItem.NameCol;
	theFolder = theClickName.substr(0,6);
	theFile = objectGrid.selectedItem.FileCol;
	bitmapPath = "../images/" + theFolder + "/000Tmb/" + theClickName + ".JPG";
}


private function saveImageToFileSystem():void
{
	var jPEGEncoder:JPEGEncoder = new JPEGEncoder(500);
	var imageSnapshot:ImageSnapshot = ImageSnapshot.captureImage(img, 0, jPEGEncoder);
	var fileReference:FileReference = new FileReference();
//	fileReference.save(imageSnapshot.data, "img123.jpg");
}
