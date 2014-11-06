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
	function FormularioSocioInfo($socio)
	{
		global $task,$option,$Itemid,$artzaraadminpath, $artzaraadminurl,$imagenX;

?>
	<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == "exit") {
				submitform( pressbutton );
				return;
			}

			if(form.password.value != ""){
				if (!confirm('Tu nuevo Password es: '+form.led.value+' . Continuar?'))
					return;
			}

			// do field validation
			setact( pressbutton );
		}
	</script>

	<table class="adminheading">
			<tr>
				<td align="right">
<?php
				mosMenuBar::startTable();
				mosMenuBar::spacer();
				mosMenuBar::save();
				mosMenuBar::spacer("10");
				mosMenuBar::cancel('exit','Volver');
				mosMenuBar::endTable();
?>
				</td>
			</tr>
		</table>
<br />
	<table width="100%">
		<tr>
			<td width="60%" valign="top">
				<table class="adminform">
				<tr>
					<th colspan="2">
					Socio Numero: <?php echo $socio->id;?>
					</th>
				</tr>
				<tr>
					<td>
					Nombre:
					</td>
					<td>
					<?php echo $socio->nombre;?>
					</td>
				</tr>
				<tr>
					<td>
					Apellidos:
					</td>
					<td>
					<?php echo $socio->apellidos; ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<img src="<?php echo (($socio->foto=='')?($imagenX):($artzaraadminurl."images/".$socio->foto)); ?>" height="100" width="100"/>
					</td>
				</tr>
				<tr>
					<td>
					Direccion:
					</td>
					<td>
						<?php echo $socio->direccion; ?>
					</td>
				</tr>
				<tr>
					<td>
					Dni:
					</td>
					<td>
						<?php echo $socio->dni; ?>
					</td>
				</tr>
				<tr>
					<td>
					Telefono:
					</td>
					<td>
						<?php echo $socio->telefono; ?>
					</td>
				</tr>
				<tr>
					<td>
					Movil:
					</td>
					<td>
						<?php echo $socio->movil; ?>
					</td>
				</tr>
				<tr>
					<td>
					Email:
					</td>
					<td>
						<?php echo $socio->email;?>
					</td>
				</tr>
				<tr>
					<td>
					Haber:
					</td>
					<td>
						<?php echo $socio->haber?>
					</td>
				</tr>
				</table>
			</td>
			<td width="40%" valign="top">
				<table class="adminform">
				<form action="index.php" method="post" enctype="multipart/form-data" name="adminForm">
				<tr>
					<th colspan="2">
					Usuario Mambo :<?php echo $socio->id_mambo;?>
					</th>
				</tr>
				<tr>
					<td>
					Nick:
					</td>
					<td>
					<?php echo $socio->nick;?>
					</td>
				</tr>
				<tr>
					<td>
					Idioma:
					</td>
					<td>
						<select name="idioma" size="2">
							<option <?php if(trim($socio->idioma)=="eu") echo "selected";?> value="eu">Euskera</option>
							<option <?php if(trim($socio->idioma)=="es") echo "selected";?> value="es">Castellano</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
					Clave de Entrada:
					</td>
					<td>
						<p>Si no lo tocas no se cambia</p>
						<input name="password" type="password" class="inputbox" size="15" />
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center" width="200">
						<?php showKeypad('adminForm','password'); ?>
					</td>
				</tr>
				<input type="hidden" name="option" value="<?php echo $option; ?>"/>
				<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
				<input type="hidden" name="task" value="<?php echo $task; ?>"/>
				<input type="hidden" name="accion" value=""/>
				</form>
				</table>
			</td>
		</tr>
	</table>
<?php
}
?>
