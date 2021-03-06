<?php
/**
* Artzara - A Mambo Forms Application
* @version 1.0.0
* @package Artzara
* @copyright (C) 2004 by Peter Koch
* @license Released under the terms of the GNU General Public License
**/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
// Variables Globales


class ArtzaraAdminLib
{
	function GuardarFamilia()
	{
		global $database, $logFile, $imagenX, $artzaraadminpath, $artzaraadminurl;
		$resultado = FALSE;
		$newImageName = "";

		// Se puede hacer un bind, pero hay que andar al loro con el imagefile.

		$row = new mosArtzaraFamilia( $database );

		if (!$row->bind( $_POST ))
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}

		if(!intval(mosGetParam( $_POST,'published',0)))
		{
			$row->published = 0;
		}

		$nombre_imagen = $_FILES['nombre_imagen']['name'];

		if($nombre_imagen == '')
		{
			$logFile->writeLog("Guardar Familia: nombre_imagen vacia!");
			// No se envia imagen. La imagen queda como esta, luego si existe id,
			// hay que tomar su valor y guardarlo, sino se borrara
			if($row->id)
			{
				// estamos actualizando, asi que hay que mantener el valor
				$row2 = new mosArtzaraFamilia( $database );
				$row2->load( $row->id );
				$row->imagefile = $row2->imagefile;
			}
			else
			{
				// nueva imagen, sin imagen, HTML se encarga de poner la X
				$row->imagefile = "";
			}
		}
		else
		{
			$logFile->writeLog("Guardar Familia: nombre_imagen:".$nombre_imagen.": ".$_FILES['nombre_imagen']['type']);
			$prefijo = date("zHis");
			$imagenPath = $artzaraadminpath."images/".$prefijo.basename($nombre_imagen);

			// elimino la imagen anterior si esta existiese
			if($row->id)
			{
				//Estamos actualizando imagen
				$row2 = new mosArtzaraFamilia( $database );
				$row2->load( $row->id );
				if($row2->imagefile!='' && !unlink($artzaraadminpath."images/".$row2->imagefile))
						$logFile->writeLog("No se ha podido eliminar: ".$artzaraadminpath."images/".$row2->imagefile);
			}

			// Se envia imagen, hay que descargarla y poner el path online de la foto.
			if (is_uploaded_file($_FILES['nombre_imagen']['tmp_name']))
			{
				if($_FILES['nombre_imagen']['type'] == 'image/gif' ||
					$_FILES['nombre_imagen']['type'] == 'image/pjpeg' ||
					$_FILES['nombre_imagen']['type'] == 'image/jpeg')
				{
					// copiarlo con otro nombre para que cada familia tenga su imagen asociada
					if(!copy($_FILES['nombre_imagen']['tmp_name'],$imagenPath))
					{
						$logFile->writeLog("Error: No se pudo copiar el archivo:".$imagenPath);
						return FALSE;
					}
				}
				else
				{
					$logFile->writeLog("Error: Tipo fichero incorrecto:". $_FILES['nombre_imagen']['type']);
					return FALSE;
				}
			}
			else
			{
			$logFile->writeLog("Error: Possible file upload attack. Filename: " . $_FILES['nombre_imagen']['name']);
				return FALSE;
			}
			$row->imagefile = $prefijo.basename($nombre_imagen);
			$logFile->writeLog("GuardarFamilia: Imagen cargada: ".$row->imagefile);
		}

