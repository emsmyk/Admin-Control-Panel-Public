<?
function api_file_old($name, $live){
  $file = "./json/$name.json";
  if(file_exists($file)){
    if (time()-filemtime($file) > $live) {
      return;
    }
    else {
      echo file_get_contents($file);
      die();
      return;
    }
  }
}

function api_update_file($name, $dane){
  $dane = json_encode($dane);
  $file = "./json/$name.json";

  unlink($file);
  file_put_contents($file, $dane);
  echo $dane;
  die();
  return;
}

function beautify_filename($filename) {
    // reduce consecutive characters
    $filename = preg_replace(array(
        // "file   name.zip" becomes "file-name.zip"
        '/ +/',
        // "file___name.zip" becomes "file-name.zip"
        '/_+/',
        // "file---name.zip" becomes "file-name.zip"
        '/-+/'
    ), '-', $filename);
    $filename = preg_replace(array(
        // "file--.--.-.--name.zip" becomes "file.name.zip"
        '/-*\.-*/',
        // "file...name..zip" becomes "file.name.zip"
        '/\.{2,}/'
    ), '.', $filename);
    // lowercase for windows/unix interoperability http://support.microsoft.com/kb/100625
    $filename = mb_strtolower($filename, mb_detect_encoding($filename));
    // ".file-name.-" becomes "file-name"
    $filename = trim($filename, '.-');
    return $filename;
}

function connection($host, $user, $pass, $dbname) {
  $connect = @mysql_connect($host, $user, $pass) or die('blad polaczenia z baza');
  mysql_select_db($dbname,$connect) or die('socket error - brak bazy danych');
  mysql_query("SET NAMES 'utf8'");
}
?>
