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
$accion = mosGetParam( $_REQUEST, 'accion');

switch ($screen)
{
	default:
		$logFile->writeLog("Nuevo Sobre: menu principal");
		NavUser();
		mostrarTitulo("NUEVO SOBRE");
		ModuloNewSobre($accion);
		NavUser();
		break;
}
$logFile->writeLog("FIN Nuevo Sobre");

?>
