<?php



class ArrayStringFunctions {
    public static function arrayToString($arrayIn) {
      $string_out = NULL;
	  foreach ($arrayIn as $oneChar) {
	    $string_out .= $oneChar;
	  }
	  return $string_out;
    }
    public static function stringToArray($stringIn) {
      return str_split($stringIn);
    }
}

?>
