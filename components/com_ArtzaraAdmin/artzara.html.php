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
<script src="<?php echo $artzaraadminurl;?>calendario/javascripts.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="<?php echo $artzaraadminurl;?>js/artzarajavascript.js" type="text/javascript"></script>

<?php



/**
* @package Mambo_4.5.1
*/

function EnvioBackError($message)
{
	global $baseURL;
	/* Para ir hacia atras

	href='javascript:history.go(-1)'

	global $mainframe;
	$params =& new mosParameters( "" );
	$params->def( "back_button", $mainframe->getCfg( "back_button" ) );
	mosHTML::BackButton ( $params );
	*/

	echo "<p>Se ha producido un error.</p>\n";
	if($message)
		echo "<p>$message</p>\n";
	echo "<br><br>";
	echo "<div class=\"back_button\">\n";
	echo "<a href='javascript:history.go(-1)'>\n";
	echo "[ Back ]</a>\n";
	echo "</div>\n";
}

function EnvioError($message,$task="000")
{
	global $baseURL;
	/* Para ir hacia atras

	href='javascript:history.go(-1)'

	global $mainframe;
	$params =& new mosParameters( "" );
	$params->def( "back_button", $mainframe->getCfg( "back_button" ) );
	mosHTML::BackButton ( $params );
	*/

	echo "<p>Se ha producido un error.</p>\n";
	if($message)
		echo "<p>$message</p>\n";
	echo "<br><br>";
	echo "<div class=\"back_button\">\n";
	echo "<a href=\"".$baseURL."&task=".$task."\" >\n";
	echo "[ Atras ]</a>\n";
	echo "</div>\n";
}

function EnvioResultOK($message,$task="000")
{
	global $baseURL;

	echo "<p>TODO DE PUTA MADRE</p>\n";
	if($message)
		echo "<p>$message</p>\n";
	echo "<br><br>";
	echo "<div class=\"back_button\">\n";
	echo "<a href=\"".$baseURL."&task=".$task."\" >\n";
	echo "[ Atras ]</a>\n";
	echo "</div>\n";
}

function mostrarProductos($accion,$columnas,$idFamilia,&$productosNotIndexed,$idproductoselecc=NULL)
{
	global $task,$option,$Itemid,$mosConfig_live_site, $artzaraadminurl, $imagenX, $baseURL;

	$index = 0;

	$productos = array_values($productosNotIndexed);

	//Grupos
	echo "<TABLE>\n";
	if(($numProductos = count($productos)) < 1)
		echo "??NO EXISTEN PRODUcTOS EN ESTA FAMILIA!!";
	else
	{
		do {
			if($index >= $numProductos)
				break;
			// tenemos productos!
			echo "<tr>\n";
			for ($i = 1; $i <= $columnas; $i++)
			{
				echo "<td>\n";
				if($index < $numProductos)
				{
					echo "<TABLE align=\"center\" class=\"prod\">\n";
					echo "<tr>\n";
					echo "<td>";
					$producto = $productos[$index];

					echo "<a href=\"".$baseURL."&task=$task&accion=$accion&idfamilia=".$idFamilia."&idproducto=".$producto->id."\">";
					if($producto->imagefile == "" || $producto->imagefile == NULL)
					{
						echo "<img src=\"".$imagenX."\" height=\"50\" width=\"50\"/>";
					}
					else
					{
						$imagePath = $artzaraadminurl."images/".$producto->imagefile;
						echo "<img src=\"".$imagePath."\" height=\"50\" width=\"50\"/>";
					}
					echo "</a>\n";
					echo "</td>\n";
					echo "</tr>\n";
					echo "<tr>\n";

					if($producto->id == $idproductoselecc)
					{
						echo "<td bgcolor=#c1ffb7>";
						echo "<a href=\"".$baseURL."&task=$task&accion=$accion&idfamilia=".$idFamilia."&idproducto=".$producto->id."\">";
						echo $producto->nombre;
						echo "</a>\n";
					}
					else
					{
						echo "<td>";
						echo "<a href=\"".$baseURL."&task=$task&accion=$accion&idfamilia=".$idFamilia."&idproducto=".$producto->id."\">";
						echo $producto->nombre;
						echo "</a>\n";
					}
					echo "</td>\n";
					echo "</tr>\n";
					echo "</TABLE>";
					$index++;
				}
				echo "</td>\n";
			}
			echo "</tr>\n";
		} while (1);
	}
	echo "</TABLE>";
}