		if (!$row->store())
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}
		// Tenemos los datos grabados

		$resultado = TRUE;

		return $resultado;
	}

	function EliminarFamilia($idFamilia)
	{
		global $database, $artzaraadminpath, $logFile;
		$result = FALSE;

		$row2 = new mosArtzaraFamilia( $database );
		if(!($row2->load( $idFamilia )))
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}

		if($row2->imagefile!='' && !unlink($artzaraadminpath."images/".$row2->imagefile))
		{
			$logFile->writeLog("No se ha podido eliminar: ".$artzaraadminpath."images/".$row2->imagefile);
		}

		$row = new mosArtzaraFamilia( $database );

		if (!$row->delete($idFamilia))
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}

		return TRUE;
	}

	function GuardarProducto()
	{
		global $database, $imagenX, $artzaraadminpath, $logFile;
		$resultado = FALSE;

		// Se puede hacer un bind, pero hay que andar al loro con el imagefile.
		$logFile->writeLog("GuardarProducto: ".$_POST['nombre']);

		$row = new mosArtzaraProducto( $database );

		if (!$row->bind( $_POST ))
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}

		if(!intval(mosGetParam( $_POST,'published',0)))
		{
			$row->published = 0;
		}

		// Hacer comprobaciones
		// la familia existe
		if(DDBB::obtenerFamilia($row->id_grupo) == null)
		{
			$logFile->writeLog("Error: Familia desconocida");
			return FALSE;
		}

		//comprobar datos contabilidad
		// si al doubleval le pasas algo que no es un numero, devuelve 0
		$importePrecio_compra = doubleval($row->precio_compra);
  		$importeIva = doubleval($row->iva);
		$importeVenta_publico_dosis = doubleval($row->venta_publico_dosis);
		$importeRecargo_dosis = doubleval($row->recargo_dosis);
		$importeNum_dosis_por_botella = intval($row->num_dosis_por_botella);

		$logFile->writeLog("importePrecio_compra= $importePrecio_compra");

		if($importePrecio_compra <= 0)
		{
			$logFile->writeLog("Error: El importe de compra es invalido");
			return FALSE;
		}
		if($importeIva < 0 || $importeIva > 1)
		{
			$logFile->writeLog("Error: El iva es invalido");
			return FALSE;
		}
		if($importeVenta_publico_dosis <= 0)
		{
			$logFile->writeLog("Error: El importe de venta al publico es invalido");
			return FALSE;
		}
		if($importeRecargo_dosis < 0)
		{
			$logFile->writeLog("Error: El importe de recargo es invalido");
			return FALSE;
		}
		if($importeNum_dosis_por_botella <= 0)
		{
			$logFile->writeLog("Error: El importe de venta al publico es invalido");
			return FALSE;
		}

		$importeIva = $importeIva + 1;
		$importeRecargo_dosis = $importeRecargo_dosis + 1;

		$supuesto = ($importePrecio_compra*$importeIva*$importeRecargo_dosis) / $importeNum_dosis_por_botella;

		if(abs($importeVenta_publico_dosis - $supuesto) > 0.001)
		{
			$logFile->writeLog("Error: Datos contables del producto incorrectos");
			return FALSE;
		}

		$nuevoProd = $row->id ? FALSE : TRUE;

		$nombre_imagen = $_FILES['nombre_imagen']['name'];

		if($nombre_imagen == '')
		{
			$logFile->writeLog("nombre_imagen vacia!");
			// No se envia imagen. La imagen queda como esta, luego si existe id,
			// hay que tomar su valor y guardarlo, sino se borrara
			if($row->id)
			{
				// estamos actualizando, asi que hay que mantener el valor
				$row2 = new mosArtzaraProducto( $database );
				$row2->load( $row->id );
				$row->imagefile = $row2->imagefile;
			}
			else
			{
				// nueva imagen, sin imagen, HTML se encarga de poner la X
				$row->imagefile = "";
			}
		}
		else
		{
			$logFile->writeLog("Guardar Familia: nombre_imagen:".$nombre_imagen.": ".$_FILES['nombre_imagen']['type']);
			$prefijo = date("zHis");
			$imagenPath = $artzaraadminpath."images/".$prefijo.basename($nombre_imagen);
			// elimino la imagen anterior si esta existiese
			if($row->id)
			{
				//Estamos actualizando imagen
				$row2 = new mosArtzaraProducto( $database );
				$row2->load( $row->id );
				if($row2->imagefile!='' && !unlink($artzaraadminpath."images/".$row2->imagefile))
						$logFile->writeLog("No se ha podido eliminar: ".$artzaraadminpath."images/".$row2->imagefile);
			}

			// Se envia imagen, hay que descargarla y poner el path online de la foto.
			if (is_uploaded_file($_FILES['nombre_imagen']['tmp_name']))
			{
				if($_FILES['nombre_imagen']['type'] == 'image/gif' ||
					$_FILES['nombre_imagen']['type'] == 'image/pjpeg' ||
					$_FILES['nombre_imagen']['type'] == 'image/jpeg')
				{
					if(!copy($_FILES['nombre_imagen']['tmp_name'],$imagenPath))
					{
						$logFile->writeLog("Error: No se pudo copiar el archivo:".$imagenPath);
						return FALSE;
					}
				}
				else
				{
					$logFile->writeLog("Error: Tipo fichero incorrecto:". $_FILES['nombre_imagen']['type']);
					return FALSE;
				}
			} else
			{
				$logFile->writeLog("Error: Possible file upload attack. Filename: " . $_FILES['nombre_imagen']['name']);
				return FALSE;
			}
			$row->imagefile = $prefijo.basename($nombre_imagen);
			$logFile->writeLog("GuardarProducto: Imagen cargada: ".$row->imagefile);
		}
