<script>
    ayuda = true;
    $('#ayuda').html(<?php echo CJSON::encode($this->renderPartial('_ayuda',null,true))?>);
    $('#btn_ayuda>a').addClass('ayudaActiva');
</script>

<pre>Configuraciones</pre>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'socio-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
        'htmlOptions' => array('class' => 'well'),
)); ?>

        <p class="note">Los campos con <span class="required">*</span> son requeridos.</p>

	<?php echo $form->errorSummary($configuraciones_model); ?>

        <div class="row-fluid">
            <div class="span6">
                <div class="row-fluid">
                        <?php echo $form->labelEx($configuraciones_model,'diaVencimiento'); ?>
                        <?php echo $form->numberField($configuraciones_model,'diaVencimiento',array('size'=>2, 'style'=>'width:30px','maxlength'=>30, 'class'=>'form-control', 'min'=>1, 'max'=>28)); ?>
                        <?php echo $form->error($configuraciones_model,'diaVencimiento'); ?>
                </div>
                <div class="row-fluid">
                        <?php echo $form->labelEx($configuraciones_model,'diasVencimientoPorClases'); ?>
                        <?php echo $form->numberField($configuraciones_model,'diasVencimientoPorClases',array('size'=>2, 'style'=>'width:30px','maxlength'=>30, 'class'=>'form-control', 'min'=>7, 'max'=>60)); ?>
                        <?php echo $form->error($configuraciones_model,'diasVencimientoPorClases'); ?>
                </div>
                <div class="row-fluid">
                        <?php echo $form->labelEx($configuraciones_model,'diasTerminarContrato'); ?>
                        <?php echo $form->numberField($configuraciones_model,'diasTerminarContrato',array('size'=>2, 'style'=>'width:30px','maxlength'=>30, 'class'=>'form-control', 'min'=>1, 'max'=>60)); ?>
                        <?php echo $form->error($configuraciones_model,'diasTerminarContrato'); ?>
                </div>
                <div class="row-fluid">
                        <?php echo $form->labelEx($configuraciones_model,'borrarContratosTerminados'); ?>
                        <?php echo $form->dropDownList($configuraciones_model,'borrarContratosTerminados',array('0'=>'No','1'=>'Si')); ?>
                        <?php echo $form->error($configuraciones_model,'borrarContratosTerminados'); ?>
                </div>
            </div>
            <div class="span6">
                <div class="row-fluid">
                </div>
            </div>
        </div>

        <div class="row buttons">
		<?php echo CHtml::submitButton('Guardar', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->