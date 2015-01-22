<script>
    function format (d) {
        // `d` is the original data object for the row
        var tablaAsistencia = '';
        $.each( d.alumnos, function( key, alumno ) {
            tablaAsistencia += '<tr>';
            tablaAsistencia += '<td>'+alumno.apellido+'</td>';
            tablaAsistencia += '<td>'+alumno.nombre+'</td>';
            tablaAsistencia += '<td>';
            var id_input = 'a_S'+alumno.id_socio+'_H'+d.id_horario;
            tablaAsistencia += '<input id="'+id_input+'" style="display:inline" type="checkbox" value="1" name="asistio" '+((alumno.asistio)? 'checked':'')+'>';
            tablaAsistencia += "<span id='span_"+id_input+"' class='fa fa-spinner fa-spin' style='display: none'></span>";
            tablaAsistencia += '</td>';
            tablaAsistencia += '</tr>';
        });
        return '<div id="warpAsist" style="display: none;"><table id="asistencia'+d.id_evento+'" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
                '<thead><tr><th>Apellido</th><th>Nombre</th><th>Asistio</th></tr></thead>'+
            tablaAsistencia+
        '</table>';
    }
    var url = Yii.app.createUrl('pruebas/asistencias');
    var table;

    function procesar(fecha){
        $('#tablaAsistencias').dataTable().fnReloadAjax();
    }
    $(document).ready(function() {
       //asistenciasDelDia('fecha');
        table = $('#tablaAsistencias').DataTable({
            "bPaginate": false,
            //"bRetrieve": true,
            "bFilter": true,
            "bPlaceHolder": 'Busqueda de eventos',
            "oLanguage": {
                "sSearch": "",
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
                        fnCallback(data);
                    }
                });
            },
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
        $('body').delegate('input[type="search"]','keydown',function(event){
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
                $('#warpAsist').slideUp(function(){
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
                        //"sInfo": "Mostrando _START_ a _END_ de _TOTAL_ eventos",
                    },
                    'bInfo': false,
                    //"fnInitComplete":function(){
                        //console.log('hola');
                    //},
                });
                $('#warpAsist').slideDown();
                tr.addClass('shown');
                eventosCheck();
            }
        } );
    } );
    function eventosCheck(){
        $('#tablaAsistencias').delegate('input[type="checkbox"]','click',function(event){
            var idVal = $(this).prop('id');
            var sPos = idVal.indexOf('S');
            var hPos = idVal.indexOf('H');
            var id_socio = idVal.substring(sPos+1,hPos-1);
            var id_horario = idVal.substring(hPos+1);
            var url = '';
            if ($(this).is(":checked")) {
                url = Yii.app.createUrl('pruebas/presente');
            } else {
                url = Yii.app.createUrl('pruebas/ausente');                
            }
            $.ajax({
                "dataType": 'json',
                //"contentType": "application/json; charset=utf-8",
                "type": "POST",
                "url": url,
                "data": {id_socio: id_socio, id_horario: id_horario, fecha:$('#asistenciaFecha').val()},
                beforeSend: function(xhr) {
                    $('#span_'+idVal).show();
                },
                "success": function (data)
                {
                    $('#span_'+idVal).hide();
                }
            });
            
        });        
    }
</script>
<div class="row-fluid">
    <div class="span6">
        <?php 
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                //'model' => $model,
                //'attribute' => 'fecha_nac',
                'name' => 'asistencia',
                'value' => date('Y-m-d'),
                'options' => array(
                        //'showOn' => 'both',             // also opens with a button
                        'dateFormat' => 'yy-mm-dd',     // format of "2012-12-25"
                        'language'=>'es',
                        'showOtherMonths' => true,      // show dates in other months
                        'selectOtherMonths' => true,    // can seelect dates in other months
                        'changeYear' => true,           // can change year
                        'changeMonth' => true,          // can change month
                        'yearRange' => '1960:date(y)',     // range of year
                        'onClose' =>'js:function(date){procesar(date)}'
                        ////'yearRange' => '-99:+2',
                        //'minDate' => '1960-01-01',      // minimum date
                        //'maxDate' => '2099-12-31',      // maximum date
                        //'showButtonPanel' => true,      // show button panel
                ),
                'htmlOptions' => array(
                    'size' => '10',         // textField size
                    'maxlength' => '10',    // textField maxlength
                    'id'=>'asistenciaFecha'
                ),
            ));
        
        ?>
    </div>
</div>
<div class="row-fluid">
    <row class="span6">
        <table id="tablaAsistencias" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th></th>
                    <th>Evento</th>
                    <th>Profesor</th>
                    <th>Horario</th>
                </tr>
            </thead>
        </table>        
    </row>
</div>