/*
		$logFile->writeLog("GuardarProducto: id: ".$row->id);
		$logFile->writeLog("GuardarProducto: ref: ".$row->ref);
		$logFile->writeLog("GuardarProducto: nombre: ".$row->nombre);
		$logFile->writeLog("GuardarProducto: id_grupo: ".$row->id_grupo);
		$logFile->writeLog("GuardarProducto: published: ".$row->published);
		$logFile->writeLog("GuardarProducto: nombre_imagen: ".$row->imagefile);
		$logFile->writeLog("GuardarProducto: precio_compra: ".$row->precio_compra);
		$logFile->writeLog("GuardarProducto: iva: ".$row->iva);
		$logFile->writeLog("GuardarProducto: venta_publico_dosis: ".$row->venta_publico_dosis);
		$logFile->writeLog("GuardarProducto: recargo_dosis: ".$row->recargo_dosis);
		$logFile->writeLog("GuardarProducto: num_dosis_por_botella: ".$row->num_dosis_por_botella);
		$logFile->writeLog("GuardarProducto: proveedor: ".$row->proveedor);
		$logFile->writeLog("GuardarProducto: telefono_contacto: ".$row->telefono_contacto);
		$logFile->writeLog("GuardarProducto: email: ".$row->email);
*/

		if (!$row->store())
		{
			$logFile->writeLog("Error: ".$row->getError());
			unlink($artzaraadminpath."images/".$row->imagefile);
			return FALSE;
		}
		// Tenemos los datos grabados

		if($nuevoProd)
		{
			//nueva entrada
			//a�adimos entrada en el stock
			$rowStock = new mosArtzaraStock( $database );

			$rowStock->id = 0;
			$rowStock->id_grupo = $row->id_grupo;
			$rowStock->id_prod = $row->id;
			$rowStock->cantidad = 0;
			$rowStock->dosis = 0;
			$rowStock->fechamod = date('Y-m-d H:i:s');

			if (!$rowStock->store())
			{
				$logFile->writeLog("Error: ".$rowStock->getError());
				GenericLib::EliminarProducto($row->id);
				return FALSE;
			}
		}

		return TRUE;
	}

	function EliminarProducto($idProducto)
	{
		global $database, $artzaraadminpath, $logFile;
		$logFile->writeLog("EliminarProducto $idProducto");

		$row2 = new mosArtzaraProducto( $database );
		if(!($row2->load( $idProducto )))
		{
			$logFile->writeLog("Error: ");
			return false;
		}
		$logFile->writeLog("EliminarProducto eliminamos imagen si hay");

		if($row2->imagefile!='' && !unlink($artzaraadminpath."images/".$row2->imagefile))
		{
			$logFile->writeLog("No se ha podido eliminar: ".$artzaraadminpath."images/".$row2->imagefile);
		}

		$logFile->writeLog("EliminarProducto eliminamos stock");
		//Eliminamos la entrada de stock
		DDBB::EliminarStock($row2->id_grupo,$idProducto);
		//Eliminamos las entradas de Entradas
		$logFile->writeLog("EliminarProducto entradas");
		DDBB::ResetEntradas($row2->id_grupo,$idProducto);

		$row = new mosArtzaraProducto( $database );

		if (!$row->delete($idProducto))
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}

		return TRUE;
	}

	function GuardarStock(){
		global $database, $logFile;

		$logFile->writeLog("GuardarStock");

		$row = new mosArtzaraStock( $database );

		if (!$row->bind( $_POST ))
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}

		$row->fechamod = date('Y-m-d H:i:s');

		if (!$row->store())
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}
		// Tenemos los datos grabados
		return TRUE;
	}

	function GuardarEntrada(){
		global $database, $logFile;

		$logFile->writeLog("GuardarEntrada");

		$row = new mosArtzaraEntradas( $database );

		if (!$row->bind( $_POST ))
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}

		$numUnidades = intval(mosGetParam( $_REQUEST,'displayOut',0));

		if($numUnidades <= 0)
		{
			$logFile->writeLog("GuardarEntrada Error: Cantidad no valida: {$row->cantidad}");
			return FALSE;
		}

		$newStock = &DDBB::obtenerStock($row->id_grupo,$row->id_prod);
		if($newStock === null)
		{
			$logFile->writeLog("GuardarEntrada Error: Familia o producto no validos: Familia:{$row->id_grupo} Producto:{$row->id_prod}");
			return FALSE;
		}

		$newStock->cantidad = $newStock->cantidad + $numUnidades;
		$newStock->fechamod = date('Y-m-d H:i:s');

		$row->cantidad = $numUnidades;
		if (!$row->store())
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}

		if (!$newStock->store())
		{
			$logFile->writeLog("Error: ".$newStock->getError());
			return FALSE;
		}

		// Tenemos los datos grabados
		return TRUE;
	}

	

	function GuardarSalida(){
		global $database, $logFile;

		$logFile->writeLog("GuardarSalida");

		$row = new mosArtzaraSalidas( $database );

		if (!$row->bind( $_POST ))
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}

		if (!$row->store())
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}
		// Tenemos los datos grabados
		return TRUE;
	}
	
	function AnularSobre($id){
		global $database, $logFile;
				
		$row = DDBB::obtenerSobre($id);

		if ($row === NULL)
		{
			return FALSE;
		}
		
		$row->state = _ANULADO;
		
		if (!$row->store())
		{
			$logFile->writeLog("Error: ".$row->getError());
			return FALSE;
		}
		
		$logFile->writeLog("AnularSobre: anulado OK! sobre $id");		
		return TRUE;		
	}
}
?>
