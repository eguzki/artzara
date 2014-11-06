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
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.');
$artzaraadminpath = $mosConfig_absolute_path.'/components/com_artzaraadmin/';
$artzaraadminurl = $mosConfig_live_site.'/components/com_artzaraadmin/';

$artzarauserpath = $mosConfig_absolute_path.'/components/com_artzarauser/';
$artzarauserurl = $mosConfig_live_site.'/components/com_artzarauser/';

$baseURL = $mosConfig_live_site."/index.php?Itemid=$Itemid&option=$option";

// load the html drawing class
require_once( $mainframe->getPath( 'front_html' ) );
require_once( 'administrator/includes/menubar.html.php' );

// load the libs
require_once( $artzaraadminpath."artzaraadmin.class.php" );
require_once( $artzaraadminpath."artzara.html.php" );
require_once( $artzaraadminpath."artzara.lib.php" );
require_once( $artzaraadminpath."artzara.ddbb.php" );
require_once( $artzaraadminpath."artzaralog.php" );
require_once( $artzaraadminpath."keypad/keypad.php" );
// Por ahora un unico idioma, pero luego dependera del usuario
require_once( $artzaraadminpath."artzara.es.php" );
// load the libs from user
require_once( $artzarauserpath."artzarauser.lib.php" );

if (file_exists( $artzaraadminpath .'installation/index.php' )){
	require_once ( $artzaraadminpath."installation/index.php" );
}
else{
$userMambo = $mainframe->getUser();
if(!$userMambo->id){
	// Pantalla Inicio
	$socios = &DDBB::obtenerSocios(1);
	if($socios === NULL)
	{
		EnvioError("No se pudieron obtener los socios",'000');
		break;
	}
	PantallaLogin($socios);
}
else{
$user = &DDBB::obtenerSocioFromMambo($userMambo->id);
if( $user === null || !$user->activo){
	$baseURL = "index.php?e=1";
	EnvioError("El usuario registrado no se encuentra dado de alta, o no esta activo","000");
}
else{

$logFile = new LogClass("User",$_SERVER['REMOTE_ADDR'],$user->id.": {$user->nick}({$user->nombre}):");
$logFile->openFile();
$logFile->writeLog("BEGIN SCRIPT");


if(!isset($task))
	$task = mosGetParam( $_REQUEST, 'task', "0");

list($menuoption,$screen) = GenericLib::obtenerRuta($task);
$logFile->writeLog("menuoption=$menuoption screen=$screen");

//$logFile->writeLog("REQUEST_URI={$_SERVER['REQUEST_URI']}");

$r = mosGetParam( $_REQUEST, 'Itemid', null );
if($r === null)
{
	$message = 'Itemid no enviado';
	EnvioError($message,"000");
}
else
{

switch ($menuoption) {

	case "11":
		echo "<div class=\"main\">";
		$logFile->writeLog("Menuoption: Nuevo Sobre");
		include_once($artzarauserpath."artzarauser.newsobre.php");
		echo "</div>";
		break;

	case "12":
		echo "<div class=\"main\">";
		$logFile->writeLog("Menuoption: Socio Info");
		include_once($artzarauserpath."artzarauser.socioinfo.html.php");
		include_once($artzarauserpath."artzarauser.socioinfo.php");
		echo "</div>";
		break;

	case "13":
		echo "<div class=\"main\">";
		$logFile->writeLog("Menuoption: Sobres");
		include_once($artzarauserpath."artzarauser.sobres.php");
  		echo "</div>";
		break;
	case "99":
		$logFile->writeLog("Menuoption: ImprimirSobre");
		PrintSobre();
		break;
	default:
		echo "<div class=\"main\">";
		$logFile->writeLog("A_MainMenu");
		MenuPrincipal();
		echo "</div>";
		break;
}
}
$logFile->writeLog("END SCRIPT");
//Final del Script
$logFile->closeFile();

//FIN DEL SCRIPT DE INICIO

}
}
}

?>
