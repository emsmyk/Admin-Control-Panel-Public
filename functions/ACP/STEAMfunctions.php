<?
function toCommunityID($id) {
	if (preg_match('/^STEAM_/', $id)) {
		$parts = explode(':', $id);
		return bcadd(bcadd(bcmul($parts[2], '2'), '76561197960265728'), $parts[1]);
	} elseif (is_numeric($id) && strlen($id) < 16) {
		return bcadd($id, '76561197960265728');
	} else {
		return $id;
	}
}
function toSteamID($id) {
    if (is_numeric($id) && strlen($id) >= 16) {
        $z = bcdiv(bcsub($id, '76561197960265728'), '2');
    } elseif (is_numeric($id)) {
        $z = bcdiv($id, '2');
    } else {
        return $id;
    }
    $y = bcmod($id, '2');
    return 'STEAM_0:' . $y . ':' . floor($z);
}
?>
