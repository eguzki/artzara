<?php
/**
* Artzara - A Mambo Forms Application
* @version 1.0.0
* @package Artzara
* @copyright (C) 2004 by Peter Koch
* @license Released under the terms of the GNU General Public License
**/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
// Variables Globales

class DDBB
{
	/*
	*
	* USUARIOS
	*
	*/

	function &obtenerSocios($solo_activos = 0){
		global $database, $logFile;
		// Se obtienen todos o solo los activos
		if($solo_activos)
			$query = "SELECT id FROM #__artzara_socios WHERE activo = 1 AND nombre <> 'Administrator' AND nick <> 'admin'";
		else
			$query = "SELECT id FROM #__artzara_socios AND nombre <> 'Administrator' AND nick <> 'admin'";
		$database->setQuery( $query );
		$rows = $database->loadObjectList();

		if($rows === NULL)
		{
			//$logFile->writeLog(_DATABASE_ERROR);
			return null;
		}
		$socios = array();
		foreach($rows as $row)
		{
			$socio = new mosArtzaraSocios( $database );
			$socio->load( $row->id);
			$socios["{$socio->id}"] = $socio;
		}
		return $socios;
	}


	function &obtenerSociosMambo($solo_no_bloqueados = 0){
		global $database, $logFile;

		$query = "select #__users.id,#__users.name,#__users.username from #__users"
		."\n INNER JOIN #__core_acl_aro ON #__core_acl_aro.value = #__users.id"
		."\n INNER JOIN #__core_acl_groups_aro_map ON #__core_acl_groups_aro_map.aro_id = #__core_acl_aro.aro_id"
		."\n INNER JOIN #__core_acl_aro_groups ON #__core_acl_aro_groups.group_id = #__core_acl_groups_aro_map.group_id"
		."\n INNER JOIN #__groups ON #__groups.name = #__core_acl_aro_groups.name"
		."\n WHERE #__groups.name = 'registered'"
		.(($solo_no_bloqueados) ? "\n AND block = '0'":"")
		."\n order by registerDate desc";

		$logFile->writeLog("obtenerSocios: query: $query");
		$database->setQuery( $query );
		$rows = $database->loadObjectList();

		if ($database->getErrorNum()){
			$logFile->writeLog("obtenerSocios: error:".$database->stderr());
			$rows = null;
		}
		return $rows;
	}

	function &obtenerSocio($idSocio){
		global $database, $logFile;

		$row = new mosArtzaraSocios( $database );
		if(!$row->load( $idSocio ))
		{
			$logFile->writeLog("obtenerSocio: Error: ".$row->getError());
			return null;
		}
		//$logFile->writeLog("obtenerSocio: devolviendo datos de familia: ".$row->id);
		return $row;
	}

	function &obtenerSocioFromMambo($idMamboSocio){
		global $database, $logFile;

		$query = "SELECT * FROM mos_artzara_socios WHERE id_mambo = $idMamboSocio";

		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if($rows === NULL)
		{
			$logFile->writeLog("obtenerSocioFromMambo: Database Error o no existe: ".$database->stderr());
			return null;
		}
		return $rows[0];
	}

	function &obtenerListadoSocios(&$filtroInfo,&$pageNav)
	{
		global $database, $logFile;

		$query = "SELECT * FROM #__artzara_socios WHERE nombre <> 'Administrator' AND nick <> 'admin'";
		$AND = " AND ";

		if($filtroInfo->activo){
			$val = $filtroInfo->activo % 2;
			$query = $query . $AND . "activo = $val";
		}
		if($filtroInfo->debo){
			$query = $query . $AND . "haber ".(($filtroInfo->debo==1)?("<"):(">="))." 0";
		}

		$query = $query . " ORDER BY fecha_alta DESC LIMIT {$pageNav->limitstart},{$pageNav->limit}";

		$logFile->writeLog("obtenerListadoSocios: Query: $query");

		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if($rows === NULL)
		{
			$logFile->writeLog("obtenerListadoSocios: Database Error: ".$database->stderr());
		}
		return $rows;
	}

