<?php
/* @var $this AdministrarSalonController */
/* @var $data Salon */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_salon')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_salon), array('view', 'id'=>$data->id_salon)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombre')); ?>:</b>
	<?php echo CHtml::encode($data->nombre); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('color')); ?>:</b>
	<?php echo CHtml::encode($data->color); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('capacidad_max')); ?>:</b>
	<?php echo CHtml::encode($data->capacidad_max); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('descripcion')); ?>:</b>
	<?php echo CHtml::encode($data->descripcion); ?>
	<br />


</div>