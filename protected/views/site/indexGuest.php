<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
//Yii::app()->miscomponentes->verificarMorosos();
?>
<script type="text/javascript">
    window.onload = function()
    {
        if(!window.jQuery)
        {
            alert('jQuery not loaded');
        }
        else
        {
            $(document).ready(function(){
                $('.bootstrap-ejemplo').tooltip({'placement':'right', 'trigger' : 'hover'});
                //$('#mipopover').popover();
                
   });
 }
 };

</script>
<h1>Bienvenido a <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<p>Por favor, loguearse para poder empezar a usar la aplicacion.</p>
