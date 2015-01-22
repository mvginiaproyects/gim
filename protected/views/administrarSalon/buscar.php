<div class="span9">
<h1>Busqueda de salones</h1>

<p>
    Ingrese uno o mas criterios de búsqueda y presione <b>Enter</b>
</p>
<p>
    Luego, haga click en un salón para administrarlo.
</p>

<script>  
    function limpiar()
    {
       var id='document-grid';
       var inputSelector='#'+'salon-grid'+' .filters input, '+'#'+'salon-grid'+' .filters select';
       $(inputSelector).each( function(i,o) {
            $(o).val('');
       });
       var data=$.param($(inputSelector));
       $.fn.yiiGridView.update('salon-grid', {data: data});
       //alert('ok');
       return false;
    };

    function eliminar(idSalon) {
        console.log($("#myModal4"));
        url = Yii.app.createUrl('administrarSalon/eliminar',{id_salon: idSalon});
        $('#btnenviar3').prop('href',url);
        $("#myModal4").modal("show");
    
    };
</script>
<?php 
//$this->widget('zii.widgets.grid.CGridView', array(
$this->widget('application.components.GGridView', array(
	'id'=>'salon-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
        'footer_count'=>true,
        'footer_count_text'=>'Salones encontrados:',
        'columns'=>array(
            	array(
                        'name'=>'id_salon',
			'value'=>'$data->id_salon',
			'htmlOptions'=>array('style' => 'width: 10%; text-align: center;'),
                        'filterHtmlOptions'=>array('style'=>'padding-right: 15px')
		),
            	array(
			'name'=>'nombre',
			'value'=>'$data->nombre',
                        'htmlOptions'=>array('style' => 'width: 10%; text-align: center;'),
                        'filterHtmlOptions'=>array('style'=>'padding-right: 15px')
		),
		//'nombre',
                array(
			'name'=>'color',
			//'value'=>'$data->color',
                        'type'=>'raw',
                        'filter'=>false,
                        'value'=>function($data){
                            $color = '<span class="center" style="border:hidden;-moz-border-radius: 8px;border-radius: 8px;width:50px;height:15px;background-color:'.$data->color.'"></span>';                   
                            return $color;
                        },
                        'htmlOptions'=>array('style' => 'width: 8%; text-align: center; background-color: $data->color'),
                        'filterHtmlOptions'=>array('style'=>'padding-right: 15px')
		),
		//'color',
		//'capacidad_max',
                array(
			'name'=>'descripcion',
			'value'=>'$data->descripcion',
                        'htmlOptions'=>array('style' => 'width: 40%; text-align: left;'),
                        'filterHtmlOptions'=>array('style'=>'padding-right: 15px')
		),
		//'descripcion',
            
                array(
                    'header'=>'Acciones',
                    'type'=>'raw',
                    'htmlOptions'=>array('style' => 'width: 10%; text-align: center;'),
                    'filter'=>CHtml::link('<i class="icon-remove"></i>','#', 
                                array('onclick'=>'limpiar();', 'class'=>'pull-right btn btn-info btn-small')
                            ),
                    'value'=>function($data){
                        $btn_detalles = CHtml::link('<i class="icon-search"></i>',
                                            array('administrarSalon/detalles','id'=>$data->id_salon),                                 
                                            array('class'=>'pull-right btn btn-success btn-small')
                                        );                   
                        $btn_eliminar = CHtml::htmlButton('<i class="icon-remove"></i>',
                                            array('onclick'=>'eliminar('.$data->id_salon.');',
                                            //'confirm'=>'Está seguro de querer eliminar este registro?',
                                            'class'=>'pull-right btn btn-danger btn-small')     
                                        );
                        //$salida = $btn_eliminar."<span class='pull-right'>|</span>".$btn_detalles;
                        $salida = $btn_detalles;
                        return $salida;
                    },
                ),
	),
)); 
?>
</div>
<?php $mimodal4 = $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'myModal4',
        //'backdrop'=>'static',
        //'keyboard'=>false,
        )
);
$mimodal4->options = array('backdrop'=> 'static','keyboard'=> true,'show' => false);
//Yii::trace(CVarDumper::dumpAsString($mimodal));
?>
 
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Borrar Salón</h4>
    </div>
 
    <div class="modal-body">
        <p>Esto borrará el Salón <span id="nombreCompleto"> y todos sus registros asociados</span></p>
        <p>¿Esta seguro de continuar?</p>
    </div>
 
    <div class="modal-footer">
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'id'=>'btnenviar3',
                'type' => 'primary',
                'label' => 'Confirmar',
                'url' => '#',
            )
        ); ?>
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'id'=>'btncancelar',
                'label' => 'Cancelar',
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
            )
        ); ?>
    </div>
<?php $this->endWidget(); ?>
