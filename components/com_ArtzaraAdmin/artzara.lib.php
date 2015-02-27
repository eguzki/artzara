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
DEFINE('_TODOS_SOBRES', 0 );
DEFINE('_PROCESADO', 1 );
DEFINE('_NO_PROCESADO', 2 );
DEFINE('_NOT_CLOSED', 3 );
DEFINE('_ANULADO', 4 );

DEFINE('_ARTZARA', "ARTZARA" );

DEFINE('LIM_LISTADO', 50 );

$imagenX = $artzaraadminurl."images/imagenX.jpg";
$artzaraLogo = $artzaraadminurl."images/artzaralogo.jpg";
$numColumnas = 5;


class GenericLib
{
	function myIcono( $task='', $icon='', $iconOver='', $alt='', $listSelect=true ) {
		if ($listSelect) {
			$href = "javascript:if (document.adminForm.boxchecked.value == 0){ alert('Selecciona un elemento para $alt');}else{submitbutton('$task')}";
		} else {
			$href = "javascript:submitbutton('$task')";
		}
		?>
		<td>
		<a class="toolbar" href="<?php echo $href;?>" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('<?php echo $task;?>','','administrator/images//<?php echo $iconOver;?>',1);">
		<img name="<?php echo $task;?>" src="administrator/images/<?php echo $icon;?>" alt="<?php echo $alt;?>" border="0" align="middle" />
		&nbsp;
		<?php echo $alt; ?>
		</a>
		</td>
		<?php
	}

	function &ObtenerListadoConsumos($idSobre){
		global $database, $logFile;

		$consumos = &DDBB::ObtenerConsumos($idSobre);
		$rows = array();
		foreach($consumos as $consumo){
			$consumosInfo = new ConsumosInfo();
			$consumosInfo->id = $consumo->id;
			$consumosInfo->cantidad = $consumo->cantidad;
			$family = &DDBB::obtenerFamilia($consumo->id_grupo);
			if($family === null)
				return null;
			$product =  &DDBB::obtenerProducto($consumo->id_prod);
			if($product === null)
				return null;
			$consumosInfo->familyName = $family->nombre;
			$consumosInfo->productName = $product->nombre;
			$consumosInfo->importe = $consumo->total;
			$rows["{$consumosInfo->id}"] = $consumosInfo;
		}
		return $rows;
	}

