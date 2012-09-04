<?php
//require_once '../interfaces/iValidation.class.php';
require_once '../classes/iValidationData.class.php';

// a number of validation objects - one per field perhaps? 
// an array of objects per DAO to match entered and required fields - not for derived nor database-assigned values

// throws a runtime exception
class Validation {
	// holds messages the validation object has assigned for invalid fields
	//private $messages = array();

	private $vd = null;

	//private $field;
	//private $fieldData;
	//private $validationTypes; 
	
	public function __construct() {
		
	}
	
	/*
	public function __construct($field, $data) {
		// not sure what is going to be passed in as yet
		// data, fields, and validation types required for each field
		// all parameters MUST NOT be null
		if (is_null($field)) {
			throw RunTimeException('Validation constructor reports $field parameter was null.');
		} else {
			$this->setField($field);
		}
		
		if (is_null($data)) {
			throw RunTimeException('Validation constructor reports $data array was null.');
		} else {
			$this->setFieldData($data);
		}
		
		//if (is_null($validationTypes)) {
			//throw RunTimeException('Validation constructor reports $validationTypes array was null.');
		//} else {
			//$this->setValidationTypes($validationTypes);
		//}
	}
	
	private function setField($field) {
		$this->field = $field;
	}
	
	public function getField() {
		return $this->field;
	}
	
	private function setFieldData($fieldData) {
		$this->fieldData = $fieldData;
	}
	
	
	private function setValidationTypes($validationTypes) {
		$this->validationTypes = $validationTypes;
	}
	*/
	
	public function validateField(iValidationData $vd, $rule) {
		switch ($rule) {
			case 'required':
				return $this->validateNonEmpty($vd);
			
			case 'email':
				return $this->validateEmail($vd);
				
			case 'numeric':
				break;
} 
	}
	
	public function isValid() {
		// if there are no messages return true
		if (empty($messages)) return true;
		return false;
	}
	
	/**
	 * Method: validateEmail
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Adapted from: http://www.marketingtechblog.com/valid-email-address-length/#ixzz23ykn3s1y
	 * Date: August 2012
	 */
	private function validateEmail(iValidationData $vd) {
		// assumes that there is an email address! Check there is one...
		//if (!isset($vd->getFieldData()) || $vd->getFieldData() == null) {
		//print_r($vd);
		if ($vd->getFieldData() == null) {
			return 'Please provide a valid email address.';
		}
		// We check that there's one @ symbol, and that the lengths are right.
		if (strpos($vd->getFieldData(), '@') === false) {
			return str_replace('_', ' ', $vd->getField()).' is not valid. Email addresses must include @.';
		}
		
		list($local, $domain) = explode('@',$vd->getFieldData());
		//print 'Local: '.$local;
		//print '; Domain: '.$domain;
		if (strlen($local) > 64) {
			return str_replace('_', ' ', $vd->getField()).' name is an invalid length.';
		} else if (strlen($domain) > 255) {
		//if (!preg_match("^[^@]{1,64}@[^@]{1,255}$", $vd->getFieldData())) {
			// Email invalid because wrong number of characters 
			// in one section or wrong number of @ symbols.
			return str_replace('_', ' ', $vd->getField()).' domain address is an invalid length.';
			/*
			$this->setMessage(str_replace('_', ' ', $this->field).' is invalid.');
			return false;
			*/
		}
		
		// Split it into sections to make life easier
		$email_array = explode('@', $vd->getFieldData());
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
			if(preg_match("/[^a-zA-Z0-9-_@.!#$%&'*\/+=?^`{\|}~]/", $local_array[$i])) {
			//if (!preg_match("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&↪'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",$local_array[$i])) {
				return ucfirst(str_replace('_', ' ', $vd->getField())).' is invalid - it contains unrecognised characters.';
				/*
				$this->setMessage(ucfirst(str_replace('_', ' ', $this->field)).' is invalid - it contains unrecognised characters.');
				return false;
				*/
			}
		}
		
		// Check if domain is IP. If not, it should be valid domain name
		if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) {
				return 'The '.str_replace('_', ' ', $vd->getField()).' address domain is invalid.';
				/*
				$this->setMessage('The '.str_replace('_', ' ', $this->field).' address domain is invalid.');
				return false; // Not enough parts to domain
				*/
			}
			for ($i = 0; $i < sizeof($domain_array); $i++) {
				if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|↪([A-Za-z0-9]+))$/", $domain_array[$i])) {
					return ucfirst(str_replace('_', ' ', $vd->getField())).' is invalid - it contains unrecognised characters.';
					/*
					$this->setMessage(ucfirst(str_replace('_', ' ', $this->field)).' is invalid - it contains unrecognised characters.');
					return false;
					*/
				}
			}
		}
		// seems to be a valid email address.
		return null;
	}
	
	private function validateNonEmpty(iValidationData $vd) {
		if (is_null($this->trimData($vd->getFieldData())) || $vd->getFieldData() === '') {
			// validation failed
			return ucfirst('Please provide your '.str_replace('_', ' ', $vd->getField())).'.';
			/*
			$this->setMessage(ucfirst(str_replace('_', ' ', $this->field)).' must not be empty.');
			return false;
			*/
		}
		return null;
	}
	
	private function validateNumeric(iValidationData $vd) {
		if (!is_numeric($vd->getFieldData())) {
			$this->setMessage(''.str_replace('_', ' ', $vd->getField()).' is not a numeric.');
			return false;
		}
		return null;
	}
	
	private function trimData($data) {
		return trim($data);
	}
}
?>