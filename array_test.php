<?php
 $rows = $_POST['rows'];
 $cols = $_POST['cols'];

 // initRC($rows,$cols);

 function initRC($rows,$cols) {

 if (isset($rows)) {
 $rowsHeads = $rows;
}else {
 $rowsHeads = "key1:val1,key2:val2,key3:val3,key4:val4,key5:val5,key6"; 
}

if(isset($cols)){
  $colmsHeads = $cols; 
}
else {
  $colmsHeads = "col1:val1,col2:val2,col3:val3,col4:val4,col5:val5,col6";
}

//$arr = $keywords = preg_split("/[\s,]+/", $str);


$rgrps = explode(",", $rowsHeads);

//print_r($rgrps);

$result = array();

foreach ($rgrps as $key => $value) {
  
  $subArr =explode(":", $value);
  
  $key=$subArr[0];
  
  if (isset($subArr[1])) {$val=$subArr[1];}
  else {$val=null;}

  $result += array($key => $val );
  
}

$rgrps = $result;
$rows = $rgrps;

/*echo "</br>";
print_r($result);
echo "</br>RowHeads".json_encode($result);
*/

$cgrps = explode(",", $colmsHeads);

//print_r($rgrps);

$result = array();
$lastKey='0';
foreach ($cgrps as $key => $value) {
  
  $subArr =explode(":", $value);
  
  $key=$subArr[0];
  
  if (isset($subArr[1])) {$val=$subArr[1];}
  else {$val=null;}

  $result += array($lastKey => array($key => $val));
  $lastKey = $key;
}

$cgrps = $result;
$cols = $cgrps;


/*echo "</br>";
print_r($result);
echo "</br>ColumnHeads".json_encode($result);
*/

return  ['rows'=>$rgrps,'cols'=>$cgrps];

}

return true;

$users = array (
     'Imtiaz Rayhan' => array ( "Address" => 'Bangladesh', "Occupation" => 'Student', "marks" => array(10,12,30) ),
     'John Doe'      => array ( "Address" => 'Earth', "Occupation" => 'Freelancer', "marks" => array(20,22,40)),
     'Istiak Rayhan' => array ( "Address" => 'Bangladesh', "Occupation" => 'Blogger', "marks" => array(30,32,40))  
  );
//Using a loop to output elements from the array
foreach ($users as $key1 => $val1) {
   echo $key1;
   echo "<br>";
  foreach ($val1 as $key2 => $val2) {
   echo $key2." - >";
   echo "<br>";
   if (is_array($val2)){
       foreach ($val2 as $key3 => $val3) {
       echo $key3." - >".$val3 ;
       echo "<br>";
       }
      }
   } 
  echo "<br>"; 
}  
 
//Using Keys to fetch element from the array
echo $users['Imtiaz Rayhan']['Address'] . "<br>";
 
?>