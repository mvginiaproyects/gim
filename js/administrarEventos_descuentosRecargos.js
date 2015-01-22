var editar_id_dr = 0;

$(document).ready(function(){

    $('#DescuentoRecargo_tipo').change(function(){
        $('#DescuentoRecargo_cargar').html('Cargar '+$(this).val());
    });
    
    $('#DescuentoRecargo_modo').change(function(){
        if ($('#DescuentoRecargo_modo').val()=='%'){
            $('#DescuentoRecargo_monto').prop('max','100');
        }else{
            $('#DescuentoRecargo_monto').prop('max','999');            
        }
    });

    $('#DescuentoRecargo_descripcion').on('change keyup',function(){
        if ($('#DescuentoRecargo_monto').val()>0 && $(this).val()!=''){
            $('#DescuentoRecargo_cargar').removeAttr("disabled");            
        } else {
            $('#DescuentoRecargo_cargar').attr("disabled", "disabled");            
        }
        
    });
    
    $('#DescuentoRecargo_monto').on('change keyup',function(){
        if ($('#DescuentoRecargo_descripcion').val()!='' && $(this).val()>0){
            $('#DescuentoRecargo_cargar').removeAttr("disabled");            
        } else {
            $('#DescuentoRecargo_cargar').attr("disabled", "disabled");            
        }
    });
    
});

function eliminar(id){
    if (editar_id_dr==0){
        $('#fila'+id).find('i.icon-remove').removeClass().addClass('fa fa-spinner fa-spin');
        $.ajax({
            type: 'POST',
            url: Yii.app.createUrl('administrarEventos/eliminarDR',{id_dr:id}),
            beforeSend: function(){
                $('#fila'+id).find('i.icon-remove').removeClass().addClass('fa fa-spinner fa-spin');    
            },
            success: function(data){
                //console.log(data);
                $('#fila'+id).remove();
            },
        });
    }
}

function modificar(id, tipo){
    if (editar_id_dr==0){
        editar_id_dr = id;
        $('#fila'+id).find('i.fa-pencil-square-o').removeClass().addClass('fa fa-spinner fa-spin');
        var descripcion = $('#fila'+id+' td:nth-child(1)').html();
        var modo = $('#fila'+id+' td:nth-child(2)').html();
        var monto = $('#fila'+id+' td:nth-child(3)').html();
        
        $('#DescuentoRecargo_tipo').val(tipo);        
        $('#DescuentoRecargo_modo').val(modo);
        $('#DescuentoRecargo_descripcion').val(descripcion);
        $('#DescuentoRecargo_monto').val(monto);
        $('#DescuentoRecargo_tipo').attr("disabled", "disabled");
        $('#DescuentoRecargo_cargar').hide();
        $('#btncancelar').show();
        $('#btneditar').show();
        
    }
}

function cancelarEdicion() {
    $('#DescuentoRecargo_descripcion').val('');
    $('#DescuentoRecargo_monto').val('');    
    $('#btncancelar').hide();
    $('#btneditar').hide();
    $('#DescuentoRecargo_cargar').show();
    $('#DescuentoRecargo_tipo').removeAttr("disabled");
    $('#tablas_descuentos_recargos').find('i.fa.fa-spinner.fa-spin').removeClass().addClass('fa fa-pencil-square-o');
    editar_id_dr=0;
}

function guardarDescuentoRecargo(){
    if (!$('#DescuentoRecargo_cargar').attr('disabled')){
        var datos = getFormData($('#horizontalForm'));
        var txtbtn = $('#DescuentoRecargo_cargar').html();
        $.ajax({
            type: 'POST',
            data: {modo:'guardar', datos: datos},
            url: Yii.app.createUrl('administrarEventos/guardarDR'),
            beforeSend: function(){
                $('#DescuentoRecargo_cargar').html('Guardando... <i class="fa fa-spinner fa-spin"></i>').attr("disabled", "disabled");
            },
            success: function(data){
                console.log(data);
                var descripcion = $('#DescuentoRecargo_descripcion').val();
                var modo = $('#DescuentoRecargo_modo').val();
                var monto = $('#DescuentoRecargo_monto').val();
                $('#DescuentoRecargo_descripcion').val('');
                $('#DescuentoRecargo_monto').val('');
                $('#DescuentoRecargo_cargar').html(txtbtn);
                var tipo_sel = $('#DescuentoRecargo_tipo').val();
                var nuevaFila = '';
                nuevaFila += '<tr id="fila'+data+'"><td>';
                nuevaFila += descripcion;
                nuevaFila += '</td><td>';
                nuevaFila += modo;
                nuevaFila += '</td><td>';
                nuevaFila += monto;
                nuevaFila += '</td><td>';
                nuevaFila +='<a class="btn btn-small" title="Editar" onclick="js:modificar('+data+','+"'"+tipo_sel+"'"+')"><i class="fa fa-pencil-square-o"></i></a>';
                nuevaFila +='<a class="btn btn-small" title="Eliminar" onclick="js:eliminar('+data+')"><i class="icon-remove"></i></a>';
                nuevaFila += '</td></tr>';
                $('#tabla_'+tipo_sel+'s_activos > tbody').append(nuevaFila);
            },
        });
    }
}

function getFormData($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i){
        var txt = n['name'].split('[')[1].split(']')[0];
        indexed_array[txt] = n['value'];
    });

    return indexed_array;
}

function guardarEdicion(){
    var datos = getFormData($('#horizontalForm'));
    $.ajax({
        type: 'POST',
        data: {id:editar_id_dr, modo:'editar', datos: datos},
        url: Yii.app.createUrl('administrarEventos/guardarDR'),
        beforeSend: function(){
            $('#btncancelar').hide();
            $('#btneditar').html('Guardando... <i class="fa fa-spinner fa-spin"></i>').attr("disabled", "disabled");
        },
        success: function(data){
            var descripcion = $('#DescuentoRecargo_descripcion').val();
            var modo = $('#DescuentoRecargo_modo').val();
            var monto = $('#DescuentoRecargo_monto').val();
            $('#fila'+data+' > td:nth-child(1)').html(descripcion);
            $('#fila'+data+' > td:nth-child(2)').html(modo);
            $('#fila'+data+' > td:nth-child(3)').html(monto);
            $('#DescuentoRecargo_descripcion').val('');
            $('#DescuentoRecargo_monto').val('');
            $('#btncancelar').hide();
            $('#btneditar').hide().html('Guardar').removeAttr("disabled");
            $('#DescuentoRecargo_cargar').show();
            $('#tablas_descuentos_recargos').find('i.fa.fa-spinner.fa-spin').removeClass().addClass('fa fa-pencil-square-o');
            $('#DescuentoRecargo_tipo').removeAttr("disabled");
            editar_id_dr=0;
        },
    });
    
}
