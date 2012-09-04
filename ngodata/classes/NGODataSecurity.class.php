<?php
require_once ('NGOData.class.php');
require_once ('../factories/NGOMetadataFactory.class.php');
//require_once ('NGOMetadatum.class.php');

// this class provides the security methods for NGOData

// Parts of this class are from:
// A reversible password encryption routine by:
// Copyright 2003-2009 by A J Marston <http://www.tonymarston.net>
// Distributed under the GNU General Public Licence

class NGODataSecurity extends NGOData {

	private $scramble1;     // 1st string of ASCII characters
	private $scramble2;     // 2nd string of ASCII characters
	private $adj;           // 1st adjustment value (optional)
    private $mod;			// 2st adjustment value (optional)
    
    private $rounds;
	private $prefix;
    
    private $NGODataGlobVar = 'gSecTerm'; // for global salt
    
    private static $instance;
    
    /**
	 * @desc Holds the content of the salt file if required. Should only be required when bootstrapping the app
	 * NOTE: DO NOT CACHE!!!
	 */
    var $fileSalt;
    //var $errors;        // array of error messages


	private function __construct() {
		// need to get rid of this array...
		
		
		$this->errors = array();
		$s1 = '! #$%&()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[]^_`abcdefghijklmnopqrstuvwxyz{|}~';
		$s2 = 'f^jAEokIOzU[,2&q1{3`h5w_94p@6s8?BgP>dFV=m D<TcS%Ze|r:lGK/uCy.J]x)HiQ!#$~(;Lt-R}7MaNvW+Ynb*0X';
		$adj = 2.50;  // this value is added to the rolling fudgefactors
        $mod = 3;     // if divisible by this the adjustment is made negative
        $this->setInstance($s1, $s2, $adj, $mod);
        
        // security
        $this->rounds = 12;
        
        
        //$this->NGODataGlobVar = ''
	}
	
	public function getInstance() {
		if (!self::$instance) {
			self::$instance = new NGODataSecurity();
		}
		return self::$instance;
	}
	
	
	private function setInstance($s1, $s2, $adj, $mod) {
		$this->scramble1 = $s1; //'! #$%&()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[]^_`abcdefghijklmnopqrstuvwxyz{|}~';
		$this->scramble2 = $s2; // 'f^jAEokIOzU[,2&q1{3`h5w_94p@6s8?BgP>dFV=m D<TcS%Ze|r:lGK/uCy.J]x)HiQ!#$~(;Lt-R}7MaNvW+Ynb*0X';
		$this->adj = $adj; //2.53;  // this value is added to the rolling fudgefactors
        $this->mod = $mod; //3;
        
	}
	
	

	private function validateLogin($username, $password) {
		// ALSO: you should sanitize your username variable with trim() and mysql_real_escape_string(). 
		
		// add this to the database class
		// Ex: mysql_real_escape_string(trim($_POST['username']));
		
		//print '<>br />Password entered: '.$password.'<br />';
		//$sql = 'SELECT password FROM user where username="'.($username).'" limit 1';
		//$db = DaybookDB::getInstance();
		//$data = $db->select($sql);
		
		// DO NOT access objects directly - only go through factories
		$uf = new UserFactory();
		$user = $uf->validateLogin($username, $password);
		//$user = new User();
		if ($user->getUser($username, $password)) {
			// verify password
			if ($this->verify()) {
				// login good
				return true;
			} else {
				// log the attempt
			}
			
		}
		
		return FALSE;
		//return true;
	}
	
	
	
	
	/************** DO NOT USE BELOW *****************/
	/**
	 * Function: generateHash()
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * @desc This is a one-way cipher only
	 * @return string Returns the a string of length 256, the first 128 is the salt.
	 * @param string $entityId The id of the requesting entity - required to retrieve their salt. Default is "0".
	 * @param string $text The text to hash.
	 */
	/*
	public function generateHash($entityId=0, $text='') {
	//private function generateHash($entityId="0", $text) {
		if ($text == '') {
			// nothing to hash!
			$this->userMessage(parent::EMESS);
			$this->logMessage(parent::EMESS, 'NGODataSecurity.class.php', 'generateHash()', 'Cannot generate hash, as no text has been supplied to hash!');
			return FALSE;
		}
		
		// need to get the salts - one from entity, one global
		$entSalt = 'there is no entity salt';
		if ($entityId != "0") {
			$entSalt = $this->getEntitySalt($entityId);
		}
		echo $entSalt;
		$globSalt = $this->getGlobalSalt();
		if (!$globSalt) {
			return false; //Error condition that is already handled 
		}
		echo $globSalt;
		// ok, we have something for both - create hash
		
		$salt = hash('sha512', uniqid(mt_rand(), true) . $globSalt . strtolower($entSalt));
	
		$hash = $salt.$text;
		for ($i=0; $i<100; $i++) {
			$hash = hash('sha512', $hash);
		}
		$hash = $salt.$hash;
		return $hash;
	}
	*/
	
