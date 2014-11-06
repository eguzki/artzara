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
$accion = mosGetParam( $_REQUEST, 'accion');

switch ($screen)
{
	default:
		$logFile->writeLog("Mensajes: menu principal");
		NavMensajes();
		mostrarTitulo("Mensajes");
		ModuloMensajesAdmin($accion);
		NavMensajes();
		break;
}
$logFile->writeLog("Fin modulo Mensajes");

function NavMensajes(){
		global $option,$Itemid;
?>
		<table border='0' cellpadding='5' cellspacing='5' width='100%'>
		<tr>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid&task=130"; ?>" class='buttonbar'>Listado Mensajes</a>
			</td>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid"; ?>" class='buttonbar'>Inicio</a>
			</td>
		</tr>
		</table>
<?php
	}

function ModuloMensajesAdmin($act){
	global $task,$option,$Itemid,$baseURL,$logFile,$user,$database;

	$logFile->writeLog("ModuloMensajesAdmin: accion $act");
	switch ($act) {
		case "edit":
		case "new":
			$logFile->writeLog("New/View Mensajes");
			$ids = mosGetParam( $_POST,'id');
			if(is_array($ids))
			{
				$id = $ids[0];
			}
			else
				$id = $ids;

			$mensaje = null;

			if($id){
				$mensaje = &DDBB::obtenerMensaje($id); //puede ser null
				if($mensaje === null){
					EnvioError("ModuloMensajesAdmin: No se pudo obtener el mensaje",$task);
					break;
				}
				if(!$mensaje->visto && $user->id == $mensaje->id_destino){
					$mensaje->visto = 1;
					if(!$mensaje->store()){
						EnvioError("ModuloMensajesAdmin: No se pudo actualizar a visto el mensaje",$task);
						break;
					}
				}
			}

			$socios = &DDBB::obtenerSocios(1);
			if($socios === NULL){
				EnvioError("ModuloMensajesAdmin: No se pudieron obtener los socios",$task);
				break;
			}
			$socio = new mosArtzaraSocios( $database );
			$socio->id = 0;
			$socio->nick = "TODOS";
			$socio->nombre = "TODOS";
			$socios["0"]=$socio;
			ShowMensaje($socios,$mensaje);
			break;
		case "enviar":
			if(!GenericLib::GuardarMensaje())
			{
				$message = 'El mensaje NO PUDO enviarse. Pruebe otra vez';
				EnvioError($message,$task);
				break;
			}
			mosRedirect($baseURL."&task=$task");
			break;
		case "cancel":
			mosRedirect($baseURL."&task=$task");
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
				if(!DDBB::EliminarMensaje($id))
					$result = FALSE;
			}
			if(!$result){
				$message = 'No se pudieron eliminar todos los mensajes';
				EnvioError($message,$task);
			}
			mosRedirect($baseURL."&task=$task");
			break;
		default:
			//Listado
			$logFile->writeLog("Listado Mensajes");
			$limit = intval( mosGetParam( $_REQUEST, 'limit', '' ) );
			$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );

			$remite = intval( mosGetParam( $_REQUEST,'id_remite') );
			$destino = intval( mosGetParam( $_REQUEST,'id_destino') );
			$fechaDesde = mosGetParam( $_REQUEST,'fechadesde');
			$fechaHasta = mosGetParam( $_REQUEST,'fechahasta');

			$filtroInfo = new MensajesFiltroInfo();
			$filtroInfo->id_remite = $remite;
			$filtroInfo->id_destino = $destino;
			$filtroInfo->fechaDesde = $fechaDesde;
			$filtroInfo->fechaHasta = $fechaHasta;

			// get the total number of records
			$total = DDBB::obtenerTotalMensajes($filtroInfo);

			$limit = $limit ? $limit : LIM_LISTADO ;

			if ( $total <= $limit ){
				$limitstart = 0;
			}

			require_once("includes/pageNavigation.php");
			$pageNav = new mosPageNav( $total, $limitstart, $limit );

			$datos = &DDBB::obtenerListadoMensajes($filtroInfo,$pageNav);

			if($datos === null){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			$logFile->writeLog("Listado Mensajes:".count($datos)." mensajes");

			$socios = &DDBB::obtenerSocios(1);
			if($socios === NULL){
				EnvioError("ModuloMensajesAdmin: No se pudieron obtener los socios",$task);
				break;
			}

			ShowMensajeList($datos,$socios,$pageNav,$filtroInfo);
			break;
	}

}
?>
