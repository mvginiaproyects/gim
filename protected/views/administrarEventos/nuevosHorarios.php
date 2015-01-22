<?php 
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl.'/js/administrarEventos_nuevosHorarios.js');
    //$cs->registerCssFile($baseUrl.'/css/yourcss.css');    
    //echo "<pre>";
    //print_r($horarios[1]);
    //echo "</pre>";
?>
<style type='text/css'>

.ayuda {
    display: block;
    position: absolute;
    top: 30px;
    width:100%;
    height: auto;
    border:groove 3px #383838;
    -moz-border-radius: 11px;
    -webkit-border-radius: 11px;
    border-radius: 11px;
}
	
</style>

<script type="text/javascript">
    ayuda = true;
    $('#ayuda').html(<?php echo CJSON::encode($this->renderPartial('_ayuda',null,true))?>);
    $('#btn_ayuda>a').addClass('ayudaActiva');

    var id_salon_seleccionado = 1;
    var id_evento_seleccionado = 1;
    var id_evento_seleccionado = 1;
    var horarios_salones = <?php echo CJSON::encode($horarios);?>;
    var horarios_salon_seleccionado;
    
    var idEventoSeleccionado = <?php echo $idEventoSeleccionado;?>
    
    var horarios_nuevos = {};
    var horarios_borrados = {};
    var horarios_cambiados = {};
    
    var url_horariosOtrosSalones = '<?php echo Yii::app()->createAbsoluteUrl("administrarEventos/horariosOtrosSalones"); ?>';
    var url_guardarCambiosHorarios = '<?php echo Yii::app()->createAbsoluteUrl("administrarEventos/guardarCambiosHorarios"); ?>';
    $(document).ready(function() {
        tabla_eventos = $('#tabla_eventos').dataTable( {
                "sScrollY": '200px',
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
                tabla_eventos.$('tr.row_selected').removeClass('row_selected');
                $(this).addClass('row_selected');
            }
            mostrar_horarios();
        });
        $("#tabla_salones tbody tr").click( function( e ) {
            if ( $(this).hasClass('row_selected') ) {
                //$(this).removeClass('row_selected');
            }
            else {
                $('table#tabla_salones > tbody > tr.row_selected').removeClass('row_selected');
                $(this).addClass('row_selected');
            }
            mostrar_horarios();
        });
        if (idEventoSeleccionado!=0){
            //console.log($("#tabla_eventos #"+idEventoSeleccionado));
            //$("#tabla_eventos #"+idEventoSeleccionado).trigger( "click" );
            $("#tabla_eventos #"+idEventoSeleccionado).addClass('row_selected');
            id_evento_seleccionado = parseInt($("#tabla_eventos #"+idEventoSeleccionado).prop('id'));
        }

    });
</script>
<div class="separador5"></div>
<pre>
  Crear nuevos horarios
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
                                    echo '<td id="'.$evento->id_profesor.'">'.$evento->nombreProfesor.'</td>';
                                echo '</tr>';
                        };
                    ?>
                </tbody>
            </table>
        </div>            
        <div class="row-fluid">
            <table id="tabla_salones" class="table table-hover">
                <thead>
                    <tr>
                        <th>Salones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($salones as $salon){
                                echo '<tr id="'.$salon->id_salon.'">';
                                    echo '<td>'.$salon->nombre.'</td>';
                                echo '</tr>';
                        };
                    ?>
                </tbody>
            </table>            
        </div>
        <div class="row-fluid">
            <table id="tabla_disponibilidad" class="table">
                <thead>
                    <tr>
                        <th>Disponibilidad</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input id='disponibilidad' value="15" type='number' name='quantity' min='1' max='100' style='width: 45px; font-size: 12px;'>
                        </td>
                    </tr>
                </tbody>
            </table>            
            
        </div>
    </div>
    <div class="span9">
        <div id="nuevosHorarios">
            <blockquote>
                Seleccione un evento y un salon
            </blockquote>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12 center">
        <a onclick="guardarCambiosNuevosHorarios();" class="btn btn-primary disabled" id="btn_guardar_cambios" href="#">Guardar cambios</a>
    </div>
</div>