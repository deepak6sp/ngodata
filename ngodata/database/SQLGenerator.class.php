<?php
require_once '../exceptions/NGODataSQLGeneratorException.class.php';
//require_once '../database/iSQLStatement.class.php'; //  we need this interface
require_once '../database/SQLStatement.class.php';
require_once '../database/BindParam.class.php';
require_once '../interfaces/iSQLGenerator.class.php';
require_once '../interfaces/iValueObject.class.php';
require_once '../factories/ExceptionFactory.class.php';

	/**
	 * For reference: $data is to be an associative array keyed on field name:
	 * data
	 * 		|_[item]
	 * 		|	|_[fieldname]=>[value]
	 * 		|	|_[fieldname]=>[value]...
	 * 		|_[item]
	 * 			|_[fieldname]=>[value]
	 * 			|_[fieldname]=>[value]...
	 */


class SQLGenerator implements iSQLGenerator {
	public function __construct() {
	}
	
	/**
	 * Method: createSelectStatement
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Checks input and delegates work
	 * @param unknown_type $tableName
	 * @param unknown_type $fields
	 */
	//public function createSelectStatement($tableName, $fields, $where) {
	public function createSelectStatement(iValueObject $valueObject) {
		return $this->prepareSelect($valueObject);
		//return $this->prepareSelect($tableName, $fields, $where);
	}
	
	
	/**
	 * Method: createInsertStatement
	 * @author Steve Cooke <sa_cooke@me.com>
	 */
	//public function createInsertStatement($tableName, $data) {
	public function createInsertStatement(iValueObject $valueObject) {
		return $this->prepareInsert($valueObject);
		//return $this->prepareInsert($tableName, $data);
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $tableName
	 * @param array $data
	 * @param array $where
	 */
	public function createUpdateStatement(iValueObject $valueObject) {
		return $this->prepareUpdate($valueObject);
	}
	
	
	public function createTruncateStatement(iValueObject $valueObject) {
		return $this->prepareTruncate($valueObject);
	}
	
	
	/**
	 * Method: prepareSelect(iValueObject $valueObject)
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: altered Aug 2012
	 * @param iValueObject $valueObject
	 * @return iSQLStatement Returns an SQLStatement object containing the sql and bind parameters.
	 */
	private function prepareSelect(iValueObject $valueObject) {
		//echo '<pre>';
		//echo 'In SQLGenerator->prepareSelect()';
		//echo '</pre>';
		$qo = $valueObject->getQueryObject();
		$fields = $qo->getFields();
		$tablename = $qo->getTablename();
		$where = $qo->getWhere();
		//echo '<pre>';
		//echo 'In SQLGenerator->prepareSelect(): where - ';
		//print_r($where);
		//echo '; tablename - '.$tablename;
		//echo '</pre>';
		
		$sql = "select ";
		if (isset($fields) && !is_null($fields)) {
			// $fields is an array - carry on...
			foreach ($fields as $field=>$type) {
				$sql .= $field.", "; 
			}
			//for ($i=0; $i<count($fields); $i++) {
				//use fetch_assoc...
				//$sql .= $fields[$i].", "; 
			//}
			// remove trailing comma and space
			$sql = substr($sql, 0, -2);
		}
		$sql .= " from ".$tablename;
		// parse the where clause
		
		if (count($where) > 0) {
			$bind = new BindParam();
			$sql .= " where ";
			foreach ($where as $field=>$value) {
				if (count($where) > 1) {
					$sql .= $field." in (";
					for ($i=0; $i<count($value); $i++) {
						$sql .= "'".$value[$i]."', ";
					}
					$sql = substr($sql, 0, -2);
					$sql .= ") and ";
				} else {
					$sql .= $field."='".$value."' and ";
				}
			}
			$sql = preg_replace('/and $/', '', $sql);
			return new SQLStatement($sql, $bind);
		} else {
			return new SQLStatement($sql, null); // no need for a BindParam, as there are no values to bind
		}
		//echo '...sql from NGODataSQLGenerator->prepareSelect: '.$sql.'...';
		//print '. Still in SQLGenerator->prepareSelect(); the sql: '.$sql;
		
		// need to return a statement object
		return new SQLStatement($sql, $fields);
		//return $this->renderSqlSafe($sql);
		
	}
	
	
	/**
	 * Method: prepareInsert
	 * @author Steve Cooke <sa_cooke@me.com>
	 * Date: edited August 2012
	 * @param iValueObject An iValueObject - ensures we have the correct object type to work with.
	 * @return Returns an iSqlStatement object
	 */
	private function prepareInsert(iValueObject $valueObject) {
		echo '<pre>Preparing insert statement for: ';
		print_r($valueObject);
		echo '</pre>';
		
		$bind = new BindParam();
		$qo = $valueObject->getQueryObject();
		$fields = $qo->getUserDataFields();
		$pd = $valueObject->getData();
		$data = $pd->getData();
		$sql = 'insert into '.$qo->getTablename().' (';
		
		//$fields = $qo->getFields(); //array
		$values = '';
		// use the userDataFields array..
		foreach ($fields as $field=>$type) {
			$sql .= $field.", ";
			$values .= '?, ';
		}
		/*
		foreach ($data as $item) {
			foreach ($item as $dataField=>$value) {
				if ($dataField !== 'id') $sql .= $dataField.", ";// && $value == '0') ;// do nothing;
				//else $sql .= $dataField.", ";
			}
		}
		*/
		
		$sql = substr($sql, 0, -2);
		$values = substr($values, 0, -2);
		$sql .= ') values ('.$values.')';
		
		// dataField should match the dbField...
		foreach ($data as $item) {
			foreach ($item as $dataField=>$value) {
				if ($dataField !== 'id') {  //;// && $value == '0') ;// do nothing;
				//else {
					//$sql .= '?, ';
					// look for $dataField in $fields array for the field type
					if (array_key_exists($dataField, $fields)) {
						//echo '<pre>Going into the bind param: ';
						//print $fields[$dataField].', and the associated value: '.$value;
						//echo '</pre>';
						$bind->add($fields[$dataField], $value);
					}
				}
			}
		}
		//$sql = substr($sql, 0, -2);
		//$sql .= ")";
		//echo '<pre>The sql: '.$sql.' and the bind params: ';
		//print_r($bind->get());
		//echo '</pre>';
		
		return $stm = new SQLStatement($sql, $bind);
	}
	
	
	
	/**
	 * Method: 
	 * Enter description here ...
	 * @param string $tableName
	 * @param array $data
	 * @param array $where
	 */
	private function prepareUpdate($valueObject) {
		$data = $valueObject->getData();
		$where = $valueObject->getQueryObject()->getWhere();
		$sql = "update ".$valueObject->getTablename()." set ";
		foreach ($data as $item) {
			foreach ($item as $key=>$value) {
				$sql .= $key."='?, ";
			}
		}
		// remove trailing comma...
		$sql = substr($sql, 0, -2);
		
		$sql .= " where ";
		//foreach ($where as $item) {
		foreach ($where as $item) {
			foreach ($item as $field=>$value) {
				$sql .= $valueObject->getTablename().".".$field."='".$value."' and ";
			}
		}
		//
		// remove trailing 'and' and spaces
		$sql = substr($sql, 0, -5);
		
		echo $sql;
		//return $this->renderSqlSafe($sql);
	}
		
	
	
	/**
	 * Method: prepareTruncate($valueObject)
	 * Returns the sql statement required to truncate the table defined in $valueObject
	 * @param NGOValueObjectInterface $valueObject
	 * @return string Returns the sql statement required to truncate the table defined in $valueObject.
	 */
	private function prepareTruncate($valueObject) {
		$sql = 'truncate table '.$valueObject->getTablename();
		return $sql;
	}
	
	
	/**
	 * Answer from StackOverFlow:
	 * http://stackoverflow.com/questions/173400/php-arrays-a-good-way-to-check-if-an-array-is-associative-or-sequential
	 * Enter description here ...
	 * @param unknown_type $array
	 */
	function isAssoc($array) {
		return (bool)count(array_filter(array_keys($array), 'is_string'));
	}
	
	
	private function renderSqlSafe() {
		$args = func_get_args();
		$format = array_shift($args);
		for($i = 0, $l = count($args); $i < $l; $i++) {
			$args[$i] = mysql_escape_string($args[$i]);
		}
		return vsprintf($format, $args);
	}

	
	private function retrieveFields($data) {
		$fields = array();
		// get the fields from the first itm in the array
		foreach ($data[0] as $key=>$value) {
			$fields[] = $key;
		}
		return $fields;
	}
	
	
	public function testArray() {
		$data = array();
		$item = array('name'=>'Steve', 'number'=>'0403 832 208', 'car'=>'Veyron');
		$data[] = $item;
		//$item = array('name'=>'Keiron', 'number'=>'0403 832 208', 'car'=>'Toyota');
		//$data[] = $item;
		$item = array('name'=>'Lynn', 'number'=>'0403 832 208', 'car'=>'VW');
		//$data[] = $item;
		$item = array('name'=>'Janine', 'number'=>'0403 832 208', 'car'=>'Nissan');
		$data[] = $item;
		$item = array('name'=>'Zac', 'number'=>'0403 832 208', 'car'=>'Mini');
		$data[] = $item;
		$item = array('name'=>'Jai', 'number'=>'0403 832 208', 'car'=>'Robin Reliant');
		$data[] = $item;
		
		echo '<pre>';
		print_r($data);
		echo '</pre>';
		
		echo '<pre>';
		print_r($this->retrieveFields($data));
		echo '</pre>';
		//return $data;
	}
}
?>