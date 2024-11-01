/*
* Invierte la marca de los checkbox en un formulario
* Llamar con onclick="toogleCheck(document.getElementById('nomform'));
*/
function toogleCheck(form) {
	for( i = 0, n = form.elements.length; i < n; i++ ) {
		if( form.elements[i].type == "checkbox" ) {
			if( form.elements[i].checked == true )
				form.elements[i].checked = false;
			else
				form.elements[i].checked = true;
		}
	}
}


/*
* Llamar con <input type="checkbox" name="FieldName" onclick="SetAllCheckBoxes('FormName', 'FieldName', this.checked);" />
* FieldName es el mismo para toodos los checkbox del formulario que queremos gestionar
* CheckValue puede ser tambien true o false
*/
function SetAllCheckBoxes(FormName, FieldName, CheckValue)
{
	if(!document.forms[FormName])
		return;
	var objCheckBoxes = document.forms[FormName].elements[FieldName];
	if(!objCheckBoxes)
		return;
	var countCheckBoxes = objCheckBoxes.length;
	if(!countCheckBoxes)
		objCheckBoxes.checked = CheckValue;
	else
		// set the check value for all check boxes
		for(var i = 0; i < countCheckBoxes; i++)
			objCheckBoxes[i].checked = CheckValue;
}



/*
* Llamar con <input type="checkbox" name="accion" onclick="show_hide(this.checked);" />
* el id del div es "contenido"
* o bien con onclick="show_hide(document.getElementById('contenido'));
*/
function show_hide(hide) {
	if (document.layers)
		document.contenido.visibility = hide ? 'hide' : 'show';
	else {
		var g = document.all ? document.all.contenido :
		document.getElementById('contenido');
		g.style.visibility = hide ? 'hidden' : 'visible';
	}
}

