<?php

$condicion = new CDbCriteria();
$condicion->with = array('idPersona'=>array(
    'condition'=>'DAYOFMONTH(fecha_nac)=DAYOFMONTH(CURDATE()) AND MONTH(fecha_nac)=MONTH(CURDATE())'
));
$socios_model = Socio::model()->findAll($condicion);

?>

<div class="row-fluid">
    <blockquote>Cumpleaños del dia
    <?php 
        echo CHtml::link('<i class="icon-chevron-up"></i>',
                                        array('#'), 
                                        array(
                                            'id'=>'btn_tabla_cumpleaniero',
                                            'class'=>'btn btn-info btn-mini pull-right', 
                                            'title'=>'Contraer',
                                            'onclick'=>'swap($(this), $("#warp_tabla_cumpleaniero"))')
                                    );
    ?>
    </blockquote>
    <div id="warp_tabla_cumpleaniero">
        <table id="tabla_cumpleanieros" class="table table-hover">
            <thead>
                <tr>
                    <th>Id socio</th>
                    <th>Dni</th>
                    <th>Apellido</th>
                    <th>Nombre</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($socios_model as $socio){
                            echo '<tr id="'.$socio->id_socio.'">';
                                echo '<td>'.$socio->id_socio.'</td>';
                                echo '<td>'.$socio->dni.'</td>';
                                echo '<td>'.$socio->apellido.'</td>';
                                echo '<td>'.$socio->nombre.'</td>';
                            echo '</tr>';
                    };
                ?>
            </tbody>
    </table>
    </div>
</div>
