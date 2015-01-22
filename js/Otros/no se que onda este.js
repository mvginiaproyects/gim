var horarios_totales;
var nuevo = true;
function mostrar_horarios(){
    if ($('table#tabla_salones > tbody > tr.row_selected').length && tabla_eventos.$('tr.row_selected').length){
        $('#btn_guardar_cambios').removeClass('disabled');
        id_salon_seleccionado = $('table#tabla_salones > tbody > tr.row_selected').prop('id');
        id_evento_seleccionado = tabla_eventos.$('tr.row_selected').prop('id');
        horarios_salon_seleccionado = [];
        horarios_salon_seleccionado = horarios_salones[id_salon_seleccionado];
        $.ajax({
            type: 'POST',
            url: url_horariosOtrosSalones,
            data: {id_salon: id_salon_seleccionado, id_evento: id_evento_seleccionado},
            dataType: 'json',
            beforeSend: function(dato){
                $('#nuevosHorarios').html("<span id='span_enviar' class='fa fa-spinner fa-spin'></span> Buscando horarios...");
            },
            success: function(resultado){
                $('#nuevosHorarios').html('');
                //var horarios_totales = [];
                //horarios_totales.push.apply(horarios_salon_seleccionado,resultado);
                var horarios_salon_seleccionado_temp = $.merge([],horarios_salon_seleccionado);
                horarios_totales = $.merge(horarios_salon_seleccionado_temp,resultado);
                //console.log(horarios_totales);
                calendario_nuevos_horarios(horarios_totales);
                if (!nuevo)
                    $('#nuevosHorarios').weekCalendar("refresh");
                else
                    nuevo = false;
            },
            error: function(result) { // if error occured
               console.log('Error');
            }
        });        
    }
    
}

function guardarCambios(){
    $.ajax({
       type: 'POST',
       url: url_guardarCambiosHorarios,
       data: {cambiados: horarios_cambiados, borrados: horarios_borrados, nuevos: horarios_nuevos},
       //dataType: 'json',
       success: function(resultado){
           $('#nuevosHorarios').html(resultado);
       },
       error: function(error){
           $('#nuevosHorarios').html('error');
       }
    });
}

function calendario_nuevos_horarios(datos){
   var $calendar2 = $('#nuevosHorarios');
   $calendar2.weekCalendar({
        date: new Date(2013,6,1),
        showHeader:false,
        notOverlapInEdit: true,//allowCalEventOverlap debe estar en true
        //editOnlyNewEvent: true,//readonly debe estar en false y agregar la funcion en calendarAfterLoad
        timeSeparator: " a ",
        readonly: false,
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
      eventNew : function(calEvent, $event) {
            calEvent['title']=$('table#tabla_eventos > tbody > tr.row_selected > td:nth-child(1)').html();
            calEvent['body']=$('table#tabla_eventos > tbody > tr.row_selected > td:nth-child(2)').html();
            calEvent['disponibilidad']=$('#disponibilidad').prop('value');
            calEvent['salon']=$('table#tabla_salones > tbody > tr.row_selected > td').html();
            calEvent['id_salon'] = id_salon_seleccionado;
            horarios_nuevos["'"+calEvent['id']+"'"] = calEvent;
          $calendar2.weekCalendar("updateEvent", calEvent);
      },
      eventClick : function(calEvent, $event) {

      },
        deletable: function(calEvent, element) {
            if (calEvent['id_salon']!=id_salon_seleccionado || !calEvent['borrable']){
                return false;
            }       
          return true;
        },
        eventDelete: function(calEvent, element, dayFreeBusyManager, calendar, clickEvent) {
            if (calEvent['id'].charAt(0) != 'n') {
                if (horarios_cambiados["'"+calEvent.id+"'"])
                    delete horarios_cambiados["'"+calEvent.id+"'"];
                horarios_borrados["'"+calEvent['id']+"'"] = calEvent['id'];
                //console.log(horarios_borrados);
                //console.log(horarios_cambiados);
            }
            calendar.weekCalendar('removeEvent',calEvent.id);
	},
        eventDrop: function(calEvent, element) {
             if (calEvent['id'].charAt(0) != 'n') {
                horarios_cambiados["'"+calEvent.id+"'"] = calEvent;
                //console.log(horarios_cambiados);
            }
        },
        eventResize: function(calEvent, element) {
            if (calEvent['id'].charAt(0) != 'n') {
                horarios_cambiados["'"+calEvent.id+"'"] = calEvent;
                //console.log(horarios_cambiados);
            }
        },
        draggable: function(calEvent, element) {
            if (calEvent['id_salon']!=id_salon_seleccionado){
                return false;
            }            
          return true;
        },
        resizable: function(calEvent, element) {
            if (calEvent['id_salon']!=id_salon_seleccionado){
                return false;
            }            
          return true;
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
      },
      calendarBeforeLoad: function(calendar) {
            //calendar.weekCalendar('option','allowCalEventOverlap',false);
         //calendar.weekCalendar("data");
         //calendar.weekCalendar("refresh");
      }
      //calendarAfterLoad: function(calendar) {
          //if (calendar.weekCalendar('option').editOnlyNewEvent) {
              //calendar.weekCalendar('option','readonly',true);
          //}
      //}
   });

  
}
