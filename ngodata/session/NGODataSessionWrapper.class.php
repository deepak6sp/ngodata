<?php
//require 'NGODataSession.class.php';
class NGODataSessionWrapper {
	
	private static $sessionStarted = false;
	
	public static function start() {
		if (self::$sessionStarted == false) {
			// use for database session handling
			//new NGODataSession();
			//$a = session_id();
			//if (empty($a)) {
			session_start();
			//self::set('sessionid', session_id());
			self::$sessionStarted = true;
			//echo "SID: ".SID."<br>session_id(): ".session_id()."<br>COOKIE: ".$_COOKIE["PHPSESSID"];
			//}
		}
	}
	
	public static function set($key, $value) {
		$_SESSION[$key] = $value;
	}
	
	public static function get($key, $secondKey = false) {
		if ($secondKey == true) {
			if (isset($_SESSION[$key][$secondKey]))
				return $_SESSION[$key][$secondKey];
		} else {
			if (isset($_SESSION[$key]))
			return $_SESSION[$key];
		}
		return false;
	}
	
	public static function setNewId() {
		session_regenerate_id();
	}
	
	public static function setSafe() {
		$a = session_id();
		if ($a == '') session_start();
		if (!isset($_SESSION['safety'])) {
			session_regenerate_id();
			$_SESSION['safety'] = true;
		}
		$_SESSION['sessionid'] = session_id();
	}
	
	public static function display() {
		
		echo '<pre>';
		//echo 'id: ';
		//print_r(session_id);
		print_r($_SESSION);
		echo '</pre>';
		
		
	}
	
	public static function destroy() {
		if (self::$sessionStarted == true) {
			session_unset();
			session_destroy();
		}
	}
}
?>