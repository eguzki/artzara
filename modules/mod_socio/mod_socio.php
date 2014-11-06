<?php
/*
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2004 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

/*
 * mod_block_random.php
 *
 * This Mambo module works in conjunction with Gallery integrated into,
 * or "embedded" in your Mambo site.
 * This module selects a random photo for display.  It will only display photos
 * from albums that are visible to the public.  It will not display hidden
 * photos.
 *
 * Once a day (or whatever you set CACHE_EXPIRED to) we scan all albums and
 * create a cache file listing each public album and the number of photos it
 * contains.  For all subsequent attempts we use that cache file.  This means
 * that if you change your albums around it may take a day before this block
 * starts (or stops) displaying them.
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
global $mosConfig_absolute_path, $mainframe;

$artzaraadminpath = $mosConfig_absolute_path.'/components/com_artzaraadmin/';
require_once ( $artzaraadminpath."artzara.ddbb.php" );
$userMambo = $mainframe->getUser();

$user = &DDBB::obtenerSocioFromMambo($userMambo->id);

if(isset($user) && $user!=null){
	echo "<p>KAIXO {$user->nick}</p>";
	if($user->haber < 0)
		$color = "red";
	else
		$color = "green";
	echo "<p><b><font color=\"$color\">HABER: {$user->haber}</b></font></p>";
}
else{
	echo "Usuario no registrado";
}

?>
