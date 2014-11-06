<?php
/**
* @version $Id: ARTZARAUSER.html.php
* @package Mambo_4.5.1
* @copyright (C) 2000 - 2004 Miro International Pty Ltd
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
$artzaraadminurl = $mosConfig_live_site.'/components/com_artzaraadmin/';
?>

<!-- libreria de javascript -->
<script language="JavaScript1.2" src="<?php echo $artzaraadminurl;?>js/artzaraadminjavascript.js" type="text/javascript"></script>
<script language="JavaScript1.2" src="<?php echo $artzarauserurl;?>js/artzarauserjavascript.js" type="text/javascript"></script>

<?php

/**
* @package Mambo_4.5.1
*/
function MenuPrincipal()
{
	global $option,$Itemid,$user;
	$link = "index.php?option=$option&Itemid=$Itemid";

?>
	<table class="adminheading" border="0">
		<tr>

			<th class="cpanel">
			Menu Principal
			</th>
		</tr>
	</table>

	<table width="100%" class="cpanel">
		<tr>
			<td align="center" width="25%">
			<a href="<?php echo $link; ?>&task=110&id_socio=<?php echo $user->id?>" style="text-decoration:none;">
			<img src="administrator/images/messaging.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>NUEVO SOBRE</h2>
			</a>
			</td>

			<td align="center" width="25%">
			<a href="<?php echo $link; ?>&task=120" style="text-decoration:none;">
			<img src="administrator/images/support.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>INF. SOCIO</h2>
			</a>
			</td>

			<td align="center" width="25%">
			<a href="<?php echo $link; ?>&task=130" style="text-decoration:none;">
			<img src="administrator/images/searchtext.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>SOBRES</h2>
			</a>
			</td>

			<td align="center" width="25%">
			<a href="index.php?option=logout" style="text-decoration:none;">
			<img src="administrator/images/cancel_f2.png" width="48px" height="48px" align="middle" border="0"/>
			<br />
			<h2>SALIR</h2>
			</a>
			</td>
	</tr>
	</table>
<?php
}

function NavUser(){
		global $option,$Itemid,$user;
?>
		<table border='0' cellpadding='5' cellspacing='5' width='100%'>
		<tr>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid&task=110&id_socio={$user->id}"; ?>" class='buttonbar'>Nuevo Sobre</a>
			</td>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid&task=120"; ?>" class='buttonbar'>Socio Info</a>
			</td>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid&task=130"; ?>" class='buttonbar'>Listado Sobres</a>
			</td>
			<td><a href="<?php echo "index.php?option=$option&Itemid=$Itemid"; ?>" class='buttonbar'>Menu Principal</a>
			</td>
		</tr>
		</table>
<?php
	}

//Por ahora no se pone
function ShowUserMensajeList(&$mensajes,&$pageNav,&$filtroInfo,&$socios){
	global $option,$Itemid,$task,$user;

	$link = "index.php?option=$option&Itemid=$Itemid&task=$task&accion=\"\"".
		"&id_destino={$filtroInfo->id_destino}&fechadesde={$filtroInfo->fechaDesde}".
		"&fechahasta={$filtroInfo->fechaHasta}";
?>
	<script language="JavaScript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if(pressbutton == 'remove'){
			if (confirm("No podras eliminar los mensajes publicos aunque lo intentes. \nAre you sure you want to delete selected items?"))
				setact(pressbutton);
		}
		setact(pressbutton);
	}
	</script>

	<table>
		<tr>
		<td width="50%">
			<table class="adminheading">
				<tr>
					<th>
					Lista Mensajes
					</th>
				</tr>
			</table>
		</td>
		<td width="50%">
<?php
			mosMenuBar::startTable();
			mosMenuBar::spacer();
			GenericLib::myIcono( '', 'publish.png', 'publish_f2.png', 'NuevoListado', false );
			mosMenuBar::spacer();
			mosMenuBar::addNew();
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
			<td>
				<table class="adminform">
				<tr>
					<th colspan="2">
					Filtro
					</th>
				</tr>
				<tr>
					<td>
					<input type="radio" name="id_destino" value="0" <?php if(!$filtroInfo->id_destino) echo "checked=\"checked\""?> class="inputbox" size="1" />TODOS
					<input type="radio" name="id_destino" value="<?php echo $user->id; ?>" <?php if($filtroInfo->id_destino) echo "checked=\"checked\""?> class="inputbox" size="1" />PARA MI
					</td>
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
		</tr>
	</table>
	<table cellpadding="4" cellspacing="0" border="0" width="100%">
		<tr>
			<td width="100%" class="sectionname"><img src="images/mos_gel.png" width="70" height="67" align="middle" />LISTADO
			</td>
			<td nowrap>Display #</td>
			<td> <?php echo $pageNavP->writeLimitBox($link); ?> </td>
		</tr>
	</table>

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<tr>
			<th width="2%" class="title">#</th>
			<th width="3%" class="title">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($mensajes); ?>);" />
			</th>
			<th width="5%" class="title">Id</th>
			<th width="15%" class="title">Remite</th>
			<th width="15%" class="title">Destino</th>
			<th width="45%" class="title">Titulo</th>
			<th width="5%" class="title">Visto</th>
			<th width="10%" class="title">Fecha</th>
		</tr>
<?php
		$k = 0;
		for($i=0; $i < count( $mensajes ); $i++)
		{
			$mensaje = $mensajes[$i];
			if($mensaje->id_destino){
				$socioDestino = "YO";
			}
			else
				$socioDestino = "A TODOS";

			$socio =  $socios["{$mensaje->id_remite}"];
			$socioRemite = $socio->nick."( ".$socio->nombre.")";

			$vistoImg = $mensaje->visto ? 'tick.png' : 'publish_x.png';
?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center"><?php echo $i+$pageNav->limitstart+1;?></td>
			<td align="center"><input type="checkbox" id="cb<?php echo $i;?>" name="id[]" value="<?php echo $mensaje->id; ?>" onclick="isChecked(this.checked);" /></td>
			<td align="center"><a href="javascript: void(0);" onclick="return enviarItem('cb<?php echo $i;?>','edit')"><?php echo $mensaje->id; ?></a></td>
			<td align="center"><?php echo $socioRemite; ?></td>
			<td align="center"><?php echo $socioDestino; ?></td>
			<td align="center"><?php echo $mensaje->subject; ?></td>
			<td align="center"><img src="images/<?php echo $vistoImg;?>" width="12" height="12" border="0"/></td>
			<td align="center"><?php echo $mensaje->inputdate; ?></td>
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
