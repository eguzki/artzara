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
		$logFile->writeLog("Socios");
		NavSocios();
		mostrarTitulo("Socios","user");
		ModuloSocio($accion);
		NavSocios();
		break;
	case "2":
		$logFile->writeLog("Listado Cuenta Socio");
		NavSocios();
		mostrarTitulo("Listado Cuentas Socio","modules");
		ModuloCuentaSocios($accion);
		NavSocios();
		break;

	default:
		$logFile->writeLog("A_Principal");
		mostrarTitulo("SOCIOS: MENU PRINCIPAL","user");
		PantallaMainSocios();
		break;
}



$logFile->writeLog("FIN Socios");


function ModuloCuentaSocios($act){
	global $task,$option,$Itemid,$logFile,$baseURL,$database;
	$logFile->writeLog("ModuloCuentaSocios: accion $act");
	switch ($act) {
		case "remove":
			$logFile->writeLog("Borrar CuentasSocios");
			$arrayIds = mosGetParam( $_POST,'id');
			if(!$arrayIds || count($arrayIds) < 1)
			{
				EnvioError(_PARAMETERS_INVALID,$task);
				break;
			}
			$result = TRUE;
			foreach($arrayIds as $id)
			{
				if(!DDBB::EliminarCuentaSocio($id))
					$result = FALSE;
			}
			if($result)
			{
				$message = 'Cuenta de Socios ELIMINADOS OK';
				EnvioResultOK($message,$task);
			}
			else
			{
				$message = 'No se pudieron eliminar todas las cuentas de socios';
				EnvioError($message,$task);
			}
			break;
		case "cancel":
			mosRedirect($baseURL."&task=$task");
			break;
		default:
			//Lista usuarios
			$limit = intval( mosGetParam( $_REQUEST, 'limit', '' ) );
			$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );

			$idSocio = intval( mosGetParam( $_REQUEST,'idSocio') );
			$fechaDesde = mosGetParam( $_REQUEST,'fechadesde');
			$fechaHasta = mosGetParam( $_REQUEST,'fechahasta');

			$filtroInfo = new CuentaSociosFiltroInfo();
			$filtroInfo->idSocio = $idSocio;
			$filtroInfo->fechaDesde = $fechaDesde;
			$filtroInfo->fechaHasta = $fechaHasta;

			// get the total number of records
			$total = DDBB::obtenerTotalCuentasSocios($filtroInfo);

			$limit = $limit ? $limit : LIM_LISTADO ;

			if ( $total <= $limit )
			{
				$limitstart = 0;
			}

			require_once("includes/pageNavigation.php");
			$pageNav = new mosPageNav( $total, $limitstart, $limit );

			$datos = &DDBB::obtenerListadoCuentasSocio($filtroInfo,$pageNav);

			if($datos === null){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			$socios = &DDBB::obtenerSocios(1);
			if($socios === NULL)
			{
				EnvioError("No se pudieron obtener los socios",$task);
				break;
			}
			ShowCuentasSocios($datos,$socios,$pageNav,$filtroInfo);
			break;
	}
}


