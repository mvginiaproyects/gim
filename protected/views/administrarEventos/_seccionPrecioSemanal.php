<div id="s2" class="seccion-precio" style="display: none; margin-top: 5px">
    <div class="row-fluid">
        <div class="span1" style="padding: 10px 0">
            <div style="padding: 5% 0">
                <a class="pull-right btn-small" href="#" onclick="quitarTipoActivo(2)"><i class="icon-remove"></i></a>
            </div>
        </div>
        <div class="span11" style="margin-top: 10px;">
            <div class="row-fluid">
                <div class="span12">
                    Clases semanales: 
                    <?php 
                        $lista = array( '1'=>'1',
                                        '2'=>'2',
                                        '3'=>'3',
                                        '4'=>'4',
                                        '5'=>'5',
                                        '6'=>'6'
                            );
                        echo CHtml::dropDownList('dias_x_semana', '0', $lista, array('style'=>'width:47px;'));
                    ?>
                    <a class="btn-small" href="#" onclick="agregarPrecioSemanal()"><i class="fa fa-plus fa-2x"></i></a>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12" id="dias_x_semanas_activos">
                    <div id="d1" style="display: none"></div>
                    <div id="d2" style="display: none"></div>
                    <div id="d3" style="display: none"></div>
                    <div id="d4" style="display: none"></div>
                    <div id="d5" style="display: none"></div>
                    <div id="d6" style="display: none"></div>
                </div>
            </div>
        </div>
    </div>
</div>