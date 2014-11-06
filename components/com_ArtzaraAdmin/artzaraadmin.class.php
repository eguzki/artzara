<?php
/**
* Content code
* @package hello_world
* @Copyright (C) 2004 Doyle Lewis
* @ All rights reserved
* @ hello_world is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version 1.0
**/

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' 
);
/**
* Artzara_grupos table class
*/
class mosArtzaraFamilia extends mosDBTable
{
  // INT(11) AUTO_INCREMENT
  var $id=null;
  // TEXT
  var $nombre=null;
   // TEXT
  var $imagefile=null;
  // TINYINT(1)
  var $published=null;

/**
* @param database A database connector object
*/
  function mosArtzaraFamilia( &$db )
  {
  	$this->mosDBTable( '#__artzara_familias', 'id', $db );
  }
}

/**
* Artzara_productos table class
*/
class mosArtzaraProducto extends mosDBTable
{
	// INT(11) AUTO_INCREMENT
  var $id=null;
	// INT
  var $id_grupo=null;
	// INT
  var $ref=null;
    // TEXT
  var $nombre=null;
   // TEXT
  var $imagefile=null;
  // TINYINT(1)
  var $published=null;
  // DOUBLE
  var $precio_compra=null;
  // DOUBLE
  var $iva=null;
  // DOUBLE
  var $venta_publico_dosis=null;
  // DOUBLE
  var $recargo_dosis=null;
  // INT
  var $num_dosis_por_botella=null;
   // TEXT
  var $proveedor=null;
   // TEXT
  var $telefono_contacto=null;
  // TEXT
  var $email=null;

/**
* @param database A database connector object
*/
  function mosArtzaraProducto( &$db )
  {
  	$this->mosDBTable( '#__artzara_productos', 'id', $db );
  }
}

/**
* Artzara_stock table class
*/
class mosArtzaraStock extends mosDBTable
{
  // INT(11) AUTO_INCREMENT
  var $id=null;
  // INT
  var $id_grupo=null;
   // INT
  var $id_prod=null;
  // INT
  var $cantidad=null;
  // INT
  var $dosis=null;
  // DATETIME
  var $fechamod=null;

/**
* @param database A database connector object
*/
  function mosArtzaraStock( &$db )
  {
  	$this->mosDBTable( '#__artzara_stock', 'id', $db );
  }
}

class mosArtzaraInventario extends mosDBTable
{
  // INT(11) AUTO_INCREMENT
  var $id=null;
  // INT
  var $id_grupo=null;
   // INT
  var $id_prod=null;
  // INT
  var $cantidad=null;
  // INT
  var $dosis=null;
  // Double
  var $total=null;
  // DATETIME
  var $inputdate=null;

/**
* @param database A database connector object
*/
  function mosArtzaraInventario( &$db )
  {
  	$this->mosDBTable( '#__artzara_inventario', 'id', $db );
  }
}

class mosArtzaraEntradas extends mosDBTable
{
  // INT(11) AUTO_INCREMENT
  var $id=null;
  // INT
  var $id_grupo=null;
   // INT
  var $id_prod=null;
  // INT
  var $cantidad=null;
  // DATETIME
  var $inputdate=null;

/**
* @param database A database connector object
*/
  function mosArtzaraEntradas( &$db )
  {
  	$this->mosDBTable( '#__artzara_entradas', 'id', $db );
  }
}

class mosArtzaraConsumos extends mosDBTable
{
  // INT(11) AUTO_INCREMENT
  var $id=null;
  // INT
  var $id_sobre=null;
  // INT
  var $id_grupo=null;
   // INT
  var $id_prod=null;
  // INT
  var $cantidad=null;
  // Double
  var $total=null;
  // DATETIME
  var $inputdate=null;

/**
* @param database A database connector object
*/
  function mosArtzaraConsumos( &$db )
  {
  	$this->mosDBTable( '#__artzara_consumos', 'id', $db );
  }
}

