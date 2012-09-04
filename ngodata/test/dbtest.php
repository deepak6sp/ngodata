<?php
class DbTest {
	// this is a test implementation of a data abstraction layer
	public function __construct() {
		
	}
	
	// Handles multiple rows
	// $data is to be an associative array keyed on field name:
	// data
	// 		|_[item]
	// 		|	|_[fieldname]=>[value]
	// 		|	|_[fieldname]=>[value]...
	// 		|_[item]
	// 			|_[fieldname]=>[value]
	// 			|_[fieldname]=>[value]...
	public function doInsert($tableName, $data) {
		$fields = $this->retrieveFields($data);
		// insert into metadata (variable, value, is_encrypted) values (
		$sql = "insert into ".$tableName. " set (";
		foreach ($fields as $item) {
			 $sql .= $item.", ";
		}
		echo $sql;
		// remove trailing comma and space
		$sql = substr($sql, 0, -2);
		$sql .= ") values ('";
		
		foreach ($data as $item) {
			foreach ($item as $key=>$value) {
				$sql .= $value."', '";
			}
			// remove trailing comma...
			$sql = substr($sql, 0, -3);
			$sql .= "),('";
		}
		$sql = substr($sql, 0, -3);
		//$sql .= ", \$id";
		
		return $this->renderSqlSafe($sql);
	}
	
	// Usage: $sql = sql("SELECT * FROM tblChicken WHERE pkChicken = %d", $id)
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
		//$item = array('name'=>'Lynn', 'number'=>'0403 832 208', 'car'=>'VW');
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
		
		$text = $this->insert('crap', $data);
		echo '<pre>';
		print $text;
		echo '</pre>';
		
		
	}
	
}
?>