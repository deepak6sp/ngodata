<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../model/TitleFactory.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../database/DBFactory.class.php';

//require_once $_SERVER['DOCUMENT_ROOT'].'/../database/DBFactory.class.php';


$str = 'title_id_another_couple';
echo '<pre>String before: ';
print_r($str);
echo '</pre>';
// adapted from: https://snipt.net/hongster/underscore-to-camelcase/
$words = explode('_', strtolower($str));
$return = '';
foreach ($words as $word) {
$return .= ucfirst(trim($word));
}

//$str = str_replace('_','',ucwords(str_replace('_','_',$str)));
echo '<pre>String after: ';
print_r($return);
echo '</pre>';

//$tf = new TitleFactory(new SQLFactory(new DBFactory()));
//$tf->setDBFactory($this->dbFactory);
		
		//echo '<pre>The Title factory in InitialDataContainer: ';
		//print_r($tf);
		//echo '</pre>';
		
		
		// id => 0 means new field, and assign new id
/*
$data[] = array('id'=>'0');
$data[] = array('title'=>'Mr');
$data[] = array('description'=>'Mister');
$pd = new PostData($data);
$tdao = $tf->createTitle($pd);
echo '<pre>The Title data object: ';
print_r($tdao);
echo '</pre>';
$tdao->saveObject();
*/



//echo 'Testing validation: ';
//$tv = array();
/*
try {
	//$factory = new DBConnectionFactory();
	//$database = $factory->getDatabaseConnection('ngodata');
	$config = DBConfigurationFactory::getDatabaseConfiguration('ngodata');
	
	//$database = new MySQLConfiguration("localhost", "ngodata", "tLp_1039", "ngodata");
	$db = DBFactory::createDatabaseHandler($config);
	$res = $db->query("SELECT description FROM title");
}
catch (Exception $e) {
	echo "<p>sorry, there is an error.</p>";
	// log the error...
	//send_error($e->getMessage());
}
*/
?>
