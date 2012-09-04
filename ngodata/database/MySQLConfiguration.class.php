<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../database/iDBConfigReader.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../database/iDBConfigWriter.class.php';

/**
 * Class: MySQLConfiguration
 * @author Steve Cooke <sa_cooke@me.com>
 * Adapted from http://www.dreamincode.net/forums/topic/223360-connect-to-your-database-using-oop-php5-with-mysql-and-mysqli/
 * Create one for each database I try to connect to
 */
final class MySQLConfiguration implements iDBConfigReader, iDBConfigWriter {
	
	private $host;
	private $username;
	private $password;
	private $dbname;
	
	public function __construct($host = NULL, $user = NULL, $pass = NULL, $db = "") {
		$this->setHost($host);
		$this->setUser($user);
		$this->setPassword($pass);
		$this->setDatabase($db);
	}
	
	public function getHost() {
		return $this->host;
	}
	
	public function setHost($host = NULL) {
		if (NULL === $host) {
			$this->host = NULL;
		} elseif (!is_string($host)) {
			$this->host = ini_get("mysqli.default_host");
		} else {
			$this->host = $host;
		}
		return $this->host;
	}
	
	public function getUser() {
		return $this->username;
	}
	
	public function setUser($user = NULL) {
		if (!is_string($user)) {
			$user = ini_get("mysqli.default_user");
		}
		$this->username = $user;
		return $this->username;
	}
	
	public function getPassword() {
		return $this->password;
	}
	
	public function setPassword($pass = NULL) {
		if (NULL === $pass) {
			$this->password = NULL;
		} elseif (!is_string($pass)) {
			$this->password = ini_get("mysqli.default_pw");
		} else {
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
}
?>