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

	function ShowMensajeList(&$datos,&$socios,&$pageNav,&$filtroInfo){
		global $option,$Itemid,$task;

		$link = "index.php?option=$option&Itemid=$Itemid&task=$task&accion=list".
		"&id_remite={$filtroInfo->id_remite}&id_destino={$filtroInfo->id_destino}&fechadesde={$filtroInfo->fechaDesde}&fechahasta={$filtroInfo->fechaHasta}";
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
				mosMenuBar::addNew();
				mosMenuBar::spacer();
				mosMenuBar::trash();
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
					<th>
					Socio Remitente
					</th>
				</tr>
				<tr>
					<td>
					<br />
					<select name="id_remite" size="6">
						<option value="0">TODOS</option>
					<?php
						foreach($socios as $socio){
							$selected = ($filtroInfo->id_remite == $socio->id)?("selected"):("");
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
				<tr>
					<th>
					Socio Destinatario
					</th>
				</tr>
				<tr>
					<td>
					<br />
					<select name="id_destino" size="6">
						<option value="0">TODOS</option>
					<?php
						foreach($socios as $socio){
							$selected = ($filtroInfo->id_destino == $socio->id)?("selected"):("");
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
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($datos); ?>);" />
			</th>
			<th width="5%" class="title">Id</th>
			<th width="15%" class="title">Remite</th>
			<th width="15%" class="title">Destino</th>
			<th width="35%" class="title">Titulo</th>
			<th width="20%" class="title">Fecha</th>
			<th width="5%" class="title">Visto</th>
		</tr>
<?php
		$k = 0;
		for($i=0; $i < count( $datos ); $i++)
		{
			$mensaje = $datos[$i];
			if($mensaje->id_destino){
				$socio = $socios["{$mensaje->id_destino}"];
				$socioDestino = $socio->nick."( ".$socio->nombre.")";
			}
			else
				$socioDestino = "A TODOS";

			$socio =  $socios["{$mensaje->id_remite}"];
			$socioRemite = $socio->nick."( ".$socio->nombre.")";

			$vistoImg = $mensaje->visto ? 'tick.png' : 'publish_x.png';
?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $i+$pageNav->limitstart+1;?></td>
			<td><input type="checkbox" id="cb<?php echo $i;?>" name="id[]" value="<?php echo $mensaje->id; ?>" onclick="isChecked(this.checked);" /></td>
			<td><a href="javascript: void(0);" onclick="return enviarItem('cb<?php echo $i;?>','edit')"><?php echo $mensaje->id; ?></a></td>
			<td><?php echo $socioRemite; ?></td>
			<td><?php echo $socioDestino; ?></td>
			<td><?php echo $mensaje->subject; ?></td>
			<td><?php echo $mensaje->inputdate; ?></td>
			<td><img src="administrator/images/<?php echo $vistoImg;?>" width="12" height="12" border="0"/></td>
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
