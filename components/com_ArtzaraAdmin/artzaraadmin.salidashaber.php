<?php
//Artzara Admin Component//
/**
* Content code
* @package SOCIOS
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
	case "1":
		$logFile->writeLog("Lista Salidas");
		NavSalidas();
		mostrarTitulo("Lista Salidas");
		ModuloSalidas($accion);
		NavSalidas();
		break;
	case "2":
		$logFile->writeLog("Listado Donaciones");
		NavSalidas();
		mostrarTitulo("Listado Donaciones");
		ModuloSalidasHaber($accion);
		NavSalidas();
		break;

	default:
		$logFile->writeLog("Salidas Principal");
		mostrarTitulo("SALIDAS: MENU PRINCIPAL");
		PantallaMainSalidas();
		break;
}

function ModuloSalidas($act){
	global $task,$option,$Itemid,$logFile,$baseURL;
	$logFile->writeLog("ModuloSalidas: accion $act");
	switch ($act) {
		case "edit":
		case "new":
			$logFile->writeLog("ModuloSalidas: Nuevo/Editar Salida");
			$idSalida = intval(mosGetParam( $_POST,'id_salida'));
			$salida = &DDBB::obtenerSalida($idSalida);
			FormularioSalida($salida);
			break;
		case "save":
			$logFile->writeLog("ModuloSalidas: Salvar Salida");
			if(!ArtzaraAdminLib::GuardarSalida())
			{
				$message = 'La salida NO PUDO Darse de alta. Pruebe otra vez';
				EnvioError($message,$task);
				break;
			}
			mosRedirect($baseURL."&task=151");
			break;
		case "cancel":
			mosRedirect($baseURL."&task=151");
			break;
		case "remove":
			$logFile->writeLog("Borrar Salidas");
			$arrayIds = mosGetParam( $_POST,'id');
			if(!$arrayIds || count($arrayIds) < 1)
			{
				EnvioError(_PARAMETERS_INVALID,$task);
				break;
			}
			$result = TRUE;
			foreach($arrayIds as $id)
			{
				if(!DDBB::EliminarSalida($id))
					$result = FALSE;
			}
			if(!$result)
			{
				$message = 'No se pudieron eliminar todas las Salidas';
				EnvioError($message,$task);
				break;
			}
			mosRedirect($baseURL."&task=151");
			break;
		default:
			// get the total number of records
			$salidas = &DDBB::obtenerSalidas();
			if($salidas === null){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}

			if(isset($salidas["0"]))
				unset($salidas["0"]);

			ShowSalidas($salidas);
			break;
	}
	$logFile->writeLog("Fin ModuloSalidas");
}


function ModuloSalidasHaber($act){
	global $task,$option,$Itemid,$logFile,$baseURL,$database;

	$logFile->writeLog("ModuloSalidasHaber: accion $act");
	switch ($act) {
		case "remove":
			$logFile->writeLog("Borrar entradas SalidasHaber");
			$arrayIds = mosGetParam( $_POST,'id');
			if(!$arrayIds || count($arrayIds) < 1)
			{
				EnvioError(_PARAMETERS_INVALID,$task);
				break;
			}
			$result = TRUE;
			foreach($arrayIds as $id)
			{
				if(!DDBB::EliminarSalidaHaber($id))
					$result = FALSE;
			}
			if(!$result)
			{
				$message = 'No se pudieron eliminar todas las Salidas';
				EnvioError($message,$task);
				break;
			}
			mosRedirect($baseURL."&task=152");
			break;
		case "cancel":
			mosRedirect($baseURL."&task=152");
			break;
		default:
			$limit = intval( mosGetParam( $_REQUEST, 'limit', '' ) );
			$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );

			$id_salida = intval( mosGetParam( $_REQUEST,'id_salida') );
			$fechaDesde = mosGetParam( $_REQUEST,'fechadesde');
			$fechaHasta = mosGetParam( $_REQUEST,'fechahasta');

			$filtroInfo = new SalidasHaberFiltroInfo();
			$filtroInfo->id_salida = $id_salida;
			$filtroInfo->fechaDesde = $fechaDesde;
			$filtroInfo->fechaHasta = $fechaHasta;

			// get the total number of records
			$total = DDBB::obtenerTotalSalidasHaber($filtroInfo);

			$limit = $limit ? $limit : LIM_LISTADO ;

			if ( $total <= $limit )
			{
				$limitstart = 0;
			}

			require_once("includes/pageNavigation.php");
			$pageNav = new mosPageNav( $total, $limitstart, $limit );

			$datos = &DDBB::obtenerListadoSalidasHaber($filtroInfo,$pageNav);

			if($datos === null){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}

			$salidas = &DDBB::obtenerSalidas();
			if($salidas === null){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			if(isset($salidas["0"]))
				unset($salidas["0"]);

			if($salidas === null){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}

			$salidasTotales = array();

			foreach($salidas as $salida){
				$salidasTotales["{$salida->id}"] = DDBB::obtenerSalidasTotales($salida->id,$filtroInfo->fechaDesde,$filtroInfo->fechaHasta);
			}

			$logFile->writeLog("ModuloSalidasHaber, numero de datos=".count($datos));

			ShowSalidasHaber($datos,$salidas,$salidasTotales,$pageNav,$filtroInfo);
			break;
	}
	$logFile->writeLog("Fin ModuloSalidasHaber");
}

?>
