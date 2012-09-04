<?php
// error factory
//require_once '../classes/Error.class.php';
class ExceptionFactory {
	
// ERROR HANDLING system
	
	// - include for static classes
	const EMESS = 0; // Error message
	const WMESS = 1; // Warning message
	const IMESS = 2; // Information message
	
	const ERRLOG = '../NGOData.log';
	
	// default message text
	private $eMessageText = "Oops! Something's gone wrong. It's been logged, and we're working to get it fixed as soon as we can.";
	private $wMessageText = "Oops! Something's gone wrong. It's been logged, and we're working to get it fixed as soon as we can, but you may try again.";
	
	
	public function __construct() {
	}
	
	/**
	 *
	 */
	private function translateMessageType($messType) {
		switch ($messType) {
			case self::EMESS:
				return "error";
				break;
			
			case self::WMESS:
				return "warning";
				break;
			
			case self::IMESS:
				return "information";
				break;
			
			default:
				return "Unknown message type";
		}		
	}
	
	// the 
	public function raiseError($error) {
		// $error is the caught exception
		$newErrorArray = $this->translateArrayIntoError($error);
		$newErrorArray['error_type'] = self::EMESS;
		return $this->processError($newErrorArray);
		//$this->setUserMessage(self::EMESS, $this->eMessageText);
		//print_r($newErrorArray);
		
		// returning FALSE means error logging failed - stop system
		return $this->processError($newErrorArray);
		//return $this->logError($newErrorArray);
	}
	
	public function raiseWarning($error) {
		$newErrorArray = $this->translateArrayIntoError($error);
		$newErrorArray['error_type'] = self::WMESS;
		return $this->processError($newErrorArray);
	}
	
	public function raiseMessage($error) {
		$newErrorArray = $this->translateArrayIntoError($error);
		$newErrorArray['error_type'] = self::IMESS;
		return $this->processError($newErrorArray);
		//$this->setUserMessage(self::IMESS, $this->eMessageText);
	}
	
	private function processError($error) {
		// set the message sent to the UI
		$errorType = $error['error_type'];
		switch($error['error_type'])  {
			case self::EMESS:
				$this->setUserMessage(self::EMESS, $this->eMessageText);
				break;
			case self::WMESS:
				$this->setUserMessage(self::WMESS, $this->wMessageText);
				break;
			case self::IMESS:
				$this->setUserMessage(self::IMESS, $error['message']);
				break;
		}
		// returning FALSE means error logging failed - stop system
		//return $this->logError($error);
		$this->logMessage($error);
	}
	
	
	private function logError($errorArray) {
		$error = new Error();
		if ($error->create($errorArray)) return TRUE;
		else return FALSE;
	}
	
	private function translateArrayIntoError($error) {
		$newErrorArray['class'] = $error->getFile();
		$trace = $error->getTrace();
		$newErrorArray['function'] = $trace[0]['function'];
		//$newErrorArray['function'] = $error->getTrace()->[0]['function'];
		$newErrorArray['message'] = $error->getMessage();
		// should check $_SESSION is set...
		$newErrorArray['user_id'] = $_SESSION['user_id'];
		return $newErrorArray;
	}
	
	/**
	 * Function: formatMessage(int $messageType, string $messageText)
	 * @author Steve Cooke
	 * Date: Jan 2011
	 * @param int $messageType Describes the type of message to be displayed
	 * @param string $messageText The textual content of the message.
	 * Part of error handling system - display prettified message to users.
	 */
	public function setUserMessage($messageType, $messageText='') {
		// good message text!
		// Something's gone wrong. We're working to get it fixed as soon as we can.
		$_SESSION['message_type'] = self::translateMessageType($messageType);
		if ($messageText == '') {
			// use default message
			$_SESSION['message_text'] = self::getDefaultMessage($messageType);
		} else {
			$_SESSION['message_text'] = $messageText;
		}
		return true;
	}
	
	
	private function getDefaultMessage($messageType) {
		if ($messageType == self::EMESS) return $this->eMessageText;
		return $this->wMessageText;
	}
	
	private function checkIsUserMessageSet() {
		if (!isset($_SESSION['message_text'])) return false;
		return true;
	}
	
	
	/** 
	 * FUnction: logMessage(string $messType, string $errorMessage)
	 * @author Steve Cooke <sa_cooke@internode.on.net>
	 * Date: Jan 2011
	 * @param string $messType Describes the message - could be WARNING,
	 * ERROR, INFORMATION, etc.
	 * @param string $message The error description to write to the log
	 * @return Returns TRUE if file is locked and written to; else FALSE is returned.
	 */
	//public function logMessage($messType, $class, $function, $messText) {
	public function logMessage($messageArray) {
		$message = "------ ".$this->translateMessageType($_SESSION['message_type'])." ------\r\n";
		$message .= "Date: ".date('d m y H:i:s')."\r\n";
		$message .= "Class: ".$messageArray['class']."\r\n";
		$message .= "Function: ".$messageArray['function']."\r\n";
		$message .= "Message: ".$messageArray['message']."\r\n";
		
		// may need to test whether we have a legal file handle
		$fp = fopen(self::ERRLOG, 'a');

		if (flock($fp, LOCK_EX)) { // gain an exclusive lock
			fwrite($fp, $message);
			flock($fp, LOCK_UN); // release the lock
		} else {
			// cannot get lock
			return false;
		}

		fclose($fp);
		return true;
	}
}
?>