//Array.prototype.getLength = function(){
  //var i, c = 0;
  //for(i in this) if(Object.prototype.hasOwnProperty.call(this, i)) c++;
  //return c;
//};

var ayuda;

function mostrarOcultarAyuda(){
    if (ayuda) {
        if($('#ayuda').is(':visible'))
            $('#ayuda').slideUp();
        else         
            $('#ayuda').slideDown();
    }
}

function mostrarMensaje(clase, titulo, mensaje, cerrar){
    cerrar = typeof cerrar !== 'undefined' ? cerrar : true;
    $('<div id="msg_x" class="container"><div class="row-fluid"><div class="span12"><div class="alert alert-block alert-'+clase+'"><button type="button" class="close" onclick="$('+"'"+'#msg_x'+"'"+').remove()">&times;</button><h4>'+titulo+'</h4>'+mensaje+'</div></div></div></div>').insertAfter('#ayuda');
    if (cerrar)
        $('#msg_x').fadeIn().animate({opacity: 1.0}, 4000).fadeOut("slow", function(e){$(this).remove();});
}

var weekday=new Array(7);
weekday[0]="-";
weekday[1]="Lunes";
weekday[2]="Martes";
weekday[3]="Miercoles";
weekday[4]="Jueves";
weekday[5]="Viernes";
weekday[6]="Sabado";
weekday[7]="Domingo";

function getLength(aarray){
  var i, c = 0;
  for(i in aarray) if(Object.prototype.hasOwnProperty.call(aarray, i)) c++;
  return c;    
}

function restaurarDatosFormModificarSocio(){
	//alert(nombre);
	$('#Socio_nombre').val(nombre);
	$('#Socio_apellido').val(apellido);
	$('#Socio_fecha_ingreso').val(fecha_ingreso);
	$('#Socio_estado').prop('selectedIndex',estado);

 	$('#Socio_dni').val(dni);
	$('#Socio_email').val(email);
	$('#Socio_direccion').val(direccion);
	$('#Socio_telefono').val(telefono);
	$('#Socio_nac').val(fecha_nac);
	$('#Socio_sexo_'+sexo).prop('checked',true);
        
        $('#myModal').modal('hide');
}

function restaurarDatosFormModificarSalon(){
	//alert(nombre);
	$('#Salon_nombre').val(nombre);
	$('#Salon_color').val(color);
	$('#Salon_capacidad_max').val(capacidad_max);
	$('#Salon_descripcion').val(descripcion);;
       
        $('#myModal').modal('hide');
}


//function guardarDatosFormModificarSocio(){
	//$('#socio-form').submit();
	//$('#myModal').delay(3000).modal('hide');
//}

