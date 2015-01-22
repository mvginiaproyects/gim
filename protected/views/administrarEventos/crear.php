<div class="separador5"></div>
<pre>
  Alta de EVENTO
</pre>


<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'enableAjaxValidation'=>false,
    'type'=>'horizontal',
    'htmlOptions' => array('class' => 'well'),
)); 

?>

	<p class="note">Los campos con <span class="required">*</span> son requeridos.</p>

	<?php echo $form->errorSummary(array($evento_model)); ?>

        <div class="row-fluid">
            <div class="span6">
		<?php echo $form->labelEx($evento_model,'nombre'); ?>
		<?php echo $form->textField($evento_model,'nombre',array('size'=>30,'maxlength'=>30, 'class'=>'form-control')); ?>
		<?php echo $form->error($evento_model,'nombre'); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span6">
		<?php echo $form->labelEx($evento_model,'id_profesor'); ?>
		<?php
                    $lista = CHtml::listData($profesores_model, 'id_profesor', 'fullname');
                    echo $form->dropDownList($evento_model,'id_profesor',$lista,array('class'=>'form-control'));
                ?>
		<?php echo $form->error($evento_model,'id_profesor'); ?>
            </div>
        </div>
       
        <p>
            <div class="row-fluid">
                <div class="span1 center">
                    <?php echo CHtml::submitButton('Guardar', array('class' => 'btn btn-primary')); ?>
                </div>
            </div>
        </p>
<?php $this->endWidget(); ?>