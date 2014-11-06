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

/**
* @package Mambo_4.5.1
*/

	function SobresPantallaPrincipal()
	{
		global $option,$Itemid;
		$link = "index.php?option=$option&Itemid=$Itemid";
?>
		<table width="100%" class="cpanel">
		<tr>
			<td align="center" width="25%">
			<a href="<?php echo $link; ?>&task=141" style="text-decoration:none;">
			<img src="administrator/images/templatemanager.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>PROCESADO SOBRES</h2>
			</a>
			</td>

			<td align="center" width="25%">
			<a href="<?php echo $link; ?>&task=142" style="text-decoration:none;">
			<img src="administrator/images/query.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>LISTADO SOBRES</h2>
			</a>
			</td>

			<td align="center" width="25%">
			<a href="<?php echo $link; ?>&task=143&accion=setsocio" style="text-decoration:none;">
			<img src="administrator/images/messaging.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>NUEVO SOBRE</h2>
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

	function NavSobres(){
		global $option,$Itemid;
?>
		<table border='0' cellpadding='5' cellspacing='5' width='100%'>
		<tr>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid&task=141"; ?>" class='buttonbar'>PROCESADO SOBRES</a>
			</td>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid&task=142"; ?>" class='buttonbar'>LISTADO SOBRES</a>
			</td>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid&task=143&accion=setsocio"; ?>" class='buttonbar'>NUEVO SOBRE</a>
			</td>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid"; ?>" class='buttonbar'>Inicio</a>
			</td>
		</tr>
		</table>
<?php
	}

	function PantallaCodigoSobresHTML(){
		global $option,$Itemid, $artzaraadminurl;
		$sobreGif = $artzaraadminurl."images/sobre.gif";
?>
	<div id="ctr" align="center">
		<div class="login">
			<div class="login-form">
				<img src="<?php echo $sobreGif; ?>" alt="Sobre" />
				<form action="index.php" method="post" name="adminForm" id="adminForm">
					<div class="form-block">
						<div class="inputlabel">Codigo Sobre</div>
						<div>
							<input name="idsobre" readonly type="text" class="inputbox" size="15" />
						</div>
						<div align="left">
							<input type="submit" name="submit" class="button" value="Enviar" />
						</div>
					</div>
					<div class="form-block">
						<div>
							<?php showKeypad('adminForm','idsobre'); ?>
						</div>
					</div>
					<input type="hidden" name="task" value="141"/>
					<input type="hidden" name="accion" value="new"/>
					<input type="hidden" name="option" value="<?php echo $option; ?>"/>
					<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
				</form>
			</div>
			<div class="login-text">
				<div class="ctr">
					<img src="administrator/templates/mambo_admin/images/security.png" width="64" height="64" alt="security" />
				</div>
				<p>Introducir codigo sobre!</p>
				<p>Use a valid id Sobre to gain access to the console.</p>
			</div>
			<div class="clr"></div>
		</div>
	</div>
<?php
	}
	
	function PantallaNuevoProcesadoHTML(&$sobre,$nombreSocio,&$salidas){
		global $option,$Itemid;
?>

		<table class="adminheading">
			<tr>
				<td align="right">
<?php
				mosMenuBar::startTable();
				mosMenuBar::save('procesar');
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
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				setact(pressbutton);
				return;
			}
			
			// do field validation
			if(!validoImporte(form.displayOut.value))
			{
				alert('Importe invalido');

			} else {
				setact(pressbutton);
			}
		}

		function calculoTotal(cantidad){
		
		var apagar = <?php echo $sobre->total; ?>;
		var numSalidas = <?php echo count($salidas); ?>;
		if(cantidad != null && !isNaN(cantidad))
		{
			var deboDisabled;
			if(cantidad<=apagar)
				deboDisabled = false;
			else
				deboDisabled = true;
			for (var i = 0; i < numSalidas; i++) {
				if(document.adminForm.salida[i].value == -1)
				{
					document.adminForm.salida[i].disabled = deboDisabled;
				}
				else
					document.adminForm.salida[i].disabled = !deboDisabled;
			}
		}
	}
		</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table width="100%">
		<tr>
			<td width="60%" valign="top">
				<table class="adminform">
				<tr>
					<th colspan="2">
					Datos del Sobre: <?php echo $sobre->id; ?>
					</th>
				</tr>
				<tr>
					<td>
					Nombre Socio:
					</td>
					<td>
					<input type="text" readonly name="name" class="inputbox" size="40" value="<?php echo $nombreSocio; ?>" />
					</td>
				</tr>
				<tr>
					<td>
					Importe del sobre:
					</td>
					<td>
					<input type="text" readonly name="total" class="inputbox" size="40" value="<?php echo $sobre->total; ?>" />
					</t$totald>
				</tr>
				<tr>
					<td>
					Fecha Sobre:
					</td>
					<td>
					<input class="inputbox" readonly type="text" name="inputdate" size="40" value="<?php echo $sobre->inputdate; ?>" />
					</td>
				</tr>
				<tr>
					<td>
					Destino:
					</td>
					<td>
<?php
						foreach($salidas as $salida){
							$checked = ($salida->id == $sobre->id_salida)?("checked=\"checked\""):("");
?>
							<input type="radio" name="salida" value="<?php echo $salida->id;?>" <?php echo $checked;?> class="inputbox" size="1" /><?php echo $salida->name;?>
<?php
						}
?>
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
					Importe Pagado
					</th>
				</tr>
				<tr>
					<td>
					<br />
					Pago: <input type="text" readonly name="displayOut" onBlur="calculoTotal(parseFloat(this.value))" class="inputbox" size="15" value="" />
					</td>
				</tr>
				<tr>
					<td width="100">
						<?php showKeypad(); ?>
					</td>
				</tr>
				</table>

			</td>
		</tr>
		</table>
		<input type="hidden" name="idsobre" value="<?php echo $sobre->id;?>"/>
		<input type="hidden" name="task" value="141"/>
		<input type="hidden" name="accion" value=""/>
		<input type="hidden" name="option" value="<?php echo $option; ?>"/>
		<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
	</form>
<?php
	}

	function PantallaNewSobreWithoutSocio(&$socios){
		global $task,$option,$Itemid, $artzaraadminurl;
		$sobreGif = $artzaraadminurl."images/sobre.gif";
?>
	<div id="ctr" align="center">
		<div class="login">
			<div class="login-form">
				<img src="<?php echo $sobreGif; ?>" alt="Sobre" />
				<form action="index.php" method="post" name="adminForm" id="adminForm">
					<div class="form-block">
						<div class="inputlabel">Codigo Socio</div>
						<div>
							<select name="id_socio" size="6">
							<?php
								foreach($socios as $socio){
							?>
									<option value="<?php echo $socio->id; ?>"><?php echo $socio->nick ."(".$socio->nombre.")"; ?></option>
							<?php
								}
							?>
							</select>
						</div>						
						<div align="left">
							<input type="submit" name="submit" class="button" value="Enviar" />
						</div>
					</div>
					<input type="hidden" name="task" value="<?php echo $task; ?>"/>
					<input type="hidden" name="accion" value="newSobre"/>
					<input type="hidden" name="option" value="<?php echo $option; ?>"/>
					<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
				</form>
			</div>
			<div class="login-text">
				<div class="ctr">
					<img src="administrator/templates/mambo_admin/images/security.png" width="64" height="64" alt="security" />
				</div>
				<p>Selecciona un socio!</p>
				<p>ON EGIN!</p>
			</div>
			<div class="clr"></div>
		</div>
	</div>
<?php
	}
?>
