<?php
/**
 * Interface: iDBConfigWriter
 * @author Steve Cooke <sa_cooke@me.com>
 * Adapted from http://www.dreamincode.net/forums/topic/223360-connect-to-your-database-using-oop-php5-with-mysql-and-mysqli/
 */
interface iDBConfigWriter {
	public function setHost();
	public function setUser();
	public function setPassword();
	public function setDatabase();
	//public function getPort();
	//public function getSocket();
}
?>