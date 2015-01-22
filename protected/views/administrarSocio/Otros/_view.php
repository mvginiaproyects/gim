<?php
/* @var $this AdministrarSocioController */
/* @var $data Socio */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_socio')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_socio), array('view', 'id'=>$data->id_socio)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_persona')); ?>:</b>
	<?php echo CHtml::encode($data->id_persona); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_ingreso')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_ingreso); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombre')); ?>:</b>
	<?php echo CHtml::encode($data->nombre); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('apellido')); ?>:</b>
	<?php echo CHtml::encode($data->apellido); ?>
	<br />


</div>