<?php
interface iDBConfigReader
{
	public function getHost();
	public function getUser();
	public function getPassword();
	public function getDatabase();
	public function getPort();
	public function getSocket();
}

?>