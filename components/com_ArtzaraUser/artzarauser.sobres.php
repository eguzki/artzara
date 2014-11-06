<?php
//Artzara Admin Component//
/**
* Content code
* @package ARTZARAADMIN
* @Copyright (C) 2004 Doyle Lewis
* @ All rights reserved
* @ ARTZARAADMIN is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version 1.0
**/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
//$screen viene de arriba
$accion = mosGetParam( $_REQUEST, 'accion', "0");

switch ($screen)
{
	default:
		$logFile->writeLog("Sobres: menu principal");
		NavUser();
		mostrarTitulo("Sobres");
		ModuloSobresUser($accion);
		NavUser();
		break;
}
$logFile->writeLog("Fin modulo Lista Sobres");

function ModuloSobresUser($act){
	global $task,$option,$Itemid,$logFile,$baseURL,$user;
	$logFile->writeLog("ModuloSobresUser: accion $act");
	switch ($act) {
		case "edit":

			$ids = mosGetParam( $_POST,'id');
			if(is_array($ids))
			{
				$id = $ids[0];
			}
			else
			{
				$id = $ids;
			}
			$logFile->writeLog("View Sobre:$id");

			$row = &DDBB::obtenerSobre($id);
			if($row === NULL)
			{
				EnvioError(_PARAMETERS_INVALID,$task);
				break;
			}
			$consumosArrayInfo = &GenericLib::ObtenerListadoConsumos($row->id);
			if($consumosArrayInfo === NULL)
			{
				EnvioError("No se pudieron obtener los consumos del sobre: {$row->id}",$task);
				break;
			}
			$salidas = &DDBB::obtenerSalidas();
			if($salidas === NULL)
			{
				EnvioError("No se pudieron obtener los destinatarios del haber",$task);
				break;
			}

			$socio = &DDBB::obtenerSocio($row->id_socio);
			if($socio === NULL)
			{
				EnvioError("No se pudieron obtener los datos del socio: {$row->id_socio}",$task);
				break;
			}
			PantallaFormularioSobre($row,$consumosArrayInfo,$salidas,$socio,0);
			break;
		case "cancel":
			mosRedirect($baseURL."&task=$task");
			break;
		case "exit":
			mosRedirect($baseURL."&task=000");
			break;
		default:
			//Listado

			$limit = intval( mosGetParam( $_REQUEST, 'limit', '' ) );
			$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
			$id_socio = $user->id;
			$fechadesde = mosGetParam( $_REQUEST,'fechadesde');
			$fechahasta = mosGetParam( $_REQUEST,'fechahasta');
			$debo = intval(mosGetParam( $_REQUEST,'debo'));// 0.- todos 1.- debo 2.- haber

			$filtroInfo = new SobresFiltroInfo();
			$filtroInfo->state = _PROCESADO;
			$filtroInfo->idSocio = $id_socio;
			$filtroInfo->fechaDesde = $fechadesde;
			$filtroInfo->fechaHasta = $fechahasta;
			$filtroInfo->debo = $debo;

			// get the total number of records
			$total = DDBB::obtenerTotalSobres("#__artzara_sobres",$filtroInfo);
			$limit = $limit ? $limit : LIM_LISTADO ;

			if ( $total <= $limit )
			{
				$limitstart = 0;
			}

			require_once("includes/pageNavigation.php");
			$pageNav = new mosPageNav( $total, $limitstart, $limit );

			$socios = array();

			$socios[] = $user;

			$salidas = &DDBB::obtenerSalidas();
			if($salidas === NULL){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			$salida = new mosArtzaraSalidas( $database );
			$salida->id = -1;
			$salida->name= "AL DEBO";
			$salidas["-1"] = $salida;

			$datos = &DDBB::obtenerListadoSobres("#__artzara_sobres",$filtroInfo,$pageNav);
			if($datos === null)
			{
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			$logFile->writeLog("ModuloSobresUser:usuarios:".count($socios));
			PantallaListadoSobresHTML($datos,$pageNav,$filtroInfo,$socios,$salidas,0);
			break;
	}
}
?>
