function enviarFormulario(formulario)
{
	var f = eval('document.'+formulario);
	try
	{
		f.onsubmit();
	}
	catch(e){}
	f.submit();
}

function setact(boton)
{
	document.adminForm.accion.value=boton;
	enviarFormulario('adminForm');
}

function enviarItem( id , accion ) {
	var f = document.adminForm;
	cb = eval( 'f.' + id );
	if (cb)
	{
		cb.checked = true;
		setact(accion);
	}
	return false;
}

function settask(boton)
{
	document.adminForm.task.value=boton;
	enviarFormulario('adminForm');
}

function submitbutton(pressbutton) {
	var form = document.adminForm;
	setact(pressbutton);
}

function is_validEmail(str)
{
	var exito;
	var regexpr;

	exito = false;
	if(str == '')
		return false;
	regexpr = /[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}/;
	if (str.search(regexpr) != -1)
		exito = true;
	return exito;
}

function validoFloat(valor)
{
	// Para campos que solo admiten numeros positivos
	var parsedValue = parseFloat(valor);
	var result = true;
	if(valor=='' || isNaN(parsedValue)|| parsedValue <= 0)
		result = false;
	return result;
}

function validoEntero(valor)
{
	var parsedValue = parseInt(valor);
	var result = true;
	if(valor=='' || isNaN(parsedValue)|| parsedValue < 0)
		result = false;
	return result;
}

function validoInt(valor)
{
	var parsedValue = parseInt(valor);
	var result = true;
	if(valor=='' || isNaN(parsedValue)|| parsedValue <= 0)
		result = false;
	return result;
}

function validoRatio(valor)
{
	var parsedValue = parseFloat(valor);
	var result = true;
	if(valor=='' || isNaN(parsedValue)|| parsedValue < 0 || parsedValue > 1)
		result = false;
	return result;
}

function validoImporte(valor)
{
	var parsedValue = parseFloat(valor);
	var result = true;
	if(valor=='' || isNaN(parsedValue)|| parsedValue < 0)
		result = false;
	return result;
}