class mosArtzaraSocios extends mosDBTable
{
  // INT(11) AUTO_INCREMENT
  var $id=null;
  // TEXT(30)
  var $nombre=null;
  // TEXT(50)
  var $apellidos=null;
   // TEXT(20)
  var $nick=null;
  // TEXT
  var $foto=null;
   // TEXT(50)
  var $direccion=null;
  // TEXT(9)
  var $dni=null;
   // TEXT(12)
  var $telefono=null;
   // TEXT(12)
  var $movil=null;
   // INT
  var $id_mambo=null;
  // TINYINT(1)
  var $acceso_admin=null;
   // TEXT(30)
  var $email=null;
  // TEXT(9)
  var $idioma=null;
     // INT
  var $haber=null;
   // TINYINT(1)
  var $activo=null;
  // DATETIME
  var $fecha_alta=null;

/**
* @param database A database connector object
*/
  function mosArtzaraSocios( &$db )
  {
  	$this->mosDBTable( '#__artzara_socios', 'id', $db );
  }
}

class mosArtzaraCuentaSocios extends mosDBTable
{
  // INT(11) AUTO_INCREMENT
  var $id=null;
  // INT
  var $id_socio=null;
  // double
  var $saldo=null;
   // double
  var $pago=null;
  // DATETIME
  var $inputdate=null;
/**
* @param database A database connector object
*/
  function mosArtzaraCuentaSocios( &$db )
  {
  	$this->mosDBTable( '#__artzara_cuentasocios', 'id', $db );
  }
}

class mosArtzaraSobres extends mosDBTable
{
  // INT(11) AUTO_INCREMENT
  var $id=null;
  // INT
  var $id_socio=null;
  // double
  var $total=null;
   // double
  var $pago=null;
  // INT
  var $id_salida=null;
  // DATETIME
  var $inputdate=null;
    // DATETIME
  var $processdate=null;
  // TINYINT(1)
  var $state=null;

/**
* @param database A database connector object
*/
  function mosArtzaraSobres( &$db )
  {
  	$this->mosDBTable( '#__artzara_sobres', 'id', $db );
  }
}

class mosArtzaraSalidasHaber extends mosDBTable
{
  // INT AUTO_INCREMENT
  var $id=null;
  // INT
  var $id_sobre=null;
 // DATETIME
  var $inputdate=null;
   // DOUBLE
  var $importe=null;
  // INT
  var $id_salida=null;

/**
* @param database A database connector object
*/
  function mosArtzaraSalidasHaber( &$db )
  {
  	$this->mosDBTable( '#__artzara_salidashaber', 'id', $db );
  }
}

class mosArtzaraSalidas extends mosDBTable
{
  // INT AUTO_INCREMENT
  var $id=null;
  // TEXT
  var $name=null;

/**
* @param database A database connector object
*/
  function mosArtzaraSalidas( &$db )
  {
  	$this->mosDBTable( '#__artzara_salidas', 'id', $db );
  }
}

class mosArtzaraMensajes extends mosDBTable
{
  // INT AUTO_INCREMENT
  var $id=null;
  // TEXT
  var $msg=null;
  // VARCHAR(30)
   var $subject=null;
   // Remite
   var $id_remite = null;
  // Destino
  var $id_destino = null;
	// flag visto
  var $visto = null;
  // fecha
  var $inputdate=null;

/**
* @param database A database connector object
*/
  function mosArtzaraMensajes( &$db )
  {
  	$this->mosDBTable( '#__artzara_mensajes', 'id', $db );
  }
}

class MensajesFiltroInfo{
	var $id_remite = null;
	var $id_destino = null;
	var $fechaDesde = null;
	var $fechaHasta = null;
}

class SalidasHaberFiltroInfo{
	var $fechaDesde = null;
	var $fechaHasta = null;
	var $id_salida = 0;
}

class CuentaSociosFiltroInfo{
	var $idSocio = 0;
	var $fechaDesde = null;
	var $fechaHasta = null;

}

class SociosFiltroInfo{
	var $activo = 0; // 0.- Todos 1.- activos 2.- bloqueados
	var $debo=0; // 0.- Todos 1.- debo 2.-haber
}

class BodegaFiltroInfo{
	var $idFamilia = 0;
	var $idProducto = 0;
	var $nombreFamilia = null;
	var $nombreProducto = null;
	var $fechaDesde = null;
	var $fechaHasta = null;
}

class SobresFiltroInfo{
	var $idSocio = 0;
	var $fechaDesde = null;
	var $fechaHasta = null;
	var $state = null;
	var $debo=0;
}

class ConsumosInfo{
	var $id=null;
	// INT
	var $familyName=null;
	// INT
	var $productName=null;
	//INT
	var $cantidad=null;
	// DOUBLE
	var $importe=null;
}
?>
