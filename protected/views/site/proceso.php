<script type="text/javascript">
    $(document).ready(function(){
        $('#myModal').modal('show');  
        procesar();
    });

    var posicion = 0;
    var tamano = 0;
    var tamano1 = 0;
    function procesar() {
        var url2 = '<?php echo Yii::app()->createAbsoluteUrl("site/index");?>';
        //var posicion = 0;
        //var tamano = 0;
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("site/procesar");?>', 
            dataType: 'json',
            data: {posicion: posicion, tamano: tamano, tamano1: tamano1},
            beforeSend: function(){
            },
            success: function(data) {
                //console.log('Tamano: '+data.progreso);
                //console.log("Antes: "+posicion);
                posicion=data.posicion;
                //console.log("Despues: "+posicion);
                $('#pr').css('width',data.progreso+'%');
                $('#pr').html(data.progreso+"%");
                if (data.progreso<100){                    
                    tamano = data.tamano;
                    tamano1 = data.tamano1;
                    procesar(); 
                }
                else
                {
                    //var url2 = Yii.app.createUrl("site/index",{vencidos: tamano});
                    $('#pr').html("Listo!");
                    setTimeout(function() {window.location = url2;}, 1000);                    
                }
                    console.log(data);
            },
            error: function(data){
                console.log('error');
            }
        });
    };    


</script>
<?php $mimodal = $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'myModal',
        )
    );
    $mimodal->options = array('backdrop'=> 'static','keyboard'=> true,'show' => false);
?>
 
    <div class="modal-header">
        <h4>Verificando contratos vencidos...</h4>
    </div>
 
    <div class="modal-body">
        <div class="progress progress-striped active">
          <div id="pr" class="bar" style="width: 0%;">0%</div>
        </div>        
    </div>
 
    <div class="modal-footer">
        
    </div>
<?php $this->endWidget(); ?>