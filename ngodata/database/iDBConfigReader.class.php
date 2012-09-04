<?php
/**
 * Interface: iDBConfigReader
 * @author Steve Cooke <sa_cooke@me.com>
 * Adapted from http://www.dreamincode.net/forums/topic/223360-connect-to-your-database-using-oop-php5-with-mysql-and-mysqli/
 */
interface iDBConfigReader {
	public function getHost();
	public function getUser();
	public function getPassword();
	public function getDatabase();
	//public function getPort();
	//public function getSocket();
}
?>