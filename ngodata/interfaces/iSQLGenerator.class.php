<?php
interface iSQLGenerator {
	
	public function createSelectStatement(iValueObject $object);
	//public function createSelectStatement($tableName, $fields, $where);
	public function createInsertStatement(iValueObject $object);
	//public function createInsertStatement($tableName, $data);
	public function createUpdateStatement(iValueObject $object);
}
?>