function mostrarFamilias($accion,$numColumnas,&$familiasNotIndexed,$idFamiliaSelecc = NULL)
{
	global $task,$option,$Itemid,$mosConfig_live_site, $artzaraadminurl, $imagenX, $baseURL,$logFile;
	$index = 0;

	$familias = array_values($familiasNotIndexed);

	//Grupos
	echo "<TABLE>\n";
	if(($numFamilias = count($familias)) < 1)
		echo "??NO EXISTEN FAMILIAS DE PRODUCTOS!!";
	else
	{
		do {
			if($index >= $numFamilias)
				break;
			// tenemos familias!
			echo "<tr>\n";
			for ($i = 1; $i <= $numColumnas; $i++)
			{
				echo "<td>\n";
				if($index < $numFamilias)
				{
					echo "<TABLE align=\"center\" class=\"menubar\">\n";
					echo "<tr>\n";
					echo "<td>";
					$familia = $familias[$index];
					echo "<a href=\"".$baseURL."&task=$task&accion=$accion&idfamilia=".$familia->id."\">";
					if($familia->imagefile == "" || $familia->imagefile == NULL)
						echo "<img src=\"".$imagenX."\" height=\"50\" width=\"50\"/>";
					else
					{
						$imagePath = $artzaraadminurl."images/".$familia->imagefile;
						echo "<img src=\"".$imagePath."\" height=\"50\" width=\"50\"/>";
					}
					echo "</a>\n";
					echo "</td>\n";
					echo "</tr>\n";
					echo "<tr>\n";
					if($familia->id == $idFamiliaSelecc)
					{
						echo "<td bgcolor=#c1ffb7>";
						echo "<a href=\"".$baseURL."&task=$task&accion=$accion&idfamilia=".$familia->id."\">";
						echo $familia->nombre;
						echo "</a>\n";
					}
					else
					{
						echo "<td>";
						echo "<a href=\"".$baseURL."&task=$task&accion=$accion&idfamilia=".$familia->id."\">";
						echo $familia->nombre;
						echo "</a>\n";
					}
					echo "</td>\n";
					echo "</tr>\n";
					echo "</TABLE>";
					$index++;
				}
				echo "</td>\n";
			}
			echo "</tr>\n";
		} while (1);
	}
	echo "</TABLE>";
}

function PantallaCalculo(&$producto){
	if($producto == null){
		echo "SELECCIONE UN PRODUCTO";
		return;
	}
	// Ya tenemos un producto
?>
	<script language="JavaScript">

	function calculoTotal(cantidad){
		var precio = <?php echo $producto->venta_publico_dosis; ?>;
		var aux = 0;
		if(cantidad != null && !isNaN(cantidad))
		{
			aux = cantidad * precio * 100;
			document.adminForm.displayOut.value = parseInt(aux)/100;
			document.adminForm.numDosis.value = cantidad;
		}
	}

	</script>
	<table width="100%" class="adminform">
		<tr>
			<td>
				<?php echo $producto->nombre; ?>
			</td>
			<td>
				<?php echo $producto->venta_publico_dosis." euros/dosis"; ?>
			</td>
		</tr>
		<tr>
			<td align="center" width="200">
						<?php showKeypad('adminForm','numDosis'); ?>
			</td>
			<td>
				Unidades: <input type="text" readonly name="numDosis" value="" />
				<!--<input type="text" readonly name="displayOut" class="inputbox" onChange="calculoTotal(parseInt(this.value));" value=""/>-->
				
			</td>
		</tr>
	</table>


<?php

}

function PantallaListaConsumiciones(&$consumosNotIndexed){
	$consumos = array_values($consumosNotIndexed);
?>
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		<tr>
			<th width="2%" class="title">
			#
			</th>
			<th width="3%" class="title">
				<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($consumos); ?>);" />
			</th>
			<th width="10%" class="title">
			id
			</th>
			<th width="25%" class="title">
			Familia
			</th>
			<th width="25%" class="title">
			Producto
			</th>
			<th width="10%" class="title">
			Num. Dosis
			</th>
			<th width="20%" class="title">
			Precio
			</th>	
		</tr>
