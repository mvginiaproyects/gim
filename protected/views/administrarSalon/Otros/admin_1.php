<?php
/* @var $this AdministrarSalonController */
/* @var $model Salon */

$this->breadcrumbs=array(
	'Buscar Salones',
);

//$this->menu=array(
//	array('label'=>'List Salon', 'url'=>array('index')),
//	array('label'=>'Create Salon', 'url'=>array('create')),
//);

//Yii::app()->clientScript->registerScript('search', "
//$('.search-button').click(function(){
	//$('.search-form').toggle();
	//return false;
//});
//$('.search-form form').submit(function(){
//	$('#salon-grid').yiiGridView('update', {
//		data: $(this).serialize()
//	});
//	return false;
//});
//");
?>

<div class="span12 center">
<h1>Busqueda de salones</h1>

<p>
    Ingrese uno o mas criterios de búsqueda y presione <b>Enter</b>
</p>
<p>
    Luego, haga click en un salón para administrarlo.
</p>

<script>
    var boton = '<?php echo CHtml::resetButton('Clear!', array('id'=>'form-reset-button'));?>';
    function insertar(){
        $('div.summary').before('<div style="float:left;">'+boton+'</div>');        
    }
    //$('#form-reset-button').click(function()
    function limpiar()
    {
       var id='document-grid';
       var inputSelector='#'+'contrata-evento-grid'+' .filters input, '+'#'+'contrata-evento-grid'+' .filters select';
       $(inputSelector).each( function(i,o) {
            $(o).val('');
       });
       var data=$.param($(inputSelector));
       $.fn.yiiGridView.update('contrata-evento-grid', {data: data});
       //alert('ok');
       return false;
    };
</script>


<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array('model'=>$model,)); ?>
</div><!-- search-form -->


< $this->widget('application.components.GGridView', array( >
	'id'=>'salon-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
        'footer_count'=>true,
        'footer_count_text'=>'Salones encontrados:',
	'columns'=>array(
            	array(
                        'name'=>'id_salon',
			'value'=>'$data->id_salon',
			'htmlOptions'=>array('style' => 'width: 17%; text-align: center;'),
		),
		//'id_salon',
            	array(
			'name'=>'nombre',
			'value'=>'$data->nombre',
			//'filter'=>false,
		),
		//'nombre',
                array(
			'name'=>'color',
			'value'=>'$data->color',
			//'filter'=>false,
		),
		//'color',
                array(
			'name'=>'capacidad_max',
			'value'=>'$data->capacidad_max',
			//'filter'=>false,
		),
		//'capacidad_max',
                array(
			'name'=>'descripcion',
			'value'=>'$data->descripcion',
			//'filter'=>false,
		),
		//'descripcion',
            
                array(
                    'header'=>'Acciones',
                    'type'=>'raw',
                    'filter'=>CHtml::link('<i class="icon-remove"></i>','#', 
                                array('onclick'=>'limpiar();', 'class'=>'pull-right btn btn-info btn-small')
                     ),
                    'value'=>function($data){
                        $btn_detalles = CHtml::link('<i class="icon-search"></i>',
                                            array('administrarSalon/detalles','id'=>$data->id_salon), 
                                            array('class'=>'pull-right btn btn-success btn-small')
                                        );
                        $btn_eliminar = CHtml::link('<i class="icon-remove"></i>',
                                            array('administrarSalon/eliminar','id'=>$data->id_salon), 
                                            array('confirm' => 'Esta seguro de eliminar el salon '.$data->id_salon.'?', 'class'=>'pull-right btn btn-danger btn-small')
                                        );
                        $salida = $btn_eliminar."<span class='pull-right'>|</span>".$btn_detalles;
                        //$btn_eliminar = CHtml::link('<i class="icon-remove"></i>','#', array('onclick' => 'eliminar('.$data->id_socio.',"'.$data->apellido.'")', 'class'=>'pull-right btn btn-danger btn-small'));
                        return $salida;
                    },
                ),
		//array(
		//	'class'=>'CButtonColumn',
            
            
        ),
  
)); ?>
</div>