	/**
	 * Function: retrieveHashAndSalt($hash)
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * Date: March 2011
	 * @param string $hash String of length 256, first 128 is salt, second is hashed term.
	 * @return array Returns array containing 2 elements named 'term' and 'hash' that contain strings of length 128.
	 * NOTE: DEPRECATED
	 */
	/*
	public function retrieveHashAndSalt($hash) {
		// record error if no hash
		if ($hash == NULL|| strlen($hash) != 256) {
			//error
			$this->userMessage(parent::EMESS);
			$this->logMessage(parent::EMESS, 'NGODataSecurity.class.php', 'retrieveHashAndSalt()', 'Cannot retrieve hash and salt, as no hash has been supplied.');
			return false;
		}
		// ok
		$has = NULL;
		// split $hash
		$salt = substr($hash, 0 , 128);
		$term = substr($hash, 129, 128);
		$has['term'] = $term;
		$has['salt'] = $salt;
		return $has;
	}
	*/
	
	/**
	 * Function: getSaltFromHash($hash)
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * @param string $hash String of lenmgth 256, first 128 is salt.
	 * @return string Returns the salt - string of length 128
	 */
	/*
	private function getSaltFromHash($hash) {
		// error condition	
		if ($hash == NULL|| strlen($hash) != 256) {
			//error
			$this->userMessage(parent::EMESS);
			$this->logMessage(parent::EMESS, 'NGODataSecurity.class.php', 'getSaltFromHash()', 'Cannot retrieve salt, as no hash has been supplied.');
			return false;
		}
		return substr($hash, 0 , 128);
	}
	*/
	
	/**
	 * Function: getHashedText($text)
	 * NOTE: Probably don't need this - simply use the generateHash function above
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 */
	/*
	public function getHashedText($text) {
		// error condition
		if ($hash == NULL|| strlen($hash) != 256) {
			//error
			$this->userMessage(parent::EMESS);
			$this->logMessage(parent::EMESS, 'NGODataSecurity.class.php', 'getHashedText()', 'Cannot authenticate text, as no text has been supplied.');
			return false;
		}
		$salt = $this->getSaltFromHash($hash);
		
		$pepper = $this->getGlobalSalt();
		
		// error condition
		if (!$globSalt) {
			return false; //Error condition that is already handled 
		}
		
		// ok, we have something for both - create hash
		
		$condiment = hash('sha512', uniqid(mt_rand(), true) . $pepper . strtolower($salt));
	
		$hash = $salt.$text;
		for ($i=0; $i<100; $i++) {
			$hash = hash('sha512', $hash);
		}
		// don't return anything yet...
	}
	*/
	
