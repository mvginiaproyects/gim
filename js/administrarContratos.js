function calcularProrrateo(precio){
    var fechaActual = new Date();
    var mes = fechaActual.getMonth();
    var anio = fechaActual.getFullYear();
    var dias = fechaActual.getDate();
    if (precio>0){
        var nuevoPrecio = Math.round(precio/diasDeMes(mes, anio) * dias);
        return nuevoPrecio;
    }
    return 0;
}

dias=[31,29,31,30,31,30,31,31,30,31,30,31]; 
function diasDeMes(mes,anio){ 
    ultimo=0; 
    if (mes==1){ 
        fecha=new Date(anio,1,29) 
        vermes=fecha.getMonth(); 
        if(vermes!=mes) ultimo=28
    } 
    if(ultimo==0) ultimo=dias[mes];
    return ultimo 
} 

function diasHastaVencimiento(año, mes) {
    var dia = 1;
    var now = new Date(),
        dateEnd = new Date(año, mes + 1, dia), // months are zero-based
        days = (dateEnd - now) / 1000/60/60/24;   // convert milliseconds to days

    return Math.round(days);
}

function renovar(boton){
    if ($(boton).hasClass("disabled")){
        return;
    }
    var url = Yii.app.createUrl('administrarContratos/confirmarRenovacion');
    console.log(datosAEnviar);
    $.ajax({
        type: 'POST',
        url: url,
        data: datosAEnviar,
        success:function(result){
            //$('#horarios').html(result);
            var url2 = Yii.app.createUrl('administrarSocio/detalles',{id: datosAEnviar.contrato.id_socio, nuevo: true});
            window.location = url2;
        },
        error: function(result) { // if error occured
           $('#horarios').html('Error');
            //alert("Error occured.please try again");
        },
        dataType: 'html'
    });
}

function contratar(boton){
    if ($(boton).hasClass("disabled")){
        return;
    }
    var url = Yii.app.createUrl('administrarContratos/confirmarContrato');
    $.ajax({
        type: 'POST',
        url: url,
        data: datosAEnviar,
        success:function(result){
            //$('#horarios').html(result);
            var url2 = Yii.app.createUrl('administrarSocio/detalles',{id: datosAEnviar.contrato.id_socio, nuevo: true});
            window.location = url2;
        },
        error: function(result) { // if error occured
           $('#horarios').html('Error');
            //alert("Error occured.please try again");
        },
        dataType: 'html'
    }); 
}

function zoomIn(){
   var calendar = $('#calendar');
   var tamanoActual = calendar.weekCalendar("option", 'timeslotHeight');
   if (tamanoActual!=30){
       tamanoActual+=5;
       calendar.weekCalendar("option", 'timeslotHeight', tamanoActual);    
   }
    
}

function zoomOut(){
   var calendar = $('#calendar');
   var tamanoActual = calendar.weekCalendar("option", 'timeslotHeight');
   if (tamanoActual!=10){
       tamanoActual-=5;
       calendar.weekCalendar("option", 'timeslotHeight', tamanoActual);    
   }
}

function verificarInscripcion(){
    if (datosEventoSeleccionado.tipo_contratos.tipo_0){
        var valor_inscripcion = buscarPrecioXTipo(0);
        datosAEnviar.cuenta_socio.valor_inscripcion = valor_inscripcion;
        datosAEnviar.cuenta_socio.inscripcion = true;
        $('#div_inscripcion').show();
        $("input[name=inscripcion]").attr('checked', true);
        $("input[name=inscripcion_valor]").prop({'disabled': true, 'value':valor_inscripcion});  
    } else {
        datosAEnviar.cuenta_socio.valor_inscripcion = 0;
        datosAEnviar.cuenta_socio.inscripcion = false;
        $('#div_inscripcion').hide();
        $("input[name=inscripcion]").attr('checked', false);
        $("input[name=inscripcion_valor]").prop({'disabled': true, 'value':0});          
    }
}

