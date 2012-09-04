<?php
require_once '../exceptions/NGODataResultParameterException.class.php';
require_once '../exceptions/NGODataResultException.class.php';
require_once '../factories/ExceptionFactory.class.php';
class NGODataResult {
	
	private $mysql;
	private $result;
	
	public function __construct($mysql, $result) {
		try {
			if (!isset($mysql)) {
				throw new NGODataResultParameterException('The $mysql parameter is not set on call to the constructor.');
			} elseif (!isset($result)) {
				throw new NGODataResultParameterException('The $result parameter is not set on call to the constructor');
			}
		} catch (NGODataResultParameterException $rpe) {
			$ef = new ExceptionFactory();
			$ef->raiseError($rpe);
			$this->mysql = NULL;
			$this->result = NULL;
		}
		// all good, so carry on...
		$this->$mysql = $mysql;
		$this->result = $result;
		// also set the errorFactory
	}
	
	public function fetchRow() {
		if ($this->result == NULL) return false;
		return mysql_fetch_assoc($this->result);
	}
	
	public function countRows() {
		if ($this->result == NULL) return false;
		
		$rows = mysql_num_rows($this->result);
		return $rows;
	}
	
	public function countAffectedRows() {
		$rows = NULL;
		try {
			if (!$rows = mysql_affected_rows($this->mysql->conId)) {
				throw NGODataResultException('There was an error counting affected rows - maybe no rows affected.');
			}
			
		} catch (NGODataResultException $re) {
			$ef = new ExceptionFactory();
			$ef->raiseWarning($re);
			return FALSE;
		}
		return $rows;
	}
	
	public function countFields() {
		$fields = NULL;
		try {
			if (!$fields = mysql_num_fields($this->result)) {
				throw new NGODataResultException('Error counting returned fields - maybe no fields to count.');
			}
		} catch (NGODataResultException $re) {
			$ef = new ExceptionFactory();
			$ef->raiseWarning($re);
			return FALSE;
		}
		return $fields;
	}
	
	public function getInsertId() {
		$id = NULL;
		try {
			if (!$id = mysql_insert_id($this->mysql->conId)) {
				// possibly need the context as well - get from calling function
				throw new NGODataResultException('Error getting insert id!');
			}
		} catch (NGODataResultException $re) {
			$ef = new ExceptionFactory();
			$ef->raiseWarning($re);
			return FALSE;
		}
		return $id;
	}
	
	public function seekRow($row = 0) {
		return FALSE; // no error yet
	}
}
?>