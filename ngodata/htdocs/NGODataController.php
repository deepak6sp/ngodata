<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../classes/NGOData.class.php';
//require_once $_SERVER['DOCUMENT_ROOT'].'/../controllers/AuthorisationControl.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../containers/RegistrationContainer.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../containers/NGODataContainer.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../database/PostData.class.php';

// NGODataController.php
if ($_GET) {
	//print_r($_GET);
	if ($_GET['request'] == 'auth') {
		print 'Getting authorisation credentials...';
		$ac = new AuthorisationControl();
		echo $ac->displayLoginPage();
	} else if ($_GET['request'] == 'reg') {
		// user has requested the registration page
		
		// need to inject some dependencies
		$dbf = new DBFactory();
		$sqlf = new SQLFactory($dbf);
		//$rc = new RegistrationContainer(new SQLFactory($dbf));
		$rc = new RegistrationContainer($sqlf);
		$rc->startRegistrationProcess();
		//print 'here we are...';
	}

} elseif ($_POST) {
	print_r($_POST);
	if (isset($_POST['registration_button'])) {
		// user has requested new registration
		$pd = new PostData($_POST, true);
		//print 'registration button pressed - validation needs to take place...';
		$dbf = new DBFactory();
		$sqlf = new SQLFactory($dbf);
		$rc = new RegistrationContainer($sqlf);
		
		$rc->processRegistration($pd);
	}
	//if ($_POST[''])
	
} else { //default
	print welcome();
	//header('Location: $_SERVER[\'DOCUMENT_ROOT\'].NGODataController.php');
}

function welcome() {
	$nc = new NGODataContainer();
	return $nc->welcomePage();
}

// THIS NEEDS TO GO SOMEWHERE!!! Unsure where yet...
/*
< ?php
function HTTPStatus($num) {
static $http = array (
301 => “HTTP/1.1 301 Moved Permanently”,
302 => “HTTP/1.1 302 Found”,
303 => “HTTP/1.1 303 See Other”,
304 => “HTTP/1.1 304 Not Modified”,
305 => “HTTP/1.1 305 Use Proxy”,
307 => “HTTP/1.1 307 Temporary Redirect”
);
header($http[$num]);
}

$status_code = $_REQUEST['redirect_status'];
if(!$status_code) $status_code = “301″;
HTTPStatus($status_code);
header(”Location: prg_view.php”);
?>
*/


?>