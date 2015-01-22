<div id="s1" class="seccion-precio" style="display: none; margin-top: 5px">
    <div class="row-fluid">
        <div class="span1" style="padding: 10px 0">
            <div style="padding: 5% 0">            
                <a class="pull-right btn-small" href="#" onclick="quitarTipoActivo(1)"><i class="icon-remove"></i></a>
            </div>
        </div>
        <div class="span11" style="margin-top: 10px;">
            <div class="row-fluid">
                <div class="span10" style="border-bottom: rgb(97, 147, 185); border-bottom-style: double;">
                    Libre
                    <div class="pull-right"></div>
                </div>
                <div class="span2">$
                    <?php
                        echo CHtml::telField('valor_libre', '0', array('style'=>'width:50%;', 'id'=>'valor_1'));
                    ?>                        
                </div>
            </div>
        </div>
    </div>
</div>