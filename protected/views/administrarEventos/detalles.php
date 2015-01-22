<?php 

    $baseUrl = Yii::app()->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl.'/js/administrarEventos_detalles.js');

    $condicion = new CDbCriteria();
    $condicion->condition = 'id_evento = '.$evento_model->id_evento.' AND estado!=3';
    
    $cantidad_alumnos = Contrato::model()->count($condicion);
    
    $condicion = new CDbCriteria();
    $condicion->condition = 'id_evento='.$evento_model->id_evento;
    $condicion->order = 'orden ASC, hora_inicio ASC';
    $condicion->with = 'idSalon';
    $horarios_model = Horario::model()->findAll($condicion);
    
    $this->breadcrumbs=array(
            'Buscar eventos'=>array('buscar'),
            'Detalles ('.$evento_model->nombre.')',
    );

?>
<script type="text/javascript">
    
    var id_evento = <?php echo $evento_model->id_evento;?>;
    var nombreEvento = "<?php echo $evento_model->nombre;?>";
    var fullNameProfesor = "<?php echo $evento_model->fullName;?>";
    var idProfesor = <?php echo $evento_model->id_profesor;?>
    
</script>


<div class="row-fluid">
    <div class="span3">
        <pre>Datos</pre>
        <div class="row-fluid">
            <h4>Nombre: </h4><span class="label label-default"><h4><?php echo $evento_model->nombre;?></h4></span>
            <h4>Profesor a cargo: </h4><span class="label label-default"><h4><?php echo $evento_model->fullName;?></h4></span>
            <h4>Cant. de socios: </h4><span class="label label-default"><h4><?php echo $cantidad_alumnos;?></h4></span>            
        </div>
        <div class="row-fluid center">
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
        <div class="separador10"></div>
    </div>
    <div class="span3">
        <pre>Precios activos</pre>
        <div class="row-fluid">
            <?php echo $this->renderPartial('_preciosActivos', array('id_evento'=>$evento_model->id_evento), true);?>        
        </div>
        <div class="row-fluid center">
            <?php 
                echo CHtml::link('Administrar precios', array('precios','id_evento_seleccionado'=>$evento_model->id_evento), array('class'=>'btn btn-primary'))
            ?>
        </div>
        <div class="separador10"></div>        
    </div>
    <div class="span3">
        <pre>Horarios</pre>
        <div class="row-fluid">
            <table id="tabla_horarios" class="table table-hover">
                <thead>
                    <tr>
                        <th>Horario</th>
                        <th>Salon</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($horarios_model as $horario) {
                        echo '<tr>';
                        echo '<td>';
                        echo $horario->dia.' '.substr($horario->hora_inicio, 0, 5).' a '.substr($horario->hora_fin, 0, 5).'hs';
                        echo '</td>';
                        echo '<td>';
                        echo $horario->idSalon->nombre;
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>            
        </div>
        <div class="row-fluid center">
            <?php 
                echo CHtml::link('Administrar horarios', array('nuevosHorarios','id_evento_seleccionado'=>$evento_model->id_evento), array('class'=>'btn btn-primary'))
            ?>            
        </div>
        <div class="separador10"></div>        
    </div>
    <div class="span3">
        <pre>Lista de socios</pre>
        <table id="tabla_lista" class="table table-hover">
            <thead>
                <tr>
                    <th></th>
                    <th>Socio</th>
                </tr>
            </thead>
        </table>
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
        <?php $this->renderPartial('_formModificarEvento',array('evento_model'=>$evento_model));?>
    </div>
 
    <div class="modal-footer">
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'id'=>'btnenviar',
                'type' => 'primary',
                'label' => 'Guardar',
                'url' => '#',
                'htmlOptions' => array('onclick'=>'js:guardarDatosFormModificarEvento();'),
            )
        ); ?>
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'id'=>'btncancelar',
                'label' => 'Cancelar',
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal', 'onclick'=>'js:restaurarDatosFormModificarEvento();'),
            )
        ); ?>
    </div>
<?php $this->endWidget(); ?>