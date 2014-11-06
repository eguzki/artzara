<?php
/**
* Artzara - A Mambo Forms Application
* @version 1.0.0
* @package Artzara
* @copyright (C) 2004 by Peter Koch
* @license Released under the terms of the GNU General Public License
**/


class LogClass
{
	var $filePointer	= NULL;		// Puntero a fichero
	var $fileName		= NULL;		// path del fichero
	var $ip				= NULL;		// visitor ip
	var $userName		= NULL;		// visitor name
	var $level			= NULL;		// Log level
	var $tActual		= NULL;		// date

	function LogClass($level,$ip,$user)
	{
		//miramos si el archivo correspondiente a hoy esta abierto, sino, abrimos uno nuevo

		$this->tActual = time();
		$this->ip = $ip;
		$this->level = $level;
		$this->userName = $user;
	}

	function openFile()
	{
		global $artzaraadminpath,$artzarauserpath;
		$name = "";
		$isLevelValid = TRUE;

		switch($this->level){
			case "Admin":
				$path = $artzaraadminpath;
				break;
			case "User":
				$path = $artzarauserpath;
				break;
			default:
				$isLevelValid = FALSE;
				break;
		}

		if(!$isLevelValid)
			return FALSE;

		$name = $this->level.date("dmy",$this->tActual).".log";

		$this->fileName = $path."log/".$name;

		// alli es donde ira $contenido cuando llamemos fwrite().
		if (!$this->filePointer = fopen($this->fileName, 'ab')) {
			return FALSE;
		}

		return TRUE;
	}

	function closeFile()
	{
		fclose($this->filePointer);
	}

	function writeLog($mss)
	{
		if($this->filePointer == NULL)
			return FALSE;

		$message = date("d-m-Y h:i:s A")."\t".$this->ip."\t".$this->userName."\t";
		$message .= $mss."\n";

		// Escribir $message a nuestro arcivo abierto.
		if (fwrite($this->filePointer, $message) === FALSE)
		{
			return FALSE;
    	}
		fflush($this->filePointer);
		return TRUE;
	}
}

?>
