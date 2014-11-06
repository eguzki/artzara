<?php
/**
* @version $Id: artzaraadmin.html.php
* @package Mambo_4.5.1
* @copyright (C) 2000 - 2004 Miro International Pty Ltd
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
?>

<!-- libreria de javascript -->
<script language="JavaScript1.2" src="<?php echo $artzaraadminurl;?>js/bodegajavascript.js" type="text/javascript"></script>

<?php

/**
* @package Mambo_4.5.1
*/

	function BodegaPantallaPrincipal()
	{
		global $option,$Itemid,$user;
	$link = "index.php?option=$option&Itemid=$Itemid"

?>
	<table width="100%" class="cpanel">
		<tr>
			<td align="center" width="25%">
			<a href="<?php echo $link; ?>&task=111" style="text-decoration:none;">
			<img src="administrator/images/messaging.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>REFERENCIAS</h2>
			</a>
			</td>

			<td align="center" width="25%">
			<a href="<?php echo $link; ?>&task=112" style="text-decoration:none;">
			<img src="administrator/images/support.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>ENTRADAS</h2>
			</a>
			</td>

			<td align="center" width="25%">
			<a href="<?php echo $link; ?>&task=113" style="text-decoration:none;">
			<img src="administrator/images/searchtext.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>CONSUMOS</h2>
			</a>
			</td>
		</tr>
		<tr>
			<td align="center" width="25%">
			<a href="<?php echo $link; ?>&task=114" style="text-decoration:none;">
			<img src="administrator/images/install.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>STOCK</h2>
			</a>
			</td>

			<td align="center" width="25%">
			<a href="<?php echo $link; ?>&task=115" style="text-decoration:none;">
			<img src="administrator/images/inbox.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>INVENTARIO</h2>
			</a>
			</td>

			<td align="center" width="25%">
			<a href="<?php echo $link; ?>&task=000" style="text-decoration:none;">
			<img src="administrator/images/cancel_f2.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>MENU PRINCIPAL</h2>
			</a>
			</td>
	</tr>
	</table>
<?php
	}

	function NavBodega(){
		global $option,$Itemid;
?>
		<table border='0' cellpadding='5' cellspacing='5' width='100%'>
		<tr>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid&task=111"; ?>" class='buttonbar'>Referencias</a>
			</td>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid&task=112"; ?>" class='buttonbar'>Entradas</a>
			</td>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid&task=114"; ?>" class='buttonbar'>Stock</a>
			</td>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid&task=115"; ?>" class='buttonbar'>Inventario</a>
			</td>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid&task=113"; ?>" class='buttonbar'>Consumos</a>
			</td>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid"; ?>" class='buttonbar'>Inicio</a>
			</td>
		</tr>
		</table>
<?php
	}

	function PantallaReferenciasHTML($tipo,&$familias,$familia=NULL,$productos=NULL,$producto=NULL)
	{
		global $option,$Itemid,$mosConfig_live_site, $imagenX, $numColumnas, $baseURL, $logFile;

		/////////////////////
		//
		//if($familia==NULL && $productos==NULL && $producto==NULL)
		//{
		//tipo 0 -> MAIN
		//tipo 1 -> nueva familia  000 = 0
		//tipo 2 -> nuevo producto
		//}
		//elseif($familia != NULL && $productos!= NULL && $producto == NULL)
		//{
		//tipo 1 -> grupo seleccionado 110 = 6
		// Aqui el parametro $familia tiene todos los datos, es un objeto de la clase familia
		//}
		//elseif($familia != NULL && $productos!= NULL && $producto != NULL)
		//{
		//tipo 1 -> producto seleccionado 111 = 7
		// Aqui familia solo representa el ID
		//}
		/////////////////////

		$a = ($familia===NULL)?(0):(1);
		$in = $a<<1;
		$a = ($productos===NULL)?(0):(1);
		$in = $in + $a;
		$in = $in<<1;
		$a = ($producto===NULL)?(0):(1);
		$in = $in + $a;

		if($in != 0 && $in != 6 && $in != 7)
		{
			$message = "Parameters not valid.";
			EnvioError($message,"111");
			return;
		}

		if($in == 0 && $tipo == 0)
			$modoPantalla = "Pantalla Principal";
		elseif($in == 0 && $tipo == 1)
			$modoPantalla = "Nueva Familia";
		elseif($in == 0 && $tipo == 2)
			$modoPantalla = "Nuevo Producto";
		elseif($in == 6 && $tipo == 1)
			$modoPantalla = "Grupo seleccionado";
		elseif($in == 7 && $tipo == 1)
			$modoPantalla = "Producto seleccionado";
		else
			$modoPantalla = "Accion desconocida";

		$logFile->writeLog("HTML_PantallaReferencias: $modoPantalla");

?>
		<table width="100%" border="1">
		<tr valign="top">
			<td width="50%">
				<table width="100%" border="1">
				<tr valign="top">
					<td>
<?php
						if($in == 6)
							mostrarFamilias("familiaseleccionada",$numColumnas,$familias,$familia->id);
						elseif($in == 7)
							mostrarFamilias("familiaseleccionada",$numColumnas,$familias,$familia);
						else
							mostrarFamilias("familiaseleccionada",$numColumnas,$familias);
?>
					</td>
				</tr>
    <tr>
					<td>
					<!-- Boton nuevo grupo -->
<?php
					echo "<a href=\"".$baseURL."&task=111&accion=nuevafamilia\">";
					echo "Nueva Familia";
					echo "</a>";
?>
					</td>
				</tr>
				<tr>
					<td>
					<!-- Productos -->
<?php
					if($in == 6)
						mostrarProductos("productoseleccionado",$numColumnas,$familia->id,$productos);
					elseif($in == 7)
						mostrarProductos("productoseleccionado",$numColumnas,$familia,$productos,$producto->id);
					else
						echo "PULSE UNA FAMILIA PARA VER LOS PRODUCTOS";
?>
					</td>
				</tr>
				<tr>
					<td>
					<!-- Boton nuevo producto -->
<?php
					echo "<a href=\"".$baseURL."&task=111&accion=nuevoproducto\">";
					echo "Nuevo Producto";
					echo "</a>";
?>
					</td>
				</tr>
				</table>
			</td>
			<td width="50%">
				<!-- Formulario -->
<?php
				if($in == 6 || ($in == 0 && $tipo == 1))
					FormularioFamilia($familia);
				elseif($in == 7 || ($in == 0 && $tipo == 2))
					FormularioProducto($familias,$producto);
				else
					echo "<h2>PANTALLA DE REFERENCIAS</h2>\n<p>SELECCIONE UNA FAMILIA</p>";
?>
			</td>
		</tr>
		</table>
<?php
	}

	function EntradasMain()
	{
		global $option,$Itemid;
?>
		<input name="NuevaEntrada" type="button" onClick="setact('1')
		" value="NuevaEntrada"/>
		<input name="Listado" type="button" onClick="setact('2')
		" value="Listado"/>
		<form name="adminForm" method="post" action="index.php">
			<input type="hidden" name="task" value="112"/>
			<input type="hidden" name="accion" value=""/>
			<input type="hidden" name="option" value="<?php echo $option; ?>"/>
			<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
		</form>
<?php
	}

	function PantallaEntradasHTML($act,&$familias,$idFamilia=NULL,$productos=NULL,$idProducto=NULL,$entrada=NULL)
	{
		global $option,$Itemid,$mosConfig_live_site, $numColumnas, $baseURL, $logFile;
		$error = FALSE;

		switch($act)
		{
			case "1":
				if($familias === null)
					$error = TRUE;
				break;
			case "2":
				if($familias === null)
					$error = TRUE;
				break;
			case "3":
				if($familias === null || !$idFamilia || $productos === null)
					$error = TRUE;
				break;
			case "4":
				if($familias === null || !$idFamilia || $productos === null)
					$error = TRUE;
				break;
			case "5":
				if($familias === null || !$idFamilia || $productos === null || !$idProducto)
					$error = TRUE;
				break;
			case "6":
				if($familias === null || !$idFamilia || $productos === null || !$idProducto)
					$error = TRUE;
				break;
			case "10":
				if($familias === null || !$idFamilia || $productos === null || !$idProducto || $entrada === null)
					$error = TRUE;
				break;
			default:
				$error = TRUE;
				break;
		}

		if($error)
		{
			$message = "Parameters not valid.";
			EnvioError($message,"112");
			return;
		}

?>
		<table width="100%" border="1">
		<tr valign="top">
			<td width="50%">
				<table class="adminform" border="1">
				<tr>
					<th>FAMILIAS</th>
				</tr>
				<tr>
					<td>
<?php
						if($act == "1")
							mostrarFamilias("4",$numColumnas,$familias);
						elseif($act == "2")
							mostrarFamilias("3",$numColumnas,$familias);
						elseif($act == "3")
							mostrarFamilias("3",$numColumnas,$familias,$idFamilia);
						elseif($act == "4")
							mostrarFamilias("4",$numColumnas,$familias,$idFamilia);
						elseif($act == "5")
							mostrarFamilias("4",$numColumnas,$familias,$idFamilia);
						elseif($act == "6")
							mostrarFamilias("3",$numColumnas,$familias,$idFamilia);
						else
							mostrarFamilias("4",$numColumnas,$familias,$idFamilia);
?>
					</td>
				</tr>
				<tr>
					<th>PRODUCTOS</th>
				</tr>
				<tr>
					<td>
					<!-- Productos -->
<?php
					if($act == "5" || $act == "10")
						mostrarProductos("5",$numColumnas,$idFamilia,$productos,$idProducto);
					elseif($act == "6")
						mostrarProductos("6",$numColumnas,$idFamilia,$productos,$idProducto);
					elseif($act == "3")
						mostrarProductos("6",$numColumnas,$idFamilia,$productos);
					elseif($act == "4")
						mostrarProductos("5",$numColumnas,$idFamilia,$productos);
					else
						echo "PULSE UNA FAMILIA PARA VER LOS PRODUCTOS";
?>
					</td>
				</tr>
				</table>
			</td>
			<td width="50%">
				<!-- Formulario -->
<?php
				if($act == "1")
					echo "<h2>NUEVA ENTRADAS</h2>\n<p>SELECCIONE UNA FAMILIA</p>";
				elseif($act == "2")
					FormularioFiltro("7");
				elseif($act == "3")
					FormularioFiltro("7",$idFamilia);
				elseif($act == "4")
					echo "<h2>NUEVA ENTRADAS</h2>\n<p>SELECCIONE UN PRODUCTO</p>";
				elseif($act == "5")
					FormularioNuevaEntrada($idFamilia,$idProducto);
				elseif($act == "6")
					FormularioFiltro("7",$idFamilia,$idProducto);
				elseif($act == "10")
					FormularioNuevaEntrada($idFamilia,$idProducto,$entrada);
?>
			</td>
		</tr>
		</table>
<?php
	}

	function FormularioNuevaEntrada($idFamilia,$idProducto)
	{
		global $option,$Itemid,$artzaraadminurl,$logFile;

		$id = 0;
		$id_grupo = $idFamilia;
		$id_prod = $idProducto;
		$inputdate = date('Y-m-d H:i:s');
		$cantidad = 0;
?>
<form action="index.php" method="post" name="adminForm">
	<table width="100%">
	<tr>
		<td width="25%">
		Fecha
		</td>
		<td width="75%">
		<?php escribe_formulario_fecha_vacio("inputdate","formularioentrada",$inputdate); ?>
		</td>
	</tr>
	<tr>
		<td width="25%">
		Numero unidades
		</td>
		<td width="75%">
		<input type="text" readonly name="displayOut" value="<?php echo $cantidad; ?>"/>
		</td>
	</tr>
	<tr>
		<td align="center" colspan="2">
			<?php showKeypad(); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<input type="button" name="guardarBtn" value="Guardar" alt="Guardar" onclick="enviarFormularioNuevaEntrada('9')"/>
		<input type="button" name="cancelarBtn" value="Cancelar" alt="Cancelar" onclick="enviarFormularioNuevaEntrada('1')"/>
		</td>
	</tr>
	</table>
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="option" value="<?php echo $option; ?>"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
	<input type="hidden" name="task" value="112"/>
	<input type="hidden" name="accion" value=""/>
	<input type="hidden" name="id_grupo" value="<?php echo $id_grupo; ?>"/>
	<input type="hidden" name="id_prod" value="<?php echo $id_prod; ?>"/>
</form>
<?php
	}

	function PantallaStockHTML(&$familias,$idFamilia=NULL,$productos=NULL,$stock=NULL)
	{
		global $option,$Itemid,$mosConfig_live_site, $imagenX, $numColumnas, $baseURL, $logFile;

		/////////////////////
		//
		//if($familia==NULL && $productos==NULL && $producto==NULL)
		//{
		//	Principal  000 = 0
		//}
		//elseif($idFamilia != NULL && $productos!= NULL && $stock == NULL)
		//{
		//	Familia Seleccionada 110 = 6
		//}
		//elseif($idFamilia != NULL && $productos!= NULL && $stock != NULL)
		//{
		//	producto seleccionado 111 = 7
		//}
		/////////////////////

		$a = ($idFamilia===NULL)?(0):(1);
		$in = $a<<1;
		$a = ($productos===NULL)?(0):(1);
		$in = $in + $a;
		$in = $in<<1;
		$a = ($stock===NULL)?(0):(1);
		$in = $in + $a;

		if($in != 0 && $in != 6 && $in != 7)
		{
			$message = "Parameters not valid.";
			EnvioError($message,"114");
			return;
		}

		if($in == 0)
			$modoPantalla = "Pantalla Principal";
		elseif($in == 6)
			$modoPantalla = "Grupo seleccionado";
		elseif($in == 7)
			$modoPantalla = "Producto seleccionado";
		else
			$modoPantalla = "Accion desconocida";

		$logFile->writeLog("HTML_PantallaStock: $modoPantalla");

?>
		<table width="100%" class="adminform">
		<tr>
			<th colspan="2">
			STOCK
			</th>
		</tr>
		<tr valign="top">
			<td width="50%">
				<table width="100%" border="1">
				<tr>
					<td>
<?php
						if($in == 6 || $in == 7)
							mostrarFamilias("familiaseleccionada",$numColumnas,$familias,$idFamilia);
						else
							mostrarFamilias("familiaseleccionada",$numColumnas,$familias);
?>
					</td>
				</tr>
				<tr>
					<td>
					<!-- Productos -->
<?php
					if($in == 6)
						mostrarProductos("productoseleccionado",$numColumnas,$idFamilia,$productos);
					elseif($in == 7)
						mostrarProductos("productoseleccionado",$numColumnas,$idFamilia,$productos,$stock->id_prod);
					else
						echo "PULSE UNA FAMILIA PARA VER LOS PRODUCTOS";
?>
					</td>
				</tr>
				</table>
			</td>
			<td width="50%">
				<!-- Formulario -->
<?php
				if($in == 7)
					FormularioStock($stock,$imagenX);
				else
					echo "<h2>PANTALLA DE STOCK</h2>\n<p>SELECCIONE UN PRODUCTO</p>";
?>
			</td>
		</tr>
		</table>
<?php
	}

	function FormularioStock($stock,$imagenX)
	{
		global $task,$option,$Itemid;

		$fechaHoy = date('Y-m-d H:i:s');
		$fechaStock = $stock->fechamod;

?>
<form action="index.php" method="post" name="adminform">
	<table width="100%">
	<tr>
		<td width="50%">
		Fecha Actual:
		</td>
		<td width="50%">
			<?php echo $fechaHoy; ?>
		</td>
	</tr>
	<tr>
		<td width="50%">
		Ultima modificacion:
		</td>
		<td width="50%">
			<?php echo $fechaStock; ?>
		</td>
	</tr>
	<tr>
		<td width="50%">
		Num unidades:
		</td>
		<td width="50%">
			<?php echo $stock->cantidad; ?>
		</td>
	</tr>
	<tr>
		<td width="50%">
		Num dosis:
		</td>
		<td width="50%">
			<?php echo $stock->dosis; ?>
		</td>
	</tr>
	</table>
	<input type="hidden" name="option" value="<?php echo $option; ?>"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
	<input type="hidden" name="task" value="<?php echo $task; ?>"/>
	<input type="hidden" name="accion" value=""/>
</form>
<?php
	}

	function NuevoInventario(&$stock,&$producto){
		global $task,$option,$Itemid,$logFile;

		$fechaHoy = date('Y-m-d H:i:s');

?>
	<table class="adminheading">
			<tr>
				<td align="right">
<?php
				mosMenuBar::startTable();
				GenericLib::myIcono( 'reset', 'delete.png', 'delete_f2.png', 'Reset Stock', false );
				mosMenuBar::spacer(10);
				mosMenuBar::save('save','Aceptar');
				mosMenuBar::spacer(10);
				mosMenuBar::cancel('cancel');
				mosMenuBar::endTable();
?>
				</td>
			</tr>
	</table>
<br>
	<script language="JavaScript">

	function submitbutton(pressbutton) {
		var inventario = document.adminForm.displayOut.value;
		var enviar = false;

		if (pressbutton == 'cancel') {
			setact(pressbutton);
		}
		else if(pressbutton == 'reset'){
			if (confirm('Esta operacion reseteara los datos de stock sin generar inventario. Desea continuar?'))
				setact(pressbutton);
		}
		else
		{
			if(!validoEntero(inventario)){
				alert('Inventario invalido');
			}
			else{
				setact(pressbutton);
			}
		}
	}
	</script>

	<h2><?php echo $producto->nombre?></h2>
	<form action="index.php" method="post" name="adminForm">
	<table width="100%">
	<tr>
		<td width="20%">
		Fecha Actual:
		</td>
		<td width="80%">
		<?php echo $fechaHoy; ?>
		</td>
	</tr>
	<tr>
		<td width="20%">
		Ultima modificacion de Stock:
		</td>
		<td width="80%">
		<?php echo $stock->fechamod; ?>
		</td>
	</tr>
	<tr>
		<td width="20%">
		Stock actual:
		</td>
		<td width="80%">
		<?php echo "Numero Unidades:".$stock->cantidad." Numero de dosis:".$stock->dosis; ?>
		</td>
	</tr>
	<tr>
		<td width="20%">
		Inventario:
		</td>
		<td width="80%">
		<input type="text" readonly name="displayOut" class="inputbox" size="15" value="" />
			<SELECT NAME="dosis">
<?php
			for ($i = 0; $i < $producto->num_dosis_por_botella; $i++) {
				echo "<OPTION size=\"4\" VALUE=\"$i\">$i</OPTION>";
			}
?>
			</SELECT>
		</td>
	</tr>
	<tr>
		<td width="20%">
		</td>
		<td width="80%">
			<?php showKeypad(); ?>
		</td>
	</tr>
	</table>
	<input type="hidden" name="id_grupo" value="<?php echo $stock->id_grupo; ?>"/>
	<input type="hidden" name="id_prod" value="<?php echo $stock->id_prod; ?>"/>
	<input type="hidden" name="option" value="<?php echo $option; ?>"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
	<input type="hidden" name="task" value="<?php echo $task; ?>"/>
	<input type="hidden" name="accion" value=""/>
</form>

<?php
	}

	function ListadoInventarioHTML(&$datos,&$nombres,&$pageNav,&$filtroInfo,&$familias,$idFamilia=0,$productos=NULL,$idProducto=0){
		global $task,$option,$Itemid,$mosConfig_live_site, $imagenX, $numColumnas, $baseURL, $logFile;

		$a = (!$idFamilia)?(0):(1);
		$in = $a<<1;
		$a = ($productos===NULL)?(0):(1);
		$in = $in + $a;
		$in = $in<<1;
		$a = (!$idProducto)?(0):(1);
		$in = $in + $a;

		$logFile->writeLog("ListadoInventarioHTML: $in");
?>
		<table class="adminheading">
	<tr>
		<td align="right">
<?php
		mosMenuBar::startTable();
		GenericLib::myIcono( '', 'publish.png', 'publish_f2.png', 'Nuevo Listado', false );
		mosMenuBar::spacer("10");
		if($idProducto){
			GenericLib::myIcono( 'new', 'new.png', 'new_f2.png', 'Hacer Inventario', false );
			mosMenuBar::spacer("10");
		}
		GenericLib::myIcono( 'remove', 'delete.png', 'delete_f2.png', 'Eliminar entrada inventario', true );
		mosMenuBar::endTable();
?>
		</td>
	</tr>
	</table>
<br />
<form action="index.php" method="post" name="adminForm">
		<table width="100%" border="1">
		<tr valign="top">
			<td width="50%">
				<table width="100%" border="1">
				<tr>
					<td>
<?php
							mostrarFamilias('0',$numColumnas,$familias,$idFamilia);
?>
					</td>
				</tr>
				<tr>
					<td>
					<!-- Productos -->
<?php
					if($in == 6)
						mostrarProductos("0",$numColumnas,$idFamilia,$productos);
					elseif($in == 7)
						mostrarProductos("0",$numColumnas,$idFamilia,$productos,$idProducto);
					else
						echo "PULSE UNA FAMILIA PARA VER LOS PRODUCTOS";
?>
					</td>
				</tr>
				</table>
			</td>
			<td width="50%">
				<!-- Formulario -->
<?php
    FormularioFiltroInventario($idFamilia,$idProducto);
?>
			</td>
		</tr>
		</table>
<?php
				BloqueListaInventario($datos,$nombres,$pageNav,$filtroInfo);
?>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="task" value="<?php echo $task; ?>"/>
		<input type="hidden" name="accion" value=""/>
		<input type="hidden" name="idfamilia" value="<?php echo $idFamilia; ?>"/>
		<input type="hidden" name="idproducto" value="<?php echo $idProducto; ?>"/>
		</form>
<?php
	}

	function PantallaInventarioHTML(&$familias,$idFamilia=NULL,$productos=NULL,$stock=NULL){
		global $option,$Itemid,$mosConfig_live_site, $imagenX, $numColumnas, $baseURL, $logFile;

		/////////////////////
		//
		//if($familia==NULL && $productos==NULL && $producto==NULL)
		//{
		//	Principal  000 = 0
		//}
		//elseif($idFamilia != NULL && $productos!= NULL && $stock == NULL)
		//{
		//	Familia Seleccionada 110 = 6
		//}
		//elseif($idFamilia != NULL && $productos!= NULL && $stock != NULL)
		//{
		//	producto seleccionado 111 = 7
		//}
		/////////////////////

		$a = ($idFamilia===NULL)?(0):(1);
		$in = $a<<1;
		$a = ($productos===NULL)?(0):(1);
		$in = $in + $a;
		$in = $in<<1;
		$a = ($stock===NULL)?(0):(1);
		$in = $in + $a;

		$logFile->writeLog("in: $in");

		if($in != 0 && $in != 6 && $in != 7)
		{
			$message = _PARAMETERS_INVALID;
			EnvioError($message,"115");
			return;
		}

		if($in == 0)
			$modoPantalla = "Pantalla Principal";
		elseif($in == 6)
			$modoPantalla = "Grupo seleccionado";
		elseif($in == 7)
			$modoPantalla = "Producto seleccionado";
		else
			$modoPantalla = "Accion desconocida";

		$logFile->writeLog("PantallaInventarioHTML: $modoPantalla");

?>
		<table width="100%" border="1">
		<tr valign="top">
			<td width="50%">
				<table width="100%" border="1">
				<tr>
					<td>
<?php
						if($in == 6 || $in == 7)
							mostrarFamilias("1",$numColumnas,$familias,$idFamilia);
						else
							mostrarFamilias("1",$numColumnas,$familias);
?>
					</td>
				</tr>
				<tr>
					<td>
					<!-- Productos -->
<?php
					if($in == 6)
						mostrarProductos("2",$numColumnas,$idFamilia,$productos);
					elseif($in == 7)
						mostrarProductos("2",$numColumnas,$idFamilia,$productos,$stock->id_prod);
					else
						echo "PULSE UNA FAMILIA PARA VER LOS PRODUCTOS";
?>
					</td>
				</tr>
				</table>
			</td>
			<td width="50%">
				<!-- Formulario -->
<?php
				if($in == 7)
					FormularioInventario($stock);
				elseif($in == 6)
					echo "<h2>PANTALLA DE INVENTARIO</h2>\n<p>SELECCIONE UN PRODUCTO</p>";
				else
					echo "<h2>PANTALLA DE INVENTARIO</h2>\n<p>SELECCIONE UNA FAMILIA</p>";

?>
			</td>
		</tr>
		</table>
<?php
	}

	function FormularioInventario($stock)
	{
		global $option,$Itemid,$imagenX;

		$fechaHoy = date('Y-m-d H:i:s');
		$fechaStock = $stock->fechamod;

$cadena = <<<FIN
<form action="index.php" method="post" name="formularioInventario">
	<table width="100%">
	<tr>
		<td width="50%">
		Fecha Actual:
		</td>
		<td width="50%">
		$fechaHoy
		</td>
	</tr>
	<tr>
		<td width="50%">
		Ultima modificacion de Stock:
		</td>
		<td width="50%">
		$fechaStock
		</td>
	</tr>
	<tr>
		<td width="50%">
		Cantidad Stock:
		</td>
		<td width="50%">
		<input type="text" name="stock" readonly value="{$stock->cantidad}"/>
		</td>
	</tr>
	<tr>
		<td width="50%">
		Inventario:
		</td>
		<td width="50%">
		<input type="text" name="cantidad" value="0"/>
		</td>
	</tr>
	<tr>
		<td width="50%" colspan="2">
			<input type="button" name="enviarBtn" value="Actualizar" alt="Actualizar" onclick="enviarFormularioInventario('formulario')"/>
			<input type="button" name="cancelarBtn" value="Cancelar" alt="Cancelar" onclick="enviarFormularioInventario('cancelar')"/>
		</td>
	</tr>
	</table>
	<input type="hidden" name="id" value="0"/>
	<input type="hidden" name="id_grupo" value="{$stock->id_grupo}"/>
	<input type="hidden" name="id_prod" value="{$stock->id_prod}"/>
	<input type="hidden" name="inputdate" value="$fechaHoy"/>
	<input type="hidden" name="option" value="$option"/>
	<input type="hidden" name="Itemid" value="$Itemid"/>
	<input type="hidden" name="task" value="115"/>
	<input type="hidden" name="accion" value=""/>
</form>
FIN;

	echo $cadena;
	}


	function FormularioProducto(&$familias,$producto)
	{
		global $option,$itemId,$artzaraadminurl,$imagenX;

		$cid = 0;
		if($producto)
		{
			$id_grupo = $producto->id_grupo;
			$ref = $producto->ref;
			$nombre = $producto->nombre;
			$imagefile = $artzaraadminurl."images/".$producto->imagefile;
			$published = $producto->published;
			$precio_compra = $producto->precio_compra;
			$iva = $producto->iva;
			$venta_publico_dosis = $producto->venta_publico_dosis;
			$num_dosis_por_botella = $producto->num_dosis_por_botella;
			$recargo_dosis = $producto->recargo_dosis;
			$proveedor = $producto->proveedor;
			$telefono_contacto = $producto->telefono_contacto;
			$email = $producto->email;
			$cid = $producto->id;
			$botonEliminar = "<input type=\"button\" name=\"eliminarBtn\" value=\"Eliminar\" alt=\"Eliminar\" onclick=\"enviarFormularioProducto('eliminar')\"/>";
		}
		else
		{
			$id_grupo = 0;
			$ref = "";
			$nombre = "";
			$imagefile = $imagenX;
			$published = "";
			$precio_compra = "";
			$iva = "";
			$venta_publico_dosis = "";
			$recargo_dosis = "";
			$num_dosis_por_botella = "";
			$proveedor = "";
			$telefono_contacto = "";
			$email = "";
			$botonEliminar ="";
		}
		$checked = ($published)?("checked"):("");
		$select_input="";
		foreach($familias as $familia)
		{
			$select_input.= "<option value=\"".$familia->id."\"";
			if($familia->id == $id_grupo)
				$select_input.= " selected";
			$select_input.= ">";
			$select_input.= $familia->nombre;
			$select_input.= "</option>\n";
		}
?>
<form action="index.php" method="post" enctype="multipart/form-data" name="formularioProducto">
	<table width="100%">
	<tr>
		<td width="50%">
		Referencia
		</td>
		<td width="50%">
		<input type="text" name="ref" value="<?php echo $ref; ?>"/>
		</td>
	</tr>
	<tr>
		<td width="50%">
		Nombre
		</td>
		<td width="50%">
		<input type="text" name="nombre" value="<?php echo $nombre; ?>"/>
		</td>
	</tr>
	<tr>
		<td width="50%">
		Grupo
		</td>
		<td width="50%">
			<select name="id_grupo">
			<?php echo $select_input; ?>
			</select>
		</td>
	</tr>
	<tr>
		<td width="50%">
		Activo
		</td>
		<td width="50%">
		<input type="checkbox" name="published" value ="1" <?php echo $checked; ?>/>
		</td>
	</tr>
	<tr>
		<td width="50%">
		Fichero Imagen
		</td>
		<td width="50%">
		Si no modificas nada, la imagen se mantiene.
		<input type="file" name="nombre_imagen" accept="image/gif,image/jpeg"/>
		</td>
	</tr>
	<tr>
		<td width="50%">
		Imagen
		</td>
		<td width="50%">
		<img src="<?php echo $imagefile; ?>" height="100" width="100"/>
		</td>
	</tr>
	<tr>
		<td colspan="2"><br />
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
			<tr>
				<th colspan="2">
					Datos contables
				</th>
			</tr>
			<tr>
				<td colspan="2">
					Usar Puntos para fracciones. 5,6 MAL 5.6 BIEN
				</td>
			</tr>
			<tr>
				<td width="50%">
				Precio compra
				</td>
				<td width="50%">
				<input type="text" name="precio_compra" onChange="calculoPrecios(this)" value="<?php echo $precio_compra; ?>"/>
				</td>
			</tr>
			<tr>
				<td width="50%">
				I.V.A.
				</td>
				<td width="50%">
				<input type="text" name="iva" onChange="calculoPrecios(this)" value="<?php echo $iva; ?>"/>% de 0 a 1
				</td>
			</tr>
			<tr>
				<td width="50%">
				Dosis por Botella
				</td>
				<td width="50%">
				<input type="text" name="num_dosis_por_botella" onChange="calculoPrecios(this)" value="<?php echo $num_dosis_por_botella; ?>"/>
				</td>
			</tr>
			<tr>
				<td width="50%">
				Precio Dosis
				</td>
				<td width="50%">
				<input type="text" name="venta_publico_dosis" onChange="calculoPrecios(this)" value="<?php echo $venta_publico_dosis; ?>"/>
				</td>
			</tr>
			<tr>
				<td width="50%">
				Recargo Dosis
				</td>
				<td width="50%">
				<input type="text" name="recargo_dosis" onChange="calculoPrecios(this)" value="<?php echo $recargo_dosis; ?>"/>% de 0 a 1
				</td>
			</tr>
			</table>
		<td>
	</tr>
	<tr>
		<td colspan="2"><br />
		</td>
	</tr>
	<tr>
		<td width="50%">
		Proveedor
		</td>
		<td width="50%">
		<input type="text" name="proveedor" value="<?php echo $proveedor; ?>"/>
		</td>
	</tr>
	<tr>
		<td width="50%">
		Telefono
		</td>
		<td width="50%">
		<input type="text" name="telefono_contacto" value="<?php echo $telefono_contacto; ?>"/>
		</td>
	</tr>
	<tr>
		<td width="50%">
		Email
		</td>
		<td width="50%">
		<input type="text" name="email" value="<?php echo $email; ?>"/>
		</td>
	</tr>
	<tr>
		<td width="50%" colspan="2">
		<input type="button" name="guardarBtn" value="Guardar" alt="Guardar" onclick="enviarFormularioProducto('guardar')"/>
		<?php echo $botonEliminar; ?>
		<input type="button" name="cancelarBtn" value="Cancelar" alt="Cancelar" onclick="enviarFormularioProducto('cancelar')"/>
		</td>
	</tr>
	</table>
	<input type="hidden" name="id" value="<?php echo $cid; ?>"/>
	<input type="hidden" name="option" value="<?php echo $option; ?>"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
	<input type="hidden" name="task" value="111"/>
	<input type="hidden" name="accion" value="formularioproducto"/>
	<input type="hidden" name="proceso" value=""/>
</form>
<?php
	}

	function FormularioFamilia($familia)
	{
		global $option,$Itemid,$artzaraadminpath, $artzaraadminurl,$imagenX;

		$cid = 0;
		if($familia)
		{
			$nombre = $familia->nombre;
			$activo = $familia->published;
			$imageFile = $artzaraadminurl."images/".$familia->imagefile;
			$cid = $familia->id;
			$botonEliminar = "<input type=\"button\" name=\"eliminarBtn\" value=\"Eliminar\" alt=\"Eliminar\" onclick=\"enviarFormularioProducto('eliminar')\"/>";
		}
		else
		{
			$nombre = "";
			$activo = 0;
			$imageFile = $imagenX;
			$botonEliminar = "";
		}
		$checked = ($activo)?("checked"):("");
$cadena = <<<FIN
<form action="index.php" method="post" enctype="multipart/form-data" name="formularioFamilia">
	<table width="100%">
	<tr>
		<td width="50%">
		Nombre Familia
		</td>
		<td width="50%">
		<input type="text" name="nombre" size="20" value="$nombre"/>
		</td>
	</tr>
	<tr>
		<td width="50%">
		Activo
		</td>
		<td width="50%">
		<input type="checkbox" name="published" value ="1" $checked/>
		</td>
	</tr>
	<tr>
		<td width="50%">
		Fichero Imagen
		</td>
		<td width="50%">
		<input type="file" name="nombre_imagen" accept="image/gif,image/jpeg"/>
		</td>
	</tr>
	<tr>
		<td width="50%">
		Imagen
		</td>
		<td width="50%">
		<img src="$imageFile" height="100" width="100"/>
		</td>
	</tr>
	<tr>
		<td width="50%" colspan="2">
			<input type="button" name="enviarBtn" value="Guardar" alt="Guardar" onclick="enviarFormularioFamilia('guardar')"/>
			$botonEliminar
			<input type="button" name="cancelarBtn" value="Cancelar" alt="Cancelar" onclick="enviarFormularioFamilia('cancelar')"/>
		</td>
	</tr>
	</table>
	<input type="hidden" name="id" value="$cid"/>
	<input type="hidden" name="option" value="$option"/>
	<input type="hidden" name="Itemid" value="$Itemid"/>
	<input type="hidden" name="task" value="111"/>
	<input type="hidden" name="accion" value="formulariofamilia"/>
	<input type="hidden" name="proceso" value=""/>
</form>
FIN;

	echo $cadena;
	}

	function PantallaConsumosHTML(&$datos,&$nombres,&$pageNav,&$filtroInfo,&$familias,$idFamilia=0,$productos=NULL,$idProducto=0,$totalProducto=-1,$totalDosis=-1){
		global $task,$option,$Itemid,$mosConfig_live_site, $numColumnas, $baseURL, $logFile;

		$a = (!$idFamilia)?(0):(1);
		$in = $a<<1;
		$a = ($productos===NULL)?(0):(1);
		$in = $in + $a;
		$in = $in<<1;
		$a = (!$idProducto)?(0):(1);
		$in = $in + $a;

		$logFile->writeLog("PantallaConsumosHTML: $in");
?>
		<table class="adminheading">
		<tr>
			<td align="right">
<?php
			mosMenuBar::startTable();
			GenericLib::myIcono( '', 'publish.png', 'publish_f2.png', 'Nuevo Listado', false );
			mosMenuBar::endTable();
?>
			</td>
		</tr>
	</table>
<br />
<form action="index.php" method="post" name="adminForm">
		<table width="100%" border="1">
		<tr valign="top">
			<td width="50%">
				<table width="100%" border="1">
				<tr>
					<td>
<?php
						mostrarFamilias('0',$numColumnas,$familias,$idFamilia);
?>
					</td>
				</tr>
				<tr>
					<td>
					<!-- Productos -->
<?php
					if($in == 6)
						mostrarProductos("0",$numColumnas,$idFamilia,$productos);
					elseif($in == 7)
						mostrarProductos("0",$numColumnas,$idFamilia,$productos,$idProducto);
					else
						echo "PULSE UNA FAMILIA PARA VER LOS PRODUCTOS";
?>
					</td>
				</tr>
				</table>
			</td>
			<td width="50%">
				<!-- Formulario -->
<?php
    			FormularioFiltroInventario($idFamilia,$idProducto);
?>
			</td>
		</tr>
		</table>
<?php
    			BloqueListaConsumos($datos,$nombres,$pageNav,$filtroInfo,$totalProducto,$totalDosis);
?>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="task" value="<?php echo $task; ?>"/>
		<input type="hidden" name="accion" value=""/>
		<input type="hidden" name="idfamilia" value="<?php echo $idFamilia; ?>"/>
		<input type="hidden" name="idproducto" value="<?php echo $idProducto; ?>"/>
		</form>
<?php
	}

	function FormularioFiltroInventario($idFamilia=0,$idProducto=0)
	{
		global $task,$option,$Itemid;
?>
	<table width="100%">
	<tr>
		<td width="50%">
		FechaDesde:
		</td>
		<td width="50%">
			<?php escribe_formulario_fecha_vacio("fechadesde","adminForm"); ?>
		</td>
	</tr>
	<tr>
		<td width="50%">
		FechaHasta:
		</td>
		<td width="50%">
			<?php escribe_formulario_fecha_vacio("fechahasta","adminForm"); ?>
		</td>
	</tr>
	</table>
<?php
	}

	function FormularioFiltro($accionFiltro,$idFamilia=0,$idProducto=0)
	{
		global $task,$option,$Itemid;

		$familiaSeleccionada = "";
		$productoSeleccionado = "";
		if($idFamilia)
			$familiaSeleccionada = "Se ha seleccionado la familia $idFamilia";
		if($idProducto)
			$productoSeleccionado = "Se ha seleccionado el producto $idProducto";
?>
<form action="index.php" method="post" name="adminForm">
	<table width="100%">
	<tr>
		<td width="50%">
			<?php echo $familiaSeleccionada; ?>
		</td>
		<td width="50%">
			<?php echo $productoSeleccionado; ?>
		</td>
	</tr>
	<tr>
		<td width="50%">
		FechaDesde:
		</td>
		<td width="50%">
			<?php escribe_formulario_fecha_vacio("fechadesde","adminForm"); ?>
		</td>
	</tr>
	<tr>
		<td width="50%">
		FechaHasta:
		</td>
		<td width="50%">
			<?php escribe_formulario_fecha_vacio("fechahasta","adminForm"); ?>
		</td>
	</tr>
	<tr>
		<td width="50%" colspan="2">
		<input type="button" name="FiltroBtn" value="Filtro" alt="Filtro" onclick="enviarListaFiltro('<?php echo $accionFiltro; ?>')"/>
		<input type="button" name="cancelBtn" value="Cancel" onclick="enviarListaFiltro('0')"/>
		</td>
	</tr>
	</table>
	<input type="hidden" name="option" value="<?php echo $option; ?>"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
	<input type="hidden" name="task" value="<?php echo $task; ?>"/>
	<input type="hidden" name="accion" value=""/>
	<input type="hidden" name="idfamilia" value="<?php echo $idFamilia; ?>"/>
	<input type="hidden" name="idproducto" value="<?php echo $idProducto; ?>"/>
</form>
<?php
	}

	function BloqueListaConsumos(&$rows,&$nombres,&$pageNav,&$filtroInfo,$totalProducto,$totalDosis){
		global $option,$Itemid,$task;

		$link = "index.php?option=$option&Itemid=$Itemid&task=$task&accion=showlist&".
		"idfamilia={$filtroInfo->idFamilia}&idproducto={$filtroInfo->idProducto}&".
		"fechadesde={$filtroInfo->fechaDesde}&fechahasta={$filtroInfo->fechaHasta}";

		$NumDosisTotal = 0;
		$importeConsumos = 0;

		for($i=0; $i < count( $rows ); $i++)
		{
			$row = $rows[$i];
			$NumDosisTotal = $NumDosisTotal + $row->cantidad;
			$importeConsumos = $importeConsumos + $row->total;
		}
?>
	<table cellpadding="4" cellspacing="0" border="0" width="100%">
		<tr>
			<td width="100%" class="sectionname">LISTADO
			</td>
			<td nowrap>Display #</td>
			<td> <?php echo $pageNav->writeLimitBox($link); ?> </td>
		</tr>
	</table>
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
	<tr>
			<th align="center" colspan="8"> <?php echo $pageNav->writePagesLinks($link); ?></th>
		</tr>
		<tr>
			<td align="center" colspan="8"> <?php echo $pageNav->writePagesCounter(); ?></td>
		</tr>
		<tr>
			<td align="center" colspan="8"> Importe Parcial (Listado en pantalla): <?php echo $importeConsumos." euro"; ?></td>
		</tr>
<?php
		if($totalProducto >= 0)
		{
?>
		<tr>
			<td align="center" colspan="8"> Importe Total (Entre las fechas elegidas): <h2><?php echo $totalDosis." dosis, hacen ".$totalProducto." euro"; ?></h2></td>
		</tr>
<?php
		}
		else{
?>
		<tr>
			<td align="center" colspan="8"> No se pueden obtener totales para este producto</td>
		</tr>
<?php
		}
?>
		<tr>
			<th width="2%" class="title">#</th>
			<th width="3%" class="title">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" />
			</th>
			<th width="5%" class="title">Id</th>
			<th width="20%" class="title">Familia</th>
			<th width="20%" class="title">Producto</th>
			<th width="10%" class="title">Numero Dosis</th>
			<th width="10%" class="title">Precio Total(euros)</th>
			<th width="30%" class="title">Fecha</th>
		</tr>
<?php
		$k = 0;
		for($i=0; $i < count( $rows ); $i++)
		{
			$row = $rows[$i];
			$nombreFamilia = $nombres[$i][0];
			$nombreProducto = $nombres[$i][1];
?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $i+$pageNav->limitstart+1;?></td>
			<td><input type="checkbox" id="cb<?php echo $i;?>" name="id[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" /></td>
			<td><?php echo $row->id; ?></td>
			<td><?php echo $nombreFamilia; ?></td>
			<td><?php echo $nombreProducto; ?></td>
			<td><?php echo $row->cantidad; ?></td>
			<td><?php echo $row->total; ?></td>
			<td><?php echo $row->inputdate; ?></td>
		</tr>
<?php
			$k = 1 - $k;
		}
?>
	</table>
<?php
	}

	function BloqueListaInventario(&$rows,&$nombres,&$pageNav,&$filtroInfo){
		global $option,$Itemid,$task;

		$link = "index.php?option=$option&Itemid=$Itemid&task=$task&accion=showlist&".
		"idfamilia={$filtroInfo->idFamilia}&idproducto={$filtroInfo->idProducto}&".
		"fechadesde={$filtroInfo->fechaDesde}&fechahasta={$filtroInfo->fechaHasta}";
?>
	<table cellpadding="4" cellspacing="0" border="0" width="100%">
		<tr>
			<td width="100%" class="sectionname">LISTADO
			</td>
			<td nowrap>Display #</td>
			<td> <?php echo $pageNav->writeLimitBox($link); ?> </td>
		</tr>
	</table>
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		<tr>
			<th width="2%" class="title">#</th>
			<th width="3%" class="title">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" />
			</th>
			<th width="5%" class="title">Id</th>
			<th width="20%" class="title">Familia</th>
			<th width="20%" class="title">Producto</th>
			<th width="5%" class="title">Unidades</th>
			<th width="5%" class="title">Dosis</th>
			<th width="10%" class="title">Precio Total(euros)</th>
			<th width="30%" class="title">Fecha</th>
		</tr>
<?php
		$k = 0;
		$importeInventario = 0;
		for($i=0; $i < count( $rows ); $i++)
		{
			$row = $rows[$i];
			$nombreFamilia = $nombres[$i][0];
			$nombreProducto = $nombres[$i][1];
?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $i+$pageNav->limitstart+1;?></td>
			<td><input type="checkbox" id="cb<?php echo $i;?>" name="id[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" /></td>
			<td><a href="javascript: void(0);" onclick="return enviarItem('cb<?php echo $i;?>')"><?php echo $row->id; ?></a></td>
			<td><?php echo $nombreFamilia; ?></td>
			<td><?php echo $nombreProducto; ?></td>
			<td><?php echo $row->cantidad; ?></td>
			<td><?php echo $row->dosis; ?></td>
			<td><?php echo $row->total; ?></td>
			<td><?php echo $row->inputdate; ?></td>
		</tr>
<?php
			$k = 1 - $k;
			$importeInventario = $importeInventario + $row->total;
		}
?>
		<tr>
			<th align="center" colspan="9"> <?php echo $pageNav->writePagesLinks($link); ?></th>
		</tr>
		<tr>
			<td align="center" colspan="9"> <?php echo $pageNav->writePagesCounter(); ?></td>
		</tr>
		<tr>
			<td align="center" colspan="9"> Total de esta pagina (hay mas paginas????): <h2><?php echo $importeInventario." euro"; ?></h2></td>
		</tr>
	</table>
<?php
	}

	function ListadoBodega(&$rows,&$nombres,&$pageNav,&$filtroInfo,$accion)
	{
		global $option,$Itemid,$task;

		$link = "index.php?option=$option&Itemid=$Itemid&task=$task&accion=$accion&".
		"idfamilia={$filtroInfo->idFamilia}&idproducto={$filtroInfo->idProducto}&".
		"fechadesde={$filtroInfo->fechaDesde}&fechahasta={$filtroInfo->fechaHasta}";

?>
<table width="100%">
	<tr>
		<td class="sectionname">FILTRO</td>
	</tr>
	<?php	if(!$filtroInfo->idFamilia && !$filtroInfo->idFamilia && !$filtroInfo->fechaDesde && !$filtroInfo->fechaHasta){	 ?>
	<tr><td><?php echo "SIN FILTRO" ?></td></tr>
	<?php }
	if($filtroInfo->idFamilia){	 ?>
	<tr><td><?php echo $filtroInfo->nombreFamilia; ?></td></tr>
	<?php }
	if($filtroInfo->idProducto){	 ?>
	<tr><td><?php echo $filtroInfo->nombreProducto; ?></td></tr>
	<?php }
	if($filtroInfo->fechaDesde){	 ?>
	<tr><td><?php echo "Fecha Desde: ".$filtroInfo->fechaDesde; ?></td></tr>
	<?php }
	if($filtroInfo->fechaHasta){	 ?>
	<tr><td><?php echo "Fecha Hasta: ".$filtroInfo->fechaHasta; ?></td></tr>
	<?php }?>
</table>
<br>
	<form action="index.php" method="post" name="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%">
		<tr>
			<td width="100%" class="sectionname">LISTADO</td>
			<td nowrap>Display #</td>
			<td> <?php echo $pageNav->writeLimitBox($link); ?> </td>
		</tr>
	</table>

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		<tr>
			<th width="2%" class="title">#</th>
			<th width="3%" class="title">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" />
			</th>
			<th width="5%" class="title">Id</th>
			<th width="30%" class="title">Familia</th>
			<th width="30%" class="title">Producto</th>
			<th width="10%" class="title">Unidades</th>
			<th width="40%" class="title">Fecha</th>
		</tr>
<?php
		$k = 0;
		for($i=0; $i < count( $rows ); $i++)
		{
			$row = $rows[$i];
			$nombreFamilia = $nombres[$i][0];
			$nombreProducto = $nombres[$i][1];
?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $i+$pageNav->limitstart+1;?></td>
			<td><input type="checkbox" id="cb<?php echo $i;?>" name="id[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" /></td>
			<td><?php echo $row->id; ?></td>
			<td><?php echo $nombreFamilia; ?></td>
			<td><?php echo $nombreProducto; ?></td>
			<td><?php echo $row->cantidad; ?></td>
			<td><?php echo $row->inputdate; ?></td>
		</tr>
<?php
			$k = 1 - $k;
		}
?>
		<tr>
			<th align="center" colspan="7"> <?php echo $pageNav->writePagesLinks($link); ?></th>
		</tr>
		<tr>
			<td align="center" colspan="7"> <?php echo $pageNav->writePagesCounter(); ?></td>
		</tr>
		<tr>
			<td colspan="7">
			<input type="button" name="BorrarBtn" value="Borrar Entradas" alt="Borrar las entradas seleccionadas" onclick="enviarListaFiltro('borrar')"/>
			</td>
		</tr>
	</table>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="<?php echo $task; ?>"/>
	<input type="hidden" name="accion" value=""/>
	</form>
<?php
	}
?>
