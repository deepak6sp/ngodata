<?php
/**
 * This class produces various html screen elements 
 */
class DisplayElements{
	public function __construct() {
	}
	
	/**
	 * Method: submitButton($name)
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: May 2012
	 * @return string Returns an html-formatted string representing a submit button
	 * @param string $name The name of the button
	 */
	public function submitButton($name) {
		return '<input id="submit-button" type="submit" value="Submit" name="'.$name.'">';
	}
	
	/**
	 * Method: submitButtonWithText($buttonText, $name)
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: May 2012
	 * @return string Returns an html-formatted string representing a submit button with text supplied by the application
	 * @param string $buttonText The text appearing on the button
	 * @param string $name The name of the button
	 */
	public function submitButtonWithText($buttonText, $name) {
		return '<input id="submit-button" type="submit" value="'.$buttonText.'" name="'.$name.'">';
	}
	
	
	/**
	 * Method: formOpen($params)
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: May 2012
	 * @return string Returns an html-formatted string representing a submit button
	 * @param array $params An array of valid parameters to appear in the form tag.  These are in the form of:
	 *  param=>value, param=>value...
	 */
	public function formOpen($params) {
		// check type...
		//if (!is_array($params)) throw an error...
		$html = '<form';
		foreach ($params as $name=>$value) {
		//($i=0; $i<count($params); $i++) {
			$html .= ' '.$name.'="'.$value.'"';
		}
		$html .= '>';
		return $html;
	}
	
	public function formClose() {
		return '</form>';
	}
	
	/**
	 * Function: pageStart()
	 * @author Steve Cooke
	 * @version
	 * Date: Feb 2011
	 * @return string RReturns the code for the start of the page
	 * @desc Constructs the code to display the page start. May need to pass auth
	 */
	public function pageStart() {
		
		$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"'."\n";
		$html .= '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";

		$html .= '<html xmlns="http://www.w3.org/1999/xhtml">'."\n";
		$html .= "<head>"."\n";
		$html .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'."\n";
		// get title from the entity?...
		$html .= "<title>NGOData hosted data management system</title>"."\n";
		
		$html .= '<link href="/css/oneColFixCtr.css" rel="stylesheet" type="text/css" />'."\n";
		//$html .= '<link href="'.dirname(dirname($_SERVER['DOCUMENT_ROOT'])).'/ngodata/css/oneColFixCtr.css" rel="stylesheet" type="text/css" />';
		//$html .= '<link href="'.self::getPath('css').'oneColFixCtr.css" rel="stylesheet" type="text/css" />';
		//$html .= '<link href="../css/superfish.css" rel="stylesheet" type="text/css" />';
		
		$html .= '<link rel="stylesheet" href="/css/menu_style.css" type="text/css" />'."\n";
		//$html .= '<link rel="stylesheet" href="'.dirname(dirname($_SERVER['DOCUMENT_ROOT'])).'/ngodata/css/menu_style.css" type="text/css" />';
		//$html .= '<link rel="stylesheet" href="'.self::getPath('css').'menu_style.css" type="text/css" />';
		
		$html .= "</head>"."\n";

		$html .= "<body>"."\n";
		$html .= "	<div class=\"container\">"."\n";
		$html .= "		<div class=header>"."\n";
		$html .= "			<!-- Logo Goes here -->"."\n";
		//$html .= "Logo";
		$html .= '			<img src="/images/NGODataLogo-noBack.png" alt="NGOData logo" />'."\n";
		$html .= "			<!-- End Logo -->"."\n";
		$html .= "		</div>"."\n";
		
		
		// the menu items
		$html .= '		<div class="menu">'."\n";
		$html .= '			<ul>'."\n";// class="sf-menu">';
		$html .= "				<!-- Add menu items by adding <li> tags -->"."\n";
      
		$html .= '				<li><a href="index.php">Home</a> '."\n";
		$html .= "				<!-- Start Sub Menu Items -->"."\n";
		//$html .= '<ul>';// class=\"subnav\">";
		//$html .= '<li><a href="#">Sub Nav</a></li>';
		//$html .= '<li><a href="#\>Sub Nav</a></li>';
		//$html .= "</ul>";
		$html .= "				<!-- End Sub Menu Item --> "."\n";
		$html .= "				</li>"."\n";
		
		// If authorised, can add other options here
		$html .= "				<li><a href=\"#\">Services</a>"."\n";
		$html .= '					<ul>'; //class=\"subnav\">";
		$html .= '						<li><a href="NGODataController.php?request=auth">NGOData login</a></li>'."\n";
		//$html .= '<li><a href="'.$_SERVER['DOCUMENT_ROOT'].'/NGODataController.php?request=auth">NGOData login</a></li>';
		$html .= '						<li><a href="NGODataController.php?request=reg">Register</a></li>'."\n";
		$html .= "					</ul>"."\n";
		$html .= "				</li>"."\n";
		
		$html .= "				<li><a href=\"#\">About</a>"."\n";
		$html .= "					<ul class=\"subnav\">"."\n";
		$html .= "						<li><a href=\"#\">Sub Nav</a></li>"."\n";
		$html .= "						<li><a href=\"#\">Sub Nav</a></li>"."\n";
		$html .= "					</ul>"."\n";
		$html .= "				</li>"."\n";
		$html .= "			</ul>"."\n";
		$html .= "		</div>"."\n";

		return $html;
	}
	
	public function pageEnd() {
		return '</body>'."\n".'</html>'."\n";
	}
	
	public function openHead1() {
		return '<h1>'."\n";
	}
	
	public function closeHead1() {
		return '</h1>'."\n";
	}
	
	public function displayErrorMessage($error) {
		$html = '';
		$html .= '<p class="error">'."\n";
		$html .= $error."\n";
		$html .= '</p>'."\n";
		return $html;
	}
	
	public function displaySuccessMessage($message) {
		$html = '';
		$html .= '<p class="success">'."\n";
		$html .= $message."\n";
		$html .= '</p>'."\n";
		return $html;
	}
}
?>