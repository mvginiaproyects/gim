//var preciosActivos = new Array();
var filtroTipo = 'T0';

function preciosActivos(tipos,horarios){
    if (!$('#filtroTipos').length && tipos.length>1){
        var seleccionHtml = '';
        seleccionHtml += '<select id="filtroTipos">';
        seleccionHtml += '<option value="T0" selected="selected">Todos</option>';
        $.each(tipos, function(i, val) {
            switch (val){
                case 'Libre':
                    seleccionHtml += '<option value="L1">Libre</option>';
                    break;
                case 'Por clase':
                    seleccionHtml += '<option value="C3">Por clase</option>';
                    break;
                default:
                    seleccionHtml += '<option value="H'+horarios[i]+'">'+val+'</option>';
            }
        });
        seleccionHtml += '</select>';
        seleccionHtml += "<span id='cargandoTablaLista' class='fa fa-spinner fa-spin'></span>";
        //$('#tabla_lista_filter').append(seleccionHtml);
        $(seleccionHtml).insertBefore('#tabla_lista_wrapper');
        $('#filtroTipos').change(function(){
            filtroTipo = $(this).val();
            $('#cargandoTablaLista').show();
            $('#tabla_lista').dataTable().fnReloadAjax();
        });
    }
}

function format (d) {
        var horariosHtml = '';
        horariosHtml += 'Tipo de contrato: '+d.tipo+'</br>';
        $.each( d.horarios, function( key, horario ) {
            horariosHtml += horario.horario + '</br>';
        });
        return '<div id="warpLista" style="display: none;">'+horariosHtml+'</div>';
    }

$(document).ready(function(){
    var url = Yii.app.createUrl('administrarEventos/getListaSocio');
    tabla_horarios = $('#tabla_horarios').dataTable( {
            "sScrollY": '350px',
            "bPaginate": false,
            //"bRetrieve": true,
            "bPlaceHolder": 'Busqueda de horarios',
            "oLanguage": {
                "sEmptyTable": "No hay horarios registrados.",
                "sSearch": "",
                //"sInfo": "Mostrando _START_ a _END_ de _TOTAL_ eventos",
            },
            "scrollCollapse": true,
            'bInfo': false,
            //"bDestroy": true
    });
    
    table = $('#tabla_lista').DataTable({
        "sScrollY": '350px',
        "bPaginate": false,
        "sLoadingRecords": "Cargando...",
        //"bRetrieve": true,
        "bFilter": true,
        "bPlaceHolder": 'Busqueda de socios',
        "oLanguage": {
            "sSearch": "",
            "sEmptyTable": "No hay socios en este evento."
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
                "data": {id_evento:id_evento, filtro:filtroTipo},
                "success": function (data)
                {
                    preciosActivos(data.tipos,data.horarios);
                    $('#cargandoTablaLista').hide();
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
                    { "data": "nombreCompleto" },
                ],
                "order": [[1, 'asc']]
    });    

    // Add event listener for opening and closing details
    $('#tabla_lista tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
        if ( row.child.isShown() ) {
            // This row is already open - close it
            $(this).html('<i class="fa fa-plus-circle"></i>');
            $('#warpLista').slideUp(function(){
                row.child.hide();
            });
            tr.removeClass('shown');
        }
        else {
            // Open this row
            $(this).html('<i class="fa fa-minus-circle"></i>');
            row.child(format(row.data())).show();
            tr.addClass('shown');
            $('#warpLista').slideDown();
        }
    });

});

function guardarDatosFormModificarEvento(){
        var data=$("#evento-form").serialize();
        var url = Yii.app.createUrl('administrarEventos/detalles',{id_evento:id_evento});
        $.ajax({
            type: 'POST',
            url: url,
            data:data,
            beforeSend:function(){
                $('#btnenviar').html("<span id='span_enviar' class='fa fa-spinner fa-spin'></span>  Enviando......").removeClass('btn-primary').addClass('btn-info');
                $('#btnenviar').attr('disabled', 'disabled').addClass('disabled');
                $('#btncancelar').attr('disabled', 'disabled').addClass('disabled');
            },
            success:function(result){
                if (result=='')
                    window.location = url;
                else {
                    restaurarDatosFormModificarEvento();
                    $('#btnenviar').html('Guardar').removeAttr("disabled").removeClass("disabled").removeClass("btn-info").addClass("btn-primary");
                    mostrarMensaje('danger','Error',result);
                }
            },
            dataType:'html'
        });    
}

function restaurarDatosFormModificarEvento(){
    $('#Evento_nombre').val(nombreEvento);
    $('#Evento_id_profesor').prop('selectedIndex',idProfesor-1);    
    $('#myModal').modal('hide');    
}