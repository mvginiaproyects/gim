<div class="span8 offset2">
<h1>Busqueda de eventos</h1>

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
       var inputSelector='#'+'contrata-evento-grid'+' .filters input, '+'#'+'contrata-evento-grid'+' .filters select';
       $(inputSelector).each( function(i,o) {
            $(o).val('');
       });
       var data=$.param($(inputSelector));
       $.fn.yiiGridView.update('contrata-evento-grid', {data: data});
       //alert('ok');
       return false;
    };
    
    function eliminar(idSocio, nombre){
        alert('TERMINAR LA FUNCION PARA ELIMINAR!! '+nombre);
    };
    
</script>
<?php 
//$this->widget('zii.widgets.grid.CGridView', array(
$this->widget('application.components.GGridView', array(
	'id'=>'contrata-evento-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
        'footer_count'=>true,
        'footer_count_text'=>'Eventos encontrados:',
        'columns'=>array(
		array(
			'name'=>'id_evento',
			'value'=>'$data->id_evento',
                        'filterHtmlOptions'=>array('style'=>'padding-right: 15px'),
                        //'filter'=>'<div style="padding-right: 15px;">'.CHtml::telField('Evento[id_evento]').'</div>',
			'htmlOptions'=>array('style' => 'width: 17%; text-align: center;'),
		),
		array(
			'name'=>'nombre',
			'value'=>'$data->nombre',
                        'filterHtmlOptions'=>array('style'=>'padding-right: 15px'),
		),
		//'dni',
		array(
			'name'=>'apellidoProfesor',
			'value'=>'$data->apellidoProfesor',
                        'filterHtmlOptions'=>array('style'=>'padding-right: 15px'),
		),
		//'apellido',
		array(
			'name'=>'nombreProfesor',
			'value'=>'$data->nombreProfesor',
                        'filterHtmlOptions'=>array('style'=>'padding-right: 15px'),
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
                                            array('administrarEventos/detalles','id_evento'=>$data->id_evento), 
                                            array('class'=>'pull-right btn btn-success btn-small')
                                        );
                        //$btn_eliminar = CHtml::link('<i class="icon-remove"></i>',
                                            //array('administrarSocio/eliminar','id'=>$data->id_socio), 
                                            //array('confirm' => 'Esta seguro de eliminar el socio '.$data->id_socio.': '.$data->nombre.', '.$data->apellido.'?', 'class'=>'pull-right btn btn-danger btn-small')
                                        //);
                        $btn_eliminar = CHtml::htmlButton('<i class="icon-remove"></i>',
                                            array('onclick'=>'eliminar('.$data->id_evento.',"'.$data->nombre.');','class'=>'pull-right btn btn-danger btn-small')
                                        );
                        //$salida = $btn_eliminar."<span class='pull-right'>|</span>".$btn_detalles;
                        $salida = $btn_detalles;
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