<?php
/* @var $this SiteController */

    $this->pageTitle=Yii::app()->name;
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl.'/js/inicio.js');
    
    $condicion = new CDbCriteria();
    $condicion->order = 'fila ASC, columna ASC';
    
    $orden_tablas_model = TablasInicio::model()->findAll($condicion);
?>
<style type="text/css">
.diaSeleccionado{
    display: inline;
    margin-left: 10px;
    font-weight:normal;
    color:#607A9C;
    letter-spacing:2pt;
    word-spacing:4pt;
    font-size:20px;
    text-align:left;
    font-family:arial black, sans-serif;
    line-height:1;
}

ul#sortable1, ul#sortable2{
    list-style: none!important;
    list-style-type: none!important;
    list-style-position: inside!important;
    margin: 0;
}

.ui-state-highlight { height: 1.5em; line-height: 1.2em; }
</style>
<script type="text/javascript">
    /*
     * insertar despues del input aria-controls="tablaAsistencias"
"<div class='diaSeleccionado'>LUNES</div>"
     */
</script>

<h1>Bienvenido a <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<div class="row-fluid">
    <div class="span6">
        <ul id="sortable1" class="connectedSortable">
            <li>
                <?php echo $this->renderPartial('_tabla_vencidos',array('socios_vencidos_model'=>$socios_vencidos_model), TRUE);?>
            </li>
            <li>
                <?php echo $this->renderPartial('_tabla_busqueda_rapida',array('socios_model'=>$socios_model), TRUE);?>                
            </li>
        </ul>
    </div>
    <div class="span6">
        <ul id="sortable2" class="connectedSortable">
            <li>
                <?php echo $this->renderPartial('_tabla_asistencias',null, TRUE);?>                
            </li>        
        </ul>

    </div>
</div>

