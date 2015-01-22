<script>
function confirmar(idContrato){
    url = Yii.app.createUrl('administrarContratos/terminar',{id_contrato: idContrato});
    $('#btnenviar2').prop('href',url);
    $("#myModal2").modal("show");
}
function borrar(idContrato){
    url = Yii.app.createUrl('administrarContratos/borrarTerminado',{id_contrato: idContrato});
    $('#btnenviar3').prop('href',url);
    $("#myModal3").modal("show");
}
</script>

<table class="table table-hover">
        <thead>
                <tr>
                        <th>Evento</th>
                        <th>Estado</th>
                        <th>Profesor a cargo</th>
                        <th>Tipo de contrato</th>
                        <th>Vencimiento</th>
                        <th>Saldo</th>
                        <th>Acciones</th>
                </tr>
        </thead>
        <tbody>
                <?php
                        function crearFila($contrato,$self){
                            $btn_renovar = CHtml::link('<i class="fa fa-refresh"></i>',
                                            array('administrarContratos/renovar','id_contrato'=>$contrato->id_contrato), 
                                            array('class'=>'btn btn-small', 'title'=>'Renovar')
                                        );                            
                            $btn_editar = CHtml::link('<i class="fa fa-pencil-square-o"></i>',
                                            array('administrarContratos/editar','id_contrato'=>$contrato->id_contrato), 
                                            array('class'=>'btn btn-small', 'title'=>'Editar horarios')
                                        );                            
                            $btn_terminar = CHtml::link('<i class="icon-remove"></i>',
                                            null, 
                                            array('class'=>'btn btn-small', 'title'=>'Terminar/Anular', 'onClick'=>'js:confirmar('.$contrato->id_contrato.')')
                                        );                            
                            $btn_borrar = CHtml::link('<i class="icon-remove"></i>',
                                            null, 
                                            array('class'=>'btn btn-small', 'title'=>'Borrar', 'onClick'=>'js:borrar('.$contrato->id_contrato.')')
                                        );                            
                            echo '<td>'.$contrato->nombreEvento.'</td>';
                            $label=($contrato->estado==1)? "success":(($contrato->estado==2)? "danger":"inverse");
                            $estado=($contrato->estado==1)? "Habilitado":(($contrato->estado==2)? "Vencido":"Terminado");
                            echo '<td><span class="label label-'.$label.'">'.$estado.'</span></td>';
                            echo '<td>'.$contrato->idEvento->idProfesor->idEmpleado->idPersona->apellido.', '.$contrato->idEvento->idProfesor->idEmpleado->idPersona->nombre.'</td>';
                            echo '<td>'.$self->tipoContrato($contrato->tipo_contrato).'</td>';                            
                            echo '<td>'.(($contrato->tipo_contrato==3)? $contrato->vencimiento.'</br>O clases restantes: '.$contrato->cantidad_clases:$contrato->vencimiento).'</td>';
                            $color_saldo = ($contrato->saldo==0)? '':(($contrato->saldo>0)? '#468847':'#d9534f');
                            echo '<td style="background-color:'.$color_saldo.';">$ '.$contrato->saldo.'</td>';
                            if ($contrato->estado==3)
                                echo '<td>'.$btn_borrar.'</td>';
                            else {
                                if ($contrato->tipo_contrato==2)
                                    echo '<td>'.$btn_renovar.$btn_editar.$btn_terminar.'</td>';
                                else
                                    echo '<td>'.$btn_renovar.$btn_terminar.'</td>';
                            }
                        }
                        $model_contrata=$model->contratos;
                        $registros_totales = 0;
                        $registros_habilitados = 0;
                        $registros_vencidos = 0;
                        $registros_terminados = 0;
                        foreach ($model_contrata as $contrato){
                                echo '<tr>';
                                //$opcion es la opcion seleccionada en los "radio buttons"
                                //0="mostrar todos"; 1="mostrar habilitados", 2="no habilitados"
                                if ($opcion==1){
                                    if ($contrato->estado!=3) {
                                        $registros_totales += 1;
                                        if ($contrato->estado==1) $registros_habilitados += 1;
                                        if ($contrato->estado==2) $registros_vencidos += 1;
                                        crearFila($contrato,$this);
                                    }
                                } else if ($opcion==2) {
                                    if ($contrato->estado==1) {
                                        $registros_habilitados += 1;
                                        crearFila($contrato,$this);
                                    }
                                } else if ($opcion==3) {
                                    if ($contrato->estado==2) {
                                        $registros_vencidos += 1;
                                        crearFila($contrato,$this);
                                    }                                    
                                } else {
                                    if ($contrato->estado==3) {
                                        $registros_terminados += 1;
                                        crearFila($contrato,$this);
                                    }                                    
                                }
                                
                                echo '</tr>';
                        };
                        if (($registros_totales == 0) && ($opcion == 1)){
                            echo '<tr>';
                            echo '<td colspan="7" style="text-align:center;"><span class="text-danger">No se encontraron eventos contratados para este socio</span></td>';
                            echo '</tr>';
                        }
                        if (($registros_habilitados == 0) && ($opcion == 2)){
                            echo '<tr>';
                            echo '<td colspan="7" style="text-align:center;"><span class="text-danger">No se encontraron eventos habilitados para este socio</span></td>';
                            echo '</tr>';
                        }
                        if (($registros_vencidos == 0) && ($opcion == 3)){
                            echo '<tr>';
                            echo '<td colspan="7" style="text-align:center;"><span class="text-danger">No se encontraron eventos vencidos para este socio</span></td>';
                            echo '</tr>';
                        }
                        if (($registros_terminados == 0) && ($opcion == 4)){
                            echo '<tr>';
                            echo '<td colspan="7" style="text-align:center;"><span class="text-danger">No se encontraron eventos terminados para este socio</span></td>';
                            echo '</tr>';
                        }
                ?>
        </tbody>
