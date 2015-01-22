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
                            echo '<td>'.$contrato->nombreEvento.'</td>';
                            $label=($contrato->estado==1)? "success":"danger";
                            $estado=($contrato->estado==1)? "Habilitado":(($contrato->estado==2)? "Vencido":"Terminado");
                            echo '<td><span class="label label-'.$label.'">'.$estado.'</span></td>';
                            echo '<td>'.$contrato->idEvento->idProfesor->idEmpleado->idPersona->apellido.', '.$contrato->idEvento->idProfesor->idEmpleado->idPersona->nombre.'</td>';
                            echo '<td>'.$self->tipoContrato($contrato->tipo_contrato).'</td>';                            
                            echo '<td>'.(($contrato->tipo_contrato==3)? $contrato->vencimiento.'</br>O clases restantes: '.$contrato->cantidad_clases:$contrato->vencimiento).'</td>';
                            $color_saldo = ($contrato->saldo==0)? '':(($contrato->saldo>0)? '#468847':'#d9534f');
                            echo '<td style="background-color:'.$color_saldo.';">$ '.$contrato->saldo.'</td>';
                            if ($contrato->estado==3)
                                echo '<td></td>';
                            else
                                echo '<td>'.$btn_renovar.' <i class="fa fa-pencil-square-o"></i></td>';
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
                                } else if ($option==3){
                                    if ($contrato->estado==2) {
                                        $registros_vencidos += 1;
                                        crearFila($contrato,$this);
                                    }                                    
                                }else {
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
                        if (($registros_totales == 0) && ($opcion == 4)){
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
        echo $opcion;
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