<?php

// NGOData helper class - extend other classes from this
/**
 * @package NGOData
 * Class: NGOData
 * @author Steve Cooke <sa_cooke@internode.on.net><Steve.cooke@ngodata.com.au>
 * Date: Jan 2011
 */
 
class NGOData {
	
	// - include for static classes
	const EMESS = 0; // Error message
	const WMESS = 1; // Warning message
	const IMESS = 2; // Information message
	
	const ERRLOG = '../NGOData.log';

	const CONTROOT = 'ngodata/controllers/';
	const CSSROOT = '../ngodata/css/';
	const IMAGEROOT = '/images/';
	const HOME = 'index.php';
	const CONTAINERROOT = 'containers/';
	
	// default message text
	private $eMessageText = "Oops! Something's gone wrong. It's been logged, and we're working to get it fixed as soon as we can.";
	private $wMessageText = "Oops! Something's gone wrong. It's been logged, and we're working to get it fixed as soon as we can, but you may try again.";
	
		
	/**
	 * Function: formatMessage(int $messageType, string $messageText)
	 * @author Steve Cooke
	 * Date: Jan 2011
	 * @param int $messageType Describes the type of message to be displayed
	 * @param string $messageText The textual content of the message.
	 * Part of error handling system - display useful message to users.
	 */
	public function userMessage($messageType, $messageText='') {
		// good message text!
		// Something's gone wrong. We're working to get it fixed as soon as we can.
		$_SESSION['message_type'] = self::translateMessageType($messageType);
		if ($messageText == '') {
			// use default message
			$_SESSION['message_text'] = self::getDefaultMessage($messageType);
		} else {
			$_SESSION['message_text'] = $messageText;
		}
		return true;
	}
	
	
	private function getDefaultMessage($messageType) {
		if ($messageType == self::EMESS) return $this->eMessageText;
		return $this->wMessageText;

	}


	/** 
	 * FUnction: logMessage(string $messType, string $errorMessage)
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * Date: Jan 2011
	 * @param string $messType Describes the message - could be WARNING,
	 * ERROR, INFORMATION, etc.
	 * @param string $message The error description to write to the log
	 * @return Returns TRUE if file is locked and written to; else FALSE is returned.
	 */
	public function logMessage($messType, $class, $function, $messText) {
		$message = "------ ".$this->translateMessageType($messType)." ------\r\n";
		$message .= "Date: ".date('d m y H:i:s')."\r\n";
		$message .= "Class: ".$class."\r\n";
		$message .= "Function: ".$function."\r\n";
		$message .= "Message: ".$messText."\r\n";
		
		// may need to test whether we have a legal file handle
		$fp = fopen(self::ERRLOG, 'a');

		if (flock($fp, LOCK_EX)) { // do an exclusive lock
			fwrite($fp, $message);
			flock($fp, LOCK_UN); // release the lock
		} else {
			// cannot get lock
			return false;
		}

		fclose($fp);
		return true;
	}
	
	
	/**
	 *
	 */
	private function translateMessageType($messType) {
		switch ($messType) {
			case self::EMESS:
				return "error";
				break;
			
			case self::WMESS:
				return "warning";
				break;
			
			case self::IMESS:
				return "information";
				break;
			
			default:
				return "Unknown message type";
		}		
	}
	
	
	public function isUserMessageSet() {
		if (!isset($_SESSION['message_text'])) return false;
		return true;
	}
	
	

	// COMMON display functions
	
