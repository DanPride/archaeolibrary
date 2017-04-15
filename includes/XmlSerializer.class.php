<?php

$thePath = strlen(dirname(__FILE__)); //length of path
$st = strlen(strrchr(dirname(__FILE__),"/")); //length last folder
$theRoot =  substr(dirname(__FILE__), 0, $thePath - $st) . "/includes/";
define('PEARDIR', $theRoot . 'PEAR/');

/**
 * XmlSerializer uses a PEAR xml parser to generate an xml response. 
 * this takes a php array and generates an xml according to the following rules:
 * - the root tag name is called "response"
 * - if the current value is a hash, generate a tagname with the key value, recurse inside
 * - if the current value is an array, generated tags with the default value "row"
 * 
 */
class XmlSerializer {

	function XmlSerializer() {
		$this->loadPearClasses();
	}

	function loadPearClasses() {
		if (class_exists('XML_Serializer')) {
			return;
		}
		$deps = array(
			'PEAR' => 'PEAR.php', 
			'XML_Parser' => 'XML/Parser.php', 
			'XML_Parser_Simple' => 'XML/Parser/Simple.php', 
			'XML_RPC' => 'XML/RPC.php', 
			'XML_Util' => 'XML/Util.php', 
			'XML_RPC_Dump' => 'XML/RPC/Dump.php', 
			'XML_RPC_Server' => 'XML/RPC/Server.php', 
			'XML_Serializer' => 'XML/Serializer.php', 
			'XML_Unserializer' => 'XML/Unserializer.php' 
		);
		
		foreach ($deps as $k => $v) {
			if (!class_exists($k)) {
				require_once(PEARDIR . $v);
			}
		}
	}

	function serialize(& $obj) {
		$serializer_options = array (
		   'addDecl' => TRUE,
		   'encoding' => 'ISO-8859-1',
		   'indent' => '  ',
		   'defaultTagName' => 'row', 
		   'rootName' => 'response'
		); 
		$serializer = new XML_Serializer($serializer_options); 

		// Serialize the data structure
		$status = $serializer->serialize($obj);

		// Check whether serialization worked
		if (PEAR::isError($status)) {
		   die($status->getMessage());
		}
		// Display the XML document
		header('Content-type: text/xml');
		echo $serializer->getSerializedData();
	}
}

?>
