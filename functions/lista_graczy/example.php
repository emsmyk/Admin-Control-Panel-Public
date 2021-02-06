<?php
namespace phpRcon;
use phpRcon\games\BF3;
use phpRcon\games\COD4;
use phpRcon\games\Rust;
use phpRcon\games\CSGO;
spl_autoload_register(function ($class) {
	$class = 'lib/'.str_replace('\\', '/', substr($class, 7)).'.class.php';
	if (file_exists($class)) {
		require_once($class);
	}
});
?>


<br />
<br />
<br />
<?php
$cod = new CSGO("91.224.117.113", 27015);
$players = $cod->getPlayers();
echo $cod->getServerName()."<br />";
echo $cod->getCurrentMap()." - ".$cod->getCurrentMode()."<br />";
?>
Players (<?php  echo $cod->getCurrentPlayerCount()."/".$cod->getMaxPlayers(); ?>) :<br />
<table width="100%" border="1">
	<tr>
		<td width="14.29%">name</td>
		<td width="14.29%">score</td>
	</tr>
<?php
		if (!empty($players)) {
			foreach ($players as $id => $player) {
				echo "\t<tr>\n";
				echo "\t\t<td>".$player['name']."</td>\n";
				echo "\t\t<td>".$player['score']."</td>\n";
				echo "\t\t<td>".$player['time']."</td>\n";
				echo "\t</tr>\n";
			}
		}
	?>
</table>