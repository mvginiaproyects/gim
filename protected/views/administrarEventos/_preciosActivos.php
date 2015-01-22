<?php 
    $condicion = new CDbCriteria();
    $condicion->condition = 'id_evento = '.$id_evento;
    $condicion->order = 'tipo ASC, clases_x_semana ASC';
    
    $precios_model = Precio::model()->findAll($condicion);
?>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Precio</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($precios_model as $precio) {
            echo '<tr>';
            echo '<td>';
            switch ($precio->tipo) {
                case 0:
                    echo 'Inscripcion';
                    break;
                case 1:
                    echo 'Libre';
                    break;
                case 2:
                    echo $precio->clases_x_semana.' veces por semana';
                    break;
                case 3:
                    echo 'Por clase';
                    break;
            }
            echo '</td>';            
            echo '<td>';
            echo '$ '.$precio->precio;
            echo '</td>';                        
            echo '</tr>';
        }
        ?>
    </tbody>
</table>
