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

	function PantallaMainSocios()
	{
		global $option,$Itemid;
		$link = "index.php?option=$option&Itemid=$Itemid";
?>
		<table width="100%" class="cpanel">
		<tr>
			<td align="center" width="33%">
			<a href="<?php echo $link; ?>&task=121" style="text-decoration:none;">
			<img src="administrator/images/user.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>SOCIOS</h2>
			</a>
			</td>

			<td align="center" width="33%">
			<a href="<?php echo $link; ?>&task=122" style="text-decoration:none;">
			<img src="administrator/images/edit_f2.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>CUENTAS SOCIOS</h2>
			</a>
			</td>

			<td align="center" width="33%">
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

	function NavSocios(){
		global $option,$Itemid;
?>
		<table border='0' cellpadding='5' cellspacing='5' width='100%'>
		<tr>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid&task=121"; ?>" class='buttonbar'>Socios</a>
			</td>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid&task=122"; ?>" class='buttonbar'>Listado Cuentas Socio</a>
			</td>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid"; ?>" class='buttonbar'>Inicio</a>
			</td>
		</tr>
		</table>
<?php
	}

	function ListaSociosMambo($socios){
		global $task,$option,$Itemid;
?>
		<form name="adminForm" method="post" action="index.php">
		<table width="99%">
		<tr>
			<td>
<?php
		echo "<select name=\"id_mambo\" size=\"10\">\n";
		foreach($socios as $socio){
			echo "<option value=\"{$socio->id}\" >{$socio->username} ({$socio->name})</option>\n";
		}
		echo "</select>";
?>
			</td>
			<td>
				<input name="enviarBtn" type="button" onClick="setact('new')" value="Nuevo Socio"/>
			</td>
		</tr>
		</table>
			<input type="hidden" name="task" value="<?php echo $task; ?>"/>
			<input type="hidden" name="accion" value=""/>
			<input type="hidden" name="option" value="<?php echo $option; ?>"/>
			<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
		</form>

<?php

	}

	function FormularioSocio($socio)
	{
		global $task,$option,$Itemid,$artzaraadminpath, $artzaraadminurl,$imagenX;

		if($socio->id)
		{
			$apellidos = $socio->apellidos;
			$foto = ($socio->foto=='')?($imagenX):$artzaraadminurl."images/".$socio->foto;
			$direccion = $socio->direccion;
			$dni = $socio->dni;
			$telefono = $socio->telefono;
			$movil = $socio->movil;
			$acceso_admin = $socio->acceso_admin;
			$idioma = $socio->idioma;
			$haber = $socio->haber;
			$fecha_alta = $socio->fecha_alta;
		}
		else
		{
			//Nuevo usuario
			$apellidos = "";
			$foto = $imagenX;
			$direccion = "";
			$dni = "";
			$telefono = "";
			$movil = "";
			$acceso_admin = 0;
			$idioma = "eu";
			$haber = 0;
			$fecha_alta = date('Y-m-d H:i:s');
		}
?>
	<script language="JavaScript1.2" type="text/javascript">
		function submitbutton(pressbutton)
		{
			var form = document.adminForm;
			if (pressbutton == "cancel") {
				setact( pressbutton );
				return;
			}

			// do field validation
			if (form.nombre.value == '') {
				alert( "Rellena el nombre, burro" );
			}
			else if(form.apellidos.value == ''){
				alert( "Rellena el los apellidos, burro" );
			}
			else if(form.nick.value == ''){
				alert( "Rellena el nick, burro" );
			}
			else if(form.haber.value == '' || isNaN(parseFloat(form.haber.value))){
				alert( "Dato Haber invalido" );
			}
			else if(form.password.value != ""){
				if (!confirm('Tu nuevo Password es: '+form.led.value+' . Continuar?'))
					return;
				setact( pressbutton );
			}
			}else {
				setact( pressbutton );
			}
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
		mosMenuBar::cancel();
		mosMenuBar::endTable();
?>
		</td>
	</tr>
	</table>
<br />
<form action="index.php" method="post" enctype="multipart/form-data" name="adminForm">
	<table width="100%">
		<tr>
			<td width="60%" valign="top">
				<table class="adminform">
				<tr>
					<th colspan="2">
					Detalles de Usuario
					</th>
				</tr>
				<tr>
					<td>
					Nombre:
					</td>
					<td>
					<input type="text" name="nombre" class="inputbox" size="20" value="<?php echo $socio->nombre;?>" />
					</td>
				</tr>
				<tr>
					<td>
					Apellidos:
					</td>
					<td>
					<input type="text" name="apellidos" class="inputbox" size="50" value="<?php echo $apellidos; ?>" />
					</td>
				</tr>
				<tr>
					<td>
					Foto:
					</td>
					<td>
						<input type="file" name="foto" accept="image/gif,image/jpeg"/>
					</td>
				</tr>
				<tr>
					<td align="right">
						<img src="<?php echo $foto; ?>" height="80" width="80"/>
					</td>
				</tr>
				<tr>
					<td>
					Direccion:
					</td>
					<td>
					<input type="text" name="direccion" class="inputbox" size="50" value="<?php echo $direccion; ?>" />
					</td>
				</tr>
				<tr>
					<td>
					Dni:
					</td>
					<td>
					<input type="text" name="dni" class="inputbox" size="9" value="<?php echo $dni; ?>" />
					</td>
				</tr>
				<tr>
					<td>
					Telefono:
					</td>
					<td>
					<input type="text" name="telefono" class="inputbox" size="12" value="<?php echo $telefono; ?>" />
					</td>
				</tr>
				<tr>
					<td>
					Movil:
					</td>
					<td>
					<input type="text" name="movil" class="inputbox" size="12" value="<?php echo $movil; ?>" />
					</td>
				</tr>
				<tr>
					<td>
					Email:
					</td>
					<td>
					<input type="text" name="email" class="inputbox" size="30" value="<?php echo $socio->email;?>" />
					</td>
				</tr>
				<tr>
					<td>
					Haber:
					</td>
					<td>
					<input type="text" name="haber" class="inputbox" size="14" value="<?php echo $haber?>" />
					</td>
				</tr>
				<tr>
					<td>
					<br />
					</td>
				</tr>
				</table>
			</td>
			<td width="40%" valign="top">
				<table class="adminform">
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
					<input type="text" name="nick" class="inputbox" size="20" value="<?php echo $socio->nick;?>" />
					</td>
				</tr>
				<tr>
					<td>
					Acceso Administracion:
					</td>
					<td>
						<input type="radio" name="acceso_admin" value="0" <?php if(!$acceso_admin) echo "checked=\"checked\""?> class="inputbox" size="1" />No
						<input type="radio" name="acceso_admin" value="1" <?php if($acceso_admin) echo "checked=\"checked\""?> class="inputbox" size="1" />Yes
					</td>
				</tr>
				<tr>
					<td>
					Idioma:
					</td>
					<td>
						<select name="idioma" size="3">
							<option <?php if($idioma=="eu") echo "SELECTED";?> value="eu">Euskera</option>
							<option <?php if($idioma=="es") echo "SELECTED";?> value="es">Castellano</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
					Bloqueado:
					</td>
					<td>
						<input type="radio" name="activo" value="0" <?php if(!$socio->activo) echo "checked=\"checked\""?> class="inputbox" size="1" />Yes
						<input type="radio" name="activo" value="1" <?php if($socio->activo) echo "checked=\"checked\""?> class="inputbox" size="1" />No
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
				</table>
			</td>
		</tr>
	</table>
	<input type="hidden" name="id" value="<?php echo $socio->id; ?>"/>
	<input type="hidden" name="id_mambo" value="<?php echo $socio->id_mambo; ?>"/>
	<input type="hidden" name="option" value="<?php echo $option; ?>"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
	<input type="hidden" name="task" value="<?php echo $task; ?>"/>
	<input type="hidden" name="accion" value=""/>
</form>
<?php
	}

	function ShowSocios(&$socios,&$pageNav,&$filtroInfo){
		global $option,$Itemid,$task;

		$link = "index.php?option=$option&Itemid=$Itemid&task=$task".
		"&activo={$filtroInfo->activo}&debo={$filtroInfo->debo}";
?>
		<script language="JavaScript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if(pressbutton == 'remove'){
				if (confirm('Are you sure you want to delete selected items?'))
					setact(pressbutton);
			}

			setact(pressbutton);
		}
		</script>

		<table class="adminheading">
			<tr>
				<td align="right">
