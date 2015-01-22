function format (d) {
    // `d` is the original data object for the row
    var tablaAsistencia = '';
    $.each( d.alumnos, function( key, alumno ) {
        tablaAsistencia += '<tr>';
        tablaAsistencia += '<td>'+alumno.apellido+'</td>';
        var siTipo3 = '  <span class="badge badge-info">Por clase</span>';
        tablaAsistencia += '<td>'+alumno.nombre +((alumno.tipo_contrato==3)? siTipo3:'')+'</td>';
        tablaAsistencia += '<td>';
        var id_input = 'asistencia-S'+alumno.id_socio+'_H'+d.id_horario+'_T'+alumno.tipo_contrato+'_C'+alumno.id_contrato;
        tablaAsistencia += '<input id="'+id_input+'" style="display:inline" type="checkbox" value="1" name="asistio" '+((alumno.asistio)? 'checked':'')+'>';
        tablaAsistencia += "<span id='error_"+id_input+"' class='badge badge-important' style='display: none'>Limite alcanzado!</span>";
        tablaAsistencia += "<span id='span_"+id_input+"' class='fa fa-spinner fa-spin' style='display: none'></span>";
        tablaAsistencia += '</td>';
        tablaAsistencia += '</tr>';
    });
    return '<div id="warpAsist" style="display: none;"><table id="asistenciaTabla-'+d.id_horario+'" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
            '<thead><tr><th>Apellido</th><th>Nombre</th><th><input id="all-S0_H'+d.id_horario+'_T0_C0" style="display:inline" type="checkbox" value="1" name="asistioAll"> Asistio</th></tr></thead>'+
        tablaAsistencia+
    '</table>';
}

var table;

function procesar(fecha){
    $('.diaSeleccionado').html($.datepicker.formatDate('DD', $(fecha).datepicker('getDate')));
    $('#cargandoTablaAsistencia').show();
    $('#tablaAsistencias').dataTable().fnReloadAjax();
}

function contraer(contraidos){
    $.each(contraidos, function(i, valor){
        swap($('#btn_'+valor),$('#warp_'+valor));
    })
}

function swap(boton,warp){
    //console.log(warp);
    if ($(boton).find('i').hasClass('icon-chevron-up')){
        $(boton).attr('title','Expandir');
        $(warp).slideUp();
        $(boton).find('i').removeClass().addClass('icon-chevron-down');
        $.ajax({
            type:'POST',
            data:{seccion:$(boton).attr('id').substring(4),accion:0},
            url: Yii.app.createUrl('site/contraidos'),
        })
    } else {
        $(boton).attr('title','Contraer');
        $(warp).slideDown();
        $(boton).find('i').removeClass().addClass('icon-chevron-up');        
        $.ajax({
            type:'POST',
            data:{seccion:$(boton).attr('id').substring(4),accion:1},
            url: Yii.app.createUrl('site/contraidos'),
        })
        tabla_socios_vencidos.fnAdjustColumnSizing();
        tabla_socios.fnAdjustColumnSizing();
    console.log($(boton).attr('id').substring(4));
    }
}

