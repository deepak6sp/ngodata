<?php
require_once '../exceptions/NGODataDBResultSetException.class.php';
require_once '../factories/ExceptionFactory.class.php';

class NGODataResultSet{
	private $db;
	private $result;
	private $convertedResult;
	
	public function __construct($db, $result) {
		$this->db = $db;
		$this->result = $result;
		$this->convertedResult = $this->convertResult($result);
	} 
	
	public function fetchRow() {
		return mysql_fetch_assoc($this->result);
	}
	
	public function countRows() {
		if (!$rows = mysql_num_rows($this->result)) {
			return false;
		}
		return $rows;
	}
	
	/**
	 * Method: getInsertId()
	 * Returns the insert id created by the database process that created the data
	 * @return boolean Returns false on error
	 */
	public function getInsertId() {
		$id = NULL;
		try {
			if (!$id = mysql_insert_id($this->db->dbconnection)) {
				throw new NGODataResultSetException('Error: error getting insertion id');
			}
		} catch (NGODataResultSetException $nrse) {
			$ef = new ExceptionFactory();
			$ef->raiseError($nrse);
			return false;
		}
		return $id;
		
	}
	
	private function convertResult($resultSet) {
		$resultArray = array();
		while ($row = mysql_fetch_assoc($resultSet)) {
			$resultArray[] = $row;
		}
		return $resultArray;
	}	
}
?>