	function GuardarSocio($id = 0)
	{
		global $database, $logFile, $imagenX, $artzaraadminpath, $artzaraadminurl;
		$resultado = FALSE;
		$newImageName = "";

		// Se puede hacer un bind, pero hay que andar al loro con el imagefile.

		$row = new mosArtzaraSocios( $database );

		$logFile->writeLog("GuardarSocio: id=$id");

		if($id){
			if(!$row->load($id)){
				$logFile->writeLog("Error: ".$row->getError());
				return FALSE;
			}
		}
		else{
			$row->fecha_alta = date('Y-m-d H:i:s');
		}

		if (!$row->bind( $_POST )){
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}

		$logFile->writeLog("GuardarSocio: haber=".$row->haber);

		$nombre_imagen = $_FILES['foto']['name'];

		if($nombre_imagen == '')
		{
			$logFile->writeLog("Guardar Socio: foto vacia!");
			// No se envia imagen. La imagen queda como esta, luego si existe id,
			// hay que tomar su valor y guardarlo, sino se borrara
			if($row->id)
			{
				// estamos actualizando, asi que hay que mantener el valor
				$row2 = new mosArtzaraSocios( $database );
				$row2->load( $row->id );
				$row->foto = $row2->foto;
			}
			else
			{
				// nueva imagen, sin imagen, HTML se encarga de poner la X
				$row->foto = "";
			}
		}
		else
		{
			$logFile->writeLog("Guardar Socio: foto:".$nombre_imagen.": ".$_FILES['foto']['type']);
			$prefijo = date("zHis");
			$imagenPath = $artzaraadminpath."images/".$prefijo.basename($nombre_imagen);
			// elimino la imagen anterior si esta existiese
			if($row->id)
			{
				//Estamos actualizando imagen
				$row2 = new mosArtzaraSocios( $database );
				$row2->load( $row->id );
				if($row2->foto!='' && !unlink($artzaraadminpath."images/".$row2->foto))
					$logFile->writeLog("No se ha podido eliminar: ".$artzaraadminpath."images/".$row2->foto);
			}

			// Se envia imagen, hay que descargarla y poner el path online de la foto.
			if (is_uploaded_file($_FILES['foto']['tmp_name']))
			{
				if($_FILES['foto']['type'] == 'image/gif' ||
					$_FILES['foto']['type'] == 'image/pjpeg' ||
					$_FILES['foto']['type'] == 'image/jpeg')
				{
					// copiarlo con otro nombre para que cada familia tenga su imagen asociada
					if(!copy($_FILES['foto']['tmp_name'],$imagenPath))
					{
						$logFile->writeLog("Error: No se pudo copiar el archivo:".$imagenPath);
						return FALSE;
					}
				}
				else
				{
					$logFile->writeLog("Error: Tipo fichero incorrecto:". $_FILES['foto']['type']);
					return FALSE;
				}
			}
			else
			{
				$logFile->writeLog("Error: Possible file upload attack. Filename: " . $_FILES['foto']['name']);
				return FALSE;
			}
			$row->foto = $prefijo.basename($nombre_imagen);
			$logFile->writeLog("GuardarFamilia: Imagen cargada: ".$row->foto);
		}
		if (!$row->store())
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}
		return TRUE;
	}

	function GuardarMensaje(){
		global $database, $logFile, $user;

		$logFile->writeLog("Guardar Mensaje");

		$row = new mosArtzaraMensajes( $database );

		if (!$row->bind( $_POST ))
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}

		$row->inputdate = date('Y-m-d H:i:s');
		$row->id = 0;
		$row->id_remite = $user->id;
		$row->visto = 0;

		if (!$row->store())
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}
		// Tenemos los datos grabados
		return TRUE;
	}

	function obtenerRuta($task)
	{
		if($task == null || !is_string($task) || strlen ($task) < 3)
			return array (null, null);

		return array(substr ($task, 0, 2),substr ($task, 2, 1));
	}

	function GenerarSalidaHaber($idSobre,$importe,$id_salida){
		global $database, $logFile;
		// Suponemos que idsobre e idsalida estan bien porque los hemos generado nosotros,
		// no son datos qeu nos vienen del usuario
		$logFile->writeLog("GenerarSalidaHaber");

		$row = new mosArtzaraSalidasHaber( $database );

		$row->id = 0;
		$row->id_sobre=$idSobre;
		$row->inputdate = date('Y-m-d H:i:s');
		$row->importe=$importe;
		$row->id_salida=$id_salida;
		
		if (!$row->store())
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}
		// Tenemos los datos grabados
		$logFile->writeLog("GenerarSalidaHaber: grabados OK!");
		return TRUE;		
	}

	function ProcesarSobre(&$sobre,$descuadre){
		global $database, $logFile;
		$logFile->writeLog("ProcesarSobre: sobre: {$sobre->id} descuadre=$descuadre id_salida={$sobre->id_salida}");

		// Tasks: 
		// 1.- Actualizar Stock
		//	2.- Contabilidad usuario 2.1: actualizar valor en cuenta 2.2: Nueva entrada en cuenta_usuarios
		//	3.- Modificar sobre y guardar (estado, pagado, fecha proceso)

		if($sobre === null || $descuadre === null)
			return FALSE;

		if($descuadre > 0 && $sobre->id_salida > 0){
			$logFile->writeLog("ProcesarSobre: Generando salida hacia {$sobre->id_salida}");
			// Descaudre positivo y id_salida diferente de 0 (usea no es para el usuario)
			if(!GenericLib::GenerarSalidaHaber($sobre->id,$descuadre,$sobre->id_salida)){
				EnvioError("NO SE HA PODIDO PROCESAR ESTE SOBRE. INTENTELO DE NUEVO",$task);
				break;
			}
		}
		elseif($descuadre != 0)
		{
			// Solo nueva entrada en cuenta socio si hay movimiento en la cuenta
			// 1.- Actualizar contabilidad Usuarios
			$socio = &DDBB::obtenerSocio($sobre->id_socio);
			if($socio === null){
				$logFile->writeLog("Error No se pudo obtener Socio: ");
				return FALSE;
			}

			$socio->haber = $socio->haber + $descuadre;
			if (!$socio->store())
			{
				$logFile->writeLog("Error: ".$socio->getError());
				return FALSE;
			}

			$newCuentaSocio = new mosArtzaraCuentaSocios( $database );
			$newCuentaSocio->id = 0;
			$newCuentaSocio->id_socio = $sobre->id_socio;
			$newCuentaSocio->saldo = $socio->haber;
			$newCuentaSocio->pago = $descuadre;
			$newCuentaSocio->inputdate = date('Y-m-d H:i:s');

			if (!$newCuentaSocio->store())
			{
				$logFile->writeLog("Error: ".$newCuentaSocio->getError());
				return FALSE;
			}
		}
		// 2.- Actualizar Stock
		$consumos = &DDBB::ObtenerConsumos($sobre->id);
		foreach($consumos as $consumo){
			$stockRow = &DDBB::obtenerStock($consumo->id_grupo,$consumo->id_prod);
			$producto = &DDBB::obtenerProducto($consumo->id_prod);
			$numDosisPorUnidad = $producto->num_dosis_por_botella;

			// Tenemos $stockRow->cantidad y $stockRow->dosis unidades
			// Hay que restar $consumo->cantidad dosis
			// existen $numDosis dosis libres
			$unidades_gastadas = intval($consumo->cantidad / $numDosisPorUnidad);
			$dosis_gastadas = $consumo->cantidad % $numDosisPorUnidad;

			$diff = $stockRow->cantidad - $unidades_gastadas;
			$stockRow->cantidad = ($diff > 0)?$diff:0;

			if($dosis_gastadas > $stockRow->dosis)
			{
				if($stockRow->cantidad>0)
				{
					$stockRow->cantidad--;
					$stockRow->dosis = $stockRow->dosis + $numDosisPorUnidad - $dosis_gastadas;
				}
				else{
					// Consumen dosis donde no hay!!!!
				}
			}
			else
			{
				$stockRow->dosis = $stockRow->dosis - $dosis_gastadas;
			}

			$stockRow->fechamod = date('Y-m-d H:i:s');
			if (!$stockRow->store())
			{
				$logFile->writeLog("Error Procesando sobre: Puede que la informacion de stock sea inconsistente: Error:");
				$logFile->writeLog("Error: ".$row->getError());
				// No devolvemos nada, pero si falla aqui se pueden dar inconsistencias
			}
			unset($stockRow);
			unset($producto);
		}

		// 3.- Guardar sobre;
		$sobre->processdate = date('Y-m-d H:i:s');
		$sobre->state = _PROCESADO;
		if (!$sobre->store())
		{
			$logFile->writeLog("Error: ".$sobre->getError());
			return FALSE;
		}
		// Tenemos los datos grabados
		$logFile->writeLog("ProcesarSobre: Procesado OK!");
		return TRUE;
	}
}