<?php
		$k = 0;
		for($i=0; $i < count( $consumos ); $i++){
			$consumo = $consumos[$i];
?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $i+1;?></td>
			<td><input type="checkbox" id="cb<?php echo $i;?>" name="id[]" value="<?php echo $consumo->id; ?>" onclick="isChecked(this.checked);" /></td>
			<td><?php echo $consumo->id; ?></td>
			<td><?php echo $consumo->familyName; ?></td>
			<td><?php echo $consumo->productName; ?></td>
			<td><?php echo $consumo->cantidad; ?></td>
			<td><?php echo $consumo->importe; ?></td>
		</tr>
<?php
			$k = 1 - $k;
		}
?>
	</table>
<?php	
}

function FormularioSobre($newSobre,$total,$producto,$consumos){
	global $task,$option,$Itemid;
	
?>
	<form action="index.php" method="post" name="adminForm">
	<table width="100%" class="adminform">
		<tr>
			<th>
			Calculo Importe
			</th>
		</tr>
		<tr>
			<td>
			<?php
				PantallaCalculo($producto);
			?>
			</td>
		</tr>
	</table>
	<table class="adminform">
		<tr>
			<th>
			Lista Consumiciones
			</th>
		</tr>
		<tr>
			<td>
			<?php
				PantallaListaConsumiciones($consumos);
			?>
			</td>
		</tr>
		<tr>
			<td align="right">
				<h4>Total : <?php echo $total; ?> euros</h4>
			</td>
		</tr>
	</table>
		<input type="hidden" name="idProducto" value="<?php echo ($producto == null)?"":($producto->id); ?>" />
		<input type="hidden" name="idSobre" value="<?php echo $newSobre->id; ?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
		<input type="hidden" name="task" value="<?php echo $task; ?>"/>
		<input type="hidden" name="accion" value=""/>
		</form>
	<table class="adminform">
	<tr>
		<th colspan="2">
		Datos del Sobre
		</th>
	</tr>
		<tr>
			<td>
			Sobre Numero:
			</td>
			<td>
				<?php echo $newSobre->id; ?>
			</td>
		</tr>
		<tr>
			<td>
			Fecha:
			</td>
			<td>
				<?php echo date('Y-m-d H:i:s'); ?>
			</td>
		</tr>
		<tr>
			<td>
			Numero de Socio:
			</td>
			<td>
				<?php echo $newSobre->id_socio; ?>
			</td>
		</tr>
	</table>
<?php
}

function PantallaNewSobre(&$newSobre,&$consumos,$total,&$familias,$idFamilia=NULL,$productos=NULL,$producto=NULL){
		global $option,$Itemid,$task,$numColumnas;
		/////////////////////
		//
		//if($idFamilia==NULL && $productos==NULL && $producto==NULL)
		//{
		//MAIN 000 -> 0
		//}
		//elseif($idFamilia != NULL && $productos!= NULL && $producto == NULL)
		//{
		// grupo seleccionado 110 -> 6
		//
		//}
		//elseif($idFamilia != NULL && $productos!= NULL && $producto != NULL)
		//{
		//	producto seleccionado 111 = 7
		//}
		/////////////////////

		$a = ($idFamilia===NULL)?(0):(1);
		$in = $a<<1;
		$a = ($productos===NULL)?(0):(1);
		$in = $in + $a;
		$in = $in<<1;
		$a = ($producto===NULL)?(0):(1);
		$in = $in + $a;

		if($in != 0 && $in != 6 && $in != 7)
		{
			EnvioError(_PARAMETERS_INVALID,$task);
			return;
		}
		$cerrarSobreLink = "index.php?option=$option&Itemid=$Itemid&task=$task&accion=cerrarsobre&idSobre={$newSobre->id}";
?>
		<script language="JavaScript1.2">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				if (confirm('Cancelando perderas los cambios hechos. ?Continuar?'))
					setact(pressbutton);
				return;
			}// para preguntar si queremos borrar esta deletelist
			else if(pressbutton == 'cerrarsobre'){
				if (confirm('No podras modificarlo si continuas. ?Continuar?'))
				{
					var enlace = '<?php echo $cerrarSobreLink; ?>';
					window.open(enlace, 'win2', 'status=no,toolbar=no,scrollbars=no,titlebar=no,menubar=no,resizable=no,width=400,height=560,directories=no,location=no');
					settask('00');
				}
				return;
			}

			// do field validation
			if (pressbutton == 'new' && (form.numDosis == null || form.numDosis.value == '' || !validoInt(form.numDosis.value))) {
				alert( "Importe invalido: Dale a la calculadora!!!!" );
			}
			else {
				setact(pressbutton);
			}
		}
		</script>

		<table class="adminheading">
			<tr>
				<td align="right">
