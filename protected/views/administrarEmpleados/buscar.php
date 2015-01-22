<div class="span8 offset2">
<h1>Busqueda de empleados</h1>

<p>
    Ingrese uno o mas criterios de búsqueda y presione <b>Enter</b>
</p>

<script>
    var boton = '<?php echo CHtml::resetButton('Clear!', array('id'=>'form-reset-button'));?>';
    function insertar(){
        $('div.summary').before('<div style="float:left;">'+boton+'</div>');        
    }
    //$('#form-reset-button').click(function()
    function limpiar()
    {
       var id='document-grid';
       var inputSelector='#'+'empleado-grid'+' .filters input, '+'#'+'empleado-grid'+' .filters select';
       $(inputSelector).each( function(i,o) {
            $(o).val('');
       });
       var data=$.param($(inputSelector));
       $.fn.yiiGridView.update('empleado-grid', {data: data});
       //alert('ok');
       return false;
    };
 
    function eliminar(idEmpleado, nombre, apellido){
        console.log($("#myModal4"));
        url = Yii.app.createUrl('administrarEmpleados/eliminar',{id_empleado: idEmpleado});
        $('#btnenviar3').prop('href',url);
        $('#nombreCompleto').html(apellido+', '+nombre);
        $("#myModal4").modal("show");
    }
    
</script>
<?php 
//$this->widget('zii.widgets.grid.CGridView', array(
$this->widget('application.components.GGridView', array(
	'id'=>'empleado-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    /*
	'selectionChanged'=>"function(id){window.location='"
          .Yii::app()->urlManager->createUrl('administrarSocio/detalles',array('id'=>''))."' + 
          $.fn.yiiGridView.getSelection(id);}",
     * 
     */
        //'summaryText'=>'total : {start} to {end} from {count}',
        //'template'=>' {items} {summary} {pager}',
        //'footer_count'=>$model->search()->itemCount,
        'footer_count'=>true,
        'footer_count_text'=>'Empleados encontrados:',
    //PARA QUE SE VEAN BIEN LOS FILTROS HAY QUE ENCERRARLOS EN UN <div style='padding-right: 15px;'>
    //ESTA ECHO EN CGridColumn CON COMENTARIOS EN LA LINEA 114
        'columns'=>array(
		array(
			'name'=>'id_empleado',
			'value'=>'$data->id_empleado',
                        //'filter'=>'<div style="padding-right: 15px;">'.CHtml::telField('Socio[id_socio]').'</div>',
                        'filterHtmlOptions'=>array('style'=>'padding-right: 15px'),
			'htmlOptions'=>array('style' => 'width: 17%; text-align: center;'),
		),
		array(
			'name'=>'dni',
			'value'=>'$data->dni',
                        //'filter'=>'<div style="padding-right: 15px;">'.CHtml::telField('Socio[dni]').'</div>',
                        'filterHtmlOptions'=>array('style'=>'padding-right: 15px')
		),
		//'dni',
		array(
			'name'=>'apellido',
			'value'=>'$data->apellido',
                        'filterHtmlOptions'=>array('style'=>'padding-right: 15px')
                        //'filter'=>'<div style="padding-right: 15px;">'.CHtml::telField('Socio[apellido]').'</div>',
		),
		//'apellido',
		array(
			'name'=>'Nombre',
			'value'=>'$data->nombre',
                        'filterHtmlOptions'=>array('style'=>'padding-right: 15px')
			//'filter'=>false,
		),
   
                array(
                    'header'=>'Acciones',
                    'type'=>'raw',
                    //'type'=>'html',
                    'filter'=>CHtml::link('<i class="icon-remove"></i>','#', 
                                array('onclick'=>'limpiar();', 'class'=>'pull-right btn btn-info btn-small')
                            ),
                    'value'=>function($data){
                        $btn_detalles = CHtml::link('<i class="icon-search"></i>',
                                            array('administrarEmpleados/detalles','id'=>$data->id_empleado), 
                                            array('class'=>'pull-right btn btn-success btn-small')
                                        );
                        //$btn_eliminar = CHtml::link('<i class="icon-remove"></i>',
                                            //array('administrarSocio/eliminar','id'=>$data->id_socio), 
                                            //array('confirm' => 'Esta seguro de eliminar el socio '.$data->id_socio.': '.$data->nombre.', '.$data->apellido.'?', 'class'=>'pull-right btn btn-danger btn-small')
                                        //);
                        $btn_eliminar = CHtml::htmlButton('<i class="icon-remove"></i>',
                                            array('onclick'=>'eliminar('.$data->id_empleado.');','class'=>'pull-right btn btn-danger btn-small')
                                        );
                        $salida = $btn_eliminar."<span class='pull-right'>|</span>".$btn_detalles;
                        //$btn_eliminar = CHtml::link('<i class="icon-remove"></i>','#', array('onclick' => 'eliminar('.$data->id_socio.',"'.$data->apellido.'")', 'class'=>'pull-right btn btn-danger btn-small'));
                        return $salida;
                    },
                ),
		//array(
			//'class'=>'CButtonColumn',
		//),
	),
)); 
?>
</div>
<?php $mimodal4 = $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'myModal4',
        //'backdrop'=>'static',
        //'keyboard'=>false,
        )
);
$mimodal4->options = array('backdrop'=> 'static','keyboard'=> true,'show' => false);
//Yii::trace(CVarDumper::dumpAsString($mimodal));
?>
 
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Borrar empleado</h4>
    </div>
 
    <div class="modal-body">
        <p>Esto borrara al empleado <span id="nombreCompleto"> y todos sus registros asociados.</span></p>
        <p>No se podrá recuperar.</p>
        <p>¿Esta seguro de continuar?</p>
    </div>
 
    <div class="modal-footer">
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'id'=>'btnenviar3',
                'type' => 'primary',
                'label' => 'Confirmar',
                'url' => '#',
            )
        ); ?>
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'id'=>'btncancelar',
                'label' => 'Cancelar',
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
            )
        ); ?>
    </div>
<?php $this->endWidget(); ?>