<?php
/**
* artzara - A Mambo 4.5 template
* @version 2.0
* @package artzara
* @copyright (C) 2004 by Arthur Konze - All rights reserved!
* @license http://www.konze.de/content/view/8/26/ Copyrighted Commercial Software
*/
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
$iso = split( '=', _ISO );
echo '<?xml version="1.0" encoding="'. $iso[1] .'"?' .'>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php if ( $my->id ) initEditor(); ?>
    <meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
    <?php mosShowHead(); ?>
    <link rel="shortcut icon" href="<?php echo $mosConfig_live_site;?>/images/favicon.ico" />
	 <link rel="stylesheet" type="text/css" href="<?php echo $mosConfig_live_site;?>/templates/artzara/css/template_css.css" />
</head>
<body>
<a name="up" id="up"></a>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td height="79" bgcolor="#8ec8e9" valign="center">
		<img width="1024" height="79" src="<?php echo $mosConfig_live_site;?>/templates/artzara/images/logo3.gif">
	</td>
</tr>
<tr>
    <td valign="top" background="<?php echo $mosConfig_live_site;?>/templates/artzara/images/topshadow.gif"><img src="<?php echo
$mosConfig_live_site;?>/templates/artzara/images/topshadow.gif" width="10" height="14"></td>
 </tr>
</table>
<table border="0" cellpadding="5" cellspacing="0" width="100%">
  <tr>
    <!-- LEFT Modules -->
    <?php
      if (mosCountModules('left')>0) {
        ?>
        <td width="180" valign="top">
          <?php mosLoadModules ( "left" ); ?>
        </td>
        <?php
      }
    ?>
    <td valign="top">
      <?php if (mosCountModules('top')>0) mosLoadModules('top','true'); ?>
      <!-- Main Content Section -->
      <?php mosMainBody(); ?>
    </td>
  </tr>
</table>

<div align="center">
  <img src="<?php echo $mosConfig_live_site;?>/templates/artzara/images/arrow2up.gif" 
width="12" height="9" border="0" align="middle">  <font color="#999999" size="1" face="Verdana"><a 
href="<?php echo sefRelToAbs($_SERVER['REQUEST_URI']); ?>#up">top of page</a></font>  <img 
src="<?php echo $mosConfig_live_site;?>/templates/artzara/images/arrow2up.gif" width="12" 
height="9" align="middle"></div><!-- Ende des Contents - Anfang des Fusses -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top" background="<?php echo 
$mosConfig_live_site;?>/templates/artzara/images/bottom_snow.jpg"><img src="<?php echo 
$mosConfig_live_site;?>/templates/artzara/images/bottom_snow.jpg"></td>  </tr></table>
<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#CFE3EB">
  <tr align="center">
    <td>
		<p>Designed by Eguzki Astiz eguzki@bigfoot.com</p>
      <?php if ( mosCountModules ('banner') ) mosLoadModules( 'banner', -1 ); ?><p />
      <?php include_once( $GLOBALS['mosConfig_absolute_path'] . '/includes/footer.php' ); ?>
    </td>
  </tr>
</table>

</body>
</html>
