<?php

$baseUrl = Yii::app()->getBaseUrl();
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl.'/js/administrarEventos_descuentosRecargos.js');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<div class="separador10"></div>
<div class="row-fluid">
    <div class="span7">
        <div class="row-fluid">
            <div class="span12">
                <pre>Nuevo descuento/recargo</pre>
                
                <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                    'id'=>'horizontalForm',
                    'enableAjaxValidation'=>false,
                    'type'=>'horizontal',
                    'htmlOptions' => array('class' => 'well'),
                    'action'=>'javascript:alert("ya va!")',
                )); 
                ?>                
        	<?php echo $form->errorSummary(array($new_dr_model)); ?>
                
        	<p class="note">Los campos con <span class="required">*</span> son requeridos.</p>
                <div class="row-fluid">
                    <div class="span6">
                        <div class="row-fluid">
                            <?php echo $form->labelEx($new_dr_model,'tipo'); ?>
                            <?php echo $form->dropDownList($new_dr_model, 'tipo', array('descuento'=>'Descuento', 'recargo'=>'Recargo'))?>
                        </div>
                        <div class="row-fluid">
                            <?php echo $form->labelEx($new_dr_model,'modo'); ?>
                            <?php echo $form->dropDownList($new_dr_model, 'modo', array('$'=>'$', '%'=>'%'))?>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="row-fluid">
                            <?php echo $form->labelEx($new_dr_model,'descripcion'); ?>
                            <?php echo $form->textField($new_dr_model,'descripcion',array('size'=>35,'maxlength'=>35, 'class'=>'form-control')); ?>
                            <?php echo $form->error($new_dr_model,'tipo'); ?>
                        </div>
                        <div class="row-fluid">
                            <?php echo $form->labelEx($new_dr_model,'monto'); ?>
                            <?php echo $form->numberField($new_dr_model,'monto',array('min'=>1, 'max'=>'999', 'style'=>'width:45px', 'class'=>'form-control')); ?>
                            <?php echo $form->error($new_dr_model,'monto'); ?>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="pull-right">
                        <?php $this->widget(
                            'bootstrap.widgets.TbButton',
                            array(
                                'id'=>'btncancelar',
                                'label' => 'Cancelar',
                                'url' => '#',
                                'htmlOptions' => array('data-dismiss' => 'modal', 'onclick'=>'js:cancelarEdicion();', 'style'=>'display:none'),
                            )
                        ); ?>
                        <?php $this->widget(
                            'bootstrap.widgets.TbButton',
                            array(
                                'id'=>'btneditar',
                                'label' => 'Guardar',
                                'type' => 'primary',
                                'url' => '#',
                                'htmlOptions' => array('data-dismiss' => 'modal', 'onclick'=>'js:guardarEdicion();', 'style'=>'display:none'),
                            )
                        ); ?>
                        <?php $this->widget(
                            'bootstrap.widgets.TbButton',
                            array(
                                'id'=>'DescuentoRecargo_cargar',
                                'type' => 'primary',
                                'label' => 'Cargar descuento',
                                'url' => '#',
                                'htmlOptions' => array('onclick'=>'js:guardarDescuentoRecargo();', 'disabled'=>'disabled'),
                            )
                        ); ?>
                        <?php //echo CHtml::submitButton('Cargar descuento', array('class'=>'btn btn-primary', 'disabled'=>'disabled', 'id'=>'DescuentoRecargo_cargar')); ?>
                    </div>
                </div>
                
                <?php $this->endWidget(); ?>                
            </div>
        </div>
        <div class="row-fluid" id="tablas_descuentos_recargos">
            <div class="span6">
                <pre>Descuentos activos</pre>
                <table id="tabla_descuentos_activos" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Descripcion</th>
                            <th>Modo</th>
                            <th>Monto</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($descuetos_recargos_model as $descuetos_recargos) {
                            if ($descuetos_recargos->tipo=='descuento'){
                                echo '<tr id="fila'.$descuetos_recargos->id_descuento_recargo.'">';
                                echo '<td>';
                                echo $descuetos_recargos->descripcion;
                                echo '</td>';
                                echo '<td>';
                                echo $descuetos_recargos->modo;
                                echo '</td>';
                                echo '<td>';
                                echo $descuetos_recargos->monto;
                                echo '</td>';
                                echo '<td>';
                                echo '<a class="btn btn-small" title="Editar" onclick="js:modificar('.$descuetos_recargos->id_descuento_recargo.','."'".'descuento'."'".')"><i class="fa fa-pencil-square-o"></i></a>';
                                echo '<a class="btn btn-small" title="Eliminar" onclick="js:eliminar('.$descuetos_recargos->id_descuento_recargo.')"><i class="icon-remove"></i></a>';
                                echo '</td>';
                                echo '</tr>';                               
                            }
                        }
                        ?>
                    </tbody>
                </table>                            
            </div>
            <div class="span6">
                <pre>Recargos activos</pre>                                
                <table id="tabla_recargos_activos" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Descripcion</th>
                            <th>Modo</th>
                            <th>Monto</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($descuetos_recargos_model as $descuetos_recargos) {
                            if ($descuetos_recargos->tipo=='recargo'){
                                echo '<tr id="fila'.$descuetos_recargos->id_descuento_recargo.'">';
                                echo '<td>';
                                echo $descuetos_recargos->descripcion;
                                echo '</td>';
                                echo '<td>';
                                echo $descuetos_recargos->modo;
                                echo '</td>';
                                echo '<td>';
                                echo $descuetos_recargos->monto;
                                echo '</td>';
                                echo '<td>';
                                echo '<a class="btn btn-small" title="Editar" onclick="js:modificar('.$descuetos_recargos->id_descuento_recargo.','."'".'recargo'."'".')"><i class="fa fa-pencil-square-o"></i></a>';
                                if ($descuetos_recargos->descripcion!='Vencimiento')
                                    echo '<a class="btn btn-small" title="Eliminar" onclick="js:eliminar('.$descuetos_recargos->id_descuento_recargo.')"><i class="icon-remove"></i></a>';
                                echo '</td>';
                                echo '</tr>';                               
                            }
                        }
                        ?>
                    </tbody>
                </table>                            
            </div>
        </div>
    </div>
    <!--
    <div class="span5">
        <pre>Descuentos/recargos aplicados</pre>     
    </div>    
    -->
</div>