//Tipo 2
function opcionHorario(fila){
    if (!$(fila).hasClass('row_selected')){
        datosAEnviar.contrato.tipoContrato = 2;
        $('#precio_inferior').html(html_precio);
        $('#tabla_opciones .row_selected').removeClass('row_selected');
        $('#opcion_horario').addClass('row_selected');
        var horarios = {events:[]};
        horarios.events = datosEventoSeleccionado.events;
        $('#horarios').html(datosEventoSeleccionado.html);
        verificarInscripcion();
        calendario(horarios);       
        actualizarPrecio();
    }
}

//Tipo 1
function opcionLibre(fila){
    if (!$(fila).hasClass('row_selected')){
        datosAEnviar.contrato.tipoContrato = 1;
        $('#resumen_precio').remove();
        $('#tabla_opciones .row_selected').removeClass('row_selected');
        $('#opcion_libre').addClass('row_selected');
        $('#horarios').html('');
        $('#horarios').append(html_precio);
        verificarInscripcion();
        actualizarPrecio();
    }
}

//Tipo 3
function opcionClase(fila){
    if (!$(fila).hasClass('row_selected')){
        datosAEnviar.contrato.tipoContrato = 3;
        $('#resumen_precio').remove();
        $('#tabla_opciones .row_selected').removeClass('row_selected');
        $('#opcion_clase').addClass('row_selected');
        $('#horarios').html('');
        $('#horarios').append(html_precio);
        
        datosAEnviar.cuenta_socio.valor_inscripcion = 0;
        datosAEnviar.cuenta_socio.inscripcion = false;
        $('#inscripcion').hide();
        $("input[name=inscripcion]").attr('checked', false);
        $("input[name=inscripcion_valor]").prop({'disabled': true, 'value':0});          

        $('#precio_parcial').html("Cant. de clases: <input id='cant_clases' type='number' value='1' name='quantity' min='1' max='10' style='width: 45px; font-size: 12px;'></br><div id='subtotal'></div>"); 
        $('#cant_clases').change(function(){
            actualizarPrecio();
        });
        $('#cant_clases').keyup(function(){
            actualizarPrecio();
        });
        actualizarPrecio();
    }
}

function obtenerFilaSeleccionada (tabla)
{
    return tabla.$('tr.row_selected');
}

function horasManana(){
   var calendar = $('#calendar');
   calendar.weekCalendar("option", 'businessHours', {start: 8, end: 13, limitDisplay: true}); 
}
function horasTarde(){
   var calendar = $('#calendar');
   calendar.weekCalendar("option", 'businessHours', {start: 13, end: 22, limitDisplay: true});
}
function horasFull(){
   var calendar = $('#calendar');
   calendar.weekCalendar("option", 'businessHours', {start: 8, end: 22, limitDisplay: true});
}

function diasAMostrar(dias){
   var calendar = $('#calendar');
   //calendar.weekCalendar("option", 'businessHours', {start: 14, end: 22, limitDisplay: true});
   diasMostrados = dias;
   calendar.weekCalendar('setDaysToShow',dias);
}

function siguiente(){
    if (diasMostrados<6){
       var calendar = $('#calendar');
        var ultimoDiaActual = calendar.weekCalendar('getCurrentLastDay').getDay();
        if ((ultimoDiaActual+diasMostrados)>=6 && ultimoDiaActual!=6){
            var fecha = new Date(2013, 6, (6-diasMostrados+1));
            calendar.weekCalendar('gotoDate',fecha);      
        } else {
            if (ultimoDiaActual!=6)
                calendar.weekCalendar('next');
        }
    }
}
function previo(){
   var calendar = $('#calendar');
    if (diasMostrados<6){
        var primerDiaActual = calendar.weekCalendar('getCurrentFirstDay').getDay();
        if ((primerDiaActual-diasMostrados)<=1 && primerDiaActual!=1){
            var fecha = new Date(2013, 6, 1);
            calendar.weekCalendar('gotoDate',fecha);      
        } else {
            if (primerDiaActual!=1)
                calendar.weekCalendar('prev');
        }
        
    }
}

