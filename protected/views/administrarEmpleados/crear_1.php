<div class="separador5"></div>
<pre>
  Alta de EMPLEADO
</pre>

<?php 
/*
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'enableAjaxValidation'=>false,
    'type'=>'horizontal',
    'htmlOptions' => array('class' => 'well'),
)); 
*/
?>
    
<!--  <div class="row-fluid">
        <div class="span6">
            <?php echo $form->labelEx($model->idPersona,'nombre'); ?>
            <?php echo $form->textField($model->idPersona,'nombre',array('size'=>30,'maxlength'=>30, 'class'=>'form-control')); ?>                
        </div>

        <div class="span6">
            <?php echo $form->labelEx($model->idPersona,'apellido'); ?>
            <?php echo $form->textField($model->idPersona,'apellido',array('size'=>30,'maxlength'=>30, 'class'=>'form-control')); ?>                
        </div>
   </div>

    <div class="row-fluid">
        <div class="span6">
            <?php echo $form->labelEx($model->idPersona,'direccion'); ?>
            <?php echo $form->textField($model->idPersona,'direccion',array('size'=>30,'maxlength'=>40, 'class'=>'form-control')); ?>                
        </div>

        <div class="span6">
            <?php echo $form->labelEx($model->idPersona,'telefono'); ?>
            <?php echo $form->textField($model->idPersona,'telefono',array('size'=>30,'maxlength'=>30, 'class'=>'form-control')); ?>                
        </div>
   </div>


    <div class="row-fluid">
        <div class="span6">
            <?php echo $form->labelEx($model->idPersona,'dni'); ?>
            <?php echo $form->textField($model->idPersona,'dni',array('size'=>30,'maxlength'=>30, 'class'=>'form-control')); ?>                
        </div>

        <div class="span6">
            <?php echo $form->labelEx($model->idPersona,'email'); ?>
            <?php echo $form->textField($model->idPersona,'email',array('size'=>30,'maxlength'=>30, 'class'=>'form-control')); ?>                
        </div>
   </div>


<div class="row-fluid"> 
            <div class="span6">                 
		<?php echo $form->labelEx($model,'tipo'); ?>
		<?php echo $form->radioButtonList($model,'tipo',array('0'=>'Profesor','1'=>'Administrativo'),array('labelOptions'=>array('style'=>'display:inline'),'separator'=>' | ','class'=>'form-control')); ?>
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
            <div class="row-fluid">
                
                <div class="span6 center">
                    <?php echo CHtml::submitButton('Guardar', array('class' => 'btn btn-primary')); ?>
                    <?php echo CHtml::submitButton('Cancelar', array('class' => 'btn btn-primary')); ?>

                </div>

            </div>
    

 -->
 
<?php $this->endWidget(); ?>

<?php 

/** @var TbActiveForm $form */
$form = $this->beginWidget(
    'booster.widgets.TbActiveForm',
    array(
        'id' => 'verticalForm',
    	'type' => 'horizontal',
        'htmlOptions' => array('class' => 'well'), // for inset effect
    )
);

echo $form->textFieldGroup($model, 'textField');
echo $form->passwordFieldGroup($model, 'password');
echo $form->checkboxGroup($model, 'checkbox');
$this->widget(
    'booster.widgets.TbButton',
    array('buttonType' => 'submit', 'label' => 'Login')
);
 
$this->endWidget();
unset($form);
?>
 

 