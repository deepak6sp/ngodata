<?php
interface iSQLFactory {
	public function prepSelectStatement(iValueObject $data);
	public function prepInsertStatement(iValueObject $data);
	public function prepUpdateStatement(iValueObject $data);
	public function prepDeleteStatement(iValueObject $data);
	public function doSelect(iValueObject $data);
	public function doSave(iValueObject $data);
	public function doDelete(iValueObject $data);
}
?>