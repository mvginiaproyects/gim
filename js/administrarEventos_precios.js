var precios_activos_guardar = {};

var precios_evento_seleccionado = [];

var idEvento = 0;

$(document).ready(function() {
    //$('#precios').html(html_precios_activos);
    if (idEventoSeleccionado!=0){
        //$("#tabla_eventos #"+idEventoSeleccionado).trigger( "click" );
        $("#tabla_eventos #"+idEventoSeleccionado).addClass('row_selected');
        idEvento = parseInt($("#tabla_eventos #"+idEventoSeleccionado).prop('id'));
        mostrarPreciosPorEvento($("#tabla_eventos #"+idEventoSeleccionado).prop('id'));
    }
    tabla_eventos = $('#tabla_eventos').dataTable( {
            "sScrollY": '200px',
            "bPaginate": false,
            //"bRetrieve": true,
            "bPlaceHolder": 'Busqueda de eventos',
            "oLanguage": {
                "sSearch": "",
                //"sInfo": "Mostrando _START_ a _END_ de _TOTAL_ eventos",
            },
            'bInfo': false,
            //"bDestroy": true
    });

    $("#tabla_eventos tbody tr").click( function( e ) {
        if ( $(this).hasClass('row_selected') ) {
            //$(this).removeClass('row_selected');
        }
        else {
            tabla_eventos.$('tr.row_selected').removeClass('row_selected');
            $(this).addClass('row_selected');
            idEvento = parseInt($(this).prop('id'));
            mostrarPreciosPorEvento($(this).prop('id'));
        }

    });

    $("#tabla_tipos tbody tr").click( function( e ) {
        $('#sinTipos').remove();
        agregarPrecioActivo(this);
    });

    $('#precios').delegate("input[name*='valor_']",'focus', function(event){
        $(this)
        .one('mouseup', function () {
            $(this).select();
            return false;
        })
        .select();
    });
    
    $('#precios').delegate("input[name*='valor_']",'keyup', function(event){
        var tipo = parseInt($(this).prop('id').substr(6,7));
        if (tipo==2){            
            var clases = $(this).attr('name').substr(8,9);
            precios_activos_guardar[tipo][clases].valor = parseInt($(this).val());
            if (precios_activos_guardar[tipo][clases].existe){
                if (sacarPrecio(tipo, clases)==parseInt($(this).val())){
                    precios_activos_guardar[tipo][clases].modificacion = 'ninguna';               
                } else {
                    precios_activos_guardar[tipo][clases].modificacion = 'modificado';                               
                }                
            }
        } else {
            precios_activos_guardar[tipo].valor = parseInt($(this).val());
            if (precios_activos_guardar[tipo].existe){
                if (sacarPrecio(tipo)==parseInt($(this).val())){
                    precios_activos_guardar[tipo].modificacion = 'ninguna';               
                } else {
                    precios_activos_guardar[tipo].modificacion = 'modificado';                               
                }                
            }            
        }
        $('#btn_guardar_cambios').removeClass('disabled');
    });
    
});

//muestra los valores de los precios cargados del evento seleccionado
//es llamado por el click en la tabla eventos
function mostrarPreciosPorEvento(idEvento){
    preciosEventoSeleccionado(idEvento);
    $('#precios').html('<pre>Precios Activos</pre><div id="precios_activos"><div id="nro0"></div><div id="nro1"></div><div id="nro2"></div><div id="nro3"></div></div>');
    var tipos_precios_activos = [];
    tipos_precios_activos = sacarTiposPrecios();
    $('#tabla_tipos>tbody>tr').show();
    $('#sinTipos').remove();
    tipos_precios_activos.forEach(function(tipo){        
        $('#t'+tipo).hide();
        if(!$('#t'+tipo).is(':visible')) {
            $(precios_html[tipo]).insertAfter($('#nro'+tipo));
            $('#s'+tipo).slideDown(250);
            cargarPrecio(parseInt(tipo));
            //$('#btn_guardar_cambios').removeClass('disabled');
        }
    });
    if (tipos_precios_activos.length==0){
        $('<blockquote id="sinTipos">Ningún precio disponible. Seleccione 1 o mas de la lista para agregarlo.</blockquote>').insertBefore($('#nro0'));
        $('#btn_guardar_cambios').addClass('disabled');
    }
    $('#btn_guardar_cambios').addClass('disabled');
}

//carga en la variable global "precios_evento_seleccionado", los precios con todos los datos
//de la tabla precios, para el evento seleccionado
function preciosEventoSeleccionado(idEvento){
    precios_evento_seleccionado = [];
    precios.forEach(function(precio){
       if (precio.id_evento==idEvento){
           precios_evento_seleccionado.push(precio);
       }
    });
}

//devuelve un array con los tipos de precios activos actualmente para el evento seleccionado
function sacarTiposPrecios(){
    var tipos = [0,1,2,3];
    var tipos_activos = [];
    //precios_activos_guardar[2] = {};

    precios_activos_guardar = {
        0:{
            modificacion: null, //ninguna, nuevo, eliminado, cambiado
            existe: false,
            idPrecio:0,
            valor: 0
        },
        1:{
            modificacion: null, //ninguna, nuevo, eliminado, cambiado
            existe: false,
            idPrecio:0,
            valor: 0        
        },
        2:{
            1:{
                modificacion: null, //ninguna, nuevo, eliminado, cambiado
                existe: false,
                idPrecio:0,
                valor: 0                    
            },
            2:{
                modificacion: null, //ninguna, nuevo, eliminado, cambiado
                existe: false,
                idPrecio:0,
                valor: 0                    
            },
            3:{
                modificacion: null, //ninguna, nuevo, eliminado, cambiado
                existe: false,
                idPrecio:0,
                valor: 0                    
            },
            4:{
                modificacion: null, //ninguna, nuevo, eliminado, cambiado
                existe: false,
                idPrecio:0,
                valor: 0                    
            },
            5:{
                modificacion: null, //ninguna, nuevo, eliminado, cambiado
                existe: false,
                idPrecio:0,
                valor: 0                    
            },
            6:{
                modificacion: null, //ninguna, nuevo, eliminado, cambiado
                existe: false,
                idPrecio:0,
                valor: 0                    
            }
        },
        3:{
            modificacion: null, //ninguna, nuevo, eliminado, cambiado
            existe: false,
            idPrecio:0,
            valor: 0        
        }
    };

    precios_evento_seleccionado.forEach(function(precio){
        var pos = jQuery.inArray(parseInt(precio.tipo), tipos);
        if (precio.tipo==2){
            //precios_activos_guardar[precio.tipo].push({clases_x_semana:precio.clases_x_semana, valor: precio.precio});
            precios_activos_guardar[precio.tipo][precio.clases_x_semana].valor = parseInt(precio.precio);
            precios_activos_guardar[precio.tipo][precio.clases_x_semana].modificacion = 'ninguna';
            precios_activos_guardar[precio.tipo][precio.clases_x_semana].existe = true;
            precios_activos_guardar[precio.tipo][precio.clases_x_semana].idPrecio = precio.id_precio;
        } else {
            precios_activos_guardar[precio.tipo].valor = parseInt(precio.precio);
            precios_activos_guardar[precio.tipo].modificacion = 'ninguna';
            precios_activos_guardar[precio.tipo].existe = true;
            precios_activos_guardar[precio.tipo].idPrecio = precio.id_precio;
        }
        if (pos!=-1){//si encontro un tipo de precio cargado para el evento
            tipos.splice(pos,1);
            tipos_activos.push(precio.tipo);
        }
        //if (tipos_activos.length==4)
            //return tipos_activos;
    });
    return tipos_activos;
}

//devuelve el precio del tipo "tipo"

function sacarPrecio(tipo, clases){
    for (index in precios_evento_seleccionado){
        if (parseInt(precios_evento_seleccionado[index].tipo)==tipo){
            if (tipo==2){
                if (precios_evento_seleccionado[index].clases_x_semana==clases)
                    return parseInt(precios_evento_seleccionado[index].precio);
            } else
                return parseInt(precios_evento_seleccionado[index].precio);
        }
    }
}

function cargarPrecio(tipo){
    switch (tipo){
        case 0:
            $('input[name=valor_inscripcion]').val(sacarPrecio(tipo));
            break;
        case 1:
            $('input[name=valor_libre]').val(sacarPrecio(tipo));
            break;
        case 2:
            cargarPrecioSemanal();
            break;
        case 3:
            $('input[name=valor_clase]').val(sacarPrecio(tipo));
            break;        
    }
}

function cargarPrecioSemanal(){
    $.each(precios_activos_guardar[2], function(dias_x_semana, precio){
        if (precio.modificacion!=null){
            $('#d'+dias_x_semana).html(sub_seccion_semanal_html);
            $('#d'+dias_x_semana).find('#cant_dias_semanal').html(dias_x_semana+' dias por semana<div class="pull-right"></div>');
            $('#d'+dias_x_semana).find('input').val(precio.valor);
            $('#d'+dias_x_semana).find('input').attr('name','valor_2_'+dias_x_semana);
            $('#d'+dias_x_semana).slideDown();
            $('select[name=dias_x_semana] option[value='+dias_x_semana+']').remove();
            //$('select[name=dias_x_semana] option[value='+dias_x_semana+']').hide();            
        }
    });
}

