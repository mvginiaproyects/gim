<?php
/* @var $this AdministrarSalonController */
/* @var $model Salon */

$this->breadcrumbs=array(
	'Salons'=>array('index'),
	$model->id_salon,
);

$this->menu=array(
	array('label'=>'List Salon', 'url'=>array('index')),
	array('label'=>'Create Salon', 'url'=>array('create')),
	array('label'=>'Update Salon', 'url'=>array('update', 'id'=>$model->id_salon)),
	array('label'=>'Delete Salon', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_salon),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Salon', 'url'=>array('admin')),
);
?>

<h1>View Salon #<?php echo $model->id_salon; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_salon',
		'nombre',
		'color',
		'capacidad_max',
		'descripcion',
	),
)); ?>
