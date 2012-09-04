<?php
require_once '../classes/Validation.class.php';
require_once '../classes/ValidationData.class.php';

/**
 * ValidationHandler: Registration
 * This is the validation handler for Registration
 * - this validates and keeps track of error messages for Registration objects.
 * @author ngodata <ngodata@me.com>
 * DO NOT ALTER - this class is created automatically by the NGOData system.
 */

class RegistrationValidationHandler {
	// this holds the details of the field validation for Registration objects.
	private $validationRules = array(
		'business_name'=>array('required'),
		'business_registration_code'=>array('required'),
		'email'=>array('required', 'email'),
		'title_id'=>array('required', 'numeric'),
		'firstname'=>array('required'),
		'surname'=>array('required')
		);

	// Array to hold the required validation objects
	private $validationObjects = null;


	// Array to hold any generated messages
	private $messages = null;



	/**
	 * Method created automatically by NGOData system
	 * This object may be constructed with no data
	 * Each time this object is invoked a new graph of validation objects is also created
	 */
	public function __construct(iPostData $pd) {
		$this->setData($pd);
	}

	private function setData(iPostData $pd) {
		$data = $pd->getData();
		$dataForValidation = array();
		foreach ($data as $item) {
			foreach ($item as $field=>$data) {
				if ($this->IsInValidationArray($field)) {
					$v = new ValidationData($field, $data);
					$dataForValidation[] = $v;
				}
			}
		}
		$this->validationObjects = $dataForValidation;
		return $this->validationObjects;
	}


	private function IsInValidationArray($field) {
		if (array_key_exists($field, $this->validationRules)) return true;
		return false;
	}


	private function getValidationObjects() {
		return $this->validationObjects;
	}


	public function validate() {
		// Reset the messages array...
		$this->messages = null;

		// Find the field, and call the relevant validation method(s)
		$vos = $this->getValidationObjects();
		$validationRules = null;
		$validate = new Validation();
		foreach ($vos as $vo) {
			$rules = $this->getRulesForField($vo->getField());
			// $rules is an array...;
			//print_r($rules);
			for ($i=0; $i<count($rules); $i++) {
				$message = $validate->validateField($vo, $rules[$i]);
				if (!is_null($message)) $this->setMessage($vo->getField(), $message);
			}
		}
	}


	private function getRulesForField($field) {
		foreach ($this->validationRules as $key=>$value) {
			if ($key === $field) {
				return $value;
			}
		}
		return false;
	}


	private function setMessage($field, $message) {
		$this->messages[$field] = $message;
	}


	public function getMessages() {
		return $this->messages;
	}
}
?>
