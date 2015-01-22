<?php

$condicion = new CDbCriteria();
$condicion->with = array('idEmpleado'=>array('with'=>'idPersona'));
$profesores_model = Profesor::model()->findAll($condicion);

$lista = CHtml::listData($profesores_model, 'id_profesor', 'fullName')

?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'evento-form',
        'action'=>'javascript:guardarDatosFormModificarEvento()',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Los campos con <span class="required">*</span> son requeridos.</p>

	<?php echo $form->errorSummary($evento_model); ?>

        <div class="row-fluid">
            <div class="span6">
                <div class="row-fluid">
                        <?php echo $form->labelEx($evento_model,'nombre'); ?>
                        <?php echo $form->textField($evento_model,'nombre',array('size'=>30,'maxlength'=>30, 'class'=>'form-control')); ?>
                        <?php echo $form->error($evento_model,'nombre'); ?>            
                </div>
                <div class="row-fluid">
                        <?php echo $form->labelEx($evento_model,'id_profesor'); ?>
                        <?php echo $form->dropDownList($evento_model,'id_profesor',$lista, array(
                            'data-placement' => 'right',
                            'data-toggle' => 'popover',
                            'data-trigger' => "hover",
                            'data-html'=>"true",
                            'data-title' => "<strong>Atencion!</strong>",
                            'data-content' => "Cambiar el profesor, puede causar conflicto en los horarios con el profesor seleccionado, y deberá revisarlo y acomodarlo manualmente, a través de <i>Administrar horarios</i>.",
                            //"disabled"=>"disabled",
                            )); ?>
                        <?php echo $form->error($evento_model,'id_profesor'); ?>            
                </div>
            </div>
        </div>
        
<?php $this->endWidget(); ?>

</div><!-- form -->