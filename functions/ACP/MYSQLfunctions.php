<?
function query($sql){ mysql_query($sql); }
function one($sql){ $one = mysql_fetch_array(mysql_query($sql)); return $one[0]; }
function row($sql){ return mysql_fetch_object(mysql_query($sql)); }
function all($sql){ $query = mysql_query($sql); $i =0; 	$array = ""; while ($row = mysql_fetch_object($query)){ $array[$i++] = $row; } return $array; }
function insert($tble, $array){
  $fields = Array();
  $values = Array();
  foreach($array as $key => $value){
    array_push($fields,$key);
    $value = ($value==='NOW') ? date('Y-m-d H:i:s') : $value;
    array_push($values,"'$value'");
  }

  $insertquery = 'INSERT INTO ' . $tble . ' (' . join(',',$fields) . ') VALUES (' . join(',',$values) . ')';
  $result = mysql_query($insertquery);
  if (!$result) {
    return 'Błąd zapytania: ' . mysql_error();
  }
  return;
}

function show($what, $die=true){ echo "<pre>";   print_r($what); echo "</pre>"; if($die==true) { die; } }
function show_kolor($var, $fontSize=10, $die=true){
    $text = print_r($var, true);
    // color code objects
    $text = preg_replace('#(\w+)(\s+Object\s+\()#s', '<span style="color: #079700;">$1</span>$2', $text);
    // color code object properties
    $text = preg_replace('#\[(\w+)\:(public|private|protected)\]#', '[<span style="color: #000099;">$1</span>:<span style="color: #009999;">$2</span>]', $text);

    echo '<pre style="font-size: '.$fontSize.'px; line-height: '.$fontSize.'px;">'.$text.'</pre>';

    if($die==true) { die; }
}

function getClass($name){ require_once('functions/'.$name.'Mgr.php');  eval('$class  = new '.$name.'Mgr;'); return $class; }

function getPlayer($id){ $player = row("select * from acp_users where user = $id"); return $player; }
?>
