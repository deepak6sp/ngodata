<?php
    require_once ('../classes/NGODataSession.class.php');
	//require_once ('../classes/NGODataSecurity.class.php');
	require_once ('../classes/NGOMetadataDB.class.php');
	//require
	
	$d = new NGOMetadataDB();
	
	
	
	
	/*
	$ds = new NGODataSecurity();
	
	$enc = $ds->encrypt(NULL,'','I saw the best minds of my generation destroyed by madness, starving hysterical naked, dragging themselves through the negro streets at dawn looking for an angry fix');
	
	print $enc;
	print $ds->decrypt('', NULL, $enc);
	*/
	
	
	
	//this is to bootstrap the application - need the global salt (or is it pepper!)
	
	//print_r($_SESSION);
	/*
    new NGODataSession();
    session_start();
	
	$q = new NGODataSecurity();
	
	$globPepper = 'I must go down to the sea again';
	$text = 'tlp_3339'.$globPepper;
	$hash = hash('sha512', $text);
	
	$q->retrieveHashAndSalt(NULL);
	print_r($_SESSION);
	//$q->generateHash(0,'1234');
	
	//print_r($_SESSION);
	*/
	
	
	
	
	/*
	$globPepper = 'I must go down to the sea again';
	$text = 'tlp_3339'.$globPepper;
	$hash = hash('sha512', $text);
	
	print $hash;
	print "\n\r Length of string: ".strlen($hash);
	 */
	 
	 
	 
	 
	 /* 
	$globalSalt = 'I must go down to the sea again';
	$entSalt = '';
	$salt = hash('sha512', uniqid(mt_rand(), true) . $globalSalt . strtolower($entSalt));
	
	print $salt;
	
	$hash = $salt.$text;
	for ($i=0; $i<100; $i++) {
		$hash = hash('sha512', $hash);
	}
	$hash = $salt.$hash;
	print $hash;
	*/
	
	
	
	
	
	/*
	$nKey = "The best laid schemes o' Mice an' Men Gang aft agley";
	$eKey = 'shoreline';
	$term = 'Nine Inch Nails';
	$p = '<html<head></head><body><p>Test encrypt...</p>';
	$p .= '<p>term: '.$term.'</p>';
	//$p .= '<p>Entity id: '.$_SESSION['entity_id'].'</p>';
	$eterm = $q->encrypt($nKey, $eKey, $term);
	if (!$eterm) ;
	
	$dterm = $q->decrypt($nKey, $eKey, $eterm);
	if (!$dterm) ;
	
	$p .= '<p>encrypted term: '.$eterm.'</p>';
	$p .= '<p>decrypted term: '.$dterm.'</p>';
	//$p .= '<p>encrypted term: '.$eterm.'</p>';
	
	print_r($q->errors);
	$p .= '</body></html>';
	*/
	
	
	
	
	 
	/*
	$file_handle = fopen("../files/salt.txt", "r");
while (!feof($file_handle)) {
   $line = fgets($file_handle);
   echo $line;
}
fclose($file_handle);
	*/
	
	
	
	
	
	
	/*
	$_SESSION['entity_id'] = '14';
    //print 'Phrase: skool is for whimps';
    $p = '<html<head></head><body><p>Test sessions...</p>';
	$p .= '<p>session id: '.session_id().'</p>';
	$p .= '<p>Entity id: '.$_SESSION['entity_id'].'</p>';
	session_regenerate_id();
	$p .= '<p>session id: '.session_id().'</p>';
	$p .= '<p>Entity id: '.$_SESSION['entity_id'].'</p>';
	
	unset($_SESSION['entity_id']);
	
	if (isset($_SESSION['entity_id'])) {
		$p .= '<p>Entity id: '.$_SESSION['entity_id'].'</p>';
	} else {
		$p .= '<p>Entity id is no longer set...</p>';
	}
	$p .= '</body></html>';
	//print hash('sha512', $p);
	*/
	
	//echo $p;
?>