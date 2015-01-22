<div class="separador5"></div>
<pre>
  Alta de SOCIO
</pre>


<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'enableAjaxValidation'=>false,
    'type'=>'horizontal',
    'htmlOptions' => array('class' => 'well'),
)); 

?>

	<p class="note">Los campos con <span class="required">*</span> son requeridos.</p>

	<?php echo $form->errorSummary(array($model,$persona)); ?>
        <?php 
            $fecha_actual = date('Y-m-d');
            echo $form->hiddenField($model,'fecha_ingreso',array('value'=>$fecha_actual)); 
        ?>

        <div class="row-fluid">
            <div class="span6">
		<?php echo $form->labelEx($model->idPersona,'nombre'); ?>
		<?php echo $form->textField($model->idPersona,'nombre',array('size'=>30,'maxlength'=>30, 'class'=>'form-control')); ?>
		<?php echo $form->error($model->idPersona,'nombre'); ?>
            </div>
            <div class="span6">
		<?php echo $form->labelEx($model->idPersona,'apellido'); ?>
		<?php echo $form->textField($model->idPersona,'apellido',array('size'=>30,'maxlength'=>30, 'class'=>'form-control')); ?>
		<?php echo $form->error($model->idPersona,'apellido'); ?>
            </div>
        </div>

	<div class="row-fluid">
            <div class="span6">
		<?php echo $form->labelEx($model,'direccion'); ?>
		<?php echo $form->textField($model,'direccion',array('size'=>30,'maxlength'=>40, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'direccion'); ?>
            </div>
            <div class="span6">
		<?php echo $form->labelEx($model,'telefono'); ?>
		<?php echo $form->textField($model,'telefono',array('size'=>30,'maxlength'=>30, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'telefono'); ?>
            </div>
	</div>

        <div class="row-fluid">
            <div class="span6">
		<?php echo $form->labelEx($persona,'dni'); ?>
		<?php echo $form->textField($persona,'dni',array('size'=>11,'maxlength'=>11, 'class'=>'form-control')); ?>
		<?php echo $form->error($persona,'dni'); ?>
            </div>
            <div class="span6">
		<?php echo $form->labelEx($persona,'email'); ?>
		<?php echo $form->textField($persona,'email',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($persona,'email'); ?>
            </div>
	</div>

	<div class="row-fluid">
            <div class="span6">
		<?php echo $form->labelEx($model->idPersona,'sexo'); ?>
		<?php echo $form->radioButtonList($model->idPersona,'sexo',array('0'=>'Masculino','1'=>'Femenino'),array('labelOptions'=>array('style'=>'display:inline'),'separator'=>' | ','class'=>'form-control')); ?>
		<?php echo $form->error($model->idPersona,'sexo'); ?>
            </div>
            <div class="span6">
		<?php echo $form->labelEx($model,'fecha_nac'); ?>
		<?php //echo $form->dateField($model,'fecha_nac',array('class'=>'form-control')); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $model,
                    //'attribute' => 'fecha_nac',
                    'name' => 'Socio[fecha_nac]',
                    'value' => '00/00/0000',
                    'options' => array(
                            //'showOn' => 'both',             // also opens with a button
                            //'dateFormat' => 'yy-mm-dd',     // format of "2012-12-25"
                            'showOtherMonths' => true,      // show dates in other months
                            'selectOtherMonths' => true,    // can seelect dates in other months
                            'changeYear' => true,           // can change year
                            'changeMonth' => true,          // can change month
                            'yearRange' => '-55:-4',     // range of year
                            //'yearRange' => '-99:+2',
                            //'minDate' => '1960-01-01',      // minimum date
                            'maxDate' => '-4Y',      // maximum date
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
        
        <p>
            <div class="row-fluid">
                <div class="span1 center">
                    <?php echo CHtml::submitButton('Guardar', array('class' => 'btn btn-primary')); ?>
                    <?php //echo CHtml::submitButton('Cancelar', array('class' => 'btn btn-default')); ?>
                </div>
            </div>
        </p>
<?php $this->endWidget(); ?>