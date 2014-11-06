<?php
/**
* @version $Id: index.php,v 1.36 2004/09/28 03:02:30 rcastley Exp $
* @package Mambo_4.5.1
* @copyright (C) 2000 - 2004 Miro International Pty Ltd
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.');

//Compruebo si ya se ha insertado el usuario admin
$query = "SELECT id FROM #__artzara_socios WHERE nombre = 'Administrator' AND nick= 'admin'";

$database->setQuery( $query );
$rows = $database->loadObjectList();
if($rows == NULL)
{
	// $rows no esta a null, es un array vacio!!!
	// No existe
	$query = "SELECT id FROM #__users WHERE name = 'Administrator' AND username= 'admin'";
	$error = false;
	$database->setQuery( $query );
	$rows = $database->loadObjectList();
	if($rows === NULL)
	{
		$error = true;
	}
	else{
		$row = $rows[0];

		$mamboSocio = new mosUser($database);
		if(!$mamboSocio->load( $row->id ))
		{
			$error = true;
		}
		else{
			$socioAdmin = new mosArtzaraSocios( $database );
			$socioAdmin->id = 0;
			$socioAdmin->nombre = "Administrator";
			$socioAdmin->apellidos= "Eguzki Astiz 2005";
			$socioAdmin->nick="admin";
			$socioAdmin->foto="";
			$socioAdmin->direccion="";
			$socioAdmin->dni="";
			$socioAdmin->telefono="";
			$socioAdmin->movil="";
			$socioAdmin->id_mambo=$mamboSocio->id;
			$socioAdmin->acceso_admin=1;
			$socioAdmin->email=$mamboSocio->email;
			$socioAdmin->idioma='eu';
			$socioAdmin->haber=0;
			$socioAdmin->activo=1;
			$socioAdmin->fecha_alta = date('Y-m-d H:i:s');
			if(!$socioAdmin->store())
			{
				$error = true;
			}

			$salidaArtzara = new mosArtzaraSalidas( $database );
			$salidaArtzara->id = 0;
			$salidaArtzara->name = _ARTZARA;
			if(!$salidaArtzara->store())
			{
				$error = true;
			}

		}
	}

	if($error){
		echo "<p>Se ha producido un error. Contacta con el administrador</p>";
	}
	else{
		echo "<p>TODO DE PUTA MADRE!!!</p>";
		echo "<p>BORRA EL DIRECTORIO INSTALLATION o no podras empezar con la aplicacion</p>";
	}
}
else
{
	// Existe
	echo "<p>Borra el directorio de installation, burro!!!</p>";
}

?>
