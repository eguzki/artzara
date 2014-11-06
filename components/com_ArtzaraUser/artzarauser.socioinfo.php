<?php
//Artzara Admin Component//
/**
* Content code
* @package ARTZARAUSER
* @Copyright (C) 2004 Doyle Lewis
* @ All rights reserved
* @ hello_world is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version 1.0
**/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
//$screen viene de arriba
$accion = mosGetParam( $_REQUEST, 'accion', "0");

switch ($screen)
{
	default:
		$logFile->writeLog("Mostrar informacion de Socio");
		NavUser();
		mostrarTitulo("INFORMACION SOCIO");
		ModuloSocioInfo($accion);
		NavUser();
		break;
}

function ModuloSocioInfo($act){
	global $task,$option,$Itemid,$database,$logFile,$user,$baseURL;
	$logFile->writeLog("accion: $act");
	switch($act)
	{
		case "save":
			$logFile->writeLog("ModuloSocioInfo: Actualizar datos Socio");
			$idioma = mosGetParam( $_REQUEST,'idioma');

			if(!$idioma){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}

			$socio = &DDBB::obtenerSocio($user->id);
			$socio->idioma = $idioma;

			if(!$socio->store())
			{
				$message = 'El socio NO PUDO Guardarse. Pruebe otra vez';
				EnvioError($message,$task);
				break;
			}

			$mamboSocio = new mosUser($database);
			if(!$mamboSocio->load( $user->id_mambo ))
			{
				$logFile->writeLog("ModuloSocioInfo: Error: ".$mamboSocio->getError());
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}

			$mamboSocio->orig_password = $mamboSocio->password;

			if(isset($_POST["password"]) && $_POST["password"] != "") {
				$mamboSocio->password = md5($_POST["password"]);
			} else {
				// Restore 'original password'
				$mamboSocio->password = $mamboSocio->orig_password;
			}

			if (!$mamboSocio->check()) {
				echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
				break;
			}

			unset($mamboSocio->orig_password); // prevent DB error!!

			if (!$mamboSocio->store()) {
				echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
				break;
			}

			mosRedirect($baseURL."&task=$task");
			break;
		case "exit":
			mosRedirect($baseURL."&task=000");
			break;
		default:
			$logFile->writeLog("user: {$user->idioma}");
			FormularioSocioInfo($user);
			break;
	}
}

?>
