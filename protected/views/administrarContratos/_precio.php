<?php
    $condicion = new CDbCriteria();
    $condicion->condition='tipo="descuento"';
    $descuentos = DescuentoRecargo::model()->findAll($condicion);
    $condicion->condition='tipo="recargo"';
    $recargos = DescuentoRecargo::model()->findAll($condicion);
    
?>
<script type="text/javascript">
/*
 * 
CUANDO VOY DE "POR CLASE" A "POR MES" EL CHECK NO FUNCIONA
    
SOLUCIONADO! AGREGUE UN .delegate EN contratar.php    
*/
    var descuentos_lista = <?php echo CJSON::encode($descuentos);?>;
    var recargos_lista = <?php
        if ($forma==='Renovar')
            echo CJSON::encode($recargos);
        else
            echo CJSON::encode('{}');
    ?>;
    $(document).ready(function() {
        if (datosAEnviar.contrato.tipoContrato==3){
            $('#prorrateo').hide();
        } else {
            $('#prorrateo').show();            
        }
        
    });
</script>

<div id="resumen_precio" class="row-fluid">
    <div class="span4 pre">
        <div class="row-fluid">
            <div class="span12">
                <blockquote>Precio parcial</blockquote>
                <div id="precio_parcial">

                </div>                
            </div>
        </div>
        <div class="row-fluid" id="div_inscripcion" style="display: none;">
            <div class="row-fluid">
                <div class="span12">
                    <blockquote>Inscripcion</blockquote>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span9">
                    <?php
                        echo CHtml::checkBox('inscripcion',false,array('id'=>'inscripcion_check','style'=>'display:inline'));
                        echo CHtml::label(' Inscripcion','inscripcion_check',array('style'=>'display:inline'));
                    ?>

                </div>
                <div class="span3">
                    <?php
                        echo CHtml::telField('inscripcion_valor', '0', array('style'=>'width:80%;', 'class'=>'disabled', 'disabled'=>'true'));
                    ?>                             
                </div>                
            </div>
        </div>
    </div>
    <div class="span4 pre">
        <div class="row-fluid">
            <blockquote>Descuento</blockquote>
            <div id="descuento">
                <div class="row-fluid">
                    <div class="span6">
                        <?php
                            $lista = CHtml::listData($descuentos, 'id_descuento_recargo', 'descripcion');
                            $otro = array('0'=>'Otro');
                            echo CHtml::dropDownList('descuento', 0, $otro+$lista, array('style'=>'width:100%;'));
                        ?>                        
                    </div>
                    <div class="span3">
                        <?php
                            echo CHtml::dropDownList('forma', 1, array('1'=>'$', '2'=>'%'), array('style'=>'width:100%;'));
                        ?>                        
                    </div>
                    <div class="span3">
                        <?php
                            //echo CHtml::telField('valor_descuento', '0', array('style'=>'width:80%;'));
                            echo CHtml::numberField('valor_descuento', '0', array('min'=>0, 'max'=>'999', 'style'=>'width:80%;'));
                        ?>                        
                    </div>
                </div>
                <div id="prorrateo" class="row-fluid">
                    <div class="span9 in">
                        <?php
                            echo CHtml::checkBox('prorrateo',false,array('id'=>'prorrateo_check','style'=>'display:inline'));
                            echo CHtml::label(' Prorrateo a fin de mes','prorrateo_check',array('style'=>'display:inline'));
                        ?>
                    </div>
                    <div class="span3">
                        <?php
                            echo CHtml::telField('prorrateo_valor', '0', array('style'=>'width:80%;', 'class'=>'disabled', 'disabled'=>'true'));
                        ?>                        
                    </div>
                </div>
            </div>                        
        </div>
        <?php if ($forma==='Renovar'):?>
            <div class="row-fluid">
                <blockquote>Recargo</blockquote>                   
                <div id="recargo">
                    <div class="row-fluid">
                        <div class="span6">
                            <?php
                                $lista = CHtml::listData($recargos, 'id_descuento_recargo', 'descripcion');
                                $otro = array('0'=>'Otro');
                                echo CHtml::dropDownList('recargo', 0, $otro+$lista, array('style'=>'width:100%;'));
                            ?>                        
                        </div>
                        <div class="span3">
                            <?php
                                echo CHtml::dropDownList('forma2', 1, array('1'=>'$', '2'=>'%'), array('style'=>'width:100%;'));
                            ?>                        
                        </div>
                        <div class="span3">
                            <?php
                                echo CHtml::telField('valor_recargo', '0', array('style'=>'width:80%;'));
                            ?>                        
                        </div>
                    </div>
                </div>                        
            </div>
        <?php endif;?>
    </div>
    <div class="span4 pre">
        <div class="row-fluid">
            <blockquote>Precio a pagar</blockquote>
            <div>
                <div id="precio_final" class="resaltar text-center">
                    $ 0
                </div>
                <?php echo CHtml::link($forma, '#', array('class'=>'btn btn-mini btn-info pull-right', 'onclick'=>strtolower($forma).'(this);', 'id'=>'btn-contratar'))?>
            </div>  
        </div>
    </div>
</div>