function buscarPrecioXTipo(tipo){
    for (i=0; i < datosEventoSeleccionado.precios.length; i++){
        if (datosEventoSeleccionado.precios[i].tipo==tipo){
            return datosEventoSeleccionado.precios[i].precio;
        }        
    }
    return 0;
}

function calcularPrecio(horarios){
    var cantidadHorarios = getLength(horarios);
    for (i=0; i < datosEventoSeleccionado.precios.length; i++){
        if (datosEventoSeleccionado.precios[i].tipo==2){
            if (datosEventoSeleccionado.precios[i].clases_x_semana==cantidadHorarios)
                return datosEventoSeleccionado.precios[i].precio;
        }
    }
    return 0;
}

//CALENDARIO PARA CONTRATAR EVENTOS
function calendario(datos){
   var $calendar = $('#calendar');
   var id = 10;
   $calendar.weekCalendar({
        date: new Date(2013,6,1),
        showHeader:false,
        //notOverlapInEdit: true,
        //editOnlyNewEvent: true,//readonly debe estar en false y agregar la funcion en calendarAfterLoad
        timeSeparator: " a ",
        readonly: true,
        shortDays: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        longDays: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
        scroll_event_body:true,
        timeslotHeight: 20,
      displayOddEven:true,
      timeslotsPerHour : 4,
      allowCalEventOverlap : true,
      overlapEventsSeparate: true,
      firstDayOfWeek : 1,
      businessHours :{start: 8, end: 22, limitDisplay: true },
      daysToShow : 6,
      switchDisplay: {'1 day': 1, '3 next days': 3, 'work week': 5, 'full week': 7},
      title: function(daysToShow) {
			return daysToShow == 1 ? '%date%' : '%start% - %end%';
      },
      height : function($calendar) {
          if ($calendar.width()<500){
              $calendar.weekCalendar({useShortDayNames : true});
          }
          else
              $calendar.weekCalendar({useShortDayNames : false});
         return 350;
      },
      eventRender : function(calEvent, $event) {
         if (calEvent.end.getTime() < new Date().getTime()) {
           
            //$event.css("backgroundColor", (calEvent.color != 'undefined')? calEvent.color:'#aaa');
            $event.find(".wc-time").css({
               "backgroundColor" : calEvent.colorSalon,
               "border" : "1px solid #888"
            });
         }
      },
      eventClick : function(calEvent, $event) {
         if (calEvent.readOnly) {
             if (parseInt(calEvent.disponibilidad)==0){
                 alert('No hay disponibilidad en este horario');
                 return;
             }
            if ($event.hasClass('seleccionar_evento')){ //deselecciono el horario
                //delete horarios_seleccionados["'"+calEvent.id+"'"];
                horarios_seleccionados.splice( $.inArray(calEvent.id,horarios_seleccionados) ,1 );
                calEvent.disponibilidad = parseInt(calEvent.disponibilidad)+1;
                actualizarPrecio();
                calEvent.seleccionado = false;
                $calendar.weekCalendar("updateEvent", calEvent);
            }else{ //selecciono el horario
                //horarios_seleccionados["'"+calEvent.id+"'"] = 'calEvent';
                horarios_seleccionados.push(calEvent.id);
                actualizarPrecio();
                calEvent.disponibilidad = parseInt(calEvent.disponibilidad)-1;
                calEvent.seleccionado = true;
                $calendar.weekCalendar("updateEvent", calEvent);
            }
            datosAEnviar.horarios_contratados = horarios_seleccionados;
            return;
         }
      },
      eventAfterRender : function (calEvent, $calEventList){
          //var overflow_body = $calEventList.outerHeight() - 17;
          //var overflow_body = $calEventList.outerHeight() - $calEventList.find(".wc-time").outerHeight();
          //$calEventList.find("#body_scroll").css({"overflow-y": "auto", "height":overflow_body});
      },
      noEvents : function() {

      },
      data : function(start, end, callback) {
         //callback(getEventData());
         callback(datos);
      }
      //calendarAfterLoad: function(calendar) {
          //if (calendar.weekCalendar('option').editOnlyNewEvent) {
              //calendar.weekCalendar('option','readonly',true);
          //}
      //}
   });

  
}

