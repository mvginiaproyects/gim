<?php
    $condicion = new CDbCriteria();
    $condicion->condition='tipo="descuento"';
    $descuentos = DescuentoRecargo::model()->findAll($condicion);
    
    