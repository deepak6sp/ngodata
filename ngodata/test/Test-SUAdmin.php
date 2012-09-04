<?php
require_once ('NGODataDB.class.php');
require_once ('MetadataDB.class.php');
require_once ('NGODataSession.class.php');
require_once ('Metadata.class.php');
//require_once ('NGOData.class.php');

new NGODataSession();
session_start();

//logFileTest();
//testDBConnection();
//testDBSelect();
//testErrorNotificationBySESSION();
//testNGODataDBGetAuthDetails();
//testInsertMetadata();
//testDisplayMetadata();
testGetAuthData();



// tests writing to logfile
function logFileTest() {
	print 'Testing writing to logfile...<br />';
	$message = "------ Testing ------\r\n";
	$message .= "Date: ".date('d m y H:i:s')."\r\n";
	$message .= "Message: This is a test\r\n";
		
	$fp = fopen('NGOData.log', 'a');

	if (flock($fp, LOCK_EX)) { // do an exclusive lock
		fwrite($fp, $message);
		print 'Wrote message: '.$message.'<br />';
		flock($fp, LOCK_UN); // release the lock
		print 'released lock...<br />';
	} else {
		// cannot get lock
		print 'Cannot get lock on file!';
	}

	fclose($fp);
}


function testDBConnection() {
	print 'Testing the database connection:<br />';
	$db = MetadataDB::getInstance();
	
	print_r($db);
}



function testDBSelect() {
	//print 'Testing the select function in metadataDB: <br />';
	$db = MetadataDB::getInstance();
	// error in sql...
	$sql = 'select varable from metadata';
	$result = $db->select($sql);
	if (!$result) {
	} else {
		//print 'All OK!';
	}
}

function testDBSelectCorrect() {
	//print 'Testing the select function in metadataDB: <br />';
	$db = MetadataDB::getInstance();
	// error in sql...
	$sql = 'select variable from metadata';
	$result = $db->select($sql);
	if (!$result) {
	} else {
		//print 'All OK!';
	}
}


function testErrorNotificationBySESSION() {
	$html = displayPageStart();
	$html .= 'Testing Error Notification By SESSION...: <br />';
	testDBSelect();
	//print_r($_SESSION);
	$html = printMessage();
	/*
	if (isset($_SESSION['message_type'])) {
		$html .= "<div class=".$_SESSION['message_type'].">";
		if (isset($_SESSION['message_text'])) {
			$html .= $_SESSION['message_text'];
			$html .= "</div>";
		}	
	}
	*/
	
	//if(isset($_SESSION['message_text']))
	
	
	$html .= "</div></body>";
	//if(isset($_SESSION['error'])) unset($_SESSION['error']);
	//if(isset($_SESSION['error_message'])) unset($_SESSION['error_message']);
	//if(isset($_SESSION['message_type'])) unset($_SESSION['message_type']);
	//if(isset($_SESSION['message_text'])) unset($_SESSION['message_text']);
	print $html;
}

function testNGODataDBGetAuthDetails() {
	$html = displayPageStart();
	$d = NGODataDB::getInstance();
	
	$data = $d->getDatabaseAuthDetails();
	foreach($data as $key=>$value) {
		$html .= 'Metadatum: '.$value.'<p>';
	}
	$html .= '</div></body></html>';
	print $html;
}


function testInsertMetadata() {
	$sql = 'insert into metadata (variable, value) values (\'test3\', \'test3 text\')';
	$md = MetadataDB::getInstance();
	$result = $md->insert($sql);
	//$html = displayPageStart();
	$html .= printMessage();
	$html .= '</div></body></html>';
	print $html;
}

function testDisplayMetadata() {
	$md = new Metadata();
	print $md->displayMetadataInTable();
}

function testGetAuthData() {
	$db = NGODataDB::getInstance();
	$html = displayPageStart();
	$html .= printMessage();
	//print '<p>';
	//print_r($db);
	//print '</p><p>';
	//print_r($_SESSION);
	//print '</p>';
	$html .= '</div></body></html>';
	print $html;
}


/**
	 * Function: displayPageStart(string $titleText, string $extraOptions)
	 * @author Steve Cooke
	 * @version
	 * Date: June 2009
	 * @param string $titleText 
	 * @param string $extraOptions 
	 */
	function displayPageStart() {
		
		//public function displayPageStart($titleText, $options) {
		
		$html = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"";
		$html .= "\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";

		$html .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
		$html .= "<head>";
		$html .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />";
		// get title from the entity?...
		$html .= "<title>NGOData hosted data management system</title>";
		
		$html .= '<link href="oneColFixCtr.css" rel="stylesheet" type="text/css" />';
		//$html .= '<link href="superfish.css" rel="stylesheet" type="text/css" />';
		$html .= '<link rel="stylesheet" href="menu_style.css" type="text/css" />';
		
		$html .= "</head>";

		$html .= "<body>";
		$html .= "<div class=\"container\">";
		$html .= "<div class=header>";
		$html .= "<!-- Logo Goes here -->";
		//$html .= "Logo";
		$html .= '<img src="NGODataLogo-noBack.png" alt="NGOData logo" />';
		$html .= "<!-- End Logo -->";
		$html .= "</div>";
		
		
		// the menu items
		$html .= '<div class="menu">';
		$html .= '<ul>';// class="sf-menu">';
		$html .= "<!-- Add menu items by adding <li> tags -->";
      
		$html .= "<li><a href=\"#\">Home</a> ";
		$html .= "<!-- Start Sub Menu Items -->";
		//$html .= '<ul>';// class=\"subnav\">";
		//$html .= '<li><a href="#">Sub Nav</a></li>';
		//$html .= '<li><a href="#\>Sub Nav</a></li>';
		//$html .= "</ul>";
		$html .= "<!-- End Sub MEnu Item --> ";
		$html .= "</li>";
		
		
		$html .= "<li><a href=\"#\">About</a>";
		$html .= '<ul>'; //class=\"subnav\">";
		$html .= "<li><a href=\"#\">Sub Nav</a></li>";
		$html .= "<li><a href=\"#\">Sub Nav</a></li>";
		$html .= "</ul>";
		$html .= "</li>";
		
		
		$html .= "<li><a href=\"#\">Services</a>";
		$html .= "<ul class=\"subnav\">";
		$html .= "<li><a href=\"#\">Sub Nav</a></li>";
		$html .= "<li><a href=\"#\">Sub Nav</a></li>";
		$html .= "</ul>";
		$html .= "</li>";
		
		
		$html .= "</ul>";
		$html .= "</div>";

		//return $html;

		return $html;
	}
	
function printMessage() {
	$html = '';
	if (isset($_SESSION['message_type'])) {
		$html .= "<div class=".$_SESSION['message_type'].">";
		if (isset($_SESSION['message_text'])) {
			$html .= $_SESSION['message_text'];
			$html .= "</div>";
		}	
	}
	if(isset($_SESSION['message_type'])) unset($_SESSION['message_type']);
	if(isset($_SESSION['message_text'])) unset($_SESSION['message_text']);
	return $html;
}

?>