/****************************
*
* FUNCIONES DE LIBRERIA
*
*****************************/

function ModuloNewSobre($act){
	global $task,$option,$Itemid,$logFile,$baseURL,$user,$database;

	$logFile->writeLog("accion: $act");
	switch($act)
	{
		case "new":
			$logFile->writeLog("new: $act");
			$idSobre = intval(mosGetParam( $_POST,'idSobre'));
			$idProducto = intval(mosGetParam( $_POST,'idProducto'));
			$numDosis = intval(mosGetParam( $_POST,'numDosis'));

			if(!$idSobre || !$idProducto || !$numDosis){
				EnvioBackError(_PARAMETERS_INVALID);
				break;
			}
			$newTask = $task."&accion=newSobre&id_socio={$user->id}";
			$sobre = DDBB::obtenerSobre($idSobre);
			if($sobre === null){
				EnvioError(_DATABASE_ERROR,$newTask);
				break;
			}

			if($sobre->state != _NOT_CLOSED){
				EnvioError(_PARAMETERS_INVALID,$newTask);
				break;
			}

			$producto = &DDBB::obtenerProducto($idProducto);
			if($producto === null)
			{
				$message = "Product $idProducto not found";
				EnvioError($message,$newTask);
				break;
			}

			$newConsumo = new mosArtzaraConsumos( $database);
			$newConsumo->id = 0;
			$newConsumo->id_sobre = $sobre->id;
			$newConsumo->id_grupo = $producto->id_grupo;
			$newConsumo->id_prod = $producto->id;
			$newConsumo->cantidad = $numDosis;
			$newConsumo->total = intval(($numDosis * $producto->venta_publico_dosis)*100)/100;
			$newConsumo->inputdate = date('Y-m-d H:i:s');

			if (!$newConsumo->store())
			{
				EnvioError("Error: ".$newConsumo->getError(),$newTask);
				break;
			}

			$logFile->writeLog("nuevo consumo: idSobre=$idSobre familia={$producto->id_grupo} idProducto=$idProducto numUnidades=$numDosis");


			$consumosArrayInfo = &GenericLib::ObtenerListadoConsumos($sobre->id);

			if($consumosArrayInfo === null){
				EnvioError(_DATABASE_ERROR,$newTask);
				break;
			}

			$total = 0;
			foreach($consumosArrayInfo as $consumo){
				$total += $consumo->importe;
			}

			$familias = &DDBB::obtenerFamilias(1);

			if($familias === null){
				EnvioError(_DATABASE_ERROR,$newTask);
				break;
			}
			$productos = &DDBB::obtenerProductos($producto->id_grupo,1);
			if($productos === NULL)
			{
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			PantallaNewSobre($sobre,$consumosArrayInfo,$total,$familias,$producto->id_grupo,$productos);
			break;
		case "cancel":
			$logFile->writeLog("cancel: elimnar entradas y sobre");
			$idSobre = intval(mosGetParam( $_POST,'idSobre'));
			$sobre = DDBB::obtenerSobre($idSobre);
			if($sobre === null){
				$message = "No se puede cancelar este sobre:apuntad el sobreID: $idSobre y avisad al administrador";
				EnvioResultOK($message,$task);
				break;
			}
			// hay que asegurarse de que esta en not closed!!
			if($sobre->state != _NOT_CLOSED){
				$message = "No se puede cancelar este sobre:apuntad el sobreID: $idSobre y avisad al administrador";
				EnvioError($message,$task);
				break;
			}

			DDBB::EliminarConsumosFromSobre($idSobre);

			DDBB::EliminarSobre($idSobre);
			mosRedirect($baseURL."&e=e");
			break;
		case "cerrarsobre":

			$idSobre = intval(mosGetParam( $_REQUEST,'idSobre'));
			$logFile->writeLog("cerrarsobre: finalizar sobre: salida:$idSalida");

			$sobre = DDBB::obtenerSobre($idSobre);
			if($sobre === null){
				$message = "No se puede cerrar este sobre:apuntad el sobreID: $idSobre y avisad al administrador";
				EnvioError($message,'140');
				break;
			}
			if($sobre->state != _NOT_CLOSED){
				$message = "No se puede cerrar este sobre:apuntad el sobreID: $idSobre y avisad al administrador";
				EnvioError($message,'140');
				break;
			}

			$consumos = &GenericLib::ObtenerListadoConsumos($sobre->id);
			if($consumos === NULL){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			$total = 0;
			foreach($consumos as $consumo){
				$total += $consumo->importe;
			}
			unset($consumos);

			// Terminar
			$sobre->pago = 0;
			$sobre->id_salida = 0;
			$sobre->inputdate = date('Y-m-d H:i:s');
			$sobre->total = $total;
			$descuadre = -$sobre->total;

			if(!GenericLib::ProcesarSobre($sobre,$descuadre))
			{
				EnvioError("NO SE HA PODIDO PROCESAR ESTE SOBRE. INTENTELO DE NUEVO",$task);
				break;
			}
			mosRedirect($baseURL."&task=$task");
			break;
		case "remove":
			//Borrar entrada(s)
			$logFile->writeLog("remove: borrar consumiciones");
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
				if(!DDBB::EliminarConsumo($arrayIds[$i]))
					$result = FALSE;
			}

			$idSobre = intval(mosGetParam( $_POST,'idSobre'));
			$sobre = DDBB::obtenerSobre($idSobre);
			if($sobre === null){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			if($sobre->state != _NOT_CLOSED){
				EnvioError(_PARAMETERS_INVALID,$task);
				break;
			}
			$familias = &DDBB::obtenerFamilias(1);
			if($familias === null){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			$consumosArrayInfo = &GenericLib::ObtenerListadoConsumos($sobre->id);
			if($consumosArrayInfo === null){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			$total = 0;
			foreach($consumosArrayInfo as $consumo){
				$total += $consumo->importe;
			}
			PantallaNewSobre($sobre,$consumosArrayInfo,$total,$familias);
			break;
		case "familiaseleccionada":
			$logFile->writeLog("familiaseleccionada");
			$idFamilia = intval(mosGetParam( $_GET,'idfamilia'));
			if(!$idFamilia)
			{
				EnvioError('Familia param invalid',$task);
				break;
			}

			$idSobre = intval(mosGetParam( $_GET,'idSobre'));
			$logFile->writeLog("sobre numero : $idSobre");
			$sobre = DDBB::obtenerSobre($idSobre);
			if($sobre === null){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			if($sobre->state != _NOT_CLOSED){
				EnvioError("Sobre no aceptado.",$task);
				break;
			}
			$familias = &DDBB::obtenerFamilias(1);
			if($familias === null){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			$consumosArrayInfo = &GenericLib::ObtenerListadoConsumos($sobre->id);
			if($consumosArrayInfo === null){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			$total = 0;
			foreach($consumosArrayInfo as $consumo){
				$total += $consumo->importe;
			}
			$productos = &DDBB::obtenerProductos($idFamilia,1);
			if($productos === NULL)
			{
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			PantallaNewSobre($sobre,$consumosArrayInfo,$total,$familias,$idFamilia,$productos);
			break;
		case "productoseleccionado":
			$logFile->writeLog("productoseleccionado");
			$idFamilia = intval(mosGetParam( $_GET,'idfamilia'));
			$idProducto = intval(mosGetParam( $_GET,'idproducto'));
			$logFile->writeLog("productoseleccionado: Familia:$idFamilia Producto:$idProducto");

			if(!$idFamilia || !$idProducto)
			{
				$message = 'Familia or Product param invalid';
				EnvioError($message,$task);
				break;
			}

			$idSobre = intval(mosGetParam( $_GET,'idSobre'));
			$sobre = DDBB::obtenerSobre($idSobre);
			if($sobre === null){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			if($sobre->state != _NOT_CLOSED){
				EnvioError(_PARAMETERS_INVALID,$task);
				break;
			}
			$familias = &DDBB::obtenerFamilias(1);
			if($familias === null){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			$consumosArrayInfo = &GenericLib::ObtenerListadoConsumos($sobre->id);
			if($consumosArrayInfo === null){
				EnvioError(_DATABASE_ERROR,$task);
				break;
			}
			$total = 0;
			foreach($consumosArrayInfo as $consumo){
				$total += $consumo->importe;
			}
			$productos = &DDBB::obtenerProductos($idFamilia,1);
			if($productos === NULL)
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
			PantallaNewSobre($sobre,$consumosArrayInfo,$total,$familias,$idFamilia,$productos,$producto);
			break;
		default:
			// Obtener datos
			$logFile->writeLog("ModuloNewSobre: default");
			$idSobre = intval(mosGetParam( $_REQUEST,'idSobre',0));

			if(!$idSobre){
				//Nuevo Sobre
				$logFile->writeLog("Nuevo Sobre!!!");
				$id_socio = intval(mosGetParam( $_REQUEST,'id_socio'));
				if(DDBB::obtenerSocio($id_socio) === null){
					EnvioError("Socio no valido","000");
					break;
				}

				// Creamos el sobre
				$sobre = new mosArtzaraSobres( $database );
				$sobre->id = 0;
				$sobre->id_socio = $id_socio;
				$sobre->total = 0;
				$sobre->id_salida = 0;
				$sobre->state = _NOT_CLOSED;
				if (!$sobre->store())
				{
					EnvioError("Error: ".$sobre->getError(),'000');
					break;
				}
				$logFile->writeLog("newSobre: id={$sobre->id}");
			}
			else{
				$sobre = DDBB::obtenerSobre($idSobre);
				if($sobre === null){
					EnvioError(_DATABASE_ERROR,$task);
					break;
				}
				if($sobre->state != _NOT_CLOSED){
					EnvioError("Datos incorrectos. El sobre solicitado esta cerrado","000");
					break;
				}
			}
			$familias = &DDBB::obtenerFamilias(1);
			if($familias === null){
				EnvioError(_DATABASE_ERROR,"000");
				break;
			}
			$consumosArrayInfo = &GenericLib::ObtenerListadoConsumos($sobre->id);
			if($consumosArrayInfo === null){
				EnvioError(_DATABASE_ERROR,"000");
				break;
			}
			$total = 0;
			foreach($consumosArrayInfo as $consumo){
				$total += $consumo->importe;
			}
			PantallaNewSobre($sobre,$consumosArrayInfo,$total,$familias);
			break;
	}
}

function PrintSobre(){
	global $task,$option,$Itemid,$logFile,$baseURL,$user,$database;

	$idSobre = intval(mosGetParam( $_REQUEST,'idSobre'));

	$sobre = DDBB::obtenerSobre($idSobre);
	if($sobre === null){
		$message = "No se puede imprimir el sobre. Vuelva a hacerlo.";
		EnvioError($message,'000');
		break;
	}
	if($sobre->state != _NO_PROCESADO){
		$message = "La operacion que se desea realizar sobre este sobre no esta autorizadar";
		EnvioError($message,'000');
		break;
	}
	$logFile->writeLog("Imprimimos sobre:$idSobre");

	$salidas = &DDBB::obtenerSalidas();
	if($salidas === NULL){
		EnvioError(_DATABASE_ERROR,$task);
		break;
	}
	$socio = &DDBB::obtenerSocio($sobre->id_socio);
	if($socio === null)
	{
		EnvioError("Socio no valido","000");
		break;
	}
	PrintSobreHtml($sobre,$salidas,$sobre->total,$socio);
}
?>