	// from Daybook-User.class.php
	/*
	private function validateLogin($username, $password) {
		//print '<>br />Password entered: '.$password.'<br />';
		$sql = 'SELECT password FROM user where username="'.($username).'" limit 1';
		$db = DaybookDB::getInstance();
		$data = $db->select($sql);
		
		// error condition - already handled, so pass to UI
		if (!$data) return FALSE;
		
		$hashed = NULL;
		//print_r($data);
		foreach ($data as $key=>$value) {
			foreach ($value as $key2=>$value2) {
				if ($key2 == 'password') $hashed = $value2;
			}
		}
		print '<br />Password from the database: '.$hashed.'<br />';
		if ($hashed != NULL) {
			$salt = substr($hashed, 0, 64);
			$hash = $salt.$password;
			for ($i=0; $i<10000; $i++) {
				$hash = hash('sha256', $hash);
			}
			$hash = $salt.$hash;
			//print 'Hash: '.$hash.'<br />';
			//print 'Password from the database: '.$hashed.'';
			if ($hashed == $hash) return TRUE;
			else return FALSE;
		}
		return FALSE;
		//return true;
	}
	*/
	
	
	/**
	 * Funciton: getGlobalSalt() 
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * @return string Returns the global salt, either from the database, or from a file. FALSE is returned on error.
	 * @desc Attempts to retrieve the global salt from the database. If that fails, an attempt is made to retrieve
	 * the global salt from a file on the filesystem. If THAT fails, an error is generated, and FALSE is returned.
	 */
	/*
	private function getGlobalSalt() {
		echo '<br />In getGlobalSalt()...';
		
		$globSaltMD = 'gSecTerm';
		$globalSalt = '';
		$md = new NGOMetadata();
		//echo '<br />$Resource is '.$md;
		$data = $md->getMetadataByVariable($globSaltMD);
		echo '<br />$data is '.$data;
		if (!$data||$data == NULL) {
			$globalSalt = $this->getSaltFromFile();
		} else {
			$globalSalt = $data->getValue();
		}
		echo '<br />global salt is '.$globalSalt;
		if (!$globalSalt) {
			$this->userMessage(parent::EMESS); //, "Something's gone wrong. We\'re working to get it fixed as soon as we can.");
			$this->logMessage(parent::EMESS, "NGODataSecurity.class.php", "getGlobalSalt", "Unable to get global salt from database or file.");
			return false;
		} else {
			return $globalSalt;
		}
	}
	
	
	private function generateMetadataHash() {
		// use to get the system security phrases. In NGOmetadata table
		// variable is "secterm", value is hashed according to this function
	}
	*/
	
	
	/**
	 * Function: getSaltFromFile()
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * @return string Returns the global salt value
	 * @desc ONLY required when bootstapping the app, but also used for initial testing
	 */
	/*
	private function getSaltFromFile() {
		$fileSalt = '';
			
		$file_handle = fopen("../files/salt.txt", "r");
		while (!feof($file_handle)) {
			//$line = fgets($file_handle);
			$fileSalt = fgets($file_handle);
			break;
			//$fileSalt = $line;
			//echo $line;
			//echo $fileSalt;
		}
		//echo $fileSalt;
		fclose($file_handle);
		if ($fileSalt == '') return false;
		
		//echo $fileSalt;
		return $fileSalt;
	}
	*/
	
	//************** DO NOT USE ABOVE *****************/
	
	/*************** REVERSIBLE CIPHER ******************/ 
	 
	/**
	 * Function: decrypt($key, $source)
	 * @author Copyright 2003-2009 by A J Marston <http://www.tonymarston.net>
	 * @desc decrypt string into its original form
	 * @return string/boolean returns encrypted string, or FALSE on error.
	 * Altered by Steve Cooke, Feb 2011
	 */
	
	//public function decrypt ($entityKey=NULL, $source) {
	public function decrypt ($source)
    // decrypt string into its original form
    {
        $this->errors = array();
		//$key = 'I must go down to the sea again';
		$key = $this->getSaltFromFile();
        
       echo 'The key! '.$key.'<br/>';
        
        // convert $key into a sequence of numbers
        $fudgefactor = $this->convertKey($key);
        if ($this->errors) return;

        if (empty($source)) {
            $this->errors[] = 'No value has been supplied for decryption';
            return;
        } // if

        $target = null;
        $factor2 = 0;

        for ($i = 0; $i < strlen($source); $i++) {
            // extract a (multibyte) character from $source
            //if (function_exists('mb_substr')) {
               // $char2 = mb_substr($source, $i, 1);
           // } else {
                $char2 = substr($source, $i, 1);
           // } // if

            // identify its position in $scramble2
            $num2 = strpos($this->scramble2, $char2);
            if ($num2 === false) {
                $this->errors[] = "Source string contains an invalid character ($char2)";
                return;
            } // if

            // get an adjustment value using $fudgefactor
            $adj     = $this->applyFudgeFactor($fudgefactor);

            $factor1 = $factor2 + $adj;                 // accumulate in $factor1
            $num1    = $num2 - round($factor1);         // generate offset for $scramble1
            $num1    = $this->checkRange($num1);       // check range
            $factor2 = $factor1 + $num2;                // accumulate in $factor2

            // extract (multibyte) character from $scramble1
            //if (function_exists('mb_substr')) {
                //$char1 = mb_substr($this->scramble1, $num1, 1);
           // } else {
                $char1 = substr($this->scramble1, $num1, 1);
            //} // if

            // append to $target string
            $target .= $char1;

            //echo "char1=$char1, num1=$num1, adj= $adj, factor1= $factor1, num2=$num2, char2=$char2, factor2= $factor2<br />\n";

        } // for

        return rtrim($target);

    }
		
		
		
		
		
		
		