<?php
				mosMenuBar::startTable();
				mosMenuBar::spacer();
				GenericLib::myIcono( '', 'publish.png', 'publish_f2.png', 'NuevoListado', false );
				mosMenuBar::spacer();
				mosMenuBar::addNew();
				mosMenuBar::spacer("10");
				mosMenuBar::trash();
				mosMenuBar::spacer("10");
				mosMenuBar::cancel();
				mosMenuBar::endTable();
?>
				</td>
			</tr>
		</table>
<br>
<form action="index.php" method="post" name="adminForm">
	<table class="adminform">
	<tr>
		<th>
		Filtro
		</th>
	</tr>
	<tr>
		<td>
		<input type="radio" name="activo" value="1" <?php if($filtroInfo->activo == 1) echo "checked=\"checked\"";?> class="inputbox" size="1" />Acivos
		<input type="radio" name="activo" value="2" <?php if($filtroInfo->activo == 2) echo "checked=\"checked\"";?> class="inputbox" size="1"/>Bloqueados
		<input type="radio" name="activo" value="0" <?php if($filtroInfo->activo != 1 && $filtroInfo->activo !=2) echo "checked=\"checked\"";?> class="inputbox" size="1" />TODOS
		</td>
	</tr>
	<tr>
		<td>
		<input type="radio" name="debo" value="1" <?php if($filtroInfo->debo == 1) echo "checked=\"checked\"";?> class="inputbox" size="1" />Morosos
		<input type="radio" name="debo" value="2" <?php if($filtroInfo->debo == 2) echo "checked=\"checked\"";?> class="inputbox" size="1"/>Saldo No Negativo
		<input type="radio" name="debo" value="0" <?php if($filtroInfo->debo == 0) echo "checked=\"checked\"";?> class="inputbox" size="1" />TODOS
		</td>
	</tr>
	<tr>
		<td>&nbsp;
		</td>
	</tr>
	</table>
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
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($socios); ?>);" />
			</th>
			<th width="5%" class="title">Id</th>
			<th width="30%" class="title">Nombre</th>
			<th width="5%" class="title">Acceso Admin</th>
			<th width="10%" class="title">Idioma</th>
			<th width="5%" class="title">Activo</th>
			<th width="20%" class="title">Alta</th>
			<th width="20%" class="title">Saldo</th>
		</tr>
