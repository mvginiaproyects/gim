<div id="s0" class="seccion-precio" style="display: none; margin-top: 5px">
    <div class="row-fluid">
        <div class="span1" style="padding: 10px 0">
            <div style="padding: 10% 0">
                <a class="pull-right btn-small" href="#" onclick="quitarTipoActivo(0)"><i class="icon-remove"></i></a>                
            </div>
        </div>
        <div class="span11" style="margin-top: 10px;">
            <div class="row-fluid">
                <div class="span10" style="border-bottom: rgb(97, 147, 185); border-bottom-style: double;">
                    Inscripcion
                    <div class="pull-right"></div>
                </div>
                <div class="span2">$
                    <?php
                        echo CHtml::telField('valor_inscripcion', '0', array('style'=>'width:50%;', 'id'=>'valor_0'));
                    ?>                        
                </div>
            </div>
        </div>
    </div>
</div>