		/*
		
		//
		print 'here is the strilng to be decrypted: '.$source;
		
		// get the NGOdata global key from the database
		$m = new NGOMetadatum();
		$NGOKey = $m->getMetadataByVar($this->NGODataGlobVar);
		
		// if it's encrypted then get the key from the file and decrypt it
		
		print 'This is the NGO key from security: ';
		print_r($NGOKey);
		print '....';
		// error condition - already handled inform UI
		if (!$NGOKey) return FALSE;
		
		print '....decrypt: $NGOKey: '.$NGOKey;
		
		if (!isset($source) || strlen($source) == 0) {
			$this->userMessage(parent::WMESS); //, 'The system is unable to complete your request at this time. This has been logged, and the site administrator has been notified.');
			$this->logMessage(parent::EMESS, 'classes/NGODataSecurity', 'decrypt()','No value has been supplied for decryption');
			
			//$this->errors[] = 'No value has been supplied for decryption';
			return FALSE;
		}
		
		// ok - continue
		$key = $NGOKey.$entityKey;
		// convert $key into a sequence of numbers
		$fudgefactor = $this->convertKey($key);

		$target = null;
		$factor2 = 0;

		for ($i = 0; $i < strlen($source); $i++) {
			// extract a (multibyte) character from $source
			if (function_exists('mb_substr')) {
				$char2 = mb_substr($source, $i, 1);
			} else {
				$char2 = substr($source, $i, 1);
			} // if

			// identify its position in $scramble2
			$num2 = strpos($this->scramble2, $char2);
			if ($num2 === false) {
				$this->userMessage(parent::WMESS); //, 'The system is unable to complete your request at this time. This has been logged, and the site administrator has been notified.');
				$this->logMessage(parent::EMESS, 'classes/NGODataSecurity', 'decrypt()','Source string contains an invalid character ($char2)');
				
				//$this->errors[] = "Source string contains an invalid character ($char2)";
				return FALSE;
			} // if

			// get an adjustment value using $fudgefactor
			$adj     = $this->applyFudgeFactor($fudgefactor);

			$factor1 = $factor2 + $adj;                 // accumulate in $factor1
			$num1 = $num2 - round($factor1);         // generate offset for $scramble1
			$num1 = $this->checkRange($num1);       // check range
			$factor2 = $factor1 + $num2;                // accumulate in $factor2

			// extract (multibyte) character from $scramble1
			if (function_exists('mb_substr')) {
				$char1 = mb_substr($this->scramble1, $num1, 1);
			} else {
				$char1 = substr($this->scramble1, $num1, 1);
			} // if

			// append to $target string
			$target .= $char1;
		} // for
		return rtrim($target);
	}*/


