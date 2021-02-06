<?
function escape($str){
	$search=array("\\","\0","\n","\r","\x1a","'",'"');
	$replace=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
	return str_replace($search,$replace,$str);
}
function unescape($str) {
	$replace=array("\\","\0","\n","\r","\x1a","'",'"');
	$search=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
	return str_replace($search,$replace,$str);
}
function preps($text){
	$text = str_replace("&lt;", "", $text);
	$text = str_replace("&gt;", "", $text);
	$text = preg_replace("/\<([^\*]+)\>/", " ", $text);
	preg_match("/\[url:\]([^\*]+)\[\/url\]/", $text, $matches);
	if(is_array($matches))
		foreach($matches as $match)
		$text = str_replace("[url:]".$match."[/url]","<a href='".$match."' target='_blank'>".$match."</a>",$text);

	$text=preg_replace("#\[b:\](.*?)\[/b\]#si", "<b>\\1</b>", $text);
	return $text;
}
function real_string($text){
	return $text = trim(escape(nl2br(preps(trim($text)))));
}
function real_string2($text){
	return $text = trim((nl2br((trim($text)))));
}
?>
