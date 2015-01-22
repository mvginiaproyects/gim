<?php
/* @var $this AdministrarSalonController */
/* @var $model Salon */

$this->breadcrumbs=array(
	'Salons'=>array('index'),
	$model->id_salon=>array('view','id'=>$model->id_salon),
	'Update',
);

$this->menu=array(
	array('label'=>'List Salon', 'url'=>array('index')),
	array('label'=>'Create Salon', 'url'=>array('create')),
	array('label'=>'View Salon', 'url'=>array('view', 'id'=>$model->id_salon)),
	array('label'=>'Manage Salon', 'url'=>array('admin')),
);
?>

<h1>Update Salon <?php echo $model->id_salon; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>