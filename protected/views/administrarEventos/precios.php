<?php
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl.'/js/administrarEventos_precios.js');
    $precio_inscripcion = $this->renderPartial('_seccionPrecioInscripcion',null,true);
    $precio_libre = $this->renderPartial('_seccionPrecioLibre',null,true);
    $precio_semanal = $this->renderPartial('_seccionPrecioSemanal',null,true);
    $precio_semanal_dia = $this->renderPartial('_subSeccionPrecioSemanal',null,true);
    $precio_clase = $this->renderPartial('_seccionPrecioClase',null,true);
?>

<style type='text/css'>

	
</style>


<script type="text/javascript">
    var precios_html = [];
    precios_html[0] = <?php echo CJSON::encode($precio_inscripcion);?>;
    precios_html[1] = <?php echo CJSON::encode($precio_libre);?>;
    precios_html[2] = <?php echo CJSON::encode($precio_semanal);?>;
    precios_html[3] = <?php echo CJSON::encode($precio_clase);?>;
    
    var sub_seccion_semanal_html = <?php echo CJSON::encode($precio_semanal_dia);?>;
    var precios = <?php echo CJSON::encode($precios);?>;
    var eventos = <?php echo CJSON::encode($eventos);?>;
    
    var idEventoSeleccionado = <?php echo $idEventoSeleccionado;?>;

</script>

<div class="separador5"></div>
<pre>
  Administrar precios de eventos
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
        <div class="row-fluid">
            <table id="tabla_tipos" class="table table-hover">
                <thead>
                    <tr>
                        <th>Tipos disponibles</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="t0" onclick="" style="display: none;">
                        <td>Inscripcion</td>
                    </tr>
                    <tr id="t1" onclick="" style="display: none;">
                        <td>Libre</td>
                    </tr>
                    <tr id="t2" onclick="" style="display: none;">
                        <td>Por mes (horario)</td>
                    </tr>
                    <tr id="t3" onclick="" style="display: none;">
                        <td>Por clase</td>
                    </tr>
                </tbody>
            </table>            
        </div>
    </div>
    <div class="span9">
        <div id="precios">
            <!--
            <blockquote>
                Seleccione un evento
            </blockquote>
            -->
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12 center">
        <a onclick="guardarCambios();" class="btn btn-primary disabled" id="btn_guardar_cambios" href="#">Guardar cambios</a>
    </div>
</div>