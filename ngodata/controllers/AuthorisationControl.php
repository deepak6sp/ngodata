<?php
require_once ('../session/NGODataSession.class.php');
require_once ('../classes/NGOData.class.php');
require_once ('../classes/ValidateField.class.php');
require_once ('../classes/NGODataDB.class.php');

if(!session_id()) {
	new NGODataSession();
	session_start();
	//print session_id();
	//print_r($_SESSION);
	if(!session_id()) {
		// error
		NGOData::userMessage(NGOData::EMESS, 'The system is unable to complete your request. 
			This has been logged and the site administrator notified.');
		NGOData::logMessage(NGOData::EMESS, 'AuthorisationControl.php', 'session_start', 'Unable to start session.');
		header('location: '.NGOData::getPath('home'));
	}
}

if($_POST) {
	if(isset($_POST['submit'])) {

		if($_POST['submit'] == 'Log in') {

			// check fields
			$validate = new ValidateField();
			$validate -> addTextField("username", $_POST['username'], "text", "y");
			$validate -> addTextField("password", $_POST['password'], "text", "y");

			if($validate -> validate()) {
				//print 'OK!';

				$db = NGODataDB::getInstance();
				header('location: ' . getCurrentPage());

			} else {
				// User input error - report to user
				$error = $validate -> create_msg();
				NGOData::userMessage(NGOData::WMESS, $error);
				header('location: ' . getCurrentPage());
			}
		}
	}
} else {
	// default...
	print displayLoginPage();
}

/**
 * Function: displayLoginPage()
 * @author Steve Cooke <sa_cooke@internode.on.net>
 * Date: Feb 2011
 * @return string Returns the html code to display the main login page
 */
function displayLoginPage() {
	$html = '';
	$html .= NGOData::displayPageStart();
	$html .= NGOData::displayMessage();
	//print_r($_SESSION);
	$html .= NGOData::startForm(NGOData::getPath('controllers') . 'AuthorisationControl.php', 'Enter login details');
	$html .= NGOData::labelWithPara('Username:');
	$html .= NGOData::inputTextfieldWithEndPara('username');
	$html .= NGOData::labelWithPara('Password:');
	$html .= NGOData::inputPasswordWithEndPara('password');
	$html .= NGOData::displayButton('Log in');
	$html .= NGOData::closeForm();
	$html .= NGOData::displayPageEnd();

	return $html;
}

function getCurrentPage() {
	return  htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES);
}
?>