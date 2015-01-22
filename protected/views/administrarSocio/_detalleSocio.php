<script type="text/javascript">
    var estado = <?php echo $model->estado;?>;
    $(document).ready(function(){
        //$('#myModal').modal({'backdrop': 'static','keyboard': false}, 'hide');
        //$('#myModal').modal('hide');
        var mensaje;
        if (estado==1)
            mensaje = "El socio ha sido deshabilitado</br>manualmente por el administrador";
        if (estado!=0)
            $('#tooltip').tooltip({'placement':'right', 'trigger' : 'hover', title: mensaje, html: true});
        
    });
    
    function guardarDatosFormModificarSocio(){
        var data=$("#socio-form").serialize();
        var url = '<?php echo Yii::app()->createAbsoluteUrl("administrarSocio/detalles",array('id'=>$model->id_socio)); ?>';
        $.ajax({
            type: 'POST',
            url: url,
            data:data,
            beforeSend:function(){
                $('#btnenviar').html("<span id='span_enviar' class='fa fa-spinner fa-spin'></span>  Enviando......").removeClass('btn-primary').addClass('btn-info');
                $('#btnenviar').attr('disabled', 'disabled').addClass('disabled');
                $('#btncancelar').attr('disabled', 'disabled').addClass('disabled');
            },
            success:function(result){
                if (result=='')
                    window.location = url;
                else {
                    console.log(result);
                    restaurarDatosFormModificarSocio();
                    $('#btnenviar').html('Guardar').removeAttr("disabled").removeClass("disabled").removeClass("btn-info").addClass("btn-primary");
                    $('<div id="msg_error" class="container" style="display: none"><div class="row-fluid"><div class="span12"><div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><h4>Error</h4>'+result+'</div></div></div></div>').insertAfter('#ayuda');
                    $('#msg_error').fadeIn().animate({opacity: 1.0}, 4000).fadeOut("slow", function(e){$(this).remove();});
                }
            },
            dataType:'html'
        });
 
}

</script>
<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid row_paddin_boton">
            <div></div>
            <div class="span4">
                    <h4>Nombre: </h4><span class="label label-default"><h4><?php echo $model->nombre;?></h4></span>
                    <h4>Apellido: </h4><span class="label label-default"><h4><?php echo $model->apellido;?></h4></span>
                    <h4>Direccion: </h4><span class="label label-default"><h4><?php echo ($model->direccion)? $model->direccion:'-';?></h4></span>
                    <h4>Telefono: </h4><span class="label label-default"><h4><?php echo ($model->telefono)? $model->telefono:'-';?></h4></span>
            </div>
            <div class="span4">
                    <h4>D.N.I.: </h4><span class="label label-default"><h4><?php echo $model->dni;?></h4></span>
                    <h4>Email: </h4><span class="label label-default"><h4><?php echo ($model->email)? $model->email:'-';?></h4></span>
                    <h4>Sexo: </h4><span class="label label-default"><h4><?php echo ($model->sexo==0)? 'Masculino':'Femenino';?></h4></span>
            </div>
            <div class="span3">
                    <h4>Estado: </h4><span id="tooltip" class="label label-<?php echo ($model->estado==0)? 'success':'danger';?>"><h4><?php echo ($model->estado==0)? 'Habilitado':'No habilitado';?></h4></span>
                    <h4>Fecha de ingreso: </h4><span class="label label-default"><h4><?php echo $model->fecha_ingreso;?></h4></span>
                    <h4>Edad: </h4><span class="label label-default"><h4><?php echo $this->CalculaEdad($model->fecha_nac);?> años</h4></span>
            </div>

        </div>
        <div class="row-fluid row_paddin_boton">
            <div class="span1 center">
                <?php $this->widget(
                    'bootstrap.widgets.TbButton',
                    array(
                          'label' => 'Modificar',
                          'type' => 'primary',
                          'htmlOptions' => array(
                              'data-toggle' => 'modal',
                              'data-target' => '#myModal',
                              'backdrop' => 'static',
                          ),
                      )
                    );
                ?>          

            </div>


        </div>
    </div>
</div>
<?php $mimodal = $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'myModal',
        //'backdrop'=>'static',
        //'keyboard'=>false,
        )
);
$mimodal->options = array('backdrop'=> 'static','keyboard'=> true,'show' => false);
//Yii::trace(CVarDumper::dumpAsString($mimodal));
?>
 
    <div class="modal-header">
        <a class="close" data-dismiss="modal" onclick="restaurarDatosFormModificarSocio();">&times;</a>
        <h4>Modificar datos</h4>
    </div>
 
    <div class="modal-body">
        <?php $this->renderPartial('_formModificarSocio',array('model'=>$model));?>
    </div>
 
    <div class="modal-footer">
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'id'=>'btnenviar',
                'type' => 'primary',
                'label' => 'Guardar',
                'url' => '#',
                'htmlOptions' => array('onclick'=>'js:guardarDatosFormModificarSocio();'),
            )
        ); ?>
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'id'=>'btncancelar',
                'label' => 'Cancelar',
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal', 'onclick'=>'js:restaurarDatosFormModificarSocio();'),
            )
        ); ?>
    </div>
<?php $this->endWidget(); ?>