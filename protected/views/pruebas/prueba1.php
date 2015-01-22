<?php
echo '<pre>';
//echo $contratosModel;
echo '</pre>';
?>

<script>
    var posicion = 0;
    var tamano = 0;
    function worker() {
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("administrarConfiguraciones/worker");?>', 
            dataType: 'json',
            data: {posicion: posicion, tamano: tamano},
            beforeSend: function(){
                //$.cookie("prog",0);
                //verificar();
            },
            success: function(data) {
                //console.log('Tamano: '+data.progreso);
                console.log("Antes: "+posicion);
                posicion=data.posicion;
                console.log("Despues: "+posicion);
                //$('#pr').val(data.progreso);
                //$('#pr').html(data.progreso+"%");
                $('#pr').css('width',data.progreso+'%');
                $('#pr').html(data.progreso+"%");
                if (data.progreso<100){                    
                    tamano = data.tamano;
                    worker(); 
                }
                else
                    $('#pr').html("Listo!");
            },
            complete: function() {
            // Schedule the next request when the current one's complete
            //setTimeout(worker, 1000);
            }
        });
    };    
    
    function error() {
        url = '<?php echo Yii::app()->createAbsoluteUrl("administrarConfiguraciones/error");?>';
        $.ajax({
            type: 'POST',
            url: url, 
            success: function(data) {
                console.log('Exito');
            },
            error: function(error) {
                console.log(error);
                window.location = url;
            }
        });
    };    

</script>


<a href="#" onclick="worker();">Run</a>
<div class="progress progress-striped active">
  <div id="pr" class="bar" style="width: 0%;">0%</div>
</div>
<a href="#" onclick="error();">Error</a>