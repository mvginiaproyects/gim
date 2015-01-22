<?php
/* @var $this AdministrarSocioController */
/* @var $model Socio */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'empleado-form',
        'action'=>'javascript:guardarDatosFormModificarEmpleado()',
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
                        <?php echo $form->labelEx($model,'apellido'); ?>
                        <?php echo $form->textField($model,'apellido',array('size'=>30,'maxlength'=>30, 'class'=>'form-control')); ?>
                        <?php echo $form->error($model,'apellido'); ?>            
                </div>
                <div class="row-fluid">
                        <?php echo $form->labelEx($model,'direccion'); ?>
                        <?php echo $form->textField($model,'direccion',array('size'=>40,'maxlength'=>40, 'class'=>'form-control')); ?>
                        <?php echo $form->error($model,'direccion'); ?>
                </div>
                <div class="row-fluid">
                        <?php echo $form->labelEx($model,'telefono'); ?>
                        <?php echo $form->textField($model,'telefono',array('size'=>30,'maxlength'=>30, 'class'=>'form-control')); ?>
                        <?php echo $form->error($model,'telefono'); ?>            
                </div>
            </div>
            <div class="span6">
                <div class="row-fluid">
                        <?php echo $form->labelEx($model,'dni'); ?>
                        <?php echo $form->textField($model,'dni',array('size'=>12,'maxlength'=>11, 'class'=>'form-control')); ?>
                        <?php echo $form->error($model,'dni'); ?>            
                </div>
                <div class="row-fluid">
                        <?php echo $form->labelEx($model,'email'); ?>
                        <?php echo $form->textField($model,'email',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
                        <?php echo $form->error($model,'email'); ?>            
                </div>
                <div class="row-fluid">
                        <?php echo $form->labelEx($model,'tipo'); ?>
                        <?php echo $form->radioButtonList($model,'tipo',array('0'=>'Profesor','1'=>'Otro'),array('labelOptions'=>array('style'=>'display:inline'),'separator'=>' | ','class'=>'form-control','style'=>'display:inline')); ?>
                        <?php echo $form->error($model,'tipo'); ?>            
                </div>
                <div class="row-fluid">
                        <?php echo $form->labelEx($model,'sexo'); ?>
                        <?php echo $form->radioButtonList($model,'sexo',array('0'=>'Masculino','1'=>'Femenino'),array('labelOptions'=>array('style'=>'display:inline'),'separator'=>' | ','class'=>'form-control','style'=>'display:inline')); ?>
                        <?php echo $form->error($model,'sexo'); ?>            
                </div>
                <!-- <div class="row-fluid">
                        <?php echo $form->labelEx($model,'estado'); ?>
                        <?php echo $form->dropDownList($model,'estado',array('1'=>'No habilitado','0'=>'Habilitado')); ?>
                        <?php echo $form->error($model,'estado'); ?>            
                </div> -->
                <div class="row-fluid">
                        <?php echo $form->labelEx($model,'fecha_nac'); ?>
                        <?php //echo $form->dateField($model,'fecha_nac',array('class'=>'form-control')); ?>
                        <?php
                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'model' => $model,
                            //'attribute' => 'fecha_nac',
                            'name' => 'Empleado[fecha_nac]',
                            'value' => $model->fecha_nac,
                            'options' => array(
                                //'showOn' => 'both',             // also opens with a button
                                'dateFormat' => 'dd-mm-yy',     // format of "2012-12-25"
                                'showOtherMonths' => true,      // show dates in other months
                                'selectOtherMonths' => true,    // can seelect dates in other months
                                'changeYear' => true,           // can change year
                                'changeMonth' => true,          // can change month
                                'yearRange' => '1960:date(y)',     // range of year
                                //'minDate' => '1960-01-01',      // minimum date
                                //'maxDate' => '2099-12-31',      // maximum date
                                //'showButtonPanel' => true,      // show button panel
                            ),
                            'htmlOptions' => array(
                                'size' => '10',         // textField size
                                'maxlength' => '10',    // textField maxlength
                            ),
                        ));
                        ?>                                    
                        <?php echo $form->error($model,'fecha_nac'); ?>            
                </div>
            </div>
        </div>
        

	<div class="row buttons">
		<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->