//me fijo si tengo seleccionado por horario, libre o por clase
function actualizarPrecio(){
    switch (datosAEnviar.contrato.tipoContrato) {
        case 1://libre
            actualizarPrecioLibre();
            break
        case 2://horario
            actualizarPrecioXHorario();
            break
        case 3://x clase
            actualizarPrecioXClase();
            break
    }
}

function actualizarPrecioXClase(){
    datosAEnviar.cuenta_socio.precioParcial = buscarPrecioXTipo(3);
    var precioCalculado = calcularPrecioXClase();
    calcularPrecioConDescuento(precioCalculado);
    if ($('[name=valor_descuento]').val()==0)
        $('#precio_final').html('$ ' + precioCalculado);
}

function calcularPrecioXClase(){
    var nroDeClases = $('#cant_clases').val();
    datosAEnviar.contrato.cantidadClases = nroDeClases;
    var subtotal = datosAEnviar.cuenta_socio.precioParcial * nroDeClases;
    $('#subtotal').html('Subtotal: $ '+subtotal);
    return subtotal;
}

function actualizarPrecioLibre(){
    datosAEnviar.cuenta_socio.precioParcial = buscarPrecioXTipo(1);
    $('#precio_parcial').html("Sub-total: $ " + datosAEnviar.cuenta_socio.precioParcial);
    calcularPrecioConDescuento(datosAEnviar.cuenta_socio.precioParcial);
}

var binding = false;
function actualizarPrecioXHorario(){//actualiza el precio parcial según los horarios que se selecciones
    horariosSeleccionados = getLength(horarios_seleccionados);
    if (horariosSeleccionados!=0){//si hay horarios seleccionados
        datosAEnviar.cuenta_socio.precioParcial = calcularPrecio(horarios_seleccionados);
        if (datosAEnviar.cuenta_socio.precioParcial >0){//si hay un precio establecido para la cantidad de horarios seleccionados
            binding = false;
            datosAEnviar.precioNuevoHorarios.nuevo = false;
            $('#btn-contratar').removeClass('disabled');
            datosAEnviar.contrato.cantidadClases = horariosSeleccionados;
            $('#precio_parcial').html("Horarios seleccionados: "+horariosSeleccionados
                    +"</br>Precio: $ " + datosAEnviar.cuenta_socio.precioParcial);                    
            calcularPrecioConDescuento(datosAEnviar.cuenta_socio.precioParcial);
        } else { //si no hay ningun precio cargado para la cantidad de horarios seleccionados
            //var precio_manual = parseInt($('#precio_manual').val());
            if (!$('#precio_manual').length)
                $('#precio_parcial').html("Horarios seleccionados: "+horariosSeleccionados
                    +"</br>No se encontro precio, ingrese un precio para cargarlo: $<input id='precio_manual' type='number' name='quantity' min='1' max='1000' style='width: 45px; font-size: 12px;'>");
            //$('#precio_manual').val(precio_manual);
            datosAEnviar.cuenta_socio.precioParcial = !(parseInt($('#precio_manual').val()))? 0:parseInt($('#precio_manual').val());
            if (datosAEnviar.cuenta_socio.precioParcial==0)
                $('#btn-contratar').addClass('disabled');
            else {
                $('#btn-contratar').removeClass('disabled');
                datosAEnviar.precioNuevoHorarios.nuevo = true;
                datosAEnviar.precioNuevoHorarios.clasesXSemana = horariosSeleccionados;
                datosAEnviar.precioNuevoHorarios.precio = datosAEnviar.cuenta_socio.precioParcial;
            }
            if (!binding){
                console.log('Paso');
                $('#precio_manual').change(function(){
                    actualizarPrecioXHorario();
                });
                $('#precio_manual').keyup(function(){
                    actualizarPrecioXHorario();
                });
                $('#precio_manual').focus(function(){
                    $(this)
                    .one('mouseup', function () {
                        $(this).select();
                        return false;
                    })
                    .select();
                });
                binding = true;                
            }
            calcularPrecioConDescuento(datosAEnviar.cuenta_socio.precioParcial);            
        } 
    } else { //si no hay ningún horario seleccionado
        $("#precio_parcial").html("Seleccione los horarios que desea contratar.");
        $('#btn-contratar').addClass('disabled');        
        binding = false;
    }
    
}

