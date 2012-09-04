<?php
session_start();
	//$test = 5;
	//if ($test > 1) {
	//trigger_error('Value of $test must be 1 or less', E_USER_NOTICE);
	//}
require_once '../exceptions/UserFieldCountException.class.php';
require_once '../factories/ErrorFactory.class.php';
	
function test1($x) {
	if ($x>1) {
		throw new UserFieldCountException('This is the UserFieldCountException...!');
	} else echo 'good...!';
}

try{  
	$_SESSION['user_id'] = 1;
	test1(5);
} catch (UserFieldCountException $fce) {
	// call the error factory and pass it the stacktrace
	$ef = new ErrorFactory();
	//return $ef->raiseError($fce);
	//$ef->raiseError($fce);
	//print_r($_SESSION);
	
	//return $ef->raiseWarning($fce);
	//$ef->raiseWarning($fce);
	//print_r($_SESSION);
	
	//return $ef->raiseMessage($fce);
	$ef->raiseMessage($fce);
	print_r($_SESSION);
	//return $ef->raiseError($fce);
			//}
	/*
	$a = $e->getTrace();
	echo 'Caught exception: trace- '.$e->getTrace(), "\n";
	echo 'Caught exception: file- '.$e->getFile(), "\n";
	//echo 'Caught exception: file- ',  $e->getClass(), "\n";
	//echo 'Caught exception: file- ',  $e->getFunction(), "\n";
	//echo 'Caught exception: class- '.$e->getClass(), "\n";
	echo 'Caught exception: function- '.$a[0]['function'], "\n";
	echo 'Caught exception: message- '.$e->getMessage(), "\n";
	print_r($e->getTrace());
	*/
}
?>