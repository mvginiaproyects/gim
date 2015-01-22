<?php
/* @var $this SiteController */

    $this->pageTitle=Yii::app()->name;
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();
    //$cs->registerScriptFile($baseUrl.'/js/inicio.js');
?>
<script type="text/javascript">
    $(document).ready(function() {
        tabla_socios_vencidos = $('#tabla_socios_vencidos').dataTable( {
                //"sScrollY": '200px',
                //"bCollapse": true,
        "scrollY":        "200px",
        "scrollCollapse": true,
        "bPaginate": false,
                //"bRetrieve": true,
                "bPlaceHolder": 'Busqueda de socios',
                "oLanguage": {
                    "sSearch": "",
                    "sEmptyTable": "No hay contratos vencidos"
                    //"sInfo": "Mostrando _START_ a _END_ de _TOTAL_ eventos",
                },
                'bInfo': false,
                //"bDestroy": true
        });
        tabla_socios = $('#tabla_socios').dataTable( {
        "scrollY":        "200px",
        //"scrollCollapse": true,
        "bPaginate": false,
                "bPlaceHolder": 'Busqueda rapida de socios',
                "oLanguage": {
                    "sSearch": "",
                    "sEmptyTable": "No hay socios cargados"
                    //"sInfo": "Mostrando _START_ a _END_ de _TOTAL_ eventos",
                },
                'bInfo': false,
                //"bDestroy": true
        });
    });
</script>

<h1>Bienvenido a <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<div class="row-fluid">
    <div class="span6">
        <div class="row-fluid">
            <blockquote>
                Lista de socios con contratos vencidos
                <?php 
                    echo CHtml::link('<i class="fa fa-refresh"></i>',
                                                    array('site/proceso'), 
                                                    array('class'=>'btn btn-small pull-right', 'title'=>'Volver a comprobar')
                                                );
                ?>
            </blockquote>
            <table id="tabla_socios_vencidos" class="table table-hover">
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
                        foreach ($socios_vencidos_model as $socio){
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
        <div class="row-fluid">
            <blockquote>Busqueda rápida de socios</blockquote>
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
    <div class="span6"></div>
</div>