	function &obtenerTotalSocios(&$filtroInfo)
	{
		global $database, $logFile;

		$query = "SELECT count(*) FROM #__artzara_socios WHERE nombre <> 'Administrator' AND nick <> 'admin'";

		$AND = " AND ";
		if($filtroInfo->activo){
			$val = $filtroInfo->activo % 2;
			$query = $query . $AND . "activo = $val";
		}
		if($filtroInfo->debo){
			$query = $query . $AND . "haber ".(($filtroInfo->debo == 1)?("<"):(">="))." 0";
		}

		$logFile->writeLog("obtenerTotalSocios: query: $query");

		$database->setQuery( $query );
		$total = $database->loadResult();
		$logFile->writeLog("obtenerTotalSocios: $total Database: ".$database->getErrorMsg());
		return $total;
	}

	function EliminarSocio($id){
		global $database, $logFile;
		// hay que eliminar sobres y cuentaSocio???
		$query = "DELETE FROM mos_artzara_cuentasocios WHERE id_socio = $id";

		$database->setQuery( $query );
		if(!$database->query())
		{
			$logFile->writeLog("EliminarSocio: Database Error: ".$database->stderr());
			return FALSE;
		}

		$row = new mosArtzaraSocios( $database );
		if (!$row->delete($id))
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}
		return TRUE;
	}

	/*
	*
	* CuentaSocio
	*
	*/
	function &obtenerCuentaSocio($id){
		global $database, $logFile;

		$row = new mosArtzaraCuentaSocios( $database );
		if(!$row->load( $idSocio ))
		{
			$logFile->writeLog("obtenerCuentaSocio: Error: ".$row->getError());
			return null;
		}
		//$logFile->writeLog("obtenerCuentaSocio: devolviendo datos de familia: ".$row->id);
		return $row;
	}

	function &obtenerListadoCuentasSocio(&$filtroInfo,&$pageNav){
		global $database, $logFile;

		$query = "SELECT * FROM #__artzara_cuentasocios ";
		$AND = "";

		if($filtroInfo->idSocio || $filtroInfo->fechaDesde || $filtroInfo->fechaHasta)
			$query = $query . "WHERE ";

		if($filtroInfo->idSocio){
			$query = $query . "id_socio = {$filtroInfo->idSocio}";
			$AND = " AND ";
		}
		if($filtroInfo->fechaDesde)
		{
			$query = $query . $AND . "inputdate >= '$fechadesde'";
			$AND = " AND ";
		}
		if($filtroInfo->fechaHasta)
			$query = $query . $AND . "inputdate <= '$fechahasta'";

		$query = $query . " ORDER BY inputdate DESC LIMIT {$pageNav->limitstart},{$pageNav->limit}";

		$logFile->writeLog("obtenerListadoCuentaSocios: Query: $query");

		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if($rows === NULL)
		{
			$logFile->writeLog("obtenerListadoCuentaSocios: Database Error: ".$database->stderr());
		}
		return $rows;
	}

	function &obtenerTotalCuentasSocios(&$filtroInfo)
	{
		global $database, $logFile;

		$query = "SELECT count(*) FROM #__artzara_cuentasocios ";

		$AND = "";

		if($filtroInfo->idSocio || $filtroInfo->fechaDesde || $filtroInfo->fechaHasta)
			$query = $query . "WHERE ";

		if($filtroInfo->idSocio){
			$query = $query . "id_socio = {$filtroInfo->idSocio}";
			$AND = " AND ";
		}
		if($filtroInfo->fechaDesde)
		{
			$query = $query . $AND . "inputdate >= '$fechadesde'";
			$AND = " AND ";
		}
		if($filtroInfo->fechaHasta)
			$query = $query . $AND . "inputdate <= '$fechahasta'";

		$logFile->writeLog("obtenerTotalCuentaSocios: query: $query");

		$database->setQuery( $query );
		$total = $database->loadResult();
		$logFile->writeLog("obtenerTotalCuentaSocios: $total Database: ".$database->getErrorMsg());
		return $total;
	}

	function EliminarCuentaSocio($id){
		global $database, $logFile;

		$row = new mosArtzaraCuentaSocios( $database );
		if (!$row->delete($id))
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}
		return TRUE;
	}

	/*
	*
	* FAMILIAS
	*
	*/
	function &obtenerFamilias($published=0)
	{
		global $database, $logFile;
		// Se obtienen todos o solo los activos
		if($published)
			$query = "SELECT id FROM #__artzara_familias WHERE published = 1";
		else
			$query = "SELECT id FROM #__artzara_familias";

		$database->setQuery( $query );
		$rows = $database->loadObjectList();

		if($rows === NULL)
		{
			$logFile->writeLog("obtenerFamilias: Database Error");
			return NULL;
		}

		$familias = array();

		foreach($rows as $row)
		{
			$familia = new mosArtzaraFamilia( $database );
			$familia->load( $row->id);
			$familias["{$familia->id}"] = $familia;
			//$logFile->writeLog("Cargando familia:". $familia->id .": ".$familia->nombre);
		}
		$logFile->writeLog("obtenerFamilias: Obtenidas ".count($familias)." familias");
		return $familias;
	}

	function &obtenerFamilia($idFamilia)
	{
		global $database, $logFile;

		$row = new mosArtzaraFamilia( $database );
		if(!$row->load( $idFamilia ))
		{
			$logFile->writeLog("obtenerFamilia: Error o no existe: ".$row->getError());
			return null;
		}
		//$logFile->writeLog("obtenerFamilia: devolviendo datos de familia: ".$row->id);
		return $row;
	}

	/*
	*
	* PRODUCTOS
	*
	*/

	function &obtenerProductos($idFamilia,$published=0)
	{
		global $database, $logFile;

		if($published)
			$query = "SELECT id FROM #__artzara_productos WHERE id_grupo = $idFamilia AND published = 1";
		else
			$query = "SELECT id FROM #__artzara_productos WHERE id_grupo = $idFamilia";

		$database->setQuery( $query );
		$rows = $database->loadObjectList();

		if($rows === NULL)
		{
			$logFile->writeLog("ObtenerProductos: Database Error");
			return NULL;
		}

		$productos = array();
		foreach($rows as $row)
		{
			$producto = new mosArtzaraProducto( $database );
			$producto->load( $row->id );
			$productos["{$producto->id}"] = $producto;
		}
		//$logFile->writeLog("obtenerProductos: Obtenidos ".count($productos)." productos");
		return $productos;
	}

	function &obtenerProducto($idProducto)
	{
		global $database, $logFile;
		$row = new mosArtzaraProducto( $database );
		if(!$row->load( $idProducto ))
		{
			$logFile->writeLog("obtenerProducto: Error: ".$row->getError());
			return null;
		}
		//$logFile->writeLog("obtenerProducto: devolviendo datos del producto: ".$row->id);
		return $row;
	}

	// STOCK

	function &obtenerStock($idFamilia,$idProducto)
	{
		global $database, $logFile;

		$query = "SELECT id FROM #__artzara_stock WHERE id_grupo = $idFamilia AND id_prod = $idProducto";
		$database->setQuery( $query );
		$rows = $database->loadObjectList();

		if($rows === NULL)
		{
			$logFile->writeLog("ObtenerProductos: Database Error".$database->stderr());
			return NULL;
		}
		else
		{
			// tenemos resultados
			$row = $rows[0];
			$stock = new mosArtzaraStock( $database );
			if(!$stock->load($row->id))
			{
				$logFile->writeLog("obtenerStock: Error: ".$stock->getError());
				return null;
			}
		}
		return $stock;
	}

	function EliminarStock($idFamilia,$idProducto){
		global $database, $logFile;
		$logFile->writeLog("EliminarStock: $idFamilia y $idProducto");
		$stock = &DDBB::obtenerStock($idFamilia,$idProducto);
		$logFile->writeLog("EliminarStock: stock: $stock");
		if($stock === null)
		{
			$logFile->writeLog("EliminarStock: Error obteniendo Stock");
			return FALSE;
		}
		$logFile->writeLog("EliminarStock");
		$row = new mosArtzaraStock( $database );
		if (!$row->delete($stock->id))
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}
		return TRUE;
	}

	// Entradas

	function &obtenerEntrada($id)
	{
		global $database, $logFile;

		$row = new mosArtzaraEntradas( $database );
		if(!$row->load( $id ))
		{
			$logFile->writeLog("obtenerEntrada: Error: ".$row->getError());
			return null;
		}
		$logFile->writeLog("obtenerEntrada: devolviendo datos de la entrada: ".$row->id);
		return $row;
	}

	function &obtenerListadoBodega($databaseName,&$filtroInfo,&$pageNav)
	{
		global $database, $logFile;

		$idFamilia = $filtroInfo->idFamilia;
		$idProducto = $filtroInfo->idProducto;
		$fechadesde = $filtroInfo->fechaDesde;
		$fechahasta = $filtroInfo->fechaHasta;

		$query = "SELECT * FROM $databaseName ";
		$AND = "";

		if($idFamilia || $idProducto || $fechadesde || $fechahasta)
			$query = $query . "WHERE ";

		if($idFamilia)
		{
			$query = $query . "id_grupo = $idFamilia";
			$AND = " AND ";
		}
		if($idProducto)
		{
			$query = $query . $AND . "id_prod = $idProducto";
		}
		if($fechadesde)
		{
			$query = $query . $AND . "inputdate >= '$fechadesde'";
			$AND = " AND ";
		}
		if($fechahasta)
			$query = $query . $AND . "inputdate <= '$fechahasta'";

		$query = $query . " ORDER BY inputdate DESC LIMIT {$pageNav->limitstart},{$pageNav->limit}";

		$logFile->writeLog("obtenerListadoBodega: Query: $query");

		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if($rows === NULL)
		{
			$logFile->writeLog("obtenerListadoBodega: Database Error: ".$database->stderr());
		}
		return $rows;

	}

	function obtenerTotalBodega($databaseName,&$filtroInfo)
	{
		global $database, $logFile;

		$idFamilia = $filtroInfo->idFamilia;
		$idProducto = $filtroInfo->idProducto;
		$fechadesde = $filtroInfo->fechaDesde;
		$fechahasta = $filtroInfo->fechaHasta;

		$query = "SELECT count(*) FROM $databaseName ";
		$AND = "";

		if($idFamilia || $idProducto || $fechadesde || $fechahasta)
			$query = $query . "WHERE ";

		if($idFamilia)
		{
			$query = $query . "id_grupo = $idFamilia";
			$AND = " AND ";
		}
		if($idProducto)
		{
			$query = $query . $AND . "id_prod = $idProducto";
		}
		if($fechadesde)
		{
			$query = $query . $AND . "inputdate >= '$fechadesde'";
			$AND = " AND ";
		}
		if($fechahasta)
			$query = $query . $AND . "inputdate <= '$fechahasta'";

		$database->setQuery( $query );

		$total = $database->loadResult();
		$logFile->writeLog("obtenerTotalBodega: $total Database: ".$database->getErrorMsg());
		return $total;
	}

	function obtenerTotalConsumosPorProducto($databaseName,$idFamilia,$idProducto,$fechadesde,$fechahasta)
	{
		global $database, $logFile;

		$total = -1;

		$query = "SELECT SUM(total) FROM $databaseName WHERE id_prod = $idProducto AND id_grupo = $idFamilia";

		$AND = " AND ";

		if($fechadesde)
		{
			$query = $query . $AND . "inputdate >= '$fechadesde'";
		}
		if($fechahasta)
			$query = $query . $AND . "inputdate <= '$fechahasta'";

		$database->setQuery( $query );

		$total = $database->loadResult();
		if($total == '')
			$total = -1;

		$logFile->writeLog("Inporte Total: $total Database: ".$database->getErrorMsg());

		return $total;
	}

	function obtenerTotalDosisConsumosPorProducto($databaseName,$idFamilia,$idProducto,$fechadesde,$fechahasta)
	{
		global $database, $logFile;

		$total = -1;

		$query = "SELECT SUM(cantidad) FROM $databaseName WHERE id_prod = $idProducto AND id_grupo = $idFamilia";

		$AND = " AND ";

		if($fechadesde)
		{
			$query = $query . $AND . "inputdate >= '$fechadesde'";
		}
		if($fechahasta)
			$query = $query . $AND . "inputdate <= '$fechahasta'";

		$database->setQuery( $query );

		$total = $database->loadResult();
		if($total == '')
			$total = -1;

		$logFile->writeLog("Dosis Totales: $total Database: ".$database->getErrorMsg());

		return $total;
	}

	function EliminarEntrada($id)
	{
		global $database, $artzaraadminpath, $logFile;

		$row = new mosArtzaraEntradas( $database );

		if (!$row->delete($id))
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}

		return TRUE;
	}

	function EliminarInventarioItem($id)
	{
		global $database, $artzaraadminpath, $logFile;

		$row = new mosArtzaraInventario( $database );

		if (!$row->delete($id))
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}

		return TRUE;
	}
	
	function &ObtenerConsumos($idSobre)
	{
		global $database, $artzaraadminpath, $logFile;

		$query = "SELECT * FROM #__artzara_consumos WHERE id_sobre = $idSobre";

		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if($rows === NULL)
		{
			$logFile->writeLog("ObtenerConsumos: Database Error: ".$database->stderr());
		}
		return $rows;
	}

	function EliminarConsumo($id)
	{
		global $database, $artzaraadminpath, $logFile;

		$row = new mosArtzaraConsumos( $database );

		if (!$row->delete($id))
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}

		return TRUE;
	}

	function EliminarConsumosFromSobre($id){
		global $database, $artzaraadminpath, $logFile;
		
		$logFile->writeLog("EliminarConsumosFromSobre: idSobre=$id");
		$query = "DELETE FROM mos_artzara_consumos WHERE id_sobre = $id";

		$database->setQuery( $query );
		if($database->query())
		{
			$result='';
		}
		else
		{
			$result = $database->stderr();
		}
		return $result;
	}

	function ResetEntradas($idFamilia,$idProducto)
	{
		global $database, $logFile;

		$logFile->writeLog("ResetEntradas: idFamilia=$idFamilia idProducto=$idProducto");
		$query = "DELETE FROM #__artzara_entradas WHERE id_grupo = $idFamilia AND id_prod = $idProducto";

		$database->setQuery( $query );
		if($database->query())
		{
			$result='';
		}
		else
		{
			$result = $database->stderr();
		}
		return $result;
	}
	
	// SOBRES
	
	function &obtenerSobre($idSobre){
		global $database, $logFile;
		$logFile->writeLog("obtenerSobre: idSobre=$idSobre");

		$row = new mosArtzaraSobres( $database );
		if(!$row->load( $idSobre ))
		{
			$logFile->writeLog("obtenerSobre: Error: ".$row->getError());
			return null;
		}
		$logFile->writeLog("obtenerSobre: devolviendo datos del sobre: ".$row->id);
		return $row;
	}
	
	function EliminarSobre($id){
		global $database, $logFile;
		$row = new mosArtzaraSobres( $database );

		if (!$row->delete($id))
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}
		return TRUE;
	}
	
	function &obtenerListadoSobres($databaseName,&$filtroInfo,&$pageNav)
	{
		global $database, $logFile;

		$idSocio = $filtroInfo->idSocio;
		$fechaDesde = $filtroInfo->fechaDesde;
		$fechaHasta = $filtroInfo->fechaHasta;
		$state = $filtroInfo->state;
		$debo = $filtroInfo->debo;

		$query = "SELECT * FROM $databaseName ";
		$AND = "";

		if($idSocio || $fechaDesde || $fechaHasta || $state || $debo)
			$query = $query . "WHERE ";

		if($idSocio)
		{
			$query = $query . "id_socio = $idSocio";
			$AND = " AND ";
		}
		if($state)
		{
			$query = $query . $AND . "state = $state";
			$AND = " AND ";
		}
		if($fechaDesde)
		{
			$query = $query . $AND . "inputdate >= '$fechaDesde'";
			$AND = " AND ";
		}
		if($fechaHasta){
			$query = $query . $AND . "inputdate <= '$fechaHasta'";
			$AND = " AND ";
		}
		if($debo){
			$query = $query . $AND . "total ".(($debo==1)?(">"):("<"))." pago";
		}

		$query = $query . " ORDER BY inputdate DESC LIMIT {$pageNav->limitstart},{$pageNav->limit}";

		$logFile->writeLog("obtenerListadoSobres: Query: $query");

		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if($rows === NULL)
		{
			$logFile->writeLog("obtenerListadoSobres: Database Error: ".$database->stderr());
		}
		return $rows;
	}

	function obtenerTotalSobres($databaseName,&$filtroInfo)
	{
		global $database, $logFile;

		$idSocio = $filtroInfo->idSocio;
		$fechaDesde = $filtroInfo->fechaDesde;
		$fechaHasta = $filtroInfo->fechaHasta;
		$state = $filtroInfo->state;
		$debo = $filtroInfo->debo;

		$query = "SELECT count(*) FROM $databaseName ";

		if($idSocio || $fechaDesde || $fechaHasta || $state || $debo)
			$query = $query . "WHERE ";

		$AND = "";

		if($idSocio)
		{
			$query = $query . "id_socio = $idSocio";
			$AND = " AND ";
		}
		if($state)
		{
			$query = $query . $AND . "state = $state";
			$AND = " AND ";
		}
		if($fechaDesde)
		{
			$query = $query . $AND . "inputdate >= '$fechaDesde'";
			$AND = " AND ";
		}
		if($fechaHasta){
			$query = $query . $AND . "inputdate <= '$fechaHasta'";
			$AND = " AND ";
		}
		if($debo){
			$query = $query . $AND . "total ".(($debo==1)?(">"):("<"))." pago";
		}

		$database->setQuery( $query );
		$logFile->writeLog("query=$query");
		$total = $database->loadResult();
		$logFile->writeLog("obtenerTotalSobres: $total Database: ".$database->getErrorMsg());
		return $total;
	}
	
	// Salidas
	function &obtenerListadoSalidasHaber(&$filtroInfo,&$pageNav){
		global $database, $logFile;

		$fechaDesde = $filtroInfo->fechaDesde;
		$fechaHasta = $filtroInfo->fechaHasta;
		$id_salida = $filtroInfo->id_salida;

		$query = "SELECT * FROM #__artzara_salidashaber ";
		$AND = "";

		if($fechaDesde || $fechaHasta || $id_salida)
			$query = $query . "WHERE ";

		$AND = "";

		if($id_salida)
		{
			$query = $query . "id_salida = $id_salida";
			$AND = " AND ";
		}
		if($fechaDesde)
		{
			$query = $query . $AND . "inputdate >= '$fechaDesde'";
			$AND = " AND ";
		}
		if($fechaHasta)
			$query = $query . $AND . "inputdate <= '$fechaHasta'";


		$query = $query . " ORDER BY inputdate DESC LIMIT {$pageNav->limitstart},{$pageNav->limit}";

		$logFile->writeLog("obtenerListadoSalidasHaber: Query: $query");

		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if($rows === NULL)
		{
			$logFile->writeLog("obtenerListadoSalidasHaber: Database Error: ".$database->stderr());
		}
		return $rows;
	}

	function obtenerTotalSalidasHaber(&$filtroInfo){
		global $database, $logFile;

		$fechaDesde = $filtroInfo->fechaDesde;
		$fechaHasta = $filtroInfo->fechaHasta;
		$id_salida = $filtroInfo->id_salida;

		$query = "SELECT count(*) FROM #__artzara_salidashaber ";

		if($fechaDesde || $fechaHasta || $id_salida)
			$query = $query . "WHERE ";

		$AND = "";

		if($id_salida)
		{
			$query = $query . "id_salida = $id_salida";
			$AND = " AND ";
		}
		if($fechaDesde)
		{
			$query = $query . $AND . "inputdate >= '$fechaDesde'";
			$AND = " AND ";
		}
		if($fechaHasta)
			$query = $query . $AND . "inputdate <= '$fechaHasta'";

		$database->setQuery( $query );

		$total = $database->loadResult();
		$logFile->writeLog("obtenerTotalSalidasHaber: $total Database: ".$database->getErrorMsg());
		return $total;
	}

	function getArtzaraSalidaId(){
		global $database, $logFile;
  		$artzaraName = _ARTZARA;
		$query = "SELECT id FROM #__artzara_salidas WHERE name='$artzaraName'";

		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if($rows === NULL)
		{
			$logFile->writeLog("obtenerSalidas: Database Error: ".$database->stderr());
			return null;
		}
		$row = $rows[0];
		return $row->id;
	}
	function &obtenerSalidas(){
		global $database, $logFile;
		$logFile->writeLog("obtenerSalidas:");

		$query = "SELECT * FROM #__artzara_salidas";

		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if($rows === NULL)
		{
			$logFile->writeLog("obtenerSalidas: Database Error: ".$database->stderr());
			return null;
		}
		$salidas = array();

		$salida = new mosArtzaraSalidas( $database );
		$salida->id = 0;
		$salida->name= "HABER";
		$salidas["0"] = $salida;

		foreach($rows as $row)
		{
			$salida = new mosArtzaraSalidas( $database );
			$salida->load( $row->id);
			$salidas["{$salida->id}"] = $salida;
			//$logFile->writeLog("Cargando familia:". $row->id .": ".$familia->nombre);
		}
		return $salidas;
	}

	function obtenerSalidasTotales($idSalida,$fechaDesde,$fechaHasta)
	{
		global $database, $logFile;
		$total = 0;

		$query = "SELECT SUM(importe) FROM #__artzara_salidashaber WHERE id_salida = $idSalida";
		$AND = " AND ";
		if($fechaDesde)
		{
			$query = $query . $AND . "inputdate >= '$fechaDesde'";

		}
		if($fechaHasta)
			$query = $query . $AND . "inputdate <= '$fechaHasta'";

		$database->setQuery( $query );

		$total = $database->loadResult();

		$total = ($total == '')?(0):($total);
		$logFile->writeLog("obtenerSalidasTotales: $total Database: ".$database->getErrorMsg());
		return $total;
	}

	function &obtenerSalida($idSalida){
		global $database, $logFile;
		$logFile->writeLog("obtenerSalida: idSalida=$idSalida");

		$row = new mosArtzaraSalidas( $database );
		if(!$row->load( $idSalida ))
		{
			$logFile->writeLog("obtenerSalida: Error: ".$row->getError());
			return null;
		}
		//$logFile->writeLog("obtenerSalida: devolviendo datos del sobre: ".$row->id);
		return $row;
	}

	function EliminarSalida($id){
		global $database, $logFile;
		$row = new mosArtzaraSalidas( $database );

		if (!$row->delete($id))
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}
		return TRUE;
	}

	function EliminarSalidaHaber($id){
		global $database, $logFile;
		$row = new mosArtzaraSalidasHaber( $database );

		if (!$row->delete($id))
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}
		return TRUE;
	}

	function &obtenerMensaje($id){
		global $database, $logFile;
		$logFile->writeLog("obtenerMensaje: id=$id");

		$row = new mosArtzaraMensajes( $database );
		if(!$row->load( $id ))
		{
			$logFile->writeLog("obtenerMensaje: Error: ".$row->getError());
			return null;
		}
		//$logFile->writeLog("obtenerSalida: devolviendo datos del sobre: ".$row->id);
		return $row;
	}

	function EliminarMensaje($id){
		global $database, $logFile;
		$row = new mosArtzaraMensajes( $database );

		if (!$row->delete($id))
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}
		return TRUE;
	}
	function obtenerListadoMensajes(&$filtroInfo,&$pageNav){
		global $database, $logFile;

		$fechaDesde = $filtroInfo->fechaDesde;
		$fechaHasta = $filtroInfo->fechaHasta;
		$id_remite = $filtroInfo->id_remite;
		$id_destino = $filtroInfo->id_destino;

		$query = "SELECT * FROM #__artzara_mensajes ";

		if($fechaDesde || $fechaHasta || $id_remite || $id_destino)
			$query = $query . "WHERE ";

		$AND = "";

		if($id_destino)
		{
			$query = $query . "id_destino = $id_destino";
			$AND = " AND ";
		}
		if($id_remite)
		{
			$query = $query . $AND . "id_remite = $id_remite";
			$AND = " AND ";
		}
		if($fechaDesde)
		{
			$query = $query . $AND . "inputdate >= '$fechaDesde'";
			$AND = " AND ";
		}
		if($fechaHasta)
			$query = $query . $AND . "inputdate <= '$fechaHasta'";

		$query = $query . " ORDER BY inputdate DESC LIMIT {$pageNav->limitstart},{$pageNav->limit}";

		$logFile->writeLog("obtenerListadoMensajes: Query: $query");

		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		if($rows === NULL)
		{
			$logFile->writeLog("obtenerListadoMensajes: Database Error: ".$database->stderr());
		}
		return $rows;
	}

	function obtenerTotalMensajes(&$filtroInfo){
		global $database, $logFile;

		$fechaDesde = $filtroInfo->fechaDesde;
		$fechaHasta = $filtroInfo->fechaHasta;
		$id_remite = $filtroInfo->id_remite;
		$id_destino = $filtroInfo->id_destino;

		$query = "SELECT count(*) FROM #__artzara_mensajes ";

		if($fechaDesde || $fechaHasta || $id_remite || $id_destino)
			$query = $query . "WHERE ";

		$AND = "";

		if($id_destino)
		{
			$query = $query . "id_destino = $id_destino";
			$AND = " AND ";
		}
		if($id_remite)
		{
			$query = $query . $AND . "id_remite = $id_remite";
			$AND = " AND ";
		}
		if($fechaDesde)
		{
			$query = $query . $AND . "inputdate >= '$fechaDesde'";
			$AND = " AND ";
		}
		if($fechaHasta)
			$query = $query . $AND . "inputdate <= '$fechaHasta'";

		$database->setQuery( $query );

		$total = $database->loadResult();
		$logFile->writeLog("obtenerTotalMensajes: $total Database: ".$database->getErrorMsg());
		return $total;
	}
}
?>
