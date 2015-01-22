<?php
/* @var $this AdministrarSocioController */
/* @var $model Socio */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="fluid-row">
		<?php echo $form->label($model,'id_socio'); ?>
		<?php echo $form->textField($model,'id_socio'); ?>
	</div>

	<div class="fluid-row">
		<?php echo $form->label($model,'fecha_ingreso'); ?>
		<?php echo $form->textField($model,'fecha_ingreso'); ?>
	</div>

	<div class="fluid-row">
		<?php echo $form->label($model,'nombre'); ?>
		<?php echo $form->textField($model,'nombre',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="fluid-row">
		<?php echo $form->label($model,'apellido'); ?>
		<?php echo $form->textField($model,'apellido',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="btn-group">
		<?php echo CHtml::submitButton('Search',array('class'=>'btn')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->