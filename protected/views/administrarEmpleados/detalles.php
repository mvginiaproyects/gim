
<script type="text/javascript">

    var nombre = <?php echo "'{$model->nombre}'";?>;
    var apellido = <?php echo "'{$model->apellido}'";?>;
    var fecha_ingreso = <?php echo "'{$model->fecha_ingreso}'";?>;
    /* var estado = <?php //echo "'{$model->estado}'";?>; */
    var dni = <?php echo "'{$model->dni}'";?>;
    var fecha_nac = <?php 
                        $fecha = Yii::app()->dateFormatter->format("dd-MM-yyyy", $model->fecha_nac);
                        echo "'{$fecha}'";
                    ?>;  
    var email = <?php echo "'{$model->email}'";?>;
    //var tipo = <?php //echo "'{$model->tipo}'";?>;
    var sexo = <?php echo "'{$model->sexo}'";?>;
    var direccion = <?php echo "'{$model->direccion}'";?>;
    var telefono = <?php echo "'{$model->telefono}'";?>;

    //function tabsEvent2(e){
        //setTimeout(function() {
            //$('#tabla_cuenta').dataTable().fnAdjustColumnSizing();
        //}, 300);
    //}
</script>
<?php 
	//echo Yii::app()->request->urlReferrer;
	$this->breadcrumbs=array(
		'Buscar Empleados'=>array('buscar'),
		'Detalles ('.$model->apellido.', '.$model->nombre.')',
	);
	$contenido = $this->renderPartial('_detalleEmpleado',array('model'=>$model),true);
	//$contenido2 = $this->renderPartial('_detalleEvento',array('model'=>$model),true);
	//$contenido3 = $this->renderPartial('_detalleCuenta',array('model'=>$model),true);
	
	$this->widget('bootstrap.widgets.TbTabs', array(
                'id'=>'tabsEmpleado',
		'type'=>'tabs', // 'tabs' or 'pills'
		'placement'=>'above', // 'above', 'right', 'below' or 'left'
		//'htmlOptions' => array('class' => 'span22'),
		'tabs'=>array(
			array('label'=>'Empleado', 'content'=>$contenido, 'active'=>!$nuevo),
			//array('label'=>'Eventos', 'content'=>$contenido2, 'active'=>$nuevo),
			//array('label'=>'Cuenta', 'content'=>$contenido3),
			//array('label'=>'Dropdown', 'items'=>array(
				//array('label'=>'@fat', 'content'=>'Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney\'s organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie etsy retro mlkshk vice blog. Scenester cred you probably haven\'t heard of them, vinyl craft beer blog stumptown. Pitchfork sustainable tofu synth chambray yr.'),
				//array('label'=>'@mdo', 'content'=>'Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater. Lomo wolf viral, mustache readymade thundercats keffiyeh craft beer marfa ethical. Wolf salvia freegan, sartorial keffiyeh echo park vegan.'),
			//)),
		),
	)); 
      /*
                    $cs = Yii::app()->getClientScript();  
                    $cs->registerScript(
                        'tabsEvent',
                        '
                            $("#tabsSocio").bind("show", function(e) {tabsEvent2(e)});
                        ',
                        CClientScript::POS_END
                    );        
        */
?>