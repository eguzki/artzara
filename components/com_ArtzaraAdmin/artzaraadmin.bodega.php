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

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

//$screen viene de arriba
$accion = mosGetParam( $_REQUEST, 'accion');

switch ($screen)
{
	case "1":
		$logFile->writeLog("A_Referencias");
		NavBodega();
		mostrarTitulo("REFERENCIAS");
		PantallaReferencias($accion);
		NavBodega();
		break;

	case "2":
		$logFile->writeLog("A_Entradas");
		NavBodega();
		mostrarTitulo("ENTRADAS");
		PantallaEntradas($accion);
		NavBodega();
		break;

	case "3":
		$logFile->writeLog("A_Consumos");
		NavBodega();
		mostrarTitulo("CONSUMOS");
		ModuloConsumos($accion);
		NavBodega();
		break;

	case "4":
		$logFile->writeLog("A_Stock");
		NavBodega();
		mostrarTitulo("STOCK");
		PantallaStock($accion);
		NavBodega();
		break;

	case "5":
		$logFile->writeLog("A_Inventario");
		NavBodega();
		mostrarTitulo("INVENTARIO");
		ModuloInventario($accion);
		NavBodega();
		break;

	default:
		$logFile->writeLog("A_Principal");
		mostrarTitulo("BODEGA: MENU PRINCIPAL");
		BodegaPantallaPrincipal();
		break;
}

function PantallaReferencias($act)
{
	global $task,$option,$Itemid,$logFile,$baseURL;
	// Independientemente de la accion, necesitamos las familias de productos
	$familias = &DDBB::obtenerFamilias();
	$logFile->writeLog("accion: $act");
	switch($act)
	{
		case "nuevafamilia":
			$logFile->writeLog("nuevafamilia");
			PantallaReferenciasHTML(1,$familias);
			break;
		case "nuevoproducto":
			$logFile->writeLog("nuevoproducto");
			PantallaReferenciasHTML(2,$familias);
			break;
		case "familiaseleccionada":
			$logFile->writeLog("familiaseleccionada");
			//obtener datos de la familia
			$idFamilia = intval(mosGetParam( $_GET,'idfamilia'));
			if(!$idFamilia)
			{
				$message = 'Familia param invalid';
				EnvioError($message,$task);
				break;
			}

			$familia = DDBB::obtenerFamilia($idFamilia);
			if($familia === NULL) //Doble == significa que no tiene parametros, el triple que es NULL
			{
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}

			//obtener los elementos de producto y enviarlo
			$productos = &DDBB::obtenerProductos($idFamilia);
			if($productos === NULL)
			{
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}

			PantallaReferenciasHTML(1,$familias,$familia,$productos);
			break;
		case "productoseleccionado":
			$idFamilia = intval(mosGetParam( $_GET,'idfamilia'));
			$idProducto = intval(mosGetParam( $_GET,'idproducto'));
			$logFile->writeLog("productoseleccionado: Familia:$idFamilia Producto:$idProducto");

			if(!$idFamilia || !$idProducto)
			{
				$message = 'Familia or Product param invalid';
				EnvioError($message,$task);
				break;
			}

			//obtener datos del elemento
			$productos = &DDBB::obtenerProductos($idFamilia);
			if($productos === null)
			{
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}

			$producto = DDBB::obtenerProducto($idProducto);
			if($producto === null)
			{
				$message = "Product $idProducto not found";
				EnvioError($message,$task);
				break;
			}
			PantallaReferenciasHTML(1,$familias,$idFamilia,$productos,$producto);
			break;
		case "formulariofamilia":
			$logFile->writeLog("formulariofamilia proceso: $proceso");
			$proceso = mosGetParam( $_POST, 'proceso', array(0) );
			switch($proceso)
			{
				case "cancelar":
					PantallaReferenciasHTML(0,$familias);
					break;
				case "guardar":
					// Nueva Familia o actualizar familia!!!
					if(!ArtzaraAdminLib::GuardarFamilia())
					{
						$message = 'Family could not be saved';
						EnvioError($message,$task);
					}
					mosRedirect($baseURL."&task=$task");
					break;
				case "eliminar":
					$idFamilia = mosGetParam( $_POST, 'id');
					if(!$idFamilia)
					{
						$message = 'Family parameter not found while erasing';
						EnvioError($message,$task);
						break;
					}

					$productos = &DDBB::obtenerProductos($idFamilia);
					if(count($productos)>0)
					{
						$message = "La familia no se puede eliminar.<br>Elimine o cambie de la familia los productos que le pertenezcan.";
						EnvioError($message,$task);
						break;
					}

					if(ArtzaraAdminLib::EliminarFamilia($idFamilia))
					{
						$message = 'Family ERASED';
						EnvioResultOK($message,$task);
					}
					else
					{
						$message = 'The family could not be erased ';
						EnvioError($message,$task);
					}
					break;
				default:
					PantallaReferenciasHTML(0,$familias);
					break;
			}
			break;
		case "formularioproducto":
			$logFile->writeLog("formularioproducto");
			$proceso = mosGetParam( $_POST, 'proceso', array(0) );
			switch($proceso)
			{
				case "cancelar":
					PantallaReferenciasHTML(0,$familias);
					break;
				case "guardar":
					// Nuevo producto o actualizar producto!!!
					if(!ArtzaraAdminLib::GuardarProducto())
					{
						$message = 'Product could not be saved';
						EnvioError($message,$task);
						break;
					}
					mosRedirect($baseURL."&task=$task");
					break;
				case "eliminar":
					$idProducto = mosGetParam( $_POST, 'id');
					$logFile->writeLog("Eliminar Producto $idProducto");
					if(!$idProducto)
					{
						$message = 'Product parameter not found while erasing';
						EnvioError($message,$task);
						break;
					}
					if(ArtzaraAdminLib::EliminarProducto($idProducto))
					{
						$message = 'Product ERASED';
						EnvioResultOK($message,$task);
					}
					else
					{
						$message = 'The product could not be erased ';
						EnvioError($message,$task);
					}
					break;
				default:
					PantallaReferenciasHTML(0,$familias);
					break;
			}
			break;
		default:
			$logFile->writeLog("default");
			PantallaReferenciasHTML(0,$familias);
			break;
	}
}

