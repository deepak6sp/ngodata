<?php
//require_once $_SERVER['DOCUMENT_ROOT'].'/../factories/NGODataDBFactory.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../factories/ErrorDisplayFactory.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../display/RegistrationDisplay.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../classes/DisplayElements.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../model/RegistrationFactory.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../database/iPostData.class.php';

require_once '../database/DBFactory.class.php';

class RegistrationContainer {
	
	//private $dbFactory = null;
	private $sqlFactory = null;
	
	public function __construct(iSQLFactory $sqlf) {
		//$this->setDBFactory($dbf);
		$this->setSQLFactory($sqlf);
	}
	
	//private function setDBFactory($dbf) {
		//$this->dbFactory = $dbf;
		//return $this->dbFactory;
	//}
	
	private function setSQLFactory($sqlf) {
		$this->sqlFactory = $sqlf;
		return $this->sqlFactory;
	}
	
	
	/**
	 * Method: startRegistrstionProcess()
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: June 2012
	 * @return string Returns the registration page to display...
	 */
	public function startRegistrationProcess($errors = null, $data = null) {
		// need to display the registration form, collect info, verify info, then email
		// info to NGOData...
		$rd = new RegistrationDisplay($this->sqlFactory);
		// inject the error display
		$rd->setErrorDisplay(new ErrorDisplayFactory());
		$de = new DisplayElements();

		$html = '';
		// need to get the page start stuff...
		$html = $de->pageStart();
		// form params...
		$p = array('action'=>'NGODataController.php', 'name'=>'registration_details', 'method'=>'post');
		$html .= $de->formOpen($p);
		$html .= $rd->displayNew($errors, $data);
		$html .= $de->submitButtonWithText('Submit registration details', 'registration_button');
		$html .= $de->formClose();
		$html .= $de->pageEnd();
		print $html;
	}
	
	
	/**
	 * 
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: August 2012
	 */
	public function processRegistration(iPostData $data) {
		$rf = new RegistrationFactory($this->sqlFactory);
		// this returns an array containing error messages, or null if no errors
		$errorMessages = $rf->validate($data);
		
		if (!is_null($errorMessages)) {
			$this->startRegistrationProcess($errorMessages, $data);
		} else {
			// save data then tell the user what has been saved, and what to do now
			$reg = $rf->createRegistration($data);
			// need to check whether the registration is unique
			if ($this->isRegistrationUnique($reg)) {
				
			}
			// set the where here somehow
			
			//echo '<pre>';
			//print_r($reg);
			//echo '</pre>';
			try {
				$result = $reg->saveObject();
			} catch (Exception $e) {
				// transfer the exception to display
				$this->registrationError($e->getMessage());
			}
			// all good
			$this->registrationSuccess();
			
		}
	}
		
	private function registrationError($error) {
		$de = new DisplayElements();
		$html = '';
		$html = $de->pageStart();
		$html .= $de->openHead1();
		$html .= 'Oops!';
		$html .= $de->displayErrorMessage('There\'s been an error at our end which means we\'re unable to process your registration at this time.  You can email your registration details to us at reg@me.com though. Include your name, your business name and code (ABN), and your email. Sorry for the hassle, we\'re working on a fix now.');
		$html .= closeHead1();
		$html .= $de->pageEnd();
		print $html;
	}
		
	private function registrationSuccess() {
		$de = new DisplayElements();
		$html = '';
		$html .= $de->pageStart();
		$html .= $de->openHead1();
		$html .= 'Thank you!';
		$html .= $de->closeHead1();
		$html .= $de->displaySuccessMessage('Your registration has been received and will be processed within the next 24 hours.');
		$html .= $de->pageEnd();
		print $html;
	}
	
	private function isRegistrationUnique(iDataAccessObject $dao) {
		$vo = $dao->getValueObject();
	}
}
?>