function ModuloSocio($act){
	global $task,$option,$Itemid,$logFile,$baseURL,$database;

	$logFile->writeLog("ModuloSocio: accion $act");
	switch ($act) {
		case 'new':
			$idMambo = intval(mosGetParam( $_REQUEST,'id_mambo'));
			$logFile->writeLog("PantallaNewSocio: idMambo $idMambo");
			if($idMambo){
				//Obtengo datos del socio
				$mamboSocio = new mosUser($database);
				if(!$mamboSocio->load( $idMambo ))
				{
					$logFile->writeLog("PantallaNewSocio: Error: ".$mamboSocio->getError());
					EnvioError(_DATABASE_ERROR,"120");
					break;
				}

				$socio = new mosArtzaraSocios($database);
				$socio->id = 0;
				$socio->nombre = $mamboSocio->name;
				$socio->nick = $mamboSocio->username;
				$socio->email = $mamboSocio->email;
				$socio->activo = ($mamboSocio->block)?(0):(1);
				$socio->id_mambo = $mamboSocio->id;

				FormularioSocio($socio);
			}
			else{
				// Obtengo todos los socios mambo
				$socios = &DDBB::obtenerSociosMambo(1);
				if($socios === null){
					EnvioError(_DATABASE_ERROR,"120");
					break;
				}
				ListaSociosMambo($socios);
			}
			break;
		case "save":
			$idMambo = intval(mosGetParam( $_POST,'id_mambo'));
			$nombre = mosGetParam( $_POST,'nombre');
			$nick = mosGetParam( $_POST,'nick');
			$email = mosGetParam( $_POST,'email');
			$activo = intval(mosGetParam( $_POST,'activo'));
			$id = intval(mosGetParam( $_POST,'id'));

			if(!$idMambo || !$nombre || !$nick || $activo === null){
				EnvioError(_PARAMETERS_INVALID,$task);
				break;
			}

			$mamboSocio = new mosUser($database);
			if(!$mamboSocio->load( $idMambo ))
			{
				$logFile->writeLog("PantallaNewSocio: Error: ".$mamboSocio->getError());
				EnvioError(_DATABASE_ERROR,"120");
				break;
			}

			// Parte de Mambo fuera de la funcion generica guardar socio
			// porque en la parte de usuario no se permite cambiar estos valores

			$mamboSocio->name = $nombre;
			$mamboSocio->username = $nick;
			$mamboSocio->email = $email;
			$mamboSocio->block = ($activo)?(0):(1);

			if(isset($_POST["password"]) && $_POST["password"] != "") {
				$mamboSocio->password = md5($_POST["password"]);
			}

			if (!$mamboSocio->check()) {
				echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
				break;
			}

			unset($mamboSocio->orig_password); // prevent DB error!!

			if (!$mamboSocio->store())
			{
				EnvioError("Error: ".$mamboSocio->getError(),$task);
				break;
			}

			if(!GenericLib::GuardarSocio($id))
			{
				$message = 'El socio NO PUDO Guardarse. Pruebe otra vez';
				EnvioError($message,$task);
				break;
			}
			mosRedirect($baseURL."&task=$task");
			break;
		case "cancel":
			mosRedirect($baseURL."&task=$task");
			break;
		case 'edit':
			$logFile->writeLog("Editar socio");
			$ids = mosGetParam( $_POST,'id',0);
			$socio = &DDBB::obtenerSocio($ids[0]);
			if($socio === NULL)
			{
				EnvioError(_PARAMETERS_INVALID,$task);
				break;
			}
			$logFile->writeLog("Idioma:".$socio->idioma);
			FormularioSocio($socio);
			break;
		case 'remove':
			$logFile->writeLog("Borrar Socios");
			$arrayIds = mosGetParam( $_POST,'id');
			if(!$arrayIds || count($arrayIds) < 1)
			{
				EnvioError(_PARAMETERS_INVALID,$task);
				break;
			}
			$result = TRUE;
			foreach($arrayIds as $id)
			{
				if(!DDBB::EliminarSocio($id))
					$result = FALSE;
			}
			if(!$result)
			{
				$message = 'No se pudieron eliminar todos los socios';
				EnvioError($message,$task);
			}
			mosRedirect($baseURL."&task=$task");
			break;
		default:
			//Lista usuarios
			$limit = intval( mosGetParam( $_REQUEST, 'limit', '' ) );
			$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );

			$debo = intval( mosGetParam( $_REQUEST,'debo',0) );// 0.- todos 1.- debo 2.- haber
			$activo = intval( mosGetParam( $_REQUEST,'activo',0) );

			$filtroInfo = new SociosFiltroInfo();
			$filtroInfo->debo = $debo;
			$filtroInfo->activo = $activo;

			$logFile->writeLog("Socios filtro: debo=$debo activo=$activo");

			// get the total number of records
			$total = DDBB::obtenerTotalSocios($filtroInfo);

			$limit = $limit ? $limit : LIM_LISTADO ;

			if ( $total <= $limit )
			{
				$limitstart = 0;
			}

			require_once("includes/pageNavigation.php");
			$pageNav = new mosPageNav( $total, $limitstart, $limit );

			$datos = &DDBB::obtenerListadoSocios($filtroInfo,$pageNav);

			if($datos === null){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			ShowSocios($datos,$pageNav,$filtroInfo);
			break;
	}
	$logFile->writeLog("Fin PantallaNewSocio");
}

?>