<?php
				mosMenuBar::startTable();
				mosMenuBar::divider();
				GenericLib::myIcono('new','next.png','next_f2.png','Aceptar',false);
				mosMenuBar::spacer(10);
				mosMenuBar::deleteList();
				mosMenuBar::divider();
				mosMenuBar::spacer(30);
				mosMenuBar::save('cerrarsobre','Fin Sobre');
				mosMenuBar::spacer(10);
				mosMenuBar::cancel('cancel','Cancelar y borrar sobre');
				mosMenuBar::endTable();
?>
				</td>
			</tr>
		</table>
		<br />
		<table width="100%">
		<tr>
			<td width="50%" valign="top">
				<table class="adminform">
				<tr>
					<th colspan="2">
					Elige Producto
					</th>
				</tr>
				<tr>
					<td>
						<table width="100%" border="1" >
						<th>
							FAMILIAS
						</th>
						<tr>
							<td>
				<?php
								$link = "familiaseleccionada&idSobre=".$newSobre->id;
								if($in == 6 || $in == 7)
									mostrarFamilias($link,$numColumnas,$familias,$idFamilia);
								else
									mostrarFamilias($link,$numColumnas,$familias);
				?>
							</td>
						</tr>
						<th>
							PRODUCTOS
						</th>
						<tr>
							<td>
							<!-- Productos -->
				<?php
							$link = "productoseleccionado&idSobre=".$newSobre->id;
							if($in == 6)
								mostrarProductos($link,$numColumnas,$idFamilia,$productos);
							elseif($in == 7)
								mostrarProductos($link,$numColumnas,$idFamilia,$productos,$producto->id);
							else
								echo "PULSE UNA FAMILIA PARA VER LOS PRODUCTOS";
				?>
							</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
			<td width="50%" valign="top">
				<table class="adminform">
				<tr>
					<td>
		<?php
						FormularioSobre($newSobre,$total,$producto,$consumos);
		?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
<?php
	}

function SobreHtml(&$sobre,&$salidas,$total,&$socio)
{
	global $task,$option,$Itemid, $artzaraadminurl, $artzaraLogo;
?>
	<table width="100%">
	<tr>
		<td width="30%">
			<img border="0" src="<?php echo $artzaraLogo; ?>" width="100" height="80">
		</td>
		<td width="70%">
			<p align="center"><font size="3">ARTZARA ELKARTEA</font><p>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>	
	<tr>
		<td>
			<p align="left">Nº</td>
		<td align="center">
			<h3><font size="5"><?php echo $sobre->id;?></font></h3>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td>
			<p align="left">Socio (<?php echo $socio->id;?>):</td>
		<td align="center">
			<p align="center"><font size="4"><?php echo $socio->nick." ( ".$socio->nombre." )";?></font></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td>
			<p align="left">Fecha</td>
		<td align="center"><font size="4"><?php echo date('Y-m-d H:i:s');?></font></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td>
		<p align="left">Total:</td>
		<td align="center"><font size="4"><?php echo $total." "; ?>(euro)</font></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td height="50">Abonar:</td>
		<td align="right" height="50">
			<table border="1" width="100%" height="50">
			<tr>
				<td align="right">(Euro)</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table width="100%">
			
<?php
					$anchoColum = intval(40/count($salidas));
					foreach($salidas as $salida){
						if($salida->name == _ARTZARA)
							continue;
?>		
					<tr>
					<td width="<?php echo $anchoColum;?>%"><?php echo $salida->name;?></td>
					<td width="<?php echo $anchoColum;?>%">
						<table border="1" width="50">
						<tr>
							<td>&nbsp;</td>
						</tr>
						</table>
					</td>
					</tr>
<?php
					}
?>
			
			</table>
		</td>
	</tr>
	</table>
<?php
}

function PrintSobreHtml(&$sobre,&$salidas,$total,&$socio)
{
	global $task,$option,$Itemid, $artzaraadminurl;
?>
	<table class="sobretable">
		<tr valign="top">
			<td width="100%">
<?php
				SobreHtml($sobre,$salidas,$total,$socio);
?>
			</td>
		</tr>
		<tr valign="top">
			<td align="left" width="100%" class="buttonheading">
				<a href="#" onclick="javascript:window.print(); window.close(); return false" title="Print">
					<img src="images/M_images/printButton.png" alt="Print" align="middle" name="image" border="0" />
				</a>
			</td>
		</tr>
	</table>
<?php
}

