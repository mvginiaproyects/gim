<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/mis_estilos.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/stickyFooter.css" />
        <!--<link rel="stylesheet" type="text/css" href="<?php //echo Yii::app()->request->baseUrl; ?>/css/bootstrap3.css" />
	[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/weekcalendar_estilos.css" />
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js');?>
	<?php //Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');?>

	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/mis_scripts.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.dataTables.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/fnReloadAjax.js');?>

	<!-- week calendar -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.weekcalendar.css" />
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.weekcalendar.js');?>
	<!-- *** -->
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.horarios.js');?>

        
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<script type="text/javascript">
    ayuda = false;
    $('#ayuda').hide();
    function cerrar(){
        if ($('#infos_mensajes').find('.alert-info')){
            $.ajax({
                url: Yii.app.createUrl('site/infoVisto'),
                success: function(data){
                    //console.log(data);
                }
            })
        }
        $('#infos_mensajes').remove();
    }
</script>    
    
<body>



	


 <!-- Part 1: Wrap all page content here -->
    <div id="wrap">
	<!--<div id="header"><h4>Menu</h4>
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div> header -->
        
        <?php 
        $ppp = '<i class="fa fa-question-circle"></i>';
        $this->widget(
            'bootstrap.widgets.TbNavbar',
            array(
                'brand' => 'Inicio',
                'fixed' => 'top',
                'collapse'=>true,
                'items' => array(
                    array(
                        'class' => 'bootstrap.widgets.TbMenu',
                        'items' => array(
                            //array('label' => 'Home', 'url' => '#'),
                            array(
                                 'label' => 'Socios',
                                 'items' => array(
                                     array('label' => 'Buscar socios', 'url' => array('administrarSocio/buscar')),
                                     array('label' => 'Agregar socios', 'url' => array('administrarSocio/crear')),
                                 ),
                                'visible'=>!Yii::app()->user->isGuest,
                             ),
                            array(
                                 'label' => 'Eventos',
                                 'items' => array(
                                     array('label' => 'Buscar Evento', 'url' => array('administrarEventos/buscar')),
                                     array('label' => 'Crear Evento', 'url' => array('administrarEventos/crearEvento')),
                                     array('label' => 'Administrar horarios', 'url' => array('administrarEventos/nuevosHorarios')),
                                     array('label' => 'Administrar precios', 'url' => array('administrarEventos/precios')),
                                     array('label' => 'Descuentos y recargos', 'url' => array('administrarEventos/descuentosRecargos')),
                                 ),
                                'visible'=>!Yii::app()->user->isGuest,
                             ),
                            array(
                                 'label' => 'Empleados',
                                 'items' => array(
                                     //array('label' => 'Buscar empleados', 'url' => array('administrarEmpleados/buscar')),
                                     array('label' => 'Crear empleados', 'url' => array('administrarEmpleados/crear')),
                                 ),
                                'visible'=>!Yii::app()->user->isGuest,
                             ),
                            array(
                                 'label' => 'Salones',
                                 'items' => array(
                                     array('label' => 'Buscar salones', 'url' => array('administrarSalon/buscar')),
                                     array('label' => 'Crear salones', 'url' => array('administrarSalon/crear')),
                                 ),
                                'visible'=>!Yii::app()->user->isGuest,
                             ),
                            
                            array('label'=>'Usuarios',
                                 'url'=>Yii::app()->user->ui->userManagementAdminUrl, 
                                 'visible'=>!Yii::app()->user->isGuest),
                            
                            array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                            array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),

                        )
                    ),

                    array(
                        'class' => 'bootstrap.widgets.TbMenu',
                        'encodeLabel'=>false,
                        'htmlOptions' => array('class' => 'pull-right'),
                        'items' => array(
                            //array('label' => 'Pruebas', 'url' => array('Pruebas/asistencias')),
                            //array('label' => 'Pruebas', 'url' => array('facebook/index')),
                            array('label' => '<i class="fa fa-cog"></i>', 'url' => array('AdministrarConfiguraciones/configuracion'), 'itemOptions' => array('id' => 'btn_configuracion'), 'visible'=>!Yii::app()->user->isGuest),
                            array('label' => '<i class="fa fa-question-circle"></i>', 'url' => 'javascript:mostrarOcultarAyuda();', 'itemOptions' => array('id' => 'btn_ayuda'), 'visible'=>!Yii::app()->user->isGuest),
                        )
                    ),
                    
                    
                )
            )
        );
        ?>
        <div id="push_nav"></div>
        <div id="ayuda" class="alert alert-info" style="display: none;">
            Aqui va la ayuda
        </div>
        <?php $msgs=Yii::app()->user->getFlashes()?>     
        <?php if (count($msgs)>0):?>
            <?php echo '<div id="infos_mensajes" class="container"><div class="row-fluid"><div class="span12">';?>
                <?php foreach ($msgs as $tipo => $mensaje):;?>
                    <div class="alert alert-block alert-<?php echo $tipo?>">
                        <button type="button" class="close" data-dismiss="alert" onclick="cerrar()">&times;</button>
                      <h4><?php echo (ucfirst($tipo)=="Success")? 'Exito!':ucfirst($tipo)?></h4>
                      <?php echo $mensaje?>
                    </div>      
                <?php endforeach;?>
                <?php
                    $cs = Yii::app()->getClientScript();  
                    $cs->registerScript(
                        'cerrar_avisos',
                        '
                            if (!$("#infos_mensajes").find(".alert").hasClass("alert-info"))
                                $("#infos_mensajes").fadeIn().animate({opacity: 1.0}, 4000).fadeOut("slow", function(e){$(this).remove();});
                        ',
                        CClientScript::POS_END
                    );        
                ?>
            <?php echo '</div></div></div>';?>
        <?php endif;?>
	<?php //if(isset($this->breadcrumbs)):?>
	<?php if(count($this->breadcrumbs)>0):?>
            <?php echo '<div class="container"><div class="row-fluid"><div class="span12">';?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
            <?php echo '</div></div></div>';?>
	<?php endif?>
    <!-- Begin page content -->
      <div class="container">
<?php echo $content; ?>
      </div>

      <div id="push"></div>

    </div>

    <div id="footer">
      <div class="container">
        <p class="muted credit">Proyecto final</p>
      </div>
    </div>        
        
        

</body>
 
 <?php echo Yii::app()->user->ui->displayErrorConsole(); ?>    
    
</html>
