<?php
require_once '../database/iBindParam.class.php';

class BindParam implements iBindParam {
	private $values = array(); 
	private $types = '';
    
    public function __construct() {
	    // nothing here yet
    }
    
	public function add($type, &$value ) {
		$this->values[] = $value;
		$this->types .= $type;
	}
   
	public function get(){
		//return 
		$bindParts = array_merge(array($this->types), $this->values);
		$bindText = '';
		for ($i=0; $i<count($bindParts); $i++) {
			$bindText .= '\''.$bindParts[$i].'\',';
		}
		// remove hanging comma
		$bindText = substr($bindText, 0, -1);
		return $bindText;
	}
	
	public function getBindResult() {
		$bindText = '';
		for ($i=0; $i<count($this->values); $i++) {
			$bindText .= '\''.$this->values[$i].'\',';
		}
		// remove hanging comma
		$bindText = substr($bindText, 0, -1);
		return $bindText;
	}
	
	public function getTypes() {
		return $this->types;
	}
	
	public function getValues() {
		return $this->values;
	}
    
// USAGE
/*
<?php
$bindParam = new BindParam();
$qArray = array();

$use_part_1 = 1;
$use_part_2 = 1;
$use_part_3 = 1;

$query = 'SELECT * FROM users WHERE ';
if($use_part_1){
    $qArray[] = 'hair_color = ?';
    $bindParam->add('s', 'red');
}
if($use_part_2){
    $qArray[] = 'age = ?';
    $bindParam->add('i', 25);
}
if($use_part_3){
    $qArray[] = 'balance = ?';
    $bindParam->add('d', 50.00);
}

$query .= implode(' OR ', $qArray);

//call_user_func_array( array($stm, 'bind_param'), $bindParam->get());

echo $query . '<br/>';
var_dump($bindParam->get());
?>

This gets you the result that looks something like this:

SELECT * FROM users WHERE hair_color = ? OR age = ? OR balance = ?
array(4) { [0]=> string(3) "sid" [1]=> string(3) "red" [2]=> int(25) [3]=> float(50) } 
*/
}
?> 