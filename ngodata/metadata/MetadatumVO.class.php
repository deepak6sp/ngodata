<?php
require_once '../interfaces/NGOValueObjectInterface.class.php';

class MetadatumVO implements NGOValueObjectInterface {
//class User {
	// Attributes
	private $qo;

	private $variable;
	private $value;
	private $is_encrypted;
	//array('variable','value','is_encrypted');
	
	public function __construct() {
	}
	
	public function getVariable() {
		return $this->variable;
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function getIsEncrypted() {
		return $this->is_encrypted;
	}
	
	/**
	 * 
	 * The $tablename field is created and set by the DBIGenerator.
	 */
	public function getTablename() {
		return $this->qo->getTablename();
	}

	/**
	 * 
	 * The $fields array is created and set by the DBIGenerator.
	 */
	public function getFields() {
		return $this->qo->getFields();
	}
	
	
	public function setData(array $data) {
		// sets the data attributes for this object
		foreach ($data as $item) {
			foreach ($item as $key=>$value) {
				switch ($key) {
				case ($key == 'variable'):
					$this->variable = $value;
					break;

				case ($key == 'value'):
					$this->value = $value;
					break;

				case ($key == 'is_encrypted'):
					$this->is_encrypted = $value;
					break;
				}
			}
		}
	}
	
	
	public function setQueryObject(NGODataQueryObjectInterface $qo) {
		$this->qo = $qo;
	}

	public function getQueryObject() {
		if (isset($this->qo)) {
			return $this->qo;
		}
		return false;
	}
	

	/**
	 * Method created automatically by NGOData system
	 */
	public function getData() {
		$data = array();
		$item = array('variable'=>$this->getVariable());
		$data[] = $item;

		$item = array('value'=>$this->getValue());
		$data[] = $item;

		$item = array('is_encrypted'=>$this->getIsEncrypted());
		$data[] = $item;

		return $data;
	}
}
?>