function buscarListaDescuentos(seleccion){
    for (i=0; i<descuentos_lista.length;i++){
        if (descuentos_lista[i].id_descuento_recargo==seleccion){
            return descuentos_lista[i];
        }
    }
}

function buscarListaRecargos(seleccion){
    for (i=0; i<recargos_lista.length;i++){
        if (recargos_lista[i].id_descuento_recargo==seleccion){
            return recargos_lista[i];
        }
    }
}

//eventos a los inputs de resumen de precio
$(function(){
    $('#precio_inferior,#horarios').on("keyup change","input[name=valor_descuento],select[name=forma]", function() {
        $('select[name=descuento]').prop('selectedIndex', 0);
        var valor = $('input[name=valor_descuento]').val();
        if (valor === ''){
            $('input[name=valor_descuento]').val('0');   
            $('input[name=valor_descuento]').select();
        }
        actualizarPrecio();
    });        
});

$(function(){
    $('#precio_inferior,#horarios').on("change","select[name=descuento]", function() {
        if (parseInt($(this).val())!=0){
            var descuentoSeleccionado = buscarListaDescuentos(parseInt($(this).val()));
            $('select[name=forma]').prop('selectedIndex', descuentoSeleccionado.modo=='%'? 1:0);
            $('input[name=valor_descuento]').val(descuentoSeleccionado.monto);
            actualizarPrecio();
        }
    });        
});

$(function(){
    $('#precio_inferior,#horarios').on("keyup change","input[name=valor_recargo],select[name=forma2]", function() {
        $('select[name=recargo]').prop('selectedIndex', 0);
        actualizarPrecio();
    });        
});

$(function(){
    $('#precio_inferior,#horarios').on("change","select[name=recargo]", function() {
        if (parseInt($(this).val())!=0){
            var recargoSeleccionado = buscarListaRecargos(parseInt($(this).val()));
            $('select[name=forma2]').prop('selectedIndex', recargoSeleccionado.modo=='%'? 1:0);
            $('input[name=valor_recargo]').val(recargoSeleccionado.monto);
            actualizarPrecio();
        }
    });        
});

$(function(){
    $('#precio_inferior,#horarios').on("focus","input[name=valor_descuento],input[name=valor_recargo]", function() {
        $(this)
        .one('mouseup', function () {
            $(this).select();
            return false;
        })
        .select();        
    });        
});

//fin

function calcularPrecioConDescuento(precio_parcial){
    var precio_total = datosAEnviar.cuenta_socio.precioParcial;
    
    if ($('#prorrateo_check').is(":checked")) {
        $("input[name=prorrateo_valor]").prop({'value':calcularProrrateo(precio_parcial)});            
        var prorrateo = parseInt($("input[name=prorrateo_valor]").val());
        datosAEnviar.cuenta_socio.valor_prorrateo = prorrateo;
        precio_parcial = parseInt(precio_parcial) - prorrateo;
    }
    
    var forma = $('[name=forma]').val();
    var descuento = parseInt($('[name=valor_descuento]').val());
    if (descuento!=0){//si hay un descuento cargado en la caja de texto
        if ($('[name=descuento]').val()!=0){ //si seleccione algún descuento de la BD
            datosAEnviar.cuenta_socio.id_descuento = $('[name=descuento]').val();
        } else { //si seleccione "Otros" descuentos
            datosAEnviar.cuenta_socio.id_descuento = 0;
        }
        
        if (forma==2) { //%
            descuento = Math.round((precio_parcial * descuento/100));
        }
        datosAEnviar.cuenta_socio.descuento = descuento;
    } else {//si no hay descuentos
        datosAEnviar.cuenta_socio.descuento = 0;
        datosAEnviar.cuenta_socio.id_descuento = null;
    };
    var forma2 = $('[name=forma2]').val();
    var recargo = (parseInt($('[name=valor_recargo]').val()))? parseInt($('[name=valor_recargo]').val()):0;
    if (recargo!=0){//si hay un recargo cargado en la caja de texto
        if ($('[name=recargo]').val()!=0){ //si seleccione algún recargo de la BD
            datosAEnviar.cuenta_socio.id_recargo = $('[name=recargo]').val();
        } else { //si seleccione "Otros" recargos
            datosAEnviar.cuenta_socio.id_recargo = 0;
        }
        
        if (forma2==2) { //%
            recargo = Math.round((precio_parcial * recargo/100));
        }
        datosAEnviar.cuenta_socio.recargo = recargo;
    }
    else{
        datosAEnviar.cuenta_socio.recargo = 0;
        datosAEnviar.cuenta_socio.id_recargo = null;        
    }

    if ($('#inscripcion_check').is(":checked")) {
        var inscripcion = parseInt($("input[name=inscripcion_valor]").val());
        datosAEnviar.cuenta_socio.valor_inscripcion = inscripcion;
        //x $("input[name=inscripcion_valor]").prop({'value':datosAEnviar.cuenta_socio.valor_inscripcion});            
        precio_parcial = parseInt(precio_parcial) + inscripcion;
    }
    precio_total = parseInt(precio_parcial) + parseInt(recargo) - parseInt(descuento);
    $('#precio_final').html('$ ' + precio_total);
    if (precio_total<0) {
        $('#btn-contratar').addClass('disabled');
    } else {
        $('#btn-contratar').removeClass('disabled');
    }
}

function calcularPrecioConDescuento2(precio_parcial){
    var precio_total = datosAEnviar.cuenta_socio.precioParcial;
    var forma = $('[name=forma]').val();
    var descuento = parseInt($('[name=valor_descuento]').val());
    if (descuento!=0){//si hay un descuento cargado en la caja de texto
        if ($('[name=descuento]').val()!=0){ //si seleccione algún descuento de la BD
            datosAEnviar.cuenta_socio.id_descuento = $('[name=descuento]').val();
        } else { //si seleccione "Otros" descuentos
            datosAEnviar.cuenta_socio.id_descuento = 0;
        }
        
        if ($('#prorrateo_check').is(":checked")) {
            $("input[name=prorrateo_valor]").prop({'value':calcularProrrateo(precio_parcial)});            
            var prorrateo = parseInt($("input[name=prorrateo_valor]").val());
            datosAEnviar.cuenta_socio.valor_prorrateo = prorrateo;
            precio_parcial = parseInt(precio_parcial) - prorrateo;
        }

        if ($('#inscripcion_check').is(":checked")) {
            var inscripcion = parseInt($("input[name=inscripcion_valor]").val());
            datosAEnviar.cuenta_socio.valor_inscripcion = inscripcion;
            //x $("input[name=inscripcion_valor]").prop({'value':datosAEnviar.cuenta_socio.valor_inscripcion});            
            precio_parcial = parseInt(precio_parcial) + inscripcion;
        }
        if (forma==1) { //$
            precio_total = precio_parcial - descuento;
        } else { //%
            descuento = Math.round((precio_parcial * descuento/100));
            //precio_total = precio_parcial - (precio_parcial * descuento/100);
            precio_total = precio_parcial - descuento;
        }
        datosAEnviar.cuenta_socio.descuento = descuento;
        //$('#precio_final').html('$ ' + precio_total);
    } else {//si no hay descuentos

        if ($('#prorrateo_check').is(":checked")) {
            $("input[name=prorrateo_valor]").prop({'value':calcularProrrateo(precio_parcial)});            
            var prorrateo = parseInt($("input[name=prorrateo_valor]").val());
            datosAEnviar.cuenta_socio.valor_prorrateo = prorrateo;
            precio_total = precio_parcial - prorrateo;
        }

        if ($('#inscripcion_check').is(":checked")) {
            var inscripcion = parseInt($("input[name=inscripcion_valor]").val());
            datosAEnviar.cuenta_socio.valor_inscripcion = inscripcion;
            //$("input[name=inscripcion_valor]").prop({'value':inscripcion});            
            precio_total = parseInt(precio_total) + parseInt(inscripcion);
        }

        datosAEnviar.cuenta_socio.descuento = 0;
        datosAEnviar.cuenta_socio.id_descuento = null;
        //$('#precio_final').html('$ ' + precio_total);
    };
    precio_parcial = precio_total;
    var forma2 = $('[name=forma2]').val();
    var recargo = parseInt($('[name=valor_recargo]').val());
    if (recargo!=0){//si hay un recargo cargado en la caja de texto
        if ($('[name=recargo]').val()!=0){ //si seleccione algún recargo de la BD
            datosAEnviar.cuenta_socio.id_recargo = $('[name=recargo]').val();
        } else { //si seleccione "Otros" recargos
            datosAEnviar.cuenta_socio.id_recargo = 0;
        }
        
        if (forma2==1) { //$
            precio_total = precio_parcial + recargo;
        } else { //%
            recargo = Math.round((precio_parcial * recargo/100));
            //precio_total = precio_parcial - (precio_parcial * descuento/100);
            precio_total = parseInt(precio_parcial) + recargo;
        }
        datosAEnviar.cuenta_socio.recargo = recargo;
    }
    else{
        datosAEnviar.cuenta_socio.descuento = 0;
        datosAEnviar.cuenta_socio.id_descuento = null;        
    }
    $('#precio_final').html('$ ' + precio_total);
    if (precio_total<0) {
        $('#btn-contratar').addClass('disabled');
    } else {
        $('#btn-contratar').removeClass('disabled');
    }
}

    
   function resetForm($dialogContent) {
      $dialogContent.find("input").val("");
      $dialogContent.find("textarea").val("");
   }
   
    function setupStartAndEndTimeFields($startTimeField, $endTimeField, calEvent, timeslotTimes) {

      $startTimeField.empty();
      $endTimeField.empty();
      for (var i = 0; i < timeslotTimes.length; i++) {
         var startTime = timeslotTimes[i].start;
         var endTime = timeslotTimes[i].end;
         var startSelected = "";
         if (startTime.getTime() === calEvent.start.getTime()) {
            startSelected = "selected=\"selected\"";
         }
         var endSelected = "";
         if (endTime.getTime() === calEvent.end.getTime()) {
            endSelected = "selected=\"selected\"";
         }
         $startTimeField.append("<option value=\"" + startTime + "\" " + startSelected + ">" + timeslotTimes[i].startFormatted + "</option>");
         $endTimeField.append("<option value=\"" + endTime + "\" " + endSelected + ">" + timeslotTimes[i].endFormatted + "</option>");

         $timestampsOfOptions.start[timeslotTimes[i].startFormatted] = startTime.getTime();
         $timestampsOfOptions.end[timeslotTimes[i].endFormatted] = endTime.getTime();

      }
      $endTimeOptions = $endTimeField.find("option");
      $startTimeField.trigger("change");
   }

// ***** FIN FUNCIONES CONTRATAR EVENTOS