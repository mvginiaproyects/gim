<?php
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl.'/js/administrarContratos.js');
    $precio = $this->renderPartial('_precio', array('forma'=>'Contratar'), true);
?>
<style type='text/css'>

            .seleccionar_evento{
                background-color: #00CC00;
            }
            .grupo_evento_1{
                background-color: rgb(168, 134, 134);
            }
            .wc-toolbar {
                font-size: 10px;
                padding: 2px 5px;
                margin: 0;
            }
	
</style>

<script type="text/javascript">
    ayuda = true;
    $('#ayuda').html(<?php echo CJSON::encode($this->renderPartial('_ayuda',null,true))?>);
    $('#btn_ayuda>a').addClass('ayudaActiva');


    var datosAEnviar = {
        cuenta_socio:{
            id_descuento: 0,
            descuento: 0,
            prorrateo: false,
            valor_prorrateo: 0,
            inscripcion: false,
            valor_inscripcion: 0,
            precioParcial: 0
        },
        contrato:{
            id_evento: null,
            id_socio: <?php echo $this->id_socio?>,
            tipoContrato: null,
            cantidadClases: null,
            vencimiento: null
        },
        horarios_contratados: {},
        precioNuevoHorarios: {
            clasesXSemana: null,
            precio: null,
            nuevo: false //falso si el precio de los horarios seleccionados existe, verdadero lo contrario
        }
    };
    var html_precio = <?php echo CJSON::encode($precio);?>;
    var largo_tabla = '<?php echo (count($eventos)>5)? '100px':''; ?>';
    var tabla;
    var evento_seleccionado = new Array();
    var horarios_seleccionados = new Array();
    var diasMostrados = 6;
    var datosEventoSeleccionado;
    $(document).ready(function() {

        $('#horarios').delegate('#prorrateo_check','click',function(event){
            if ($(this).is(":checked")) {
                calcularPrecioConDescuento(datosAEnviar.cuenta_socio.precioParcial);
                //$("input[name=prorrateo_valor]").prop({'disabled': false, 'value':calcularProrrateo(500)});                  
                datosAEnviar.cuenta_socio.prorrateo = true;
            } else {
                calcularPrecioConDescuento(datosAEnviar.cuenta_socio.precioParcial);
                $("input[name=prorrateo_valor]").prop({'disabled': true, 'value':0});  
                datosAEnviar.cuenta_socio.prorrateo = false;
            }
        });

        $('#precio_inferior').delegate('#prorrateo_check','click',function(event){
            if ($(this).is(":checked")) {
                calcularPrecioConDescuento(datosAEnviar.cuenta_socio.precioParcial);
                //$("input[name=prorrateo_valor]").prop({'disabled': false, 'value':calcularProrrateo(500)});                  
                datosAEnviar.cuenta_socio.prorrateo = true;
            } else {
                calcularPrecioConDescuento(datosAEnviar.cuenta_socio.precioParcial);
                $("input[name=prorrateo_valor]").prop({'disabled': true, 'value':0});  
                datosAEnviar.cuenta_socio.prorrateo = false;
            }    
        });

        $('#horarios').delegate('#inscripcion_check','click',function(event){
            if ($(this).is(":checked")) {
                calcularPrecioConDescuento(datosAEnviar.cuenta_socio.precioParcial);
                //$("input[name=prorrateo_valor]").prop({'disabled': false, 'value':calcularProrrateo(500)});                  
                datosAEnviar.cuenta_socio.inscripcion = true;
            } else {
                calcularPrecioConDescuento(datosAEnviar.cuenta_socio.precioParcial);
                //$("input[name=inscripcion_valor]").prop({'disabled': true, 'value':0});  
                datosAEnviar.cuenta_socio.inscripcion = false;
            }
        });

        $('#precio_inferior').delegate('#inscripcion_check','click',function(event){
            if ($(this).is(":checked")) {
                calcularPrecioConDescuento(datosAEnviar.cuenta_socio.precioParcial);
                //$("input[name=prorrateo_valor]").prop({'disabled': false, 'value':calcularProrrateo(500)});                  
                datosAEnviar.cuenta_socio.inscripcion = true;
            } else {
                calcularPrecioConDescuento(datosAEnviar.cuenta_socio.precioParcial);
                //$("input[name=inscripcion_valor]").prop({'disabled': true, 'value':0});  
                datosAEnviar.cuenta_socio.inscripcion = false;
            }    
        });


        //url = Yii.app.createUrl('administrarContratos/contratar', {id: 1, nombre: 'juan'});
        tabla = $('#tabla_eventos').dataTable( {
                "sScrollY": largo_tabla,
                "bPaginate": false,
                //"bRetrieve": true,
                "bPlaceHolder": 'Busqueda de eventos',
                "oLanguage": {
                    "sEmptyTable": "No hay eventos cargados",
                    "sSearch": "",
                    //"sInfo": "Mostrando _START_ a _END_ de _TOTAL_ eventos",
                },
                'bInfo': false,
                //"bDestroy": true

        } );
    
        $("#tabla_eventos tbody tr").click( function( e ) {
            if ( $(this).hasClass('row_selected') ) {
                //$(this).removeClass('row_selected');
            }
            else {
                tabla.$('tr.row_selected').removeClass('row_selected');
                $(this).addClass('row_selected');
            }
            var filaSeleccionada = obtenerFilaSeleccionada(tabla);
            if ( filaSeleccionada.length !== 0 ) {
                //fila = filaSeleccionada[0];
                var id = $('tr.row_selected').attr('id');
                var evento = $('tr.row_selected>td').html();

                $('#horarios').html("<span id='span_enviar' class='fa fa-spinner fa-spin'></span> Buscando horarios...");
                
                var url = '<?php echo Yii::app()->createAbsoluteUrl("administrarContratos/horarios"); ?>'; 
                /*$.post(url, {id_evento: id}, function(data, textStatus) {
                        console.log(data);
                  }, "json");*/
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {id_evento: id, id_socio: <?php echo $this->id_socio;?>},
                    dataType: 'json',
                    beforeSend:function(){
                        horarios_seleccionados = [];
                        evento_seleccionado = [];
                        //$('#resumen_precio').html('precio a pagar...');
                        evento_seleccionado[0] = id;
                        evento_seleccionado[1] = evento;
                        datosAEnviar.contrato.id_evento = parseInt(id);
                        $('#resumen_precio').remove();
                        $('#tabla_opciones').fadeOut();
                        $('#horarios').html("</br></br></br><span id='span_enviar' class='fa fa-spinner fa-spin'></span> Buscando horarios...");
                    },
                    success:function(resultado){
                        datosEventoSeleccionado = resultado;
                        $('#tabla_opciones .row_selected').removeClass('row_selected');
                        if (resultado.tipo_contratos.tipos>1){
                            $('#opcion_libre').css('display',(resultado.tipo_contratos.tipo_1)? '':'none');
                            if (resultado.tipo_contratos.tipo_2){
                                if (datosEventoSeleccionado.events.length==0){
                                    if (resultado.tipo_contratos.tipo_3){
                                        $('#opcion_horario').css('display','none');
                                        $('#opcion_clase').css('display','none');
                                        $('#tabla_opciones').fadeIn();
                                        $('#horarios').html('</br></br></br><blockquote><p>Seleccione el modo de pago</p>'+
                                                '<p>Atencion: hay precios cargados, por clase y semanal, para el evento '+evento_seleccionado[1]+' pero no hay horarios cargados y no se mostraran hasta que se cargue al menos uno.</p>'+'</blockquote>')                                    
                                    } else {
                                        $('#opcion_horario').css('display','none');
                                        $('#opcion_clase').css('display','none');
                                        $('#tabla_opciones').fadeIn();
                                        $('#horarios').html('</br></br></br><blockquote><p>Seleccione el modo de pago</p>'+
                                                '<p>Atencion: hay precio cargado de tipo semanal, para el evento '+evento_seleccionado[1]+' pero no hay horarios cargados y no se mostrara hasta que se cargue al menos uno.</p>'+'</blockquote>')                                    
                                    }
                                } else {
                                    $('#opcion_horario').css('display',(resultado.tipo_contratos.tipo_2)? '':'none');
                                    $('#opcion_clase').css('display',(resultado.tipo_contratos.tipo_3)? '':'none');
                                    $('#tabla_opciones>thead>tr>th').html(evento_seleccionado[1]);
                                    $('#tabla_opciones').fadeIn();
                                    $('#horarios').html('</br></br></br><blockquote><p>Seleccione el modo de pago</p></blockquote>')                                    
                                }
                            } else if (resultado.tipo_contratos.tipo_3){
                                $('#tabla_opciones').fadeIn();
                                $('#horarios').html('</br></br></br><blockquote><p>Seleccione el modo de pago</p>'+
                                        '<p>Atencion: El evento '+evento_seleccionado[1]+' tiene un precio cargado para contratos por clase, pero para poder seleccionarlo debe tener tambien horarios cargados.</p>'+'</blockquote>')                                                                
                            }
                        } else if (resultado.tipo_contratos.tipos==1){
                            if (resultado.tipo_contratos.tipo_1){ //libre
                                $('#opcion_horario').css('display','none');
                                $('#opcion_clase').css('display','none');
                                $('#opcion_libre').css('display','');
                                $('#opcion_libre').addClass('row_selected');
                                $('#tabla_opciones').fadeIn();
                                datosAEnviar.contrato.tipoContrato = 1;
                                $('#resumen_precio').remove();
                                $('#horarios').html('');
                                $('#horarios').append(html_precio);
                                verificarInscripcion();
                                actualizarPrecio();
                            } else if (resultado.tipo_contratos.tipo_2) { //x horario
                                if (datosEventoSeleccionado.events.length==0){
                                    $('#tabla_opciones').fadeOut();
                                    $('#horarios').html('</br></br></br><blockquote><p>Existe un precio cargado para el evento '+evento_seleccionado[1]+', pero no tiene horarios cargados y no se mostrara hasta que se cargue al menos uno.</p></blockquote>');
                                } else {
                                    $('#opcion_libre').css('display','none');
                                    $('#opcion_clase').css('display','none');
                                    $('#opcion_horario').css('display','');
                                    $('#opcion_horario').addClass('row_selected');
                                    $('#tabla_opciones').fadeIn();
                                    datosAEnviar.contrato.tipoContrato = 2;
                                    $('#precio_inferior').html(html_precio);
                                    var horarios = {events:[]};
                                    horarios.events = datosEventoSeleccionado.events;
                                    $('#horarios').html(datosEventoSeleccionado.html);
                                    verificarInscripcion();
                                    calendario(horarios);       
                                    actualizarPrecio();
                                }
                            } else { //x clase
                                $('#tabla_opciones').fadeOut();
                                $('#horarios').html('</br></br></br><blockquote><p>El evento '+evento_seleccionado[1]+' tiene un precio cargado para contratos por clase, pero para poder seleccionarlo debe tener tambien horarios cargados</p></blockquote>');
                                /*
                                $('#tabla_opciones').fadeIn();
                                $('#opcion_clase').css('display','');
                                $('#opcion_clase').addClass('row_selected');
                                datosAEnviar.contrato.tipoContrato = 3;
                                $('#resumen_precio').remove();
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
                                actualizarPrecio();
                                */
                            }
                        } else { //si no hay ningun precio cargado para el evento
                            $('#tabla_opciones').fadeOut();
                            $('#horarios').html('</br></br></br><blockquote><p>No se encontraron precios cargados para '+evento_seleccionado[1]+'</p></blockquote>');
                        }
                    },
                    error: function(result) { // if error occured
                       console.log('Error');
                        //alert(result);
                    },
                });
            }            
        });
     
    });    