function ShowMensaje(&$socios,&$mensaje,$admin = 1){
		global $option,$Itemid,$task,$artzaraadminurl;

		if(!$mensaje){
			$subject = "";
			$msg = "";
		}
		else{
			$subject = $mensaje->subject;
			$msg = $mensaje->msg;
		}
?>
	<script language="JavaScript">
	function submitbutton(accion) {
		var form = document.adminForm;
		if(accion == 'cancel'){
			form.accion.value = accion;
			return true;
		}

		if(form.subject.value == '')
		{
			alert('Debes poner algo en el titulo del mensaje');
			return false;
		}
		form.accion.value = accion;
		return true;
	}
	</script>

	<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table width="400">
	<tr><td>
	<table class="adminform">
		<tr>
			<td colspan="2">
				<div align="right">
			<?php
				if(!$mensaje){
?>
					<input type="submit" name="submit" onclick="return submitbutton('enviar');" class="button" value="Enviar" />
<?php
					echo "\n";
				}
?>
					<input type="submit" name="submit" onclick="return submitbutton('cancel');" class="button" value="Cancelar" />
				</div>
			</td>
		</tr>
		<tr>
			<td>
				Enviar a:
			</td>
			<td>
				<?php
						if(!$mensaje){
?>
						<div>
							<select name="id_destino" size="6">
<?php
							foreach($socios as $socio){
							?>
									<option value="<?php echo $socio->id; ?>"><?php echo $socio->nick ."(".$socio->nombre.")"; ?></option>
							<?php
								}
							?>
							</select>
						</div>
<?php
						}
						else{
							$socio = $socios["{$mensaje->id_destino}"];


?>
							<input name="id_destino" readonly type="text" value="<?php echo $socio->nick ."(".$socio->nombre.")"; ?>" class="inputbox" size="50" />
<?php
						}
?>
			</td>
		</tr>
		<tr>
			<td>
				Subject:
			</td>
			<td>
				<input name="subject" value="<?php echo $subject; ?>" type="text" size="50" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
			Texto:<br />
			<textarea name="msg" cols="80" rows="30"><?php echo $msg; ?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div align="right">
			<?php
				if(!$mensaje){
?>
					<input type="submit" name="submit" onclick="return submitbutton('enviar');" class="button" value="Enviar" />
<?php
					echo "\n";
				}
?>
					<input type="submit" name="submit" onclick="return submitbutton('cancel');" class="button" value="Cancelar" />
				</div>
			</td>
		</tr>
	</table>
	</td></tr></table>

	<input type="hidden" name="task" value="<?php echo $task; ?>"/>
	<input type="hidden" name="accion" value=""/>
	<input type="hidden" name="option" value="<?php echo $option; ?>"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
</form>
<?php
	}

	function PantallaFormularioSobre(&$sobre,&$consumosArrayInfoNotIndexed,&$salidas,&$socio,$admin=1){
		global $option,$Itemid,$task;

		$consumosArrayInfo = array_values($consumosArrayInfoNotIndexed);
?>
		<table class="adminheading">
			<tr>
				<td align="right">
<?php
				mosMenuBar::startTable();
			if($admin){
				mosMenuBar::spacer(10);
				mosMenuBar::save();
			}
				mosMenuBar::spacer(10);
				mosMenuBar::cancel('cancel','Volver');
				mosMenuBar::endTable();
?>
				</td>
			</tr>
		</table>
<br />
		<table width="100%" class="adminform">
		<tr>
			<th colspan="2">
			Sobre : <?php echo $sobre->id; ?>
			</th>
		</tr>
		<tr>
			<td>
			Socio:
			</td>
			<td>
			<?php echo $socio->id.": ".$socio->nick." (".$socio->nombre.")"; ?>
			</td>
		</tr>
		<tr>
			<td>
			Total Importe:
			</td>
			<td>
			<?php echo $sobre->total; ?>
			</td>
		</tr>
		<tr>
			<td>
			Pagado:
			</td>
			<td>
			<?php echo $sobre->pago; ?>
			</td>
		</tr>
		<tr>
			<td>
			Salida:
			</td>
			<td>
			<?php echo $salidas["{$sobre->id_salida}"]->name;?>
			</td>
		</tr>
		<tr>
			<td>
			Fecha creacion:
			</td>
			<td>
			<?php echo $sobre->inputdate; ?>
			</td>
		</tr>
		<tr>
			<td>
			Fecha procesado:
			</td>
			<td>
			<?php echo $sobre->processdate; ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
			<form action="index.php" method="post" name="adminForm" id="adminForm">
<?php
	if($admin){
?>
				<input type="radio" name="state" value="<?php echo _PROCESADO;?>" <?php echo ($sobre->state == _PROCESADO)?"checked=\"checked\"":"";?> class="inputbox" size="1" />PROCESADO
				<input type="radio" name="state" value="<?php echo _NO_PROCESADO;?>" <?php echo ($sobre->state == _NO_PROCESADO)?"checked=\"checked\"":"";?> class="inputbox" size="1" />NO_PROCESADO
				<input type="radio" name="state" value="<?php echo _NOT_CLOSED;?>" <?php echo ($sobre->state == _NOT_CLOSED)?"checked=\"checked\"":"";?> class="inputbox" size="1" />NO CERRADO
				<input type="radio" name="state" value="<?php echo _ANULADO;?>" <?php echo ($sobre->state == _ANULADO)?"checked=\"checked\"":"";?> class="inputbox" size="1" />ANULADO
<?php
	}
	else{
		echo "Estado: ";
		if($sobre->state == _PROCESADO) echo "PROCESADO";
		else if($sobre->state == _NO_PROCESADO) echo "NO_PROCESADO";
		else if($sobre->state == _NOT_CLOSED) echo "NOT_CLOSED";
		else if($sobre->state == _ANULADO) echo "ANULADO";
		else echo "UNKNOWN STATE";
	}
?>
				<input type="hidden" name="id" value="<?php echo $sobre->id; ?>" />
				<input type="hidden" name="boxchecked" value="1" />
				<input type="hidden" name="option" value="<?php echo $option; ?>" />
				<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
				<input type="hidden" name="task" value="<?php echo $task; ?>"/>
				<input type="hidden" name="accion" value=""/>
			</form>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
					<tr>
						<th width="2%" class="title">#</th>
						<th width="8%" class="title">Id</th>
						<th width="30%" class="title">Familia</th>
						<th width="30%" class="title">Producto</th>
						<th width="10%" class="title">Num Dosis</th>
						<th width="20%" class="title">Precio(euros)</th>
					</tr>
			<?php
					$k = 0;
					for($i=0; $i < count( $consumosArrayInfo ); $i++)
					{
						$consumosInfo = $consumosArrayInfo[$i];
			?>
					<tr class="<?php echo "row$k"; ?>">
						<td><?php echo $i+1;?></td>
						<td><?php echo $consumosInfo->id; ?></td>
						<td><?php echo $consumosInfo->familyName ?></td>
						<td><?php echo $consumosInfo->productName; ?></td>
						<td><?php echo $consumosInfo->cantidad; ?></td>
						<td><?php echo $consumosInfo->importe; ?></td>
					</tr>
			<?php
						$k = 1 - $k;
					}
			?>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;
			</td>
		</tr>
		</table>
	<?php

	}

	function PantallaListadoSobresHTML(&$rows,&$pageNav,&$filtroInfo,&$socios,&$salidas,$admin = 1){
		global $option,$Itemid,$task;

		$link = "index.php?option=$option&Itemid=$Itemid&task=$task&accion=showlist&".
		"id_socio={$filtroInfo->idSocio}&state={$filtroInfo->state}&".
		"fechadesde={$filtroInfo->fechaDesde}&fechahasta={$filtroInfo->fechaHasta}&debo={$filtroInfo->debo}";
?>
		<table class="adminheading">
			<tr>
				<td align="right">
<?php
				mosMenuBar::startTable();
				GenericLib::myIcono( '', 'publish.png', 'publish_f2.png', 'NuevoListado', false );
				mosMenuBar::spacer();
			if($admin){
					GenericLib::myIcono('anular','cancel.png','cancel_f2.png','anular');
					mosMenuBar::spacer(10);
					mosMenuBar::deleteList();
			}
				mosMenuBar::spacer(10);
				mosMenuBar::cancel('exit','Volver');
				mosMenuBar::endTable();
?>
				</td>
			</tr>
		</table>
<br />
<form action="index.php" method="post" name="adminForm">
	<table width="100%">
		<tr>
			<td width="60%" valign="top">
				<table class="adminform">
				<tr>
					<th colspan="2">
					Fecha
					</th>
				</tr>
				<tr>
					<td width="100">
					Desde:
					</td>
					<td width="85%">
					<?php escribe_formulario_fecha_vacio("fechadesde","adminForm",$filtroInfo->fechaDesde); ?>
					</td>
				</tr>
				<tr>
					<td width="100">
					Hasta:
					</td>
					<td>
					<?php escribe_formulario_fecha_vacio("fechahasta","adminForm",$filtroInfo->fechaHasta); ?>
					</td>
				</tr>
<?php
	if($admin){
?>
				<tr>
					<td colspan="2">
					<input type="radio" name="state" value="<?php echo _TODOS_SOBRES;?>" <?php if($filtroInfo->state != _PROCESADO && $filtroInfo->state != _NO_PROCESADO && $filtroInfo->state != _NOT_CLOSED && $filtroInfo->state != _ANULADO ) echo "checked=\"checked\"";?> class="inputbox" size="1" />TODOS
					<input type="radio" name="state" value="<?php echo _PROCESADO;?>" <?php if($filtroInfo->state == _PROCESADO) echo "checked=\"checked\"";?> class="inputbox" size="1" />PROCESADO
					<input type="radio" name="state" value="<?php echo _NO_PROCESADO;?>" <?php if($filtroInfo->state == _NO_PROCESADO) echo "checked=\"checked\"";?> class="inputbox" size="1"/>NO_PROCESADO
					<input type="radio" name="state" value="<?php echo _NOT_CLOSED;?>" <?php if($filtroInfo->state == _NOT_CLOSED) echo "checked=\"checked\"";?> class="inputbox" size="1" />SIN CERRAR
					<input type="radio" name="state" value="<?php echo _ANULADO;?>" <?php if($filtroInfo->state == _ANULADO) echo "checked=\"checked\"";?> class="inputbox" size="1" />ANULADO
					</td>
				</tr>
<?php
			}
?>
				<tr>
					<td colspan="2">
					<input type="radio" name="debo" value="1" <?php if($filtroInfo->debo == 1) echo "checked=\"checked\"";?> class="inputbox" size="1" />Debo
					<input type="radio" name="debo" value="2" <?php if($filtroInfo->debo == 2) echo "checked=\"checked\"";?> class="inputbox" size="1"/>Haber
					<input type="radio" name="debo" value="0" <?php if($filtroInfo->debo != 1 && $filtroInfo->debo != 2) echo "checked=\"checked\"";?> class="inputbox" size="1" />Todos
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;
					</td>
				</tr>
				</table>
			</td>
			<td width="40%" valign="top">
				<table class="adminform">
				<tr>
					<th>
					Socios
					</th>
				</tr>
				<tr>
					<td>
					<br />
					<select name="id_socio" size="6">
<?php
						if($admin){
?>
						<option value="0">Todos</option>
<?php
						}
						foreach($socios as $socio){
?>
							<option <?php if($filtroInfo->idSocio == $socio->id) echo "SELECTED";?> value="<?php echo $socio->id; ?>">
								<?php echo $socio->nick."(".$socio->nombre.")"; ?>
							</option>
<?php
						}
?>
					</select>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
<br />

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
			<th width="5%" class="title">Socio</th>
			<th width="10%" class="title">Total</th>
			<th width="10%" class="title">Pagado</th>
			<th width="15%" class="title">Salida</th>
			<th width="20%" class="title">Fecha Sobre</th>
			<th width="20%" class="title">Fecha Procesado</th>
			<th width="10%" class="title">Estado</th>

		</tr>
<?php
		$k = 0;
		for($i=0; $i < count( $rows ); $i++)
		{
			$row = $rows[$i];
			$state = "";
			if($row->state == _PROCESADO)
				$state = "PROCESADO";
			else if($row->state == _NO_PROCESADO)
				$state = "NO PROCESADO";
			else if($row->state == _ANULADO)
				$state = "ANULADO";
			else
				$state = "SIN CERRAR";

?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $i+$pageNav->limitstart+1;?></td>
			<td><input type="checkbox" id="cb<?php echo $i;?>" name="id[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" /></td>
			<td><a href="javascript: void(0);" onclick="return enviarItem('cb<?php echo $i;?>','edit')"><?php echo $row->id; ?></a></td>
			<td><?php echo $row->id_socio; ?></td>
			<td><?php echo $row->total; ?></td>
			<td><?php echo $row->pago; ?></td>
			<td><?php echo $salidas["{$row->id_salida}"]->name; ?></td>
			<td><?php echo $row->inputdate; ?></td>
			<td><?php echo $row->processdate; ?></td>
			<td><?php echo $state; ?></td>
		</tr>
<?php
			$k = 1 - $k;
		}
?>
		<tr>
			<th align="center" colspan="10"> <?php echo $pageNav->writePagesLinks($link); ?></th>
		</tr>
		<tr>
			<td align="center" colspan="10"> <?php echo $pageNav->writePagesCounter(); ?></td>
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

////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Funci?n que escribe en la p?gina un fomrulario preparado para introducir una fecha y enlazado con el calendario para seleccionarla comodamente
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function escribe_formulario_fecha_vacio($nombrecampo,$nombreformulario,$inputdate = "")
{
	global $artzaraadminurl;

	$raiz = $artzaraadminurl."calendario";
	//echo "<script>alert('$raiz')</script>\n";
	echo '
	<INPUT name="'.$nombrecampo.'" value="'.$inputdate.'" size="20">
	<input type=button value="Seleccionar fecha" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$nombrecampo.'\')">
	';
}

function mostrarTitulo($titulo,$section = "")
{
	echo "<table class=\"adminheading\">";
?>
	<tr>
		<th <?php if($section!="") echo "class=\"$section\""; ?>><?php echo $titulo;?></th>
	</tr>
	</table>
<?php
}

function PantallaLogin(&$socios){
?>
	<script language="JavaScript">
		function obtenerSocio(socio) {
			document.adminForm.username.value = socio;
		}
		function UsuarioReadOnly(){
			document.adminForm.username.readOnly = !document.adminForm.username.readOnly;
			document.adminForm.passwd.readOnly = !document.adminForm.passwd.readOnly;
			document.adminForm.username.focus();
		}
		</script>
<div id="ctr" align="center">
	<div class="login">
		<div class="login-form">
			<img src="administrator/templates/mambo_admin_blue/images/login.gif" alt="Login" />
        	<form action="index.php" method="post" name="adminForm" id="loginForm">
			<div class="form-block">
				<table>
				<tr valign="top">
					<td>
					<div class="inputlabel">Nombre de Socio</div>
		    			<div>
							<input name="username" readonly type="text" class="inputbox" size="15" />
							<input type="checkbox" name="toggle" value="" onClick="UsuarioReadOnly();" />
						</div>
	        			<div class="inputlabel">Clave</div>
		    			<div><input name="passwd" readonly type="password" class="inputbox" size="15" /></div>
					</td>
					<td align="center">
						<img src="<?php echo "administrator/images/next_f2.png"; ?>" height="60" width="60" alt="Entrar" onClick="enviarFormulario('adminForm')" />
					</td>
				</tr>
				<tr>
					<td colspan="2"><br /></td>
				</tr>
				<tr valign="top">
					<td>
						<div class="inputlabel">Listado Socios</div>
								<div>
									<select name="id_socio" ONCHANGE="obtenerSocio(this.value)" size="20">
									<?php
										foreach($socios as $socio){
									?>
											<option value="<?php echo $socio->nick; ?>"><font size="+4"><?php echo $socio->nick ."(".$socio->nombre.")"; ?></font></option>
									<?php
										}
									?>
									</select>
						</div>
					</td>
					<td align="center" width="200">
						<?php showKeypad('adminForm','passwd',1); ?>
					</td>
				</tr>
				</table>
        	</div>
			<input type="hidden" name="option" value="login" />
			</form>
    	</div>
		<div class="login-text">
			<div class="ctr"><img src="administrator/templates/mambo_admin_blue/images/security.png" width="64" height="64" alt="security" /></div>
        	<p>Ongi Etorri Artzara Elkartera</p>
			<p>Introduce tu nombre y login para entrar</p>
    	</div>
		<div class="clr"></div>
	</div>
</div>
<div id="break"></div>
<noscript>
!Warning! Javascript must be enabled for proper operation of the Administrator
</noscript>
<?php
}

?>
