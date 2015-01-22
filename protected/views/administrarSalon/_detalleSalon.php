<script type="text/javascript">
   
    function guardarDatosFormModificarSalon(){
        var data=$("#salon-form").serialize();
        var url = '<?php echo Yii::app()->createAbsoluteUrl("administrarSalon/detalles",array('id'=>$model->id_salon)); ?>';
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
                  restaurarDatosFormModificarSalon();
                  $('#btnenviar').html('Guardar').removeAttr("disabled").removeClass("disabled").removeClass("btn-info").addClass("btn-primary");
                  $('<div id="msg_error" class="container" style="display: none"><div class="row-fluid"><div class="span12"><div class="alert alert-block alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><h4>Error</h4>'+result+'</div></div></div></div>').insertAfter('#ayuda');
                  $('#msg_error').fadeIn().animate({opacity: 1.0}, 4000).fadeOut("slow", function(e){$(this).remove();});
                 }            
            },
            //error: function(result) { // if error occured
              //  alert("Error occured.please try again");
            //},
            dataType:'html'
        });
 
}

</script>
<div class="row-fluid">
    <div class="span8">
        <div class="row-fluid row_paddin_boton">
            <div></div>
            <div class="span4">
                    <h4>Nombre: </h4><span class="label label-default"><h4><?php echo $model->nombre;?></h4></span>                   
            </div>
            <div class="span4">
                <h4>Color: </h4><span class="label label-default" style="background-color: <?php echo $model->color;?>"><h4>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</h4></span>
            </div>
            <div class="span4">        
                    <h4>Descripcion: </h4><span class="label label-default"><h4><?php echo $model->descripcion;?></h4></span>
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
        <a class="close" data-dismiss="modal" onclick="restaurarDatosFormModificarSalon();">&times;</a>
        <h4>Modificar datos</h4>
    </div>
 
    <div class="modal-body">
        <?php $this->renderPartial('_formModificarSalon',array('model'=>$model));?>
    </div>
 
    <div class="modal-footer">
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'id'=>'btnenviar',
                'type' => 'primary',
                'label' => 'Guardar',
                'url' => '#',
                'htmlOptions' => array('onclick'=>'js:guardarDatosFormModificarSalon();'),
            )
        ); ?>
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'id'=>'btncancelar',
                'label' => 'Cancelar',
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal', 'onclick'=>'js:restaurarDatosFormModificarSalon();'),
            )
        ); ?>
    </div>
<?php $this->endWidget(); ?>