<?php

class A
{
    public    $name = 'Vasiliy';
    public    $text = 'Once upon a time';
}

class B
{
    public    $name = 'Had bought a cat';
    public    $text = 'named Valentine';
}

class grid
{
    public function build(  $dataSource, $headers, $fields  )
    {
        $result = '<table border="1">';
        $result .= $this -> make_head( $headers );
       
        foreach ($dataSource as $source):
            $class_name = get_class($source);
            if ( $class_name != FALSE ):
                $reflector = new ReflectionClass($source);
                echo 'Class "'. $class_name .'" found.<br />';
                $result .= $this -> make_row( $reflector, $fields, $source );
            endif;
        endforeach;
       
        $result .= '</table>';
        return $result;
    }
   
    private function make_head( $headers )
    {
        $result = '<tr>';
        foreach ( $headers as $header):
            $result .= "<th>$header</th>";
        endforeach;
        $result .= '</tr>';
        return $result;
    }
   
    private function make_row( $reflector, $fields, $source )
    {
        $result = '<tr>';
        foreach ( $fields as $field ):
            if ( $reflector -> hasProperty($field) ):
                $property = $reflector -> getProperty($field);
                $result .= '<td>'. $property -> getValue($source) .'</td>';
            endif;
        endforeach;
        $result .= '</tr>';
        return $result;
    }
}

$A = new A;
$B = new B;
$C = 'Test';

$dataSource = array( $A, $B, $C );
$headers = array( 'H1', 'H2' );
$fields = array( 'name', 'text' );

$grid = new grid;
$table = $grid -> build( $dataSource, $headers, $fields );
echo $table;

?>