<div class="row-fluid">
    <blockquote>Busqueda rápida de socios
    <?php 
        echo CHtml::link('<i class="icon-chevron-up"></i>',
                                        array('#'), 
                                        array(
                                            'id'=>'btn_tabla_busqueda_rapida',
                                            'class'=>'btn btn-info btn-mini pull-right', 
                                            'title'=>'Contraer',
                                            'onclick'=>'swap($(this), $("#warp_tabla_busqueda_rapida"))')
                                    );
    ?>
    </blockquote>
    <div id="warp_tabla_busqueda_rapida">
        <table id="tabla_socios" class="table table-hover">
            <thead>
                <tr>
                    <th>Id socio</th>
                    <th>Dni</th>
                    <th>Apellido</th>
                    <th>Nombre</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($socios_model as $socio){
                            $btn_detalles = CHtml::link('<i class="icon-search"></i>',
                                                array('administrarSocio/detalles','id'=>$socio->id_socio, 'nuevo'=>true), 
                                                array('class'=>'btn btn-small', 'title'=>'Consultar contratos')
                                            );                            
                            echo '<tr id="'.$socio->id_socio.'">';
                                echo '<td>'.$socio->id_socio.'</td>';
                                echo '<td>'.$socio->dni.'</td>';
                                echo '<td>'.$socio->apellido.'</td>';
                                echo '<td>'.$socio->nombre.'</td>';
                                echo '<td>'.$btn_detalles.'</td>';
                            echo '</tr>';
                    };
                ?>
            </tbody>
    </table>
    </div>
</div>
