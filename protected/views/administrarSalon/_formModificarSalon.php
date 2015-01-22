<?php
/* @var $this AdministrarSocioController */
/* @var $model Socio */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'salon-form',
        'action'=>'javascript:guardarDatosFormModificarSalon()',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Los campos con <span class="required">*</span> son requeridos.</p>

	<?php echo $form->errorSummary($model); ?>

        <div class="row-fluid">
            <div class="span6">
                <div class="row-fluid">
                        <?php echo $form->labelEx($model,'nombre'); ?>
                        <?php echo $form->textField($model,'nombre',array('size'=>30,'maxlength'=>30, 'class'=>'form-control')); ?>
                        <?php echo $form->error($model,'nombre'); ?>            
                </div>
                <div class="row-fluid">
                        <?php echo $form->labelEx($model,'color'); ?>
                        <!-- <?php echo $form->textField($model,'color',array('size'=>20,'maxlength'=>20, 'class'=>'form-control')); ?> -->
                        <?php
                        $this->widget('ext.colorpicker.ColorPicker', array(
                        'model' => $model,
                        //'attribute' => 'color',
                        'name' => 'Salon[color]',
                        'options' => array( // Optional
                        'pickerDefault' => "ccc", // Configuration Object for JS
                        ),
                        'htmlOptions' => array(
                        'size' => '20',         // textField size
                        'maxlength' => '30',    // textField maxlength
                        ),    
                        ));
                        ?>    
                        <?php echo $form->error($model,'color'); ?>            
                </div>
                <div class="row-fluid">
            <div class="span6">
                <div class="row-fluid">
                        <?php echo $form->labelEx($model,'descripcion'); ?>
                        <?php echo $form->textField($model,'descripcion',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
                        <?php echo $form->error($model,'descripcion'); ?>            
                </div>
            </div>
        </div>
        

	<div class="row buttons">
		<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->