</table>
<div class="row-fluid">
    <div class="span10 center">
        <?php 
                echo "<strong>";
                if (($registros_totales != 0) && ($opcion == 1)){
                    echo "<span class='text-info'>Total de eventos contratados: {$registros_totales}</span> | ";
                    echo "<span class='text-success'>Total de eventos habilitados: {$registros_habilitados}</span> | ";
                    echo "<span class='text-danger'>Total de eventos vencidos: {$registros_vencidos}</span>";
                } else
                if (($registros_habilitados != 0) && ($opcion == 2)){
                    echo "<span class='text-success'>Total de eventos habilitados: {$registros_habilitados}</span>";
                } else
                if (($registros_vencidos != 0) && ($opcion == 3)){
                    echo "<span class='text-danger'>Total de eventos vencidos: {$registros_vencidos}</span>";
                } else
                if (($registros_terminados != 0) && ($opcion == 4)){
                    echo "<span class='text-danger'>Total de eventos terminados: {$registros_terminados}</span>";
                }
                echo "</strong>";
        ?>
    </div>
</div>
<?php $mimodal2 = $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'myModal2',
        //'backdrop'=>'static',
        //'keyboard'=>false,
        )
);
$mimodal2->options = array('backdrop'=> 'static','keyboard'=> true,'show' => false);
//Yii::trace(CVarDumper::dumpAsString($mimodal));
?>
 
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Confirmar</h4>
    </div>
 
    <div class="modal-body">
        <p>Esto dará por terminado el contrato.</p>
        <p>Si tiene configurado la opcion de borrar contratos terminados, también se eliminará, junto con los registros de la cuenta y asistencia generado por este contrato.</p>
        <p>¿Esta seguro de continuar?</p>
    </div>
 
    <div class="modal-footer">
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'id'=>'btnenviar2',
                'type' => 'primary',
                'label' => 'Confirmar',
                'url' => '#',
            )
        ); ?>
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'id'=>'btncancelar',
                'label' => 'Cancelar',
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
            )
        ); ?>
    </div>
<?php $this->endWidget(); ?>
<?php $mimodal3 = $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'myModal3',
        //'backdrop'=>'static',
        //'keyboard'=>false,
        )
);
$mimodal3->options = array('backdrop'=> 'static','keyboard'=> true,'show' => false);
//Yii::trace(CVarDumper::dumpAsString($mimodal));
?>
 
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Borrar contrato</h4>
    </div>
 
    <div class="modal-body">
        <p>Esto borrara el contrato de la base de datos, junto con los registros de la cuenta y asistencia generado por este contrato.</p>
        <p>¿Esta seguro de continuar?</p>
    </div>
 
    <div class="modal-footer">
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'id'=>'btnenviar3',
                'type' => 'primary',
                'label' => 'Confirmar',
                'url' => '#',
            )
        ); ?>
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'id'=>'btncancelar',
                'label' => 'Cancelar',
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
            )
        ); ?>
    </div>
<?php $this->endWidget(); ?>