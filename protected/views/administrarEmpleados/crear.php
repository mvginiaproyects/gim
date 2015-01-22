<div class="separador5"></div>
<pre>
  Alta de EMPLEADO
</pre>

<?php 

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'enableAjaxValidation'=>false,
    'type'=>'horizontal',
    'htmlOptions' => array('class' => 'well'),
)); 

?>

<p class="note">Los campos con <span class="required">*</span> son requeridos.</p>

<?php echo $form->errorSummary(array($model,$modelPersona)); ?>
<?php 
    $fecha_actual = date('Y-m-d');
    echo $form->hiddenField($model,'fecha_ingreso',array('value'=>$fecha_actual)); 
?>

 <div class="row-fluid">
        <div class="span6">
            <?php echo $form->labelEx($modelPersona,'nombre'); ?>
            <?php echo $form->textField($modelPersona,'nombre',array('size'=>30,'maxlength'=>30, 'class'=>'form-control')); ?>                
            <?php echo $form->error($modelPersona,'nombre'); ?>
        </div>

        <div class="span6">
            <?php echo $form->labelEx($model->idPersona,'apellido'); ?>
            <?php echo $form->textField($model->idPersona,'apellido',array('size'=>30,'maxlength'=>30, 'class'=>'form-control')); ?>                
            <?php echo $form->error($model->idPersona,'apellido'); ?>
        </div>
   </div>

    <div class="row-fluid">
        <div class="span6">
            <?php echo $form->labelEx($model->idPersona,'direccion'); ?>
            <?php echo $form->textField($model->idPersona,'direccion',array('size'=>30,'maxlength'=>40, 'class'=>'form-control')); ?>                
            <?php echo $form->error($model->idPersona,'direccion'); ?>
        </div>

        <div class="span6">
            <?php echo $form->labelEx($model->idPersona,'telefono'); ?>
            <?php echo $form->textField($model->idPersona,'telefono',array('size'=>30,'maxlength'=>30, 'class'=>'form-control')); ?>                
            <?php echo $form->error($model->idPersona,'telefono'); ?>
        </div>
   </div>


    <div class="row-fluid">
        <div class="span6">
            <?php echo $form->labelEx($model->idPersona,'dni'); ?>
            <?php echo $form->textField($model->idPersona,'dni',array('size'=>30,'maxlength'=>30, 'class'=>'form-control')); ?>                
            <?php echo $form->error($model->idPersona,'dni'); ?>
        </div>

        <div class="span6">
            <?php echo $form->labelEx($model->idPersona,'email'); ?>
            <?php echo $form->textField($model->idPersona,'email',array('size'=>30,'maxlength'=>30, 'class'=>'form-control')); ?>                
            <?php echo $form->error($model->idPersona,'email'); ?>
        </div>
   </div>


<div class="row-fluid"> 
            <div class="span6">
		<?php echo $form->labelEx($model,'tipo'); ?>
		<?php echo $form->radioButtonList($model,'tipo',array('0'=>'Profesor'),array('labelOptions'=>array('style'=>'display:inline'),'separator'=>' | ','class'=>'form-control')); ?>
		<?php echo $form->error($model,'tipo'); ?>
		<?php echo $form->labelEx($model->idPersona,'sexo'); ?>
		<?php echo $form->radioButtonList($model->idPersona,'sexo',array('0'=>'Masculino','1'=>'Femenino'),array('labelOptions'=>array('style'=>'display:inline'),'separator'=>' | ','class'=>'form-control')); ?>
		<?php echo $form->error($model->idPersona,'sexo'); ?>
            </div>

            <div class="span6">
		<?php echo $form->labelEx($model->idPersona,'fecha_nac'); ?>
		<?php //echo $form->dateField($model,'fecha_nac',array('class'=>'form-control')); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $model->idPersona,
                    //'attribute' => 'fecha_nac',
                    'name' => 'Empleado[fecha_nac]',
                    'value' => '00/00/0000',
                    'options' => array(
                            //'showOn' => 'both',             // also opens with a button
                            //'dateFormat' => 'yy-mm-dd',     // format of "2012-12-25"
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
		<?php echo $form->error($model->idPersona,'fecha_nac'); ?>
            </div>
</div>
            <div class="row-fluid">
                
                <div class="span6 center">
                    <?php echo CHtml::submitButton('Guardar', array('class' => 'btn btn-primary')); ?>
                    <?php //echo CHtml::submitButton('Cancelar', array('class' => 'btn btn-primary')); ?>

                </div>

            </div>
    


 
<?php $this->endWidget(); ?>
 