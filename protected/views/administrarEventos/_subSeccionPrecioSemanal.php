<div class="row-fluid">
    <div class="span1" style="padding: 10px 0">
        <div style="padding: 5% 0">
            <a class="pull-right btn-small" href="#" onclick="quitarPrecioSemanal(this);"><i class="icon-remove"></i></a>
        </div>
    </div>
    <div class="span11" style="margin-top: 10px; margin-left: 8px">
        <div class="row-fluid">
            <div class="span10">
                <div id="cant_dias_semanal" style="border-bottom: rgb(97, 147, 185); border-bottom-style: double;"></div>
            </div>
            <div class="span2">$
                <?php
                    echo CHtml::telField('valor_dia', '0', array('style'=>'width:50%;', 'id'=>'valor_2'));
                ?>                        
            </div>
        </div>
    </div>
</div>
