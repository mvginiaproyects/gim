<?php
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl.'/js/color_picker/spectrum.js');
    $cs->registerCssFile($baseUrl.'/js/color_picker/spectrum.css');
?>

<script>
    
    var colores = <?php echo CJSON::encode($colores);?>;
    
    $(document).ready(function() {
        //console.log(colores);
        $("#Salon_color").spectrum({
            showPaletteOnly: true,
            showPalette:true,
            color: '#1abc9c',
            palette: json2array(colores)
        });
    });
    
    function json2array(json){
        var result = [];
        var keys = Object.keys(json);
        keys.forEach(function(key){
            result.push(json[key]);
        });
        return result;
    }    
</script>

<?php

/* @var $this AdministrarSalonController */
/* @var $model Salon */

$this->breadcrumbs=array(
	'Buscar Salones'=>array('buscar'),
	'Crear Salón',
);

$this->menu=array(
	array('label'=>'Listar Salones', 'url'=>array('index')),
	array('label'=>'Buscar Salones', 'url'=>array('admin')),
);
?>

<?php //$this->renderPartial('_form', array('model'=>$model)); ?>

<div class="separador5"></div>
<pre>
  Alta de SALON
</pre>


<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'enableAjaxValidation'=>false,
    'type'=>'horizontal',
    'htmlOptions' => array('class' => 'well'),
));
?>

	<p class="note">Los campos con <span class="required">*</span> son requeridos.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row-fluid">
		<?php echo $form->labelEx($model,'nombre'); ?>
		<?php echo $form->textField($model,'nombre',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'nombre'); ?>
	</div>

       
	<div class="row-fluid">
		<?php echo $form->labelEx($model,'color'); ?>
                <input id="Salon_color" name="Salon[color]" type="text">
		<?php //echo $form->textField($model,'color',array('size'=>20,'maxlength'=>20)); ?>
                <?php
                /*
                $this->widget('ext.colorpicker.ColorPicker', array(
                    'model' => $model,
                    //'attribute' => 'color',
                    'name' => 'Salon[color]',
                    'options' => array( // Optional
                    //'pickerDefault' => "#ff0000", // Configuration Object for JS
                    ),
                    'htmlOptions' => array(
                    'size' => '20',         // textField size
                    'maxlength' => '30',    // textField maxlength
                    ),    
                ));
                 * 
                 */
                ?>    
		<?php echo $form->error($model,'color'); ?> 
        
	</div>

	<div class="row-fluid">
		<?php echo $form->labelEx($model,'descripcion'); ?>
		<?php echo $form->textField($model,'descripcion',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'descripcion'); ?>
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