	/**
	 * Function: encrypt($key, $source)
	 * @author Copyright 2003-2009 by A J Marston <http://www.tonymarston.net>
	 * @desc decrypt string into its original form
	 * @return string/boolean returns encrypted string, or FALSE on error.
	 * Altered by Steve Cooke, Feb 2011
	 */
	//public function encrypt ($entityKey = "", $source, $sourcelen = 0) {
	public function encrypt ($source, $sourcelen = 0) {
		// str length should always be 256
		
		print ' #Length of source: '.strlen($source);
		if (strlen($source) < 100) {
			//print 'in strlen... - source: "'.$source.'"';
			$source = str_pad($source, 100);
			//echo ' #Length of source: '.strlen($source);
		}
		
		//echo "Source: ".$source."' - Blah! <br>";
		//echo "Length of source: ".strlen($source)."<br>";
		
		$NGOKey = '';
		
		// need to try to get the salt from the database
		$NGOKey = $this->getGlobalSalt();
		//$NGOKey = $this->getSaltFromFile();
		// ok
		//$NGOKey = $m->getValue();
		//$key = $NGOKey.$entityKey;
		$key = $NGOKey;
		//print '...... combined KEY: '.$key;
		// convert $key into a sequence of numbers
		$fudgefactor = $this->convertKey($key);
		//print '....Here is the fudgefactor: ';
		//print_r($fudgefactor);
		//if ($this->errors) return;

		// need to do something about these errors!
		if (empty($source)) {
			$this->userMessage(parent::WMESS); //, 'The system is unable to complete your request at this time. This has been logged, and the site administrator has been notified.');
			$this->logMessage(parent::EMESS, 'classes/NGODataSecurity', 'encrypt()','No value has been supplied for encryption');
				
			//$this->errors[] = 'No value has been supplied for encryption';
			return false;
		} // if

		// pad $source with spaces up to $sourcelen
		//$source = str_pad($source, $sourcelen);

		$target = null;
		$char1 = null;
		$factor2 = 0;

		for ($i = 0; $i < strlen($source); $i++) {
			// extract a (multibyte) character from $source
			if (function_exists('mb_substr')) {
				$char1 = mb_substr($source, $i, 1);
			} else {
				$char1 = substr($source, $i, 1);
			} // if
			//echo "What is char1? ".$char1."<br>";
			// identify its position in $scramble1
			$num1 = strpos($this->scramble1, $char1);
			if ($num1 === false) {
				$this->userMessage(parent::WMESS); //, 'The system is unable to complete your request at this time. This has been logged, and the site administrator has been notified.');
				$this->logMessage(parent::EMESS, 'classes/NGODataSecurity', 'encrypt()','Source string contains an invalid character ($char1)');
					
				//$this->errors[] = "Source string contains an invalid character ($char1)";
				return false;
			} // if

			// get an adjustment value using $fudgefactor
			$adj = $this->applyFudgeFactor($fudgefactor);
			$factor1 = $factor2 + $adj;             // accumulate in $factor1
			$num2 = round($factor1) + $num1;     // generate offset for $scramble2
			$num2 = $this->checkRange($num2);   // check range
			$factor2 = $factor1 + $num2;            // accumulate in $factor2

			// extract (multibyte) character from $scramble2
			if (function_exists('mb_substr')) {
				$char2 = mb_substr($this->scramble2, $num2, 1);
			} else {
				$char2 = substr($this->scramble2, $num2, 1);
			} // if

			// append to $target string
			//echo "What is char2? ".$char2."<br>";
			$target .= $char2;
			//echo "What is target now? ".$target."<br>";

			//echo "char1=$char1, num1=$num1, adj= $adj, factor1= $factor1, num2=$num2, char2=$char2, factor2= $factor2<br />\n";

		} // for
		//echo "Encrypted text: ".$target."<br>";
		return $target;
	}



 	private function convertKey ($key) {
		// convert $key into an array of numbers
		if (empty($key)) {
			$this->userMessage(parent::WMESS); //, 'The system is unable to complete your request at this time. This has been logged, and the site administrator has been notified.');
			$this->logMessage(parent::EMESS, 'classes/NGODataSecurity', 'convertKey()','No value has been supplied for the encryption key');
			
			//$this->errors[] = 'No value has been supplied for the encryption key';
			return false;
		} // if

		$array[] = strlen($key);    // first entry in array is length of $key

		$tot = 0;
		for ($i = 0; $i < strlen($key); $i++) {
		// extract a (multibyte) character from $key
			if (function_exists('mb_substr')) {
				$char = mb_substr($key, $i, 1);
			} else {
				$char = substr($key, $i, 1);
			} // if

			// identify its position in $scramble1
			$num = strpos($this->scramble1, $char);
			if ($num === false) {
				// error
				$this->userMessage(parent::WMESS); //, 'The system is unable to complete your request at this time. This has been logged, and the site administrator has been notified.');
				$this->logMessage(parent::EMESS, 'classes/NGODataSecurity', 'convertKey()','Key contains an invalid character ('.$char.')');
				//$this->errors[] = "Key contains an invalid character ($char)";
				return false;
			} // if
			$array[] = $num;        // store in output array
			$tot = $tot + $num;     // accumulate total for later
		} // for
		$array[] = $tot;            // insert total as last entry in array
		return $array;
	}
	
	
	private function checkRange ($num) {
		// check that $num points to an entry in $this->scramble1
		$num = round($num);         // round up to nearest whole number
		$limit = strlen($this->scramble1);
		while ($num >= $limit) {
			$num = $num - $limit;   // value too high, so reduce it
		} // while
		while ($num < 0) {
			$num = $num + $limit;   // value too low, so increase it
		} // while
		return $num;
	}
	
	
	private function applyFudgeFactor (&$fudgefactor) {
		// return an adjustment value  based on the contents of $fudgefactor
		// NOTE: $fudgefactor is passed by reference so that it can be modified
		$fudge = array_shift($fudgefactor);     // extract 1st number from array
		$fudge = $fudge + $this->adj;           // add in adjustment value
		$fudgefactor[] = $fudge;                // put it back at end of array

		if (!empty($this->mod)) {               // if modifier has been supplied
			if ($fudge % $this->mod == 0) {     // if it is divisible by modifier
				$fudge = $fudge * -1;           // make it negative
			} // if
		} // if
		return $fudge;
	}
}
?>