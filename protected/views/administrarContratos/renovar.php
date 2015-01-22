<?php
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl.'/js/administrarContratos.js');
    $precio = $this->renderPartial('_precio', array('forma'=>'Renovar'), true);
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

    var datosAEnviar = {
        cuenta_socio:{
            id_recargo: 0,
            recargo: 0,
            id_descuento: 0,
            descuento: 0,
            prorrateo: false,
            valor_prorrateo: 0,
            inscripcion: false,
            valor_inscripcion: 0,
            precioParcial: 0
        },
        contrato:{
            id_contrato: null,
            id_evento: null,
            id_socio: <?php echo $this->id_socio?>,//ESTE NO BORRAR
            tipoContrato: null,
            cantidadClases: null,
            vencimiento: null
        },
        horarios_contratados: {},
        horarios_originales: {},
        precioNuevoHorarios: {
            clasesXSemana: null,
            precio: null,
            nuevo: false //falso si el precio de los horarios seleccionados existe, verdadero lo contrario
        }
    };
    var datos = <?php echo CJSON::encode($datos);?>;
    var html_precio = <?php echo CJSON::encode($precio);?>;
    var tabla;
    var horarios_seleccionados = <?php echo CJSON::encode($datos['horarios_seleccionados']);?>;
    var diasMostrados = 6;
    var datosEventoSeleccionado = datos;
    $(document).ready(function() {
        datosAEnviar.contrato.id_contrato = datos['otros_datos'].id_contrato;
        datosAEnviar.contrato.vencimiento = datos['otros_datos'].vencimiento;
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

        if (datos.tipo_contratos.tipo_1){ //libre
            datosAEnviar.contrato.tipoContrato = 1;
            $('#resumen_precio').remove();
            $('#horarios').html('');
            $('#horarios').append(html_precio);
            verificarInscripcion();
            actualizarPrecio();
        } else if (datos.tipo_contratos.tipo_2) { //x horario
            datosAEnviar.contrato.tipoContrato = 2;
            datosAEnviar.horarios_originales = $.merge([], horarios_seleccionados);
            datosAEnviar.horarios_contratados = horarios_seleccionados;
            $('#precio_inferior').html(html_precio);
            var horarios = {events:[]};
            horarios.events = datosEventoSeleccionado.events;
            $('#horarios').html(datosEventoSeleccionado.html);
            verificarInscripcion();
            calendario(horarios);       
            actualizarPrecio();
        } else { //x clase
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
        }
        
        if (datos['otros_datos'].vencido){
            $('select[name="recargo"]').prop('selectedIndex', 1);
            $('select[name="recargo"]').trigger('change');
        }

    });    
</script>
<?php 
    $this->breadcrumbs=array(
            'Buscar Socios'=>array('administrarSocio/buscar'),
            'Detalles'=>array('administrarSocio/detalles','id'=>$this->id_socio),
            'Renovar contrato ('.$this->nombre_socio.')'
    );    
?>

<div class="separador5"></div>
<pre>
  Renovar contrato
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
                        echo '<tr id="'.$otros_datos['id_evento'].'" class="row_selected">';
                            echo '<td>'.$otros_datos['evento'].'</td>';
                            echo '<td>'.$otros_datos['profesor'].'</td>';
                        echo '</tr>';
                    ?>
                </tbody>
            </table>
        </div>
        <div id="opciones_de_contratos" class="row-fluid">
            <table id="tabla_opciones" class="table table-hover">
                <thead>
                    <tr>
                        <th>Tipo de contrato</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        echo '<tr id="opcion_'.$otros_datos['tipoContrato'].'" class="row_selected" onclick="opcion'.$otros_datos['tipoContrato'].'(this)">';
                            echo '<td>'.$otros_datos['tipoContrato'].'</td>';
                        echo '</tr>';                        
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id='horarios' class="span9">
        </br>
        </br>
        </br>
        <blockquote id='info_horarios'>
            <p>PROXIMAMENTE</p>
        </blockquote>
    </div>
</div>
<div class="row-fluid">
    <div id="precio_inferior" class="span12">
    </div>
</div>