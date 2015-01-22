<?php
    $baseUrl = Yii::app()->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl.'/js/administrarSocio_cuenta.js')
?>

<?php

$condicion = new CDbCriteria();
$condicion->with = array('idContrato'=>array(
                            'condition'=>'id_socio='.$model->id_socio,
                            )
                        );
$condicion->order = 't.id_cuenta_socio DESC, t.fecha DESC, t.id_contrato DESC, t.id_cuenta_socio ASC';
$cuenta_socio_model = CuentaSocio::model()->findAll($condicion);

$descuentos_recargos = DescuentoRecargo::model()->findAll();
//echo $descuentos_recargos->findByPk(1)->descripcion;
//echo '<pre>';
//print_r($cuenta_socio_model);
//echo '</pre>';
?>
<style type="text/css">
        
</style>

<div class="span8 center">
    <table id="tabla_cuenta" class="table table-hover">
        <thead>
            <tr>
                <th>Evento</th>
                <th>Concepto</th>
                <th>Fecha</th>
                <th>Debe</th>
                <th>Haber</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($cuenta_socio_model as $cuenta) {
                echo '<tr>';
                    echo '<td>'.$cuenta->idContrato->nombreEvento.'</td>';
                    echo '<td>';
                    if (($cuenta->concepto == 'Descuento' || $cuenta->concepto == 'Recargo')){
                        if ($cuenta->id_descuento_recargo!=null){
                            $descuentos_recargos = DescuentoRecargo::model()->findByPk($cuenta->id_descuento_recargo);
                            echo CHtml::Link($cuenta->concepto, null, 
                                array(
                                    'data-placement' => 'top',
                                    'data-toggle' => 'popover',
                                    'data-trigger' => "hover",
                                    'data-title' => "$cuenta->concepto",
                                    'data-content' => "$descuentos_recargos->descripcion ($descuentos_recargos->monto$descuentos_recargos->modo)"
                                ));

                        }else{
                            echo CHtml::Link($cuenta->concepto, null, 
                                array(
                                    'data-placement' => 'top',
                                    'data-toggle' => 'popover',
                                    'data-trigger' => "hover",
                                    'data-title' => "$cuenta->concepto",
                                    'data-content' => "Otro"
                                ));
                        }

                    } else
                        echo $cuenta->concepto;
                    echo '</td>';
                    echo '<td>'.$cuenta->fecha.'</td>';
                    echo '<td>'.(($cuenta->movimiento=='debe')? $cuenta->monto:'').'</td>';
                    echo '<td>'.(($cuenta->movimiento=='haber')? $cuenta->monto:'').'</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
        
        <tfoot>
            <tr>
                <th style="text-align:right">Saldo:</th>
                <th></th>
                <th style="text-align:right">Totales:</th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>        
    </table>  
</div>
    