	/**
	 * Function: displayPageStart()
	 * @author Steve Cooke
	 * @version
	 * Date: Feb 2011
	 * @return string RReturns the code for the start of the page
	 * @desc Constructs the code to display the page start. May need to pass auth
	 */
	public function displayPageStart() {
		
		$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"';
		$html .= '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';

		$html .= '<html xmlns="http://www.w3.org/1999/xhtml">';
		$html .= "<head>";
		$html .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
		// get title from the entity?...
		$html .= "<title>NGOData hosted data management system</title>";
		
		$html .= '<link href="/css/oneColFixCtr.css" rel="stylesheet" type="text/css" />';
		//$html .= '<link href="'.dirname(dirname($_SERVER['DOCUMENT_ROOT'])).'/ngodata/css/oneColFixCtr.css" rel="stylesheet" type="text/css" />';
		//$html .= '<link href="'.self::getPath('css').'oneColFixCtr.css" rel="stylesheet" type="text/css" />';
		//$html .= '<link href="../css/superfish.css" rel="stylesheet" type="text/css" />';
		
		$html .= '<link rel="stylesheet" href="/css/menu_style.css" type="text/css" />';
		//$html .= '<link rel="stylesheet" href="'.dirname(dirname($_SERVER['DOCUMENT_ROOT'])).'/ngodata/css/menu_style.css" type="text/css" />';
		//$html .= '<link rel="stylesheet" href="'.self::getPath('css').'menu_style.css" type="text/css" />';
		
		$html .= "</head>";

		$html .= "<body>";
		$html .= "<div class=\"container\">";
		$html .= "<div class=header>";
		$html .= "<!-- Logo Goes here -->";
		//$html .= "Logo";
		$html .= '<img src="'.self::getPath('images').'NGODataLogo-noBack.png" alt="NGOData logo" />';
		$html .= "<!-- End Logo -->";
		$html .= "</div>";
		
		
		// the menu items
		$html .= '<div class="menu">';
		$html .= '<ul>';// class="sf-menu">';
		$html .= "<!-- Add menu items by adding <li> tags -->";
      
		$html .= '<li><a href="'.self::getPath('home').'">Home</a> ';
		$html .= "<!-- Start Sub Menu Items -->";
		//$html .= '<ul>';// class=\"subnav\">";
		//$html .= '<li><a href="#">Sub Nav</a></li>';
		//$html .= '<li><a href="#\>Sub Nav</a></li>';
		//$html .= "</ul>";
		$html .= "<!-- End Sub MEnu Item --> ";
		$html .= "</li>";
		
		
		
		// If authorised, can add other options here
		$html .= "<li><a href=\"#\">Services</a>";
		$html .= '<ul>'; //class=\"subnav\">";
		$html .= '<li><a href="NGODataController.php?request=auth">NGOData login</a></li>';
		//$html .= '<li><a href="'.$_SERVER['DOCUMENT_ROOT'].'/NGODataController.php?request=auth">NGOData login</a></li>';
		$html .= '<li><a href="NGODataController.php?request=reg">Register</a></li>';
		$html .= "</ul>";
		$html .= "</li>";
		
		
		$html .= "<li><a href=\"#\">About</a>";
		$html .= "<ul class=\"subnav\">";
		$html .= "<li><a href=\"#\">Sub Nav</a></li>";
		$html .= "<li><a href=\"#\">Sub Nav</a></li>";
		$html .= "</ul>";
		$html .= "</li>";
		$html .= "</ul>";
		$html .= "</div>";

		return $html;
	}
	
	
	/**
	 * 
	 */
	public function displayMessage() {
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
	
	//--------------------**-------------------//
	// HTML entities, with display entities
	
	public function displayClear() {
		return '<div class=clear></div>';
	}
	
	
	public function displayOneThird($text) {
		return '<div class=onethird>'.$text.'</div>';
	}
	
	
	public function displayTwoThirds($text) {
		return '<div class=twothirds>'.$text.'</div>';
	}
	
	
	// this to be used before using columns and unformatted content
	public function startIndent() {
		return '<div class=indent>';
	}
	
	
	// used after columns and unformatted content
	public function closeProperty() {
		return '</div>';
	}
	
	
	public function header3($text) {
		return '<h3>'.$text.'</h3>';
	}
	
	
	public function para($text) {
		return '<p>'.$text.'</p>';
	}
	
	public function openOneThird() {
		return '<div class=onethird>';
	}
	
	public function openTwoThirds() {
		return '<div class=twothirds>';
	}
	
	public function startBasicForm($action) {
		return '<form id=wireframe action="'.$action.'">';
	}
	
	public function labelWithPara($label) {
		return '<p><span>'.$label.'</span>';
	}
	
	public function basicLabel($label) {
		return '<span>'.$label.'</span>';
	}
	
	public function inputPasswordWithEndPara($fieldName) {
		return '<input type="password" name="'.$fieldName.'" /></p>';
	}
	
	public function inputTextfieldWithEndPara($fieldName) {
		return '<input type="text" name="'.$fieldName.'" /></p>';
	}
	
	/**
	 * FUnction: startForm(string $action, string $formtitle)
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * Date: Feb 2011
	 * @param string $action The name of the controller to request action from
	 * @param string $formTitle The title that is displayed on the form
	 * @return string An html formatted string representing the start of the form.
	 */
	public function startForm($action, $formTitle) {
		$html =  '<div class="twocol greenform">';
		$html .= '<form action="'.$action.'" method="post">';
		$html .='<p class=heading>'.$formTitle.'</p>';
		return $html;
	}
	
	public function closeForm() {
		return '</form></div>';
	}
	
	public function displayButton($text) {
		
		return '<p><span>&nbsp;</span><input type="submit" name="submit" value="'.$text.'" /></p>';
		
		//return '<div class="btn btn_submit"><a href="#">'.$buttonTitle.'</a>';
		//return '<div class="btn btn_submit"><a href="#" onClick="submitForm();">'.$buttonTitle.'</a>';
	}
	
	
	/**
	 * Function: displayTableHead(string $tableLeftTitle, array $data)
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * Date: Feb 2011
	 * @param string $tableLeftTitle The title to appear above the left-most title column
	 * @param array $data An array containing the column head titles
	 * @return An html table head row
	 */
	public function displayTableHead($tableLeftTitle, $data) {
		//print_r($data);
		
		$html = '';
		$html .= '<table cellpadding="0" cellspacing="0" class="table-a">';
		$html .= '<thead>';
		$html .= '<tr class="row" id="titles">';
		
		if (!$tableLeftTitle == '') {
			$html .= '<th class="left" valign="top">';
			$html .= '<h3>'.$tableLeftTitle.'</h3>';
			$html .= '</th>';
		}
		
		for ($i=0; $i<count($data); $i++) {
			$html .= '<th class="right" valign="top"> <h3>'.$data[$i].'</h3></th>';
		}
		$html .= '</tr>';
		$html .= '</thead>';

		$html .= '<tbody>';
		return $html;
		
	}

	
	
	/**
	 * Function: displayTableRow(string $title, Array $data, string $row)
	 * @author Steve COoke <sa_cooke@internode.on.net>
	 * Date: Feb 2011
	 * @param string $title The title that appears on the left side of table row
	 * @param array $data The data to be displayed in the row. Will contain one or more columns.
	 * @param string $row Tells the function whether an odd or even row is to be displayed.
	 * @return An html table row
	 */
	public function displayTableRow($title, $data, $row) {
		$html = '<tr class=';
		// this seems strange, but the second row of the table is the first row of data, and therefore odd...
		if ($row == 'even') $html .= '"row">';
		elseif ($row == 'odd') $html .= '"row2">';

		if (!$title == '') $html .= '<td class="lefttitle">'.$title.'</td>';
		for ($i=0; $i<count($data); $i++) {
			$html .= '<td align="center" class="right">'.$data[$i].'</td>';
		}
		$html .= '</td>';
		return $html;
	}

	
	public function displayPageEnd() {
		return '</div></div></body></html>';
	}
	
	
	
	/**
	 * Function: getPath($dir)
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * Date: Feb 2011
	 * @param string $dir The base path to look for.
	 * @desc Very unintelligent way to parse paths...Gonna be a trial in refactoring!
	 * @return string Returns a directory path.
	 */
	public function getPath($dir) {
		
		$thisPath = self::getServerVar("PHP_SELF");
		$pathElements = pathinfo(self::getServerVar("PHP_SELF"));
		
		switch ($dir) {
			case 'css':
				if ($pathElements['dirname'] == '/') return '../'.self::CSSROOT;
				else return '../'.self::CSSROOT;
				break;
			
			case 'controllers':
				if ($pathElements['dirname'] == '/') return '../'.self::CONTROOT;
				else return '../'.self::CONTROOT;
				break;
				
			case 'images':
				if ($pathElements['dirname'] == '/') return '../'.self::IMAGEROOT;
				else return '../'.self::IMAGEROOT;
				break;
			
			case 'containers':
				if ($pathElements['dirname'] == '/') return '../'.self::CONTAINERROOT;
				else return '../'.self::CONTAINERROOT;
				break;
				
			case 'home':
				$pathParts = explode("/", $thisPath);
				if ($pathParts[1] == 'ngodata') {
					if ($pathParts[2] == 'controllers' || $pathParts[2] == 'classes' || $pathParts[2] == 'test' || $pathParts[2] == '../containers') return '../'.self::HOME;
				} else return '../'.self::HOME;
				break;
		}
	}


	/**
	 * Function: getServerVar($serverVar)
	 * @author Steve COoke <sa_cooke@internode.on.net>
	 * Date: Feb 2011
	 * @param string $serverVar The $_SERVER variable to search for.
	 * @return string Returns the string corresponding to the $_SERVER var searched for;
	 * or FALSE if the $_SERVER var cannot be found.
	 */
	private function getServerVar($serverVar) {
		foreach ($_SERVER as $k => $v) {
			if ($k == $serverVar) return $v;
		}
		return false;
	}
}
?>