<?php
		$k = 0;
		for($i=0; $i < count( $socios ); $i++)
		{
			$socio = $socios[$i];
			$activoImg = $socio->activo ? 'tick.png' : 'publish_x.png';
			$adminImg = $socio->acceso_admin ? 'tick.png' : 'publish_x.png';
?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $i+$pageNav->limitstart+1;?></td>
			<td><input type="checkbox" id="cb<?php echo $i;?>" name="id[]" value="<?php echo $socio->id; ?>" onclick="isChecked(this.checked);" /></td>
			<td><a href="javascript: void(0);" onclick="return enviarItem('cb<?php echo $i;?>','edit')"><?php echo $socio->id; ?></a></td>
			<td><?php echo $socio->nick."( ".$socio->nombre.")"; ?></td>
			<td><img src="administrator/images/<?php echo $adminImg;?>" width="12" height="12" border="0"/></td>
			<td><?php echo $socio->idioma; ?></td>
			<td><img src="administrator/images/<?php echo $activoImg;?>" width="12" height="12" border="0"/></td>
			<td><?php echo $socio->fecha_alta; ?></td>
			<td><?php echo $socio->haber; ?></td>
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

	function ShowCuentasSocios(&$cuentasSocios,&$socios,&$pageNav,&$filtroInfo){
		global $option,$Itemid,$task;

		$link = "index.php?option=$option&Itemid=$Itemid&task=$task&accion=\"show\"".
		"&idSocio={$filtroInfo->idSocio}&fechadesde={$filtroInfo->fechaDesde}&fechahasta={$filtroInfo->fechaHasta}";
?>
		<script language="JavaScript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if(pressbutton == 'remove'){
				if (confirm('Are you sure you want to delete selected items?'))
					setact(pressbutton);
			}

			setact(pressbutton);
		}
		</script>

		<table class="adminheading">
			<tr>
				<td align="right">
		<?php
				mosMenuBar::startTable();
				mosMenuBar::spacer();
				GenericLib::myIcono( '', 'publish.png', 'publish_f2.png', 'NuevoListado', false );
				mosMenuBar::spacer();
				mosMenuBar::trash();
				mosMenuBar::spacer("10");
				mosMenuBar::cancel();
				mosMenuBar::endTable();
		?>
				</td>
			</tr>
		</table>
<br>
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
					<select name="idSocio" size="6">
					<?php
						foreach($socios as $socio){
							$selected = ($filtroInfo->idSocio == $socio->id)?("selected"):("");
					?>
							<option value="<?php echo $socio->id; ?>" <?php echo $selected; ?>>
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
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($cuentasSocios); ?>);" />
			</th>
			<th width="5%" class="title">Id</th>
			<th width="30%" class="title">Nombre Socio</th>
			<th width="20%" class="title">Ultimo movimiento (euros)</th>
			<th width="20%" class="title">Saldo(euros)</th>
			<th width="20%" class="title">Fecha</th>
		</tr>
<?php
		$k = 0;
		for($i=0; $i < count( $cuentasSocios ); $i++)
		{
			$cuentasSocio = $cuentasSocios[$i];
			$socio = $socios["{$cuentasSocio->id_socio}"];
?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $i+$pageNav->limitstart+1;?></td>
			<td><input type="checkbox" id="cb<?php echo $i;?>" name="id[]" value="<?php echo $cuentasSocio->id; ?>" onclick="isChecked(this.checked);" /></td>
			<td><?php echo $cuentasSocio->id; ?></td>
			<td><?php echo $socio->nick."( ".$socio->nombre.")"; ?></td>
			<td><?php if($cuentasSocio->pago>0) echo "+";echo $cuentasSocio->pago; ?></td>
			<td><?php echo $cuentasSocio->saldo; ?></td>
			<td><?php echo $cuentasSocio->inputdate; ?></td>
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
?>
