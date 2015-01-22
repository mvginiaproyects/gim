<?php

    $condicion = new CDbCriteria();
    $condicion->condition = 'id_evento = '.$evento_model->id_evento;
    
    $cantidad_alumnos = Contrato::model()->count($condicion);

?>

<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid row_paddin_boton">
            <div></div>
            <div class="span4">
                    <h4>Nombre: </h4><span class="label label-default"><h4><?php echo $evento_model->nombre;?></h4></span>
                    <h4>Profesor a cargo: </h4><span class="label label-default"><h4><?php echo $evento_model->fullName;?></h4></span>
                    <h4>Cant. de socios: </h4><span class="label label-default"><h4><?php echo $cantidad_alumnos;?></h4></span>
            </div>
        </div>
    </div>
</div>