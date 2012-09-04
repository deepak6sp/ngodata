<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/../db/iDBConfigReader.int.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../db/iDBConfigWriter.int.php';


final class MySQLConfiguration implements iDBConfigReader, iDBConfigWriter {
	
	private $host, $username, $password, $dbname, $port, $socket;
	
	public function __construct(
		  $host = NULL
		, $user = NULL
		, $pass = NULL
		, $db   = ""
		//, $port = 3306
		//, $sock = NULL
	)
	{
		$this->setHost($host);
		$this->setUser($user);
		$this->setPassword($pass);
		$this->setDatabase($db);
		//$this->setPort($port);
		//$this->setSocket($sock);
	}
	
	public function getHost() {
		return $this->host;
	}
	
	public function setHost($host = NULL) {
		if (NULL === $host)
		{
			$this->host = NULL;
		}
		elseif (!is_string($host))
		{
			$this->host = ini_get("mysqli.default_host");
		}
		else
		{
			$this->host = $host;
		}
		return $this->host;
	}
	
	public function getUser() {
		return $this->username;
	}
	
	public function setUser($user = NULL) {
		if (!is_string($user))
		{
			$user = ini_get("mysqli.default_user");
		}
		$this->username = $user;
		return $this->username;
	}
	
	public function getPassword() {
		return $this->password;
	}
	
	public function setPassword($pass = NULL) {
		if (NULL === $pass)
		{
			$this->password = NULL;
		}
		elseif (!is_string($pass))
		{
			$this->password = ini_get("mysqli.default_pw");
		}
		else
		{
			$this->password = $pass;
		}
		return $this->password;
	}
	
	public function getDatabase() {
		return $this->dbname;
	}
	
	public function setDatabase($db = NULL) {
		$this->dbname = (string) $db;
		return $this->dbname;
	}
	
	public function getPort() {
		return $this->port;
	}
	
	public function setPort($port = NULL) {
		if (!is_int($port))
		{
			$port = ini_get("mysqli.default_port");
		}
		$this->port = $port;
		return $this->port;
	}
	
	public function getSocket() {
		return $this->socket;
	}
	
	public function setSocket($sock = NULL) {
		if (!is_string($sock))
		{
			$sock = ini_get("mysqli.default_socket");
		}
		$this->socket = $sock;
		return $this->socket;
	}
}
?>