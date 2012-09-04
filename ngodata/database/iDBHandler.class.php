<?php
interface iDBHandler
{
	// just an example, certainly more methods are needed in the final interface(s)
	public function getConnection();
	//public function setConnection($conf);
	
	public function doSelect(iSQLStatement $sql);
	public function doInsert(iSQLStatement $sql);
	public function doUpdate(iSQLStatement $sql);
	public function doCreate(iSQLStatement $sql);
	public function doDelete(iSQLStatement $sql);
}
?>