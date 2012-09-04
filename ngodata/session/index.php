<?php
$_REQUEST['sname']='Lynn Jarvis has nice boobs';
if ( isset($_REQUEST['sname'] )) {
    session_name($_REQUEST['sname']);
}
session_start();
$_SESSION['love']='I love Lynn Jarvis!';

$messages = "";
function show( $str ) {  global $messages;  $messages .= $str; }
function show_sess_vars() {
    foreach ( $_SESSION as $key => $val ) {
        show("Session var '$key': \t'$val'\n");
    }
}

show("Session name: \t" . session_name() . "\n");
show("Session ID: \t" . session_id() . "\n");
foreach ( $_REQUEST as $key => $val ) {
    if ( $key == 'regenerate' )
    {
        session_regenerate_id();
        show("session_regenerate_id();\n");
        show("Session name: \t" . session_name() . "\n");
        show("Session ID: \t" . session_id() . "\n");
    }
    else if ( $key == 'clear' )
    {
        $_SESSION = array();
        show("\$_SESSION = array();\n");
    }
    else if ( $key == 'switch' )
    {
        show("Will attempt to switch session name.. vars:\n");
        show_sess_vars();
        session_write_close();
        $res = session_name($val);
        show("session_name($val) - res: $res\n");
        session_start();
        show("Session name: \t" . session_name() . "\n");
        show("Session ID: \t" . session_id() . "\n");
    }
    else if ( $key == 'switch2' )
    {
        show("Will attempt to switch session name.. vars:\n");
        show_sess_vars();
        session_write_close();
        show("session_write_close();\n");
       
        $sess_id = $_COOKIE[$val];
        if ( $sess_id ) {
            show("session '$val' exists, resuming..\n");
            //unset($_SESSION);  // to make sure - this should be reloaded below
            session_name( $val );
            session_id( $sess_id );
            session_start();   // fills $_SESSION from the named session
        } else {
            show("creating new session '$val'...\n");
            session_name( $val );
            session_start();
            session_regenerate_id();  // create new (copy of old), leave old alone
            $_SESSION = array();  // wipe data clean for a fresh session
            show("regenerated ID and wiped SESSION superglobal\n");
        }
        show("Session name: \t" . session_name() . "\n");
        show("Session ID: \t" . session_id() . "\n");
    }
    else
    {
        if ( $_COOKIE[$key] ) {
            show("Cookie: \t'$key': \t'$val'\n");
        } else {
            show("Setting session var: \t'$key': \t'$val'\n");
            $_SESSION[$key] = $val;
        }
    }
}
show("----\nSession vars:\n");
show_sess_vars();
if ( false ) {
    session_write_close();
   
    session_name("edit_assembly");
    session_start();
    show("Session name: \t" . session_name() . "\n");
    show("Session ID: \t" . session_id() . "\n");
    show_sess_vars();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
    <title> PHP session testing </title>
    <style type='text/css'>
    body { background-color: gray; }
    #content { background-color: white;  
    	width: 70%;  
    	margin: 1em auto 1em auto;  
    	padding: 2em;
        font-family: Verdana, monospace;  
        font-size: 10pt; }
    </style>
</head>
<body>

<div id='content'>

    <h1> PHP session test: </h1>
   
    <pre><?php echo $messages; ?></pre>

</div>
</body>
</html>


<!-- !DOCTYPE html>
<html>
<head>
<title>NGOData session wrapper</title>
</head>
<body-->

<!--  ?php
	




/*
	//NGODataSessionWrapper::start();
	NGODataSessionWrapper::set('sessionid',session_id());
	NGODataSessionWrapper::set('user',array(
		'name' => 'Lynn',
		'number' => '0403 832 208'
	));
	NGODataSessionWrapper::set('test',array(
		'name' => 'Steve',
		'number' => '0403 832 208'
	));
	

	NGODataSessionWrapper::set('animal1','dog');
	NGODataSessionWrapper::set('animal2','cat');
	//NGODataSessionWrapper::setSafe();
	//NGODataSessionWrapper::setNewId();
	
	
	//echo NGODataSessionWrapper::get('test');
	//echo session_id();
	//NGODataSessionWrapper::destroy();
	NGODataSessionWrapper::display();
	
	*/
?-->


<!-- /body>
</html-->