function agregarPrecioSemanal(){
    if( $('#dias_x_semana').val() ) {
        var dias_x_semana = $("#dias_x_semana option:selected").val();
        $('#d'+dias_x_semana).html(sub_seccion_semanal_html);
        $('#d'+dias_x_semana).find('#cant_dias_semanal').html(dias_x_semana+' dias por semana<div class="pull-right"></div>');
        $('#d'+dias_x_semana).find('input').val(0);
        $('#d'+dias_x_semana).find('input').attr('name','valor_2_'+dias_x_semana);
        $('#d'+dias_x_semana).slideDown();
        $('#d'+dias_x_semana).find('input').focus();
        $("#dias_x_semana option:selected").remove();
        if (precios_activos_guardar[2][dias_x_semana].existe){
            precios_activos_guardar[2][dias_x_semana].modificacion = 'modificado';
            precios_activos_guardar[2][dias_x_semana].valor = 0;        
        } else {
            precios_activos_guardar[2][dias_x_semana].modificacion = 'nuevo';
            precios_activos_guardar[2][dias_x_semana].valor = 0;        
        }
        $('#btn_guardar_cambios').removeClass('disabled');
    }
}

function quitarPrecioSemanal(item){
    var clases = $(item).parent().parent().parent().parent().find('input').attr('name').substr(8,9);
    if (precios_activos_guardar[2][clases].existe){
        precios_activos_guardar[2][clases].modificacion = 'eliminado';
    } else {
        precios_activos_guardar[2][clases].modificacion = null;
    }
    agregarOpcionEnOrden(parseInt(clases));
    $('#btn_guardar_cambios').removeClass('disabled');
    $(item).parent().parent().parent().parent().slideUp().children().remove();
}

function agregarOpcionEnOrden(clases){
    var paso = false;
    $.each($('#dias_x_semana option'), function(index, opcion){
       if (parseInt($(opcion).val())>clases){
           $('<option value="'+clases+'">'+clases+'</option>').insertBefore($(opcion)); 
           paso = true;
           return false;
       }
    });
    if (!paso){
        $("#dias_x_semana").append('<option value="'+clases+'">'+clases+'</option>');
    }
}

function agregarPrecioActivo(tipo_seleccionado){
    var tipo = parseInt($(tipo_seleccionado).prop('id').substring(1,2));
    if (tipo!=2){
        if (precios_activos_guardar[tipo].existe){
            precios_activos_guardar[tipo].modificacion = 'modificado';
            precios_activos_guardar[tipo].valor = 0;
        } else {
            precios_activos_guardar[tipo].modificacion = 'nuevo';
        }
    }
    $(precios_html[tipo]).insertAfter($('#nro'+tipo));
    $('#s'+tipo).slideDown(250).find('input').focus();
    $(tipo_seleccionado).hide();
    $('#btn_guardar_cambios').removeClass('disabled');
}

function quitarTipoActivo(tipo){
    if (tipo!=2){
        if (precios_activos_guardar[tipo].existe){
            precios_activos_guardar[tipo].modificacion = 'eliminado';
        } else {
            precios_activos_guardar[tipo].modificacion = null;
        }
    } else {
        $.each(precios_activos_guardar[2], function(clases, precio) {
            if (precio.existe){
                precio.modificacion = 'eliminado';
            } else {
                precio.modificacion = null;
            }
        });       
    }
    $('#t'+tipo).show();
    $('#s'+tipo).slideUp(250, function(){
        $(this).remove();
    });
    $('#btn_guardar_cambios').removeClass('disabled');
}

function guardarCambios(){
    var url = Yii.app.createUrl('administrarEventos/precios');
    $.ajax({
        type:'POST',
        url: url,
        data: {precios: precios_activos_guardar, idEvento: idEvento},
        beforeSend: function(){
            $('#btn_guardar_cambios').html("<span id='span_enviar' class='fa fa-spinner fa-spin'></span>  Guardando...").addClass("disabled");
        },
        success: function(datos){
            //$('#precios_activos').html(datos);
            //$('#btn_guardar_cambios').html("Guardar cambios");
            var url2 = Yii.app.createUrl('administrarEventos/guardadoCorrecto',{idEvento: idEvento});
            window.location = url2;
        },
        error: function(datos){
            alert('Error');
        }
    });
}