function PantallaEntradas($act)
{
	global $task,$option,$Itemid,$logFile,$mainframe,$baseURL;

	$logFile->writeLog("PantallaEntradas: accion $act");
	switch($act)
	{
		case "1":
			//Familias nuevo
			$familias = &DDBB::obtenerFamilias(1);
			PantallaEntradasHTML($act,$familias);
			break;
		case "2":
			//Familias - Listado
			$familias = &DDBB::obtenerFamilias(1);
			PantallaEntradasHTML($act,$familias);
			break;
		case "3":
		case "4":
			//Productos - Nuevo o Listado
			$familias = &DDBB::obtenerFamilias(1);
			$idFamilia = intval(mosGetParam( $_GET,'idfamilia'));
			if(!$idFamilia)
			{
				$message = 'Familia param invalid';
				EnvioError($message,$task);
				break;
			}

			//obtener los elementos de producto y enviarlo
			$productos = &DDBB::obtenerProductos($idFamilia,1);
			if($productos === NULL)
			{
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			PantallaEntradasHTML($act,$familias,$idFamilia,$productos);
			break;
		case "5":
		case "6":
			//Familia-producto-Nuevo o Listado
			$idFamilia = intval(mosGetParam( $_GET,'idfamilia'));
			$idProducto = intval(mosGetParam( $_GET,'idproducto'));
			$logFile->writeLog("productoseleccionado: Familia:$idFamilia Producto:$idProducto");

			if(!$idFamilia || !$idProducto)
			{
				$message = 'Familia or Product param invalid';
				EnvioError($message,$task);
				break;
			}
			$familias = &DDBB::obtenerFamilias(1);
			//obtener datos del elemento
			$productos = &DDBB::obtenerProductos($idFamilia,1);
			if($productos === null)
			{
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			PantallaEntradasHTML($act,$familias,$idFamilia,$productos,$idProducto);
			break;
		case "7":
			//Listado
			// 5 tipos de filtro
			// Familia
			// Familia - Producto
			// Familia - fecha
			// Familia - prodcto - fecha
			// fecha
			//$logFile->writeLog("Entradas: Listado: REQUEST_URI={$_SERVER['REQUEST_URI']}");
			$limit = intval( mosGetParam( $_REQUEST, 'limit', '' ) );
			$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
			$nombreFamilia = "";
			$nombreProducto = "";
			$idFamilia = intval(mosGetParam( $_REQUEST,'idfamilia'));
			$idProducto = intval(mosGetParam( $_REQUEST,'idproducto'));
			$fechadesde = mosGetParam( $_REQUEST,'fechadesde');
			$fechahasta = mosGetParam( $_REQUEST,'fechahasta');

			if($idFamilia)
			{
				$row = DDBB::obtenerFamilia($idFamilia);
				if($row !==null)
					$nombreFamilia = $row->nombre;
			}
			if($idProducto)
			{
				$row = DDBB::obtenerProducto($idProducto);
				if($row !==null)
					$nombreProducto = $row->nombre;
			}

			$filtroInfo = new BodegaFiltroInfo();
			$filtroInfo->idFamilia = $idFamilia;
			$filtroInfo->idProducto = $idProducto;
			$filtroInfo->nombreFamilia = $nombreFamilia;
			$filtroInfo->nombreProducto = $nombreProducto;
			$filtroInfo->fechaDesde = $fechadesde;
			$filtroInfo->fechaHasta = $fechahasta;

			// get the total number of records
			$total = DDBB::obtenerTotalBodega("#__artzara_entradas",$filtroInfo);

			$limit = $limit ? $limit : LIM_LISTADO ;

			if ( $total <= $limit )
			{
				$limitstart = 0;
			}

			require_once("includes/pageNavigation.php");
			$pageNav = new mosPageNav( $total, $limitstart, $limit );

			$datos = &DDBB::obtenerListadoBodega("#__artzara_entradas",$filtroInfo,$pageNav);

			if($datos === null)
			{
				$message = _DATABASE_ERROR;
				EnvioError($message,$task);
				break;
			}

			$nombres = array();

			for($i=0; $i < count( $datos ); $i++)
			{
				$row1 = DDBB::obtenerFamilia($datos[$i]->id_grupo);
				$row2 = DDBB::obtenerProducto($datos[$i]->id_prod);
				$nombres[$i] = array(0 => $row1->nombre,1 => $row2->nombre);
				//$logFile->writeLog($i.": ".$nombres[$i][0]."; ".$nombres[$i][1]);
			}
			ListadoBodega($datos,$nombres,$pageNav,$filtroInfo,"7");
			break;
		case "borrar":
			//Borrar entrada(s)
			$arrayIds = mosGetParam( $_POST,'id');
			if(!$arrayIds || count($arrayIds) < 1)
			{
				$message = 'No se han seleccionado entradas';
				EnvioError($message,$task);
				break;
			}
			$result = TRUE;
			for($i=0; $i < count( $arrayIds ); $i++)
			{
				if(!DDBB::EliminarEntrada($arrayIds[$i]))
					$result = FALSE;
			}
			if($result)
			{
				$message = 'Entradas ELIMINADAS OK';
				EnvioResultOK($message,$task);
			}
			else
			{
				$message = 'No se pudieron eliminar todas las entradas';
				EnvioError($message,$task);
			}
			break;
		case "9":
			//Guardar Modificar
			if(!ArtzaraAdminLib::GuardarEntrada())
			{
				$message = 'Entrada could not be saved';
				EnvioError($message,$task);
			}
			mosRedirect($baseURL."&task=$task");
			break;
		default:
			EntradasMain();
			break;
	}
	$logFile->writeLog("Fin PantallaEntradas");
}

function ModuloConsumos($act)
{
	global $task,$option,$Itemid,$logFile,$baseURL,$mainframe;

	$logFile->writeLog("ModuloConsumos: accion $act");
	switch($act)
	{
		default:
			$logFile->writeLog("Default: Listado Consumos");

			$limit = intval( mosGetParam( $_REQUEST, 'limit', '' ) );
			$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
			$idFamilia = intval(mosGetParam( $_REQUEST,'idfamilia',0));
			$idProducto = intval(mosGetParam( $_REQUEST,'idproducto',0));
			$fechadesde = mosGetParam( $_REQUEST,'fechadesde');
			$fechahasta = mosGetParam( $_REQUEST,'fechahasta');

			$productos = null;
			$totalProducto = -1;
			$totalDosis = -1;

			if($idFamilia){
				$productos = &DDBB::obtenerProductos($idFamilia,1);
				if($productos === null)
				{
					EnvioError("Productos no encontrados",'000');
					break;
				}
				$logFile->writeLog("Obtenidos ".count($productos)." productos");
				if($idProducto){
				// Obtener el total de consumos del producto
					$totalProducto = DDBB::obtenerTotalConsumosPorProducto("#__artzara_consumos",$idFamilia,$idProducto,$fechadesde,$fechahasta);
					$totalDosis = DDBB::obtenerTotalDosisConsumosPorProducto("#__artzara_consumos",$idFamilia,$idProducto,$fechadesde,$fechahasta);

				}
			}

			$filtroInfo = new BodegaFiltroInfo();
			$filtroInfo->idFamilia = $idFamilia;
			$filtroInfo->idProducto = $idProducto;
			$filtroInfo->fechaDesde = $fechadesde;
			$filtroInfo->fechaHasta = $fechahasta;

			$total = DDBB::obtenerTotalBodega("#__artzara_consumos",$filtroInfo);

			$limit = $limit ? $limit : LIM_LISTADO ;

			if ( $total <= $limit )
			{
				$limitstart = 0;
			}

			require_once("includes/pageNavigation.php");
			$pageNav = new mosPageNav( $total, $limitstart, $limit );

			$datos = &DDBB::obtenerListadoBodega("#__artzara_consumos",$filtroInfo,$pageNav);

			if($datos === null)
			{
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}

			$nombres = array();

			for($i=0; $i < count( $datos ); $i++)
			{
				$row1 = &DDBB::obtenerFamilia($datos[$i]->id_grupo);
				$row2 = &DDBB::obtenerProducto($datos[$i]->id_prod);
				$nombres[$i] = array(0 => $row1->nombre,1 => $row2->nombre);
			}

			$familias = &DDBB::obtenerFamilias(1);

			PantallaConsumosHTML($datos,$nombres,$pageNav,$filtroInfo,$familias,$idFamilia,$productos,$idProducto,$totalProducto,$totalDosis);
			break;
	}
	$logFile->writeLog("Fin PantallaConsumos");
}

function PantallaStock($act)
{
	global $task,$option,$Itemid,$logFile;
	// Independientemente de la accion, necesitamos las familias de productos
	$familias = &DDBB::obtenerFamilias(1);
	switch($act)
	{
		case "familiaseleccionada":
			$logFile->writeLog("PantallaStock: familiaseleccionada");
			//obtener datos de la familia
			$idFamilia = intval(mosGetParam( $_GET,'idfamilia'));
			if(!$idFamilia)
			{
				$message = 'Familia param invalid';
				EnvioError($message,$task);
				break;
			}

			$familia = DDBB::obtenerFamilia($idFamilia);
			if($familia === NULL) //Doble == significa que no tiene parametros, el triple que es NULL
			{
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}

			//obtener los elementos de producto y enviarlo
			$productos = &DDBB::obtenerProductos($idFamilia,1);
			if($productos === NULL)
			{
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}

			PantallaStockHTML($familias,$idFamilia,$productos);
			break;
		case "productoseleccionado":
			$logFile->writeLog("PantallaStock: productoseleccionado");
			$idFamilia = intval(mosGetParam( $_GET,'idfamilia'));
			$idProducto = intval(mosGetParam( $_GET,'idproducto'));

			if(!$idFamilia || !$idProducto)
			{
				$message = 'Familia or Product param invalid';
				EnvioError($message,$task);
				break;
			}

			//obtener datos del elemento
			$productos = &DDBB::obtenerProductos($idFamilia,1);
			if($productos === null)
			{
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}

			$stock = DDBB::obtenerStock($idFamilia,$idProducto);
			if($stock === null)
			{
				$message = "Product $idProducto not found";
				EnvioError($message,$task);
				break;
			}
			//$logFile->writeLog("PantallaStock: ".gettype($stock->fechamod));
			PantallaStockHTML($familias,$idFamilia,$productos,$stock);
			break;
		default:
			$logFile->writeLog("MAIN STOCK");
			PantallaStockHTML($familias);
			break;
	}
}

function ModuloInventario($act)
{
	global $task,$option,$Itemid,$baseURL,$logFile,$database;
	// Independientemente de la accion, necesitamos las familias de productos
	switch($act)
	{
		case "new":
			$logFile->writeLog("ModuloInventario: nueva entrada inventario");
			$idFamilia = intval(mosGetParam( $_REQUEST,'idfamilia',0));
			$idProducto = intval(mosGetParam( $_REQUEST,'idproducto',0));

			if(!$idFamilia || !$idProducto){
				mosRedirect($baseURL."&task=$task");
				break;
			}
			$stock = &DDBB::obtenerStock($idFamilia,$idProducto);
			if($stock === null)
			{
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			$producto = &DDBB::obtenerProducto($idProducto);
			if($producto === null)
			{
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			NuevoInventario($stock,$producto);
			break;
		case "save":
			$logFile->writeLog("PantallaInventario: formulario");

			$idFamilia = intval(mosGetParam( $_POST,'id_grupo',0));
			$idProducto = intval(mosGetParam( $_POST,'id_prod',0));
			$numUnidades = intval(mosGetParam( $_POST,'displayOut',0));
			$numDosis = intval(mosGetParam( $_POST,'dosis',0));

			if(!$idProducto || !$idFamilia){
				EnvioError("el Idproducto o el idFamilia no se enviaron",$task);
				break;
			}

			//calculo inventario y inserto registro
			if($numUnidades<0)
			{
				EnvioError("El stock no puede ser negativo",$task);
				break;
			}

			//Volvemos a obtener el stock.
			$stock = &DDBB::obtenerStock($idFamilia,$idProducto);
			if($stock === null)
			{
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}

			$oldStockUnidades = $stock->cantidad;
			$oldStockDosis = $stock->dosis;
			$stock->dosis = $numDosis;
			$stock->cantidad = $numUnidades;
			$stock->fechamod = date('Y-m-d H:i:s');
			if (!$stock->store())
			{
				EnvioError("Error: ".$stock->getError(),$task);
				break;
			}
			// Actualizar stock
			$logFile->writeLog("PantallaInventario: Stock UPDATED OK");

			$producto = &DDBB::obtenerProducto($idProducto);
			if($producto === null)
			{
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}

			$row = new mosArtzaraInventario( $database );
			$row->id = 0;
			$row->id_grupo = $idFamilia;
			$row->id_prod = $idProducto;
			$row->cantidad = $numUnidades - $oldStockUnidades;
			$row->dosis = $numDosis - $oldStockDosis;

			$total_unidad = $producto->venta_publico_dosis * $producto->num_dosis_por_botella * $row->cantidad;
			$total_dosis = $row->dosis * $producto->venta_publico_dosis;
			$row->total = (intval(($total_unidad + $total_dosis) * 100))/100;
			$row->inputdate = date('Y-m-d H:i:s');

			if (!$row->store())
			{
				EnvioError("Error: ".$row->getError(),$task);
				break;
			}
			$logFile->writeLog("PantallaInventario: inventario Inserted OK");
			mosRedirect($baseURL."&task=$task");
			break;
		case "remove":
			$logFile->writeLog("Borrar Inventario");
			$arrayIds = mosGetParam( $_POST,'id');
			if(!$arrayIds || count($arrayIds) < 1)
			{
				EnvioError(_PARAMETERS_INVALID,$task);
				break;
			}
			$result = TRUE;
			for($i=0; $i < count( $arrayIds ); $i++)
			{
				if(!DDBB::EliminarInventarioItem($arrayIds[$i]))
					$result = FALSE;
			}
			if(!$result)
			{
				$message = 'No se pudieron eliminar todos los inventarios seleccionados';
				EnvioError($message,$task);
			}
			mosRedirect($baseURL."&task=$task");
			break;
		case "reset":
			$logFile->writeLog("PantallaInventario: reset");

			$idFamilia = intval(mosGetParam( $_POST,'id_grupo'));
			$idProducto = intval(mosGetParam( $_POST,'id_prod'));

			if(!$idFamilia || !$idProducto)
			{
				$message = 'Familia or Product param invalid';
				EnvioError($message,$task);
				break;
			}
			// Actualizar entrada de stock
			$newStock = &DDBB::obtenerStock($idFamilia,$idProducto);
			if($newStock === null){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}

			$newStock->cantidad = 0;
			$newStock->dosis = 0;

			if(!$newStock->store())
			{
				$message = 'ERROR UPDATING STOCK';
				EnvioError($message,$task);
				break;
			}
			mosRedirect($baseURL."&task=$task");
			break;
		default:
			// Listado
			$logFile->writeLog("Default: Listado Inventario");

			$limit = intval( mosGetParam( $_REQUEST, 'limit', '' ) );
			$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
			$idFamilia = intval(mosGetParam( $_REQUEST,'idfamilia',0));
			$idProducto = intval(mosGetParam( $_REQUEST,'idproducto',0));
			$fechadesde = mosGetParam( $_REQUEST,'fechadesde');
			$fechahasta = mosGetParam( $_REQUEST,'fechahasta');

			$productos = null;

			if($idFamilia){
				$productos = &DDBB::obtenerProductos($idFamilia,1);
				if($productos === null)
				{
					EnvioError("Productos no encontrados",'000');
					break;
				}
				$logFile->writeLog("Obtenidos ".count($productos)." productos");
			}

			$filtroInfo = new BodegaFiltroInfo();
			$filtroInfo->idFamilia = $idFamilia;
			$filtroInfo->idProducto = $idProducto;
			$filtroInfo->fechaDesde = $fechadesde;
			$filtroInfo->fechaHasta = $fechahasta;

				// get the total number of records
			$total = DDBB::obtenerTotalBodega("#__artzara_inventario",$filtroInfo);

			$limit = $limit ? $limit : LIM_LISTADO ;

			if ( $total <= $limit )
			{
				$limitstart = 0;
			}

			require_once("includes/pageNavigation.php");
			$pageNav = new mosPageNav( $total, $limitstart, $limit );

			$datos = &DDBB::obtenerListadoBodega("#__artzara_inventario",$filtroInfo,$pageNav);

			if($datos === null)
			{
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			$nombres = array();

			for($i=0; $i < count( $datos ); $i++)
			{
				$row1 = &DDBB::obtenerFamilia($datos[$i]->id_grupo);
				$row2 = &DDBB::obtenerProducto($datos[$i]->id_prod);
				$nombres[$i] = array(0 => $row1->nombre,1 => $row2->nombre);
			}

			$familias = &DDBB::obtenerFamilias(1);

			ListadoInventarioHTML($datos,$nombres,$pageNav,$filtroInfo,$familias,$idFamilia,$productos,$idProducto);
			break;
	}
}

?>
