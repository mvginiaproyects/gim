<script type="text/javascript">
    var seleccion
    function preseleccionar(sel){
        seleccion = sel-1;
    }

    function seleccionar(){
        $('#markComplete_'+seleccion).attr('checked',true);
        $("#span_enviar").hide();
    }

</script>
<div class="row-fluid">
    <div class="span10 offset1">
        <?php 
echo CHtml::radioButtonList('markComplete', true ,array('1'=>'Todos','2'=>'Habilitados','3'=>'Vencidos','4'=>'Terminados'), 
                array(
                    'class'=>'form-control',
                    'labelOptions'=>array('style'=>'display:inline'),
                    'separator'=>' | ',
                    'style'=>'display:inline',
                    'onchange'=>'preseleccionar(this.value);',
                    'ajax'=>array(
                        'type'=>'POST',
                        'url'=>Yii::app()->createAbsoluteUrl("administrarSocio/tabla",array('id'=>$model->id_socio)),
                        'update'=>'#tabla',
                        'data' => array(
                            'opcion' => 'js:this.value',
                        ),
                        'beforeSend' => 'function() {          
                           $("#span_enviar").show();
                        }',
                        'complete' => 'function() {
                           seleccionar();
                        }',
                    ),
                )
            );
        ?>
        <span id='span_enviar' class='fa fa-spinner fa-spin' style="display: none;"></span>
    </div>
</div>
<div class="row-fluid">
    <div id="tabla" class="span10 center" style="margin-top: 20px; margin-right: 20px;">
        <?php echo $this->renderPartial('_tablaDetalleEvento',array('model'=>$model,'opcion'=>1));?>
    </div>
</div>
<div class="row-fluid">
    <div class="span2 center">
        <?php //echo CHtml::link('Verificar estados',
                            //array('administrarContratos/calcularVencimientoDias','id'=>$model->id_socio), 
                            //array('class'=>'btn btn-primary')
                        //);
        ?>
                <?php
                    $nombre_socio = $model->apellido.', '.$model->nombre;
                    echo CHtml::link('Contratar evento',
                            array('administrarContratos/contratar','id'=>$model->id_socio, 'nombre'=>$nombre_socio), 
                            array('class'=>'btn btn-primary')
                        );
                    //echo CHtml::link('Borrar Todo', array('administrarSocio/borrarTodo','idSocio'=>$model->id_socio), array('class'=>'btn btn-primary'));
        ?>
    </div>
</div>
<?php 
	//$model_contrata=$model->contratos;
	//foreach ($model_contrata as $contrato){
		//echo 'Nombre evento: '.$contrato->nombreEvento.'</br>';
	//};
?>