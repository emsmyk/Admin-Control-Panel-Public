<?php
/**
 * File is released under GPL as can be found on
 * http://www.gnu.org/licenses/gpl.html
 */
error_reporting(0);

/* Info string */
define('A2S_INFO', "\xFF\xFF\xFF\xFF\x54\x53\x6F\x75\x72\x63\x65\x20\x45\x6E\x67\x69\x6E\x65\x20\x51\x75\x65\x72\x79\x00");

/* Replies for HL Version 1 and Version 2 (aka Source) */
define('REPLY_INFO_HL1', 'm');
define('REPLY_INFO_HL2', 'I');

/* Definitions of the bytes */
define('BYTE',     1);
define('BYTE_NUM', BYTE + 1);
define('SHORT',    BYTE_NUM + 1);
define('LONG',     SHORT + 1);
define('FLOAT',    LONG + 1);
define('STRING',   FLOAT + 1);

/**
 * The socket class
 * @author Herwin Weststrate aka Hdez
 * @contact hdez@counter-strike.nl
 * @version 2005.10.21
 */
class HLSocket
{
	/* The socket file descriptor */
	var $_socket;

	/* The way to split the incoming data */
	var $_split_info_hl2 = array('type' => BYTE, 'bersion' => BYTE_NUM, 'hostname' => STRING, 'map' => STRING, 'gamedir' => STRING, 'gamedesc' => STRING, 'appid' => SHORT, 'unknown' => BYTE_NUM, 'players' => BYTE_NUM, 'max' => BYTE_NUM, 'bots' => BYTE_NUM, 'dedicated' => BYTE, 'os' => BYTE, 'passworded' => BYTE_NUM, 'secure' => BYTE_NUM, 'gameversion' => STRING);
	var $_split_info_hl1 = array('type' => BYTE, 'ip' => STRING, 'hostname' => STRING, 'map' => STRING, 'gamedir' => STRING, 'gamedesc' => STRING, 'players' => BYTE_NUM, 'max' => BYTE_NUM, 'version' => BYTE_NUM, 'dedicated' => BYTE, 'os' => BYTE, 'passworded' => BYTE_NUM, 'secure' => BYTE_NUM, 'gameversion' => STRING);

	/**
	 * Create a new socket
	 * @param $host The ip or hostname
	 * @param $port The port
	 */
	function HLSocket($host, $port)
	{
		$this->connect($host, $port);
	}

	/**
	 * Actually make the connection to the host
	 * @param $host The ip or hostname
	 * @param $port The port
	 */
	function connect($host, $port)
	{
		$this->_socket = @fsockopen('udp://'.$host, $port);
		if (!$this->_socket)
			echo 'Error met connecten';
		stream_set_timeout($this->_socket, 1); // Set timeout to 1 sec
	}

	/**
	 * Close the connection (and the socket fd)
	 */
	function close()
	{
		fclose($this->_socket);
	}

	/**
	 * Query the server for the details
	 * @return associative array with the game info
	 */
	function details()
	{
		$this->write(A2S_INFO);
		$data = $this->read();
		$res = array();
		switch(substr($data, 0, 1))
		{
			case REPLY_INFO_HL1:
				$res = $this->split($this->_split_info_hl1, $data);
				break;
			case REPLY_INFO_HL2:
				$res = $this->split($this->_split_info_hl2, $data);
				break;
		}
		return $res;
	}

	/**
	 * Write the given message over the socket
	 * @param $msg The message to be written
	 * @deprecated This should be issued as a private function
	 */
	function write($msg)
	{
		fwrite($this->_socket, $msg);
	}

	/**
	 * Read from the socket
	 * @return The data from the socket (excluding the first four [useless] bytes)
	 * @deprecated This should be issued as a private function
	 */
	function read()
	{
		$data = fread($this->_socket, 1);
		$status = socket_get_status($this->_socket);
		if (isset($status['unread_bytes']) && $status['unread_bytes'] > 0)
			$data .= fread($this->_socket, $status['unread_bytes']);
		return substr($data, 4);
	}

	/**
	 * Split the given datatype from $data String and return the value
	 * @param $type The data type [BYTE .. STRING]
	 * @param $data The current data String
	 * @return The value of the given data type from $data
	 * @deprecated This should be issued as a private function
	 */
	function splititem($type, &$data) {
		$add = '';
		switch ($type)
		{
			case BYTE:
				$add = substr($data, 0, 1);
				$data = substr($data, 1);
				break;
			case BYTE_NUM:
				$add = ord(substr($data, 0, 1));
				$data = substr($data, 1);
				break;
			case SHORT:
				$add = ord(substr($data, 0, 1));
				$data = substr($data, 1);
				break;
			case LONG:
				$add = ord(substr($data, 0, 1));
				$data = substr($data, 1);
				break;
			case STRING:
				do
				{
					$char = substr($data, 0, 1);
					if ($char != "\x00")
						$add .= $char;
					$data = substr($data, 1);
				}
				while ($char != "\x00");
				break;
		}
		return $add;
	}

	/**
	 * Split the given datatypes from $data String and return the value
	 * @param $array The data type [BYTE .. STRING] as values of an
	 *               associative array. The keys are also the keys of
	 *               the return array
	 * @param $data The current data String
	 * @return Associative array with keys of $array and values read from $data
	 * @deprecated This should be issued as a private function
	 */
	function split($array, $data)
	{
		$res = array();
		foreach ($array as $k=>$v)
			$res[$k] = $this->splititem($v, $data);
		return $res;
	}
}
?>
