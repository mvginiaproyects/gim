<?php
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl.'/js/administrarContratos.js');
    $cs->registerScriptFile($baseUrl.'/js/administrarContratos_editar.js');
    //$precio = $this->renderPartial('_precio', array('forma'=>'Renovar'), true);
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
    };
    var datos = <?php echo CJSON::encode($datos);?>;
    var tabla;
    var horarios_seleccionados = <?php echo CJSON::encode($datos['horarios_seleccionados']);?>;
    var diasMostrados = 6;
    var datosEventoSeleccionado = datos;
</script>
<?php 
    $this->breadcrumbs=array(
            'Buscar Socios'=>array('administrarSocio/buscar'),
            'Detalles'=>array('administrarSocio/detalles','id'=>$this->id_socio),
            'Editar horarios ('.$this->nombre_socio.')'
    );    
?>

<div class="separador5"></div>
<pre>
  Cambiar horarios del contrato
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
                    <tr id="opcion_Horario" class="row_selected">
                        <th>Horario</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id='horarios' class="span9">
    </div>
</div>
<div class="row-fluid">
    <div class="span12 center">
        <?php 
            echo CHtml::link('Guardar', '#', array('class'=>'btn btn-primary disabled', 'onClick'=>'js:guardar();', 'id'=>'btn_guardar_cambios'));
        ?>
    </div>
</div>