<?php
interface iQueryObject {
	public function getFields();
	public function getUserDataFields();
	public function getDBName();
	public function getTablename();
	public function setWhere(array $w);
	public function getWhere();
}
?>