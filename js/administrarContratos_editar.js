var horarios_original = 0;

$(document).ready(function(){
        datosAEnviar.contrato.id_contrato = datos['otros_datos'].id_contrato;
        datosAEnviar.contrato.vencimiento = datos['otros_datos'].vencimiento;

        datosAEnviar.contrato.tipoContrato = 2;
        datosAEnviar.horarios_originales = $.merge([], horarios_seleccionados);
        datosAEnviar.horarios_contratados = horarios_seleccionados;
        var horarios = {events:[]};
        horarios.events = datosEventoSeleccionado.events;
        $('#horarios').html(datosEventoSeleccionado.html);
        horarios_original = horarios_seleccionados.length;
        calendario(horarios);       

});

function guardar(){
    if (!$('#btn_guardar_cambios').hasClass('disabled')){
        $.ajax({
            url: Yii.app.createUrl('administrarContratos/confirmarEdicion'),
            type: 'POST',
            data: datosAEnviar,
            beforeSend: function(){
                $('#btn_guardar_cambios').html("<span id='span_enviar' class='fa fa-spinner fa-spin'></span>  Guardando...").addClass("disabled");
            },
            success: function(respuesta){
                //console.log(respuesta);
                var url2 = Yii.app.createUrl('administrarContratos/guardadoCorrecto',{modo:'edicion', idSocio: datosAEnviar.contrato.id_socio});
                //console.log(url2);
                window.location = url2;
            },
            error: function(respuesta){
                console.log('error');
            }
        });        
    } 
}

function calendario(datos){
   var $calendar = $('#calendarEdicion');
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
         return 550;
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
                calEvent.seleccionado = false;
                $calendar.weekCalendar("updateEvent", calEvent);
                $('#btn_guardar_cambios').addClass('disabled');
            }else{ //selecciono el horario
                //horarios_seleccionados["'"+calEvent.id+"'"] = 'calEvent';
                if (horarios_seleccionados.length < horarios_original){
                    horarios_seleccionados.push(calEvent.id);
                    calEvent.disponibilidad = parseInt(calEvent.disponibilidad)-1;
                    calEvent.seleccionado = true;
                    $calendar.weekCalendar("updateEvent", calEvent);  
                    if (horarios_seleccionados.length = horarios_original)
                        $('#btn_guardar_cambios').removeClass('disabled');
                } else {
                    console.log('No se puede');
                }
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