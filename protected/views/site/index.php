<?php
/* @var $this SiteController */

    $this->pageTitle=Yii::app()->name;
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl.'/js/inicio.js');
    
    $contraidos = array();
    
    $condicion = new CDbCriteria();
    $condicion->order = 'fila ASC';
    $condicion->condition = 'columna=1';
    $orden_col1_model = TablasInicio::model()->findAll($condicion);
    $condicion->condition = 'columna=2';
    $orden_col2_model = TablasInicio::model()->findAll($condicion);
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
    padding: 5px
}

.ui-state-highlight { height: 1.5em; line-height: 1.2em; }

blockquote {
    cursor: move
}
</style>
<script type="text/javascript">
    /*
     * insertar despues del input aria-controls="tablaAsistencias"
"<div class='diaSeleccionado'>LUNES</div>"
     */
    //var contraidos = [];
    
</script>

<h1>Bienvenido a <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<div class="row-fluid">
    <div class="span6">
        <ul id="sortable1" class="connectedSortable">
            <?php 
            foreach ($orden_col1_model as $col1) {
                echo '<li>';
                echo $this->renderPartial('_'.$col1->nombre,array('socios_model'=>$socios_model,'socios_vencidos_model'=>$socios_vencidos_model), TRUE);
                echo '</li>';
                if (!$col1->expandido)
                    array_push ($contraidos, $col1->nombre);
            }
            ?>
        </ul>
    </div>
    <div class="span6">
        <ul id="sortable2" class="connectedSortable">
            <?php 
            foreach ($orden_col2_model as $col2) {
                echo '<li>';
                echo $this->renderPartial('_'.$col2->nombre,array('socios_model'=>$socios_model,'socios_vencidos_model'=>$socios_vencidos_model), TRUE);
                echo '</li>';
                if (!$col2->expandido)
                    array_push ($contraidos, $col2->nombre);
            }
            ?>
            <?php
                $cs = Yii::app()->getClientScript();  
                $cs->registerScript(
                    'contraidos',
                    '
                        var contraidos='.CJSON::encode($contraidos).';
                        contraer(contraidos)'
                        ,
                    CClientScript::POS_END
                );        
            ?>
        </ul>

    </div>
</div>

