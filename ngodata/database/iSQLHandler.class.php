<?php
interface iSQLHandler
{
	// just an example, certainly more methods are needed in the final interface(s)
	public function doSelect(iSQLStatement $sqlObject);
	public function doInsert(iSQLStatement $sqlObject);
	public function doUpdate(iSQLStatement $sqlObject);
	public function doCreate(iSQLStatement $sqlObject);
}
?>