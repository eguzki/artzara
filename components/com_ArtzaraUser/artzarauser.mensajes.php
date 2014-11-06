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
		$logFile->writeLog("Mensajes: menu principal");
		mostrarTitulo("Mensajes");
		ModuloMensajesUser($accion);
		break;
}
$logFile->writeLog("Fin modulo Mensajes");

function ModuloMensajesUser($act){
	global $task,$option,$Itemid,$logFile,$baseURL,$user;
	$logFile->writeLog("ModuloMensajesUser: accion $act");
	switch ($act) {
		case "edit":
		case "new":
			$logFile->writeLog("New/View Mensajes");
			$id = mosGetParam( $_POST,'id');
			$mensaje = null;
			if($id){
				$mensaje = &DDBB_artzaraadmin::obtenerMensaje($id); //puede ser null
				if($mensaje === null){
					EnvioError("ModuloMensajesUser: No se pudo obtener el mensaje",$task);
					break;
				}
				if(!$mensaje->visto){
					$mensaje->visto = 1;
					if(!$mensaje->store()){
						EnvioError("ModuloMensajesUser: No se pudo actualizar a visto el mensaje",$task);
						break;
					}
				}
			}
			$socios = &DDBB_artzaraadmin::obtenerSocios(1);
			if($socio === NULL){
				EnvioError("ModuloMensajesUser: No se pudieron obtener los socios",$task);
				break;
			}
			ShowMensaje($socios,$mensaje,0);
			break;
		case "enviar":
			if(!GenericLib::GuardarMensaje())
			{
				$message = 'El mensaje NO PUDO enviarse. Pruebe otra vez';
				EnvioError($message,$task);
				break;
			}
			EnvioResultOK("Nuevo mensaje enviado OK",$task);
			break;
		case "cancel":
			mosRedirect($baseURL."&task=130");
			break;
		case "remove":
			$logFile->writeLog("Borrar Mensajes");
			$arrayIds = mosGetParam( $_POST,'id');
			if(!$arrayIds || count($arrayIds) < 1){
				EnvioError(_PARAMETERS_INVALID,$task);
				break;
			}
			$result = TRUE;
			foreach($arrayIds as $id){
				$mensaje = &DDBB_artzaraadmin::obtenerMensaje($id); //puede ser null
				if($mensaje === null){
					continue;
				}
				else if($mensaje->id_destino == 0){
					continue;
				}
				else if(!DDBB_artzaraadmin::EliminarMensaje($id))
					$result = FALSE;
			}
			if($result){
				$message = 'Mensajes ELIMINADOS OK';
				EnvioResultOK($message,$task);
			}
			else{
				$message = 'No se pudieron eliminar todos los mensajes';
				EnvioError($message,$task);
			}
			break;
		default:
			//Listado

			// Obtenemos mensajes para el usuario
			$limit = intval( mosGetParam( $_REQUEST, 'limit', '' ) );
			$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
			$destino = intval( mosGetParam( $_REQUEST,'id_destino') );
			$fechaDesde = mosGetParam( $_REQUEST,'fechaDesde');
			$fechaHasta = mosGetParam( $_REQUEST,'fechaHasta');

			if($destino != 0 && $destino != $user->id){
				// Nos aseguramos de que no nos envian otro usuario
				EnvioError(_PARAMETERS_INVALID,$task);
				break;
			}

			// El destino es 0 o el del usuario loggeado

			$filtroInfo = new MensajesFiltroInfo();
			$filtroInfo->id_destino = $destino;
			$filtroInfo->fechaDesde = $fechaDesde;
			$filtroInfo->fechaHasta = $fechaHasta;

			// get the total number of records
			$total = DDBB_artzaraadmin::obtenerTotalMensajes($filtroInfo);

			$limit = $limit ? $limit : LIM_LISTADO ;
			if ( $total <= $limit ){
				$limitstart = 0;
			}

			require_once("includes/pageNavigation.php");
			$pageNav = new mosPageNav( $total, $limitstart, $limit );

			$mensajes = &DDBB_artzaraadmin::obtenerListadoMensajes($filtroInfo,$pageNav);

			if($mensajes === null){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}

			$socios = &DDBB_artzaraadmin::obtenerSocios(1);
			if($socio === NULL){
				EnvioError("ModuloMensajesUser: No se pudieron obtener los socios",$task);
				break;
			}

			ShowUserMensajeList($mensajes,$pageNav,$filtroInfo,$socios);
			break;
	}
}

?>