</script>
<?php 
    $this->breadcrumbs=array(
            'Buscar Socios'=>array('administrarSocio/buscar'),
            'Detalles'=>array('administrarSocio/detalles','id'=>$this->id_socio),
            'Contratar evento ('.$this->nombre_socio.')'
    );    
?>

<div class="separador5"></div>
<pre>
  Contratar eventos
</pre>
<div class="row-fluid">
    <div class="span3">
        <div class="row-fluid">
            <table id="tabla_eventos" class="table table-hover">
                <thead>
                    <tr>
                        <th>Evento</th>
                        <th>Profesor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($eventos as $evento){
                                echo '<tr id="'.$evento->id_evento.'">';
                                    echo '<td>'.$evento->nombre.'</td>';
                                    echo '<td>'.$evento->nombreProfesor.'</td>';
                                echo '</tr>';
                        };
                    ?>
                </tbody>
            </table>
        </div>
        <div id="opciones_de_contratos" class="row-fluid">
            <table id="tabla_opciones" class="table table-hover" style="display: none;">
                <thead>
                    <tr>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="opcion_libre" onclick="opcionLibre(this)" style="display: none;">
                        <td>Libre</td>
                    </tr>
                    <tr id="opcion_horario" onclick="opcionHorario(this)" style="display: none;">
                        <td>Por mes (horario)</td>
                    </tr>
                    <tr id="opcion_clase" onclick="opcionClase(this)" style="display: none;">
                        <td>Por clase</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id='horarios' class="span9">
        </br>
        </br>
        </br>
        <blockquote id='info_horarios'>
            <p>Seleccione un evento.</p>
        </blockquote>
    </div>
</div>
<div class="row-fluid">
    <div id="precio_inferior" class="span12">
    </div>
</div>