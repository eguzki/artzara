<?php
//Artzara Admin Component//
/**
* Content code
* @package ARTZARAADMIN
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
		$logFile->writeLog("Procesar Sobres");
		NavSobres();
		mostrarTitulo("SOBRES");
		ModuloSobres($accion);
		NavSobres();
		break;

	case "2":
		$logFile->writeLog("Listado sobres");
		NavSobres();
		mostrarTitulo("LISTADO SOBRES");
		ModuloListadoSobres($accion);
		NavSobres();
		break;

	case "3":
		$logFile->writeLog("NUEVO SOBRE");
		NavSobres();
		mostrarTitulo("NUEVO SOBRE");
		ModuloAdminNewSobre($accion);
		NavSobres();
		break;

	default:
		$logFile->writeLog("Sobres: menu principal");
		mostrarTitulo("SOBRES: MENU PRINCIPAL");
		SobresPantallaPrincipal();
		break;
}

function ModuloSobres($act)
{
	global $task,$option,$Itemid,$baseURL,$logFile;
	
	$logFile->writeLog("accion: $act");
	switch($act)
	{
		case "new":
			$logFile->writeLog("editar sobre");
			// Obtengo el idSobre
			$idSobre = intval(mosGetParam( $_REQUEST,'idsobre'));
			if(!$idSobre){
				EnvioError("Numero de sobre no valido.",$task);
				break;
			}
			$sobre = &DDBB::obtenerSobre($idSobre);
			if(!$sobre){
				EnvioError("El sobre no existe. Pruebe Otra vez",$task);
				break;
			}
			// Comprobar qeu el sobre esta en el estado de no procesado
			if($sobre->state != _NO_PROCESADO){
				EnvioError("NO SE PUEDE REALIZAR LA OPERACION SOBRE ESTE SOBRE",$task);
				break;
			}
			// Obtener el nombre del usuario para ponerlo en el formulario
			$socio = &DDBB::obtenerSocio($sobre->id_socio);
			if($socio === null){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}

			// Obtener el nombre de la si existiese
			$salidas = &DDBB::obtenerSalidas();
			if($salidas === NULL){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			$salida = new mosArtzaraSalidas( $database );
			$salida->id = -1;
			$salida->name= "AL DEBO";
			$salidas["-1"] = $salida;
			PantallaNuevoProcesadoHTML($sobre,$socio->nombre,$salidas);
			break;
		case "procesar":
			$logFile->writeLog("ModuloSobres:Procesado del sobre");
			$importe = doubleval(mosGetParam( $_REQUEST,'displayOut'));
			$idSobre = intval(mosGetParam( $_REQUEST,'idsobre'));
			$salida = intval(mosGetParam( $_REQUEST,'salida',-1));
			if($importe < 0 || !$idSobre){
				EnvioError(_PARAMETERS_INVALID,$task);
				break;
			}
			if(!is_double($importe)){
				EnvioError("NOT A DOUBLE",$task);
				break;
			}
			// Compruebo la salida
			$logFile->writeLog("ModuloSobres:salida:$salida");
			if($salida > 0)
			{
				if(DDBB::obtenerSalida($salida) === null)
				{
					EnvioError("Salida no reconocida",$task);
				}
			}
			elseif($salida < -1)
			{
				EnvioError("Salida no reconocida",$task);
			}

			$sobre = &DDBB::obtenerSobre($idSobre);
			if(!$sobre){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			// Comprobar qeu el sobre esta en el estado de no procesado
			if($sobre->state != _NO_PROCESADO){
				EnvioError("NO SE PUEDE REALIZAR LA OPERACION SOBRE ESTE SOBRE",$task);
				break;
			}

			$sobre->id_salida = $salida;
			$sobre->pago = $importe;
			$descuadre = $importe - $sobre->total;

			if($descuadre < 0)
				$sobre->id_salida = -1;
			elseif($descuadre == 0)
				$sobre->id_salida = 0;
			elseif($descuadre > 0 && $sobre->id_salida<0)
				$sobre->id_salida = DDBB::getArtzaraSalidaId();

			$logFile->writeLog("ModuloSobres:salida tras el filtro:{$sobre->id_salida}");

			if(!ArtzaraAdminLib::ProcesarSobre($sobre,$descuadre))
			{
				EnvioError("NO SE HA PODIDO PROCESAR ESTE SOBRE. INTENTELO DE NUEVO",$task);
				break;
			}
			mosRedirect($baseURL."&task=$task");
			break;
		case "cancel":
			mosRedirect($baseURL."&task=$task");
			break;
		default:
			$logFile->writeLog("peticion codigo sobre");
			PantallaCodigoSobresHTML();
			break;
	}	
}

function ModuloListadoSobres($act)
{
	global $task,$option,$Itemid,$baseURL,$logFile,$database;
	
	$logFile->writeLog("accion: $act");
	switch($act)
	{
		case "remove":
			$logFile->writeLog("Borrar sobres");
			$arrayIds = mosGetParam( $_POST,'id');
			if(!$arrayIds || count($arrayIds) < 1)
			{
				EnvioError(_PARAMETERS_INVALID,$task);
				break;
			}
			$result = TRUE;
			foreach($arrayIds as $id)
			{
				if(!DDBB::EliminarSobre($id))
					$result = FALSE;
			}
			if(!$result)
			{
				$message = 'No se pudieron eliminar todos los sobres seleccionados';
				EnvioError($message,$task);
			}
			mosRedirect($baseURL."&task=$task");
			break;
		case "anular":
			$logFile->writeLog("Anular sobres");
			$arrayIds = mosGetParam( $_POST,'id');
			if(!$arrayIds || count($arrayIds) < 1)
			{
				EnvioError(_PARAMETERS_INVALID,$task);
				break;
			}
			$result = TRUE;
			foreach($arrayIds as $id)
			{
				if(!ArtzaraAdminLib::AnularSobre($id))
					$result = FALSE;
			}
			if(!$result)
			{
				$message = 'No se pudieron anular todos los sobres';
				EnvioError($message,$task);
				break;
			}
			mosRedirect($baseURL."&task=$task");
			break;
		case "edit":
			$logFile->writeLog("Editar sobres");
			$ids = mosGetParam( $_POST,'id');

			if(is_array($ids))
			{
				$id = $ids[0];
			}
			else
			{
				$id = $ids;
			}
			$logFile->writeLog("sobreId: $id");


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
			$salida = new mosArtzaraSalidas( $database );
			$salida->id = -1;
			$salida->name= "AL DEBO";
			$salidas["-1"] = $salida;

			$socio = &DDBB::obtenerSocio($row->id_socio);
			if($socio === NULL)
			{
				EnvioError("Socio {$row->id_socio} desconocido, elimine el sobre.",$task);
				break;
			}
			PantallaFormularioSobre($row,$consumosArrayInfo,$salidas,$socio);
			break;
		case "save":
			$logFile->writeLog("Guardar sobre");
			$state = intval(mosGetParam( $_REQUEST,'state'));
			$idSobre = intval(mosGetParam( $_REQUEST,'id'));
			
			if(!$state || !$idSobre)
			{
				EnvioError(_PARAMETERS_INVALID,$task);
				break;
			}
			
			$sobre = &DDBB::obtenerSobre($idSobre);
			if(!$sobre){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			
			// Solo permitimos la actualizacion del estado
			$sobre->state = $state;
			
			if (!$sobre->store())
			{
				EnvioError("Error: ".$sobre->getError(),$task);
				break;
			}
			mosRedirect($baseURL."&task=$task");
			break;
		case "cancel":
			mosRedirect($baseURL."&task=$task");
			break;
		case "exit":
			mosRedirect($baseURL."&task=140");
		default:
			$logFile->writeLog("Mostrar lista sobres");

			$limit = intval( mosGetParam( $_REQUEST, 'limit', '' ) );
			$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );

			$state = intval(mosGetParam( $_REQUEST,'state'));
			$id_socio = intval(mosGetParam( $_REQUEST,'id_socio'));
			$fechadesde = mosGetParam( $_REQUEST,'fechadesde');
			$fechahasta = mosGetParam( $_REQUEST,'fechahasta');
			$debo = intval(mosGetParam( $_REQUEST,'debo'));// 0.- todos 1.- debo 2.- haber

			$filtroInfo = new SobresFiltroInfo();
			$filtroInfo->state = $state;
			$filtroInfo->idSocio = $id_socio;
			$filtroInfo->fechaDesde = $fechadesde;
			$filtroInfo->fechaHasta = $fechahasta;
			$filtroInfo->debo = $debo;

			$socios = &DDBB::obtenerSocios(1);
			if($socios === NULL)
			{
				EnvioError("No se pudieron obtener los socios",$task);
				break;
			}

			$salidas = &DDBB::obtenerSalidas();
			if($socios === NULL)
			{
				EnvioError("No se pudieron obtener as salidas",$task);
				break;
			}

			$salida = new mosArtzaraSalidas( $database );
			$salida->id = -1;
			$salida->name= "AL DEBO";
			$salidas["-1"] = $salida;

			// get the total number of records
			$total = DDBB::obtenerTotalSobres("#__artzara_sobres",$filtroInfo);
			$limit = $limit ? $limit : LIM_LISTADO ;

			if ( $total <= $limit )
			{
				$limitstart = 0;
			}

			require_once("includes/pageNavigation.php");
			$pageNav = new mosPageNav( $total, $limitstart, $limit );

			$datos = &DDBB::obtenerListadoSobres("#__artzara_sobres",$filtroInfo,$pageNav);
			if($datos === null)
			{
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}

			PantallaListadoSobresHTML($datos,$pageNav,$filtroInfo,$socios,$salidas);
			break;
	}
}

function ModuloAdminNewSobre($act){
	global $task,$option,$Itemid,$logFile,$database;
	
	$logFile->writeLog("accion: $act");

	switch($act)
	{
		case "setsocio":
			// Peticion del numero de socio
			$socios = &DDBB::obtenerSocios(1);
			if($socios === NULL)
			{
				EnvioError("No se pudieron obtener los socios",$task);
				break;
			}
			PantallaNewSobreWithoutSocio($socios);
			break;
		default:
			// Modulo de nuevo Sobre
			ModuloNewSobre($act);
			break;
	}
}

?>
