function enviarFormularioStock(act)
{
	if(document.formularioStock.cantidad.value=='')
	{
		alert('El campo cantidad no puede estar vacio:');
		document.formularioStock.cantidad.focus();
	}
	else
	{
		document.formularioStock.accion.value=act;
		try {
			document.formularioStock.onsubmit();
		}
		catch(e){}
		document.formularioStock.submit();
	}
}

function enviarFormularioNuevaEntrada(act)
{
	var numUnidades = document.adminForm.displayOut.value;
	if(!validoInt(numUnidades))
	{
		alert('entrada invalida');
	}
	else if(document.adminForm.inputdate.value=='')
	{
		alert('Debe introducir una fecha:');
		document.adminForm.inputdate.focus();
	}
	else
	{
		document.adminForm.accion.value=act;
		try {
			document.adminForm.onsubmit();
		}
		catch(e){}
		document.adminForm.submit();
	}
}

function enviarListaFiltro(accion)
{
	document.adminForm.accion.value=accion;
	try {
		document.adminForm.onsubmit();
	}
	catch(e){}
	document.adminForm.submit();
	return false;
}

function enviarFormularioFamilia(proceso)
{
	if(document.formularioFamilia.nombre.value=='')
	{
		alert('Introduce el nombre de la familia');
		document.formularioFamilia.nombre.focus();
	}
	else
	{
		document.formularioFamilia.proceso.value=proceso;
		try {
			document.formularioFamilia.onsubmit();
		}
		catch(e){}
		document.formularioFamilia.submit();
	}
}

function calculoPrecios(campo)
{
	var form = document.formularioProducto;
	if(campo == null)
		return;
	if(!validoFloat(trim(form.precio_compra.value)))
	{
		return;
	}
	else if(!validoRatio(trim(form.iva.value)))
	{
		return;
	}
	else if(!validoFloat(trim(form.venta_publico_dosis.value)) && !validoRatio(trim(form.recargo_dosis.value)))
	{
		return;
	}
	else if(!validoInt(trim(form.num_dosis_por_botella.value)))
	{
		return;
	}

	// Ya sabemos que no hay peligro de division por 0

	var importePrecio_compra = parseFloat(trim(form.precio_compra.value));
	var importeIva = parseFloat(trim(form.iva.value)) + 1;
	var importeVenta_publico_dosis = parseFloat(trim(form.venta_publico_dosis.value));
	var importeRecargo_dosis = parseFloat(trim(form.recargo_dosis.value)) + 1;
	var importeNum_dosis_por_botella = parseInt(trim(form.num_dosis_por_botella.value));

	if(campo == form.precio_compra){
		form.venta_publico_dosis.value = (importePrecio_compra * importeIva * importeRecargo_dosis) / importeNum_dosis_por_botella;
		importeVenta_publico_dosis = parseFloat(trim(form.venta_publico_dosis.value));
		form.recargo_dosis.value = ((importeVenta_publico_dosis * importeNum_dosis_por_botella) /(importePrecio_compra * importeIva ))-1 ;
	}
	else if(campo == form.iva){
		form.venta_publico_dosis.value = (importePrecio_compra * importeIva * importeRecargo_dosis) / importeNum_dosis_por_botella;
		importeVenta_publico_dosis = parseFloat(trim(form.venta_publico_dosis.value));
		form.recargo_dosis.value = ((importeVenta_publico_dosis * importeNum_dosis_por_botella) /(importePrecio_compra * importeIva ))-1 ;
	}
	else if(campo == form.venta_publico_dosis){
		form.recargo_dosis.value = ((importeVenta_publico_dosis * importeNum_dosis_por_botella) /(importePrecio_compra * importeIva ))-1 ;
	}
	else if(campo == form.recargo_dosis){
		form.venta_publico_dosis.value = (importePrecio_compra * importeIva * importeRecargo_dosis) / importeNum_dosis_por_botella;
	}
	else if(campo == form.num_dosis_por_botella){
		form.venta_publico_dosis.value = (importePrecio_compra * importeIva * importeRecargo_dosis) / importeNum_dosis_por_botella;
		importeVenta_publico_dosis = parseFloat(trim(form.venta_publico_dosis.value));
		form.recargo_dosis.value = ((importeVenta_publico_dosis * importeNum_dosis_por_botella) /(importePrecio_compra * importeIva ))-1 ;
	}
}

function enviarFormularioProducto(accion)
{
	var form = document.formularioProducto;
	if(form.nombre.value=='')
	{
		alert('Introduce el nombre del producto');
		form.nombre.focus();
	}
	else if(!validoFloat(trim(form.precio_compra.value)))
	{
		alert('Precio de compra invalido');
		form.precio_compra.focus();
	}
	else if(!validoRatio(trim(form.iva.value)))
	{
		alert('I.V.A. invalido');
		form.iva.focus();
	}
	else if(!validoFloat(trim(form.venta_publico_dosis.value)))
	{
		alert('precio por dosis invalido');
		form.venta_publico_dosis.focus();
	}
	else if(!validoRatio(trim(form.recargo_dosis.value)))
	{
		alert('recargo por dosis invalido');
		form.recargo_dosis.focus();
	}
	else if(!validoInt(trim(form.num_dosis_por_botella.value)))
	{
		alert('la dosis por botella es invalido');
		form.num_dosis_por_botella.focus();
	}
	else if(form.email.value !=='' && !is_validEmail(document.formularioProducto.email.value))
	{
		alert('Formato de email incorrecto');
		form.email.focus();
	}
	else
	{
		form.proceso.value=accion;
		try {
			form.onsubmit();
		}
		catch(e){}
		form.submit();
	}
}
