<div class="row-fluid">
    <blockquote>Asistencias rápidas          
    <?php 
        echo CHtml::link('<i class="icon-chevron-up"></i>',
                                        array('#'), 
                                        array(
                                            'id'=>'btn_tabla_asistencias',
                                            'class'=>'btn btn-info btn-mini pull-right', 
                                            'title'=>'Contraer',
                                            'onclick'=>'swap($(this), $("#warp_tabla_asistencias"))')
                                    );
    ?>
    </blockquote>  
    <div id="warp_tabla_asistencias">
        Fecha: 
        <?php 
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'asistencia',
                'value' => date('Y-m-d'),
                'options' => array(
                        'dateFormat' => 'yy-mm-dd',     // format of "2012-12-25"
                        //'language'=>'de',
                        'showOtherMonths' => true,      // show dates in other months
                        'selectOtherMonths' => true,    // can seelect dates in other months
                        'changeYear' => true,           // can change year
                        'changeMonth' => true,          // can change month
                        'maxDate' => 'today',
                        'yearRange' => '1960:date(y)',     // range of year
                        'showButtonPanel' => true,
                        'onClose' =>'js:function(date){procesar(this)}'
                ),
                'htmlOptions' => array(
                    'size' => '10',         // textField size
                    'maxlength' => '10',    // textField maxlength
                    'id'=>'asistenciaFecha',
                    'style'=>'width:80px;'
                ),
            ));
        ?>
        <span id='cargandoTablaAsistencia' class='fa fa-spinner fa-spin'></span>
        <table id="tablaAsistencias" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th></th>
                    <th>Evento</th>
                    <th>Profesor</th>
                    <th>Horario</th>
                </tr>
            </thead>
        </table>        
    </div>
</div>
