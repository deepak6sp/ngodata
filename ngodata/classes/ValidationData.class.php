<?php
require_once '../classes/iValidationData.class.php';

class ValidationData implements iValidationData {
	private $field = null;
	private $fieldData = null;
	public function __construct($field, $data) {
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
	}
	
	private function setField($field) {
		$this->field = $field;
	}
	
	public function getField() {
		if ($this->field === null) return null;
		return $this->field;
	}
	
	private function setFieldData($fieldData) {
		$this->fieldData = $fieldData;
	}
	
	public function getFieldData() {
		if ($this->fieldData === null) return null;
		return $this->fieldData;
	}
}
?>