<script type="text/javascript">

    var id_salon = <?php echo "'{$model->id_salon}'";?>;
    var nombre = <?php echo "'{$model->nombre}'";?>;
    var color = <?php echo "'{$model->color}'";?>;
    var capacidad_max = <?php echo "'{$model->capacidad_max}'";?>;
    var descripcion = <?php echo "'{$model->descripcion}'";?>;

</script>
<?php 
	//echo Yii::app()->request->urlReferrer;
	$this->breadcrumbs=array(
		'Buscar Salones'=>array('buscar'),
		'Detalles ('.$model->nombre.', '.$model->color.')',
	);
	$contenido = $this->renderPartial('_detalleSalon',array('model'=>$model),true);
	//$contenido2 = $this->renderPartial('_detalleEvento',array('model'=>$model),true);
	
	$this->widget('bootstrap.widgets.TbTabs', array(
		'type'=>'tabs', // 'tabs' or 'pills'
		'placement'=>'above', // 'above', 'right', 'below' or 'left'
		//'htmlOptions' => array('class' => 'span22'),
		'tabs'=>array(
			array('label'=>'Salon', 'content'=>$contenido, 'active'=>!$nuevo),
			//array('label'=>'Eventos', 'content'=>$contenido2, 'active'=>$nuevo),
			//array('label'=>'Dropdown', 'items'=>array(
				//array('label'=>'@fat', 'content'=>'Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney\'s organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie etsy retro mlkshk vice blog. Scenester cred you probably haven\'t heard of them, vinyl craft beer blog stumptown. Pitchfork sustainable tofu synth chambray yr.'),
				//array('label'=>'@mdo', 'content'=>'Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater. Lomo wolf viral, mustache readymade thundercats keffiyeh craft beer marfa ethical. Wolf salvia freegan, sartorial keffiyeh echo park vegan.'),
			//)),
		),
	)); 
?>