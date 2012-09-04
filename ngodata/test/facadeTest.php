<?php

function __autoload($class_name) {
  require($class_name. ".class.php");
}

  writeln('BEGIN TESTING FACADE PATTERN');
  writeln('');
 
  $book = new Book('Design Patterns', 'Gamma, Helm, Johnson, and Vlissides');

  writeln('Original book title: '.$book->getTitle());
  writeln('');

  $bookTitleReversed = CaseReverseFacade::reverseStringCase($book->getTitle());  
 
  writeln('Reversed book title: '.$bookTitleReversed);
  writeln('');

  writeln('END TESTING FACADE PATTERN');
 
  function writeln($line_in) {
    echo $line_in."&lt;br/&gt;";
  }

?>