function reordenarTablas(filaIni,columnaIni,filaFin,columnaFin){
            console.log('Fila ini: '+filaIni+' Col ini: '+ columnaIni + 'Fila fin: '+filaFin+' Col fin: '+ columnaFin);
    $.ajax({
        type:'POST',
        data:{filaIni:filaIni,columnaIni:columnaIni,filaFin:filaFin,columnaFin:columnaFin},
        url: Yii.app.createUrl('site/reordenarTablas'),
        success: function(resultado){
            console.log(resultado);
        },
        //datatType: 'json'
    })
}
var filaIni = 0;
var columnaIni = 0;
$(document).ready(function() {

    //$( "#sortable1, #sortable2" ).sortable({
      //connectWith: ".connectedSortable",
      //placeholder: "ui-state-highlight",
      //handle: "blockquote"
    //}).disableSelection();

    $( "#sortable1, #sortable2" ).sortable({
        connectWith: ".connectedSortable",
        //dropOnEmpty: true,
        placeholder: "ui-state-highlight",
        handle: "blockquote",
        start: function (event, ui) {
            filaIni = parseInt(ui.item.index());
            columnaIni = parseInt($(ui.item).parent().attr('id').substring(8));
            //console.log('Poss1: '+currPos1);
        },
        stop:  function (event, ui) {
            var currPos2 = ui.item.index();
            reordenarTablas(filaIni+1, columnaIni, parseInt(ui.item.index())+1,parseInt($(ui.item).parent().attr('id').substring(8)));
        }      
    });

    var url = Yii.app.createUrl('administrarAsistencias/asistencias');
    tabla_socios_vencidos = $('#tabla_socios_vencidos').dataTable( {
            //"sScrollY": '200px',
            //"bCollapse": true,
    "scrollY":        "200px",
    "scrollCollapse": true,
    "bPaginate": false,
    //"bRetrieve": true,
    "bPlaceHolder": 'Busqueda de socios',
    "oLanguage": {
        "sSearch": "",
        "sEmptyTable": "No hay contratos vencidos"
        //"sInfo": "Mostrando _START_ a _END_ de _TOTAL_ eventos",
    },
    'bInfo': false,
    //"bDestroy": true
    });
    tabla_socios = $('#tabla_socios').dataTable( {
    "scrollY":        "200px",
    //"scrollCollapse": true,
    "bPaginate": false,
            "bPlaceHolder": 'Busqueda rapida de socios',
            "oLanguage": {
                "sSearch": "",
                "sEmptyTable": "No hay socios cargados"
                //"sInfo": "Mostrando _START_ a _END_ de _TOTAL_ eventos",
            },
            'bInfo': false,
            //"bDestroy": true
    });

    table = $('#tablaAsistencias').DataTable({
        "bPaginate": false,
        "sLoadingRecords": "Cargando...",
        //"bRetrieve": true,
        "bFilter": true,
        "bPlaceHolder": 'Busqueda de eventos',
        "oLanguage": {
            "sSearch": "",
            "sEmptyTable": "No hay eventos con horarios en esta fecha."
            //"sInfo": "Mostrando _START_ a _END_ de _TOTAL_ eventos",
        },
        'bInfo': false,
        "sAjaxSource": url,
        "fnServerData": function (sSource, aoData, fnCallback) {
            $.ajax({
                "dataType": 'json',
                //"contentType": "application/json; charset=utf-8",
                "type": "POST",
                "url": sSource,
                "data": {fecha:$('#asistenciaFecha').val()},
                "success": function (data)
                {
                    $('#cargandoTablaAsistencia').hide();
                    fnCallback(data);
                }
            });
        },
        "deferLoading": 50,
        "columns": [
                    {
                        "class":          'details-control',
                        "orderable":      false,
                        "data":           null,
                        "defaultContent": '<i class="fa fa-plus-circle"></i>'
                    },
                    { "data": "evento" },
                    { "data": "profesor" },
                    { "data": "horario" },
                ],
                "order": [[1, 'asc']]
    });    

    var inputt;
    $('#span_asistencias').delegate('input[type="search"]','keydown',function(event){
        inputt = $(this);
        setTimeout(function() {
            $(inputt).focus();
        }, 1);

    });
    // Add event listener for opening and closing details
    $('#tablaAsistencias tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
        if ( row.child.isShown() ) {
            // This row is already open - close it
            $(this).html('<i class="fa fa-plus-circle"></i>');
            //$('#warpAsist').slideUp(function(){
            var tablaAsistencia = $(this).parent().next().find('table');
            $(tablaAsistencia).parent().parent().slideUp(function(){;
                row.child.hide();
            });

            tr.removeClass('shown');
        }
        else {
            // Open this row
            $(this).html('<i class="fa fa-minus-circle"></i>');
            row.child(format(row.data())).show();
            var tablaAsistencia = $(this).parent().next().find('table');
            $(tablaAsistencia).dataTable( {
                "bPaginate": false,
                "bFilter": true,
                "bPlaceHolder": 'Busqueda de socios',
                "oLanguage": {
                    "sSearch": "",
                    "sEmptyTable": "Noy a socios que asistan en este horario."
                    //"sInfo": "Mostrando _START_ a _END_ de _TOTAL_ eventos",
                },
                'bInfo': false,
                //"fnInitComplete":function(){
                    //console.log('hola');
                //},
            });
            $(tablaAsistencia).parent().parent().slideDown();
            //$('#warpAsist').slideDown();
            tr.addClass('shown');
        }
    });

    $(window).bind('resize', function () {
        tabla_socios_vencidos.fnAdjustColumnSizing();
        tabla_socios.fnAdjustColumnSizing();
    } );        

    eventosCheck();

    //var diaDeLaSemana=$.datepicker.formatDate('DD', $('#asistenciaFecha').datepicker().datepicker('getDate'));
    var weekday=new Array();
        weekday['Monday']="Lunes";
        weekday['Tuesday']="Martes";
        weekday['Wednesday']="Miércoles";
        weekday['Thursday']="Jueves";
        weekday['Friday']="Viernes";
        weekday['Saturday']="Sábado";
        weekday['Sunday']="Domingo";        
    $("<div class='diaSeleccionado'>"+weekday[$.datepicker.formatDate('DD', new Date)]+"</div>").insertAfter('#tablaAsistencias_filter input');

} );
function eventosCheck(){
    //$('#tablaAsistencias').delegate('input[id|="asistencia"]','click',function(event){
    $('#tablaAsistencias').delegate('input[type="checkbox"]','change',function(event){
        var self=$(this);
        var idVal = $(this).prop('id');
        var sPos = idVal.indexOf('S');
        var hPos = idVal.indexOf('H');
        var tPos = idVal.indexOf('T');
        var cPos = idVal.indexOf('C');
        var id_socio = idVal.substring(sPos+1,hPos-1);
        var id_horario = idVal.substring(hPos+1,tPos-1);
        var tipo_contrato = idVal.substring(tPos+1,cPos-1);
        var id_contrato = idVal.substring(cPos+1);
        var url = '';
        if ($(this).prop('name')=='asistioAll'){
            var identificador = 'asistenciaTabla-'+id_horario;
            if ($(this).is(":checked")) {
                $('input[id|="asistencia"]','#'+identificador).prop('checked',true);
                $('input[id|="asistencia"]','#'+identificador).trigger('change');
            } else {
                $('input[id|="asistencia"]','#'+identificador).prop('checked',false);
                $('input[id|="asistencia"]','#'+identificador).trigger('change');
            }
        } else {
            if ($(this).is(":checked")) {
                url = Yii.app.createUrl('administrarAsistencias/presente');
            } else {
                url = Yii.app.createUrl('administrarAsistencias/ausente');                
            }
            $.ajax({
                //"dataType": 'json',
                //"contentType": "application/json; charset=utf-8",
                "type": "POST",
                "url": url,
                "data": {id_socio: id_socio, id_horario: id_horario, fecha:$('#asistenciaFecha').val(), tipo_contrato: tipo_contrato, id_contrato: id_contrato},
                beforeSend: function(xhr) {
                    $('#span_'+idVal).show();
                },
                "success": function (data)
                {
                    $('#span_'+idVal).hide();
                    if (data=='no'){
                        $(self).attr('checked',false);
                        $('#error_'+idVal).css('position','absolute').show().animate({opacity: 1.0}, 700).fadeOut('slow');
                        //$('#error_'+idVal).css('position','absolute').show();
                    }
                },
                'error': function (data){
                    console.log('error');
                }
            });
        }

    });        
}
