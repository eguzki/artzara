<?php
//Artzara Admin Component//
/**
* Content code
* @package hello_world
* @Copyright (C) 2004 Doyle Lewis
* @ All rights reserved
* @ hello_world is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version 1.0
**/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.');


$artzaraadminpath = $mosConfig_absolute_path.'/components/'.$option.'/';
$artzaraadminurl = $mosConfig_live_site.'/components/'.$option.'/';
$baseURL = $mosConfig_live_site."/index.php?Itemid=$Itemid&option=$option";

// load the html drawing class
require_once( $mainframe->getPath( 'front_html' ) );
require_once( $mainframe->getPath( 'class' ) );
require_once( 'administrator/includes/menubar.html.php' );

// load the libs
require_once ( $artzaraadminpath."artzara.html.php" );
require_once ( $artzaraadminpath."artzara.lib.php" );
require_once ( $artzaraadminpath."artzara.ddbb.php" );
require_once ( $artzaraadminpath."artzaralog.php" );
require_once ( $artzaraadminpath."keypad/keypad.php" );
// Por ahora un unico idioma, pero luego dependera del usuario
require_once ( $artzaraadminpath."artzara.es.php" );
// libreria from Admin
require_once ( $artzaraadminpath."artzaraadmin.lib.php" );

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
		EnvioError("No se pudieron obtener los socios",$task);
		break;
	}
	PantallaLogin($socios);
}
else{

$user = &DDBB::obtenerSocioFromMambo($userMambo->id);
if( $user === null || !$user->activo || !$user->acceso_admin){
	$baseURL = "index.php?r=2";
	EnvioError("El usuario registrado no se encuentra dado de alta o no tiene permisos o no esta activo","000");
	//$logFile->writeLog("El usuario registrado no se encuentra dado de alta o no tiene permisos o no esta activo");
}
else{

$logFile = new LogClass("Admin",$_SERVER['REMOTE_ADDR'],$user->id.": {$user->nick}({$user->nombre}):");
$logFile->openFile();
$logFile->writeLog("BEGIN SCRIPT");


if(!isset($task))
	$task = mosGetParam( $_REQUEST, 'task', "0");

list($menuoption,$screen) = GenericLib::obtenerRuta($task);
$logFile->writeLog("menuoption=$menuoption screen=$screen userMambo={$userMambo->id}");

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
		$logFile->writeLog("Menuoption: Bodega");
		include_once($artzaraadminpath."artzaraadmin.bodega.html.php");
		include_once($artzaraadminpath."artzaraadmin.bodega.php");
		echo "</div>";
		break;

	case "12":
		echo "<div class=\"main\">";
		$logFile->writeLog("Menuoption: Socios");
		include_once($artzaraadminpath."artzaraadmin.socios.html.php");
		include_once($artzaraadminpath."artzaraadmin.socios.php");
		echo "</div>";
		break;

	case "13":
		echo "<div class=\"main\">";
		$logFile->writeLog("Menuoption: Mensajes");
		include_once($artzaraadminpath."artzaraadmin.mensajes.html.php");
		include_once($artzaraadminpath."artzaraadmin.mensajes.php");
		echo "</div>";
		break;

	case "14":
		echo "<div class=\"main\">";
		$logFile->writeLog("Menuoption: Sobres");
		include_once($artzaraadminpath."artzaraadmin.sobres.html.php");
		include_once($artzaraadminpath."artzaraadmin.sobres.php");
		echo "</div>";
		break;

	case "15":
		echo "<div class=\"main\">";
		$logFile->writeLog("Menuoption: informacion salidas-haber");
		include_once($artzaraadminpath."artzaraadmin.salidashaber.html.php");
		include_once($artzaraadminpath."artzaraadmin.salidashaber.php");
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
}
}
}

//FIN DEL SCRIPT DE INICIO

function MenuPrincipal()
{
	global $option,$Itemid;
	$link = "index.php?option=$option&Itemid=$Itemid";

?>
	<table class="adminheading" border="0">
		<tr>

			<th class="cpanel">
			Menu Principal
			</th>
		</tr>
	</table>

	<table width="100%" class="cpanel">
		<tr>
			<td align="center" height="100px">
			<a href="<?php echo $link; ?>&task=110" style="text-decoration:none;">
			<img src="administrator/images/menu.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>BODEGA</h2>
			</a>
			</td>

			<td align="center" height="100px">
			<a href="<?php echo $link; ?>&task=120" style="text-decoration:none;">
			<img src="administrator/images/user.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>SOCIOS</h2>
			</a>
			</td>

			<td align="center" height="100px">
			<a href="<?php echo $link; ?>&task=130" style="text-decoration:none;">
			<img src="administrator/images/langmanager.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>MENSAJES</h2>
			</a>
			</td>
	</tr>
	<tr>
		<td align="center" height="100px">
			<a href="<?php echo $link; ?>&task=140" style="text-decoration:none;">
			<img src="administrator/images/messaging.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>SOBRES</h2>
			</a>
		</td>

		<td align="center" height="100px">
			<a href="<?php echo $link; ?>&task=150" style="text-decoration:none;">
			<img src="administrator/images/browser.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>SALIDAS_HABER</h2>
			</a>
		</td>

		<td align="center" height="100px">
			<a href="index.php?option=logout" style="text-decoration:none;">
			<img src="administrator/images/cancel_f2.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>SALIR</h2>
			</a>
		</td>
	</tr>
	</table>
<?php
}

?>
