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

	function PantallaMainSalidas()
	{
		global $option,$Itemid;
		$link = "index.php?option=$option&Itemid=$Itemid";
?>
		<table width="100%" class="cpanel">
		<tr>
			<td align="center" width="33%">
			<a href="<?php echo $link; ?>&task=151" style="text-decoration:none;">
			<img src="administrator/images/templatemanager.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>DONACIONES ITEMS</h2>
			</a>
			</td>

			<td align="center" width="33%">
			<a href="<?php echo $link; ?>&task=152" style="text-decoration:none;">
			<img src="administrator/images/query.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>LISTADO DONACIONES</h2>
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

	function NavSalidas(){
		global $option,$Itemid;
?>
		<table border='0' cellpadding='5' cellspacing='5' width='100%'>
		<tr>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid&task=151"; ?>" class='buttonbar'>Salidas</a>
			</td>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid&task=152"; ?>" class='buttonbar'>Listado Donaciones</a>
			</td>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid"; ?>" class='buttonbar'>Inicio</a>
			</td>
		</tr>
		</table>
<?php
	}

	function ShowSalidas(&$salidasNotIndexed){
		global $option,$Itemid,$task;

		$salidas = array_values($salidasNotIndexed);
?>
		<script language="JavaScript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if(pressbutton == 'remove'){
				if (confirm('Are you sure you want to delete selected items?'))
					setact(pressbutton);
				return;
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
<br />
<form action="index.php" method="post" name="adminForm">
	<table cellpadding="4" cellspacing="0" border="0" width="100%">
		<tr>
			<td width="100%" class="sectionname">LISTADO
			</td>
		</tr>
	</table>

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<tr>
			<th width="2%" class="title">#</th>
			<th width="3%" class="title">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($salidas); ?>);" />
			</th>
			<th width="5%" class="title">Id</th>
			<th width="90%" class="title">Nombre</th>
		</tr>
<?php
		$k = 0;
		for($i=0; $i < count( $salidas ); $i++)
		{
			$salida = $salidas[$i];
?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $i+1;?></td>
			<td><input type="checkbox" id="cb<?php echo $i;?>" name="id[]" value="<?php echo $salida->id; ?>" onclick="isChecked(this.checked);" /></td>
			<td><a href="javascript: void(0);" onclick="return enviarItem('cb<?php echo $i;?>','edit')"><?php echo $salida->id; ?></a></td>
			<td><?php echo $salida->name; ?></td>
		</tr>
<?php
			$k = 1 - $k;
		}
?>
	</table>
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="<?php echo $task; ?>"/>
	<input type="hidden" name="accion" value=""/>
	</form>
<?php
	}

	function FormularioSalida(&$salida){
		global $task,$option,$Itemid;

		if($salida===null)
		{
			$id = 0;
			$name = "";
		}
		else
		{
			$id = $salida->id;
			$name = $salida->name;
		}
?>

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
<form action="index.php" method="post" name="adminForm">
	<table width="100%" class="adminform">
		<tr>
			<th colspan="2">
			Detalles de Salida
			</th>
		</tr>
		<tr>
			<td>
			Nombre:
			</td>
			<td>
			<input type="text" name="name" class="inputbox" size="30" value="<?php echo $name;?>" />
			</td>
		</tr>
	</table>
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="option" value="<?php echo $option; ?>"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
	<input type="hidden" name="task" value="<?php echo $task; ?>"/>
	<input type="hidden" name="accion" value=""/>
</form>
<?php
	}

	function ShowSalidasHaber(&$datos,&$salidas,&$salidasTotales,&$pageNav,&$filtroInfo){
		global $option,$Itemid,$task;

		$link = "index.php?option=$option&Itemid=$Itemid&task=$task&accion=''".
		"&id_salida={$filtroInfo->id_salida}&fechadesde={$filtroInfo->fechaDesde}&fechahasta={$filtroInfo->fechaHasta}";
?>
		<script language="JavaScript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if(pressbutton == 'remove'){
				if (confirm('Are you sure you want to delete selected items?'))
					setact(pressbutton);
				return;
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
				mosMenuBar::publish('','recargar');
				mosMenuBar::spacer();
				mosMenuBar::trash();
				mosMenuBar::spacer("10");
				mosMenuBar::cancel();
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
				<tr>
					<td colspan="2">&nbsp;
					</td>
				</tr>
				</table>
			</td>
			<td width="40%" valign="top">
				<table class="adminform">
				<tr>
					<th colspan="2">
					Salidas (Totales entre fechas {<?php echo "{$filtroInfo->fechaDesde}} y {{$filtroInfo->fechaHasta}"; ?>})
					</th>
				</tr>
				<tr>
					<td>
					<br />
					<select name="id_salida" size="4">
<?php
						foreach($salidas as $salida){
							$selected = ($filtroInfo->id_salida == $salida->id)?("selected"):("");
?>
							<option value="<?php echo $salida->id; ?>" <?php echo $selected; ?>>
								<?php echo $salida->name.": ".$salidasTotales["{$salida->id}"]." euro"; ?>
							</option>
<?php
						}
?>
					</select>
					</td>
					<td>
						Leyenda Salidas
						<ul>
							<?php
							foreach($salidas as $salida){
?>
								<li><?php echo $salida->id.": ".$salida->name; ?></li>
<?php
							}
?>
						</ul>
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
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($datos); ?>);" />
			</th>
			<th width="5%" class="title">Id</th>
			<th width="10%" class="title">id Sobre</th>
			<th width="30%" class="title">Importe</th>
			<th width="10%" class="title">id Salida</th>
			<th width="40%" class="title">Fecha</th>
		</tr>
<?php
		$k = 0;
		$total = 0;
		for($i=0; $i < count( $datos ); $i++)
		{
			$entrada = $datos[$i];
?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $i+$pageNav->limitstart+1;?></td>
			<td><input type="checkbox" id="cb<?php echo $i;?>" name="id[]" value="<?php echo $entrada->id; ?>" onclick="isChecked(this.checked);" /></td>
			<td><a href="javascript: void(0);" onclick="return enviarItem('cb<?php echo $i;?>','edit')"><?php echo $entrada->id; ?></a></td>
			<td><?php echo $entrada->id_sobre; ?></td>
			<td><?php echo $entrada->importe; ?></td>
			<td><?php echo $entrada->id_salida; ?></td>
			<td><?php echo $entrada->inputdate; ?></td>
		</tr>
<?php
			$k = 1 - $k;
			$total = $total + $entrada->importe;
		}
?>
		<tr>
			<th align="center" colspan="7"> Total (en esta pagina):<?php echo $total; ?></th>
		</tr>
		<tr>
			<th align="center" colspan="7"> <?php echo $pageNav->writePagesLinks($link); ?></th>
		</tr>
		<tr>
			<td align="center" colspan="7"> <?php echo $pageNav->writePagesCounter(); ?></td>
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
