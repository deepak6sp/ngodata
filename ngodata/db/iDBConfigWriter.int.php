<?php
interface iDBConfigWriter
{
	public function setHost();
	public function setUser();
	public function setPassword();
	public function setDatabase();
	public function setPort();
	public function setSocket();
}

?>