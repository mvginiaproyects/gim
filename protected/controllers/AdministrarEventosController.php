<?php

class AdministrarEventosController extends Controller {
    
    public $layout='//layouts/column1';
      
    public function filters()
    {

            return array(
                    'accessControl', // perform access control for CRUD operations
                    'postOnly + delete', // we only allow deletion via POST request
            );
    }
    
    public function actionDescuentosRecargos() {
        
        $new_dr_model = new DescuentoRecargo();
        
        $descuetos_recargos_model = DescuentoRecargo::model()->findAll();
        
        $this->render('descuentosRecargos',array('new_dr_model'=>$new_dr_model,'descuetos_recargos_model'=>$descuetos_recargos_model));
    }
    
    public function actionEliminarDR() {
        $id_dr = $_GET['id_dr'];
        $cuenta_model = CuentaSocio::model()->findAll('id_descuento_recargo='.$id_dr);
        try {
            foreach ($cuenta_model as $cuenta) {
                $cuenta->id_descuento_recargo = null;
                $cuenta->save();
            }
            DescuentoRecargo::model()->deleteByPk($id_dr);            
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }

    }
    
    public function actionGuardarDR() {
        $modo = $_POST['modo'];
        $datos = $_POST['datos'];
        if ($modo=='guardar')
            $descuento_recargo_model = new DescuentoRecargo;
        else
            $descuento_recargo_model = DescuentoRecargo::model()->findByPk($_POST['id']);
        
        $descuento_recargo_model->attributes = $datos;
        if ($descuento_recargo_model->validate()){
            $descuento_recargo_model->save();
            echo $descuento_recargo_model->getPrimaryKey();
        }else
            echo 'Error';
        
    }
    
    public function actionCrearEvento(){
        
        $evento_model = new Evento;
        $condicion = new CDbCriteria();
        $condicion->with = array('idEmpleado'=>array('with'=>'idPersona'));
        $profesores_model = Profesor::model()->findAll($condicion);
        
        if (isset($_POST['Evento'])){
            $evento_model->attributes = $_POST['Evento'];
            if ($evento_model->validate()){
                $evento_model->save();
                $this->redirect(array('administrarEventos/detalles', 'id_evento'=>$evento_model->getPrimaryKey()));
                //echo '<pre>';
                //print_r($evento_model);
                //echo '</pre>';
                Yii::app()->end();                
                //Yii::app()->user->setFlash('success', 'Se grabo correctamente!');
            }
        }
        //Yii::trace(CVarDumper::dumpAsString($profesores_model));
        $this->render('crear', array('evento_model'=>$evento_model, 'profesores_model'=>$profesores_model));
        
    }
    
    public function actionGetListaSocio() {
        $condicion = new CDbCriteria();
        $filtroTipo = substr($_POST['filtro'], 0, 1);
        $filtroCodigoTipo = ($filtroTipo=='H')? 2:(int)substr($_POST['filtro'],1);
        $filtroHorario = (int)substr($_POST['filtro'],1);
        if ($filtroTipo!='T')
        {
            $condicion->condition = 'id_evento = '.$_POST['id_evento'].' AND estado!=3 AND tipo_contrato='.$filtroCodigoTipo;
            if ($filtroTipo=='H')
                $condicion->with = array('contrataHorarios'=>array(
                    'condition'=>'contrataHorarios.id_horario='.$filtroHorario,
                ));
        }
        else{
            $condicion->condition = 'id_evento = '.$_POST['id_evento'].' AND estado!=3';
        }
        $lista_socios_model = Contrato::model()->findAll($condicion);
        
        $datos = Array();
        $datos["data"] = Array();
        $datos['tipos'] = Array();
        $datos['horarios'] = Array();
        
        $index = 0;
        foreach ($lista_socios_model as $socio) {
            array_push($datos["data"], array(
                'id_socio'=>$socio->id_socio,
                'nombreCompleto'=>$socio->apellidoSocio.', '.$socio->nombreSocio,
                'tipo'=>(($socio->tipo_contrato==1)? 'Libre':(($socio->tipo_contrato==2)? 'Semanal':'Por clase')),
                "horarios"=> array()
            ));
            
            if ($socio->tipo_contrato!=2){
                if (!array_search((($socio->tipo_contrato==1)? 'Libre':'Por clase'), $datos['tipos']))
                    array_push ($datos['tipos'], (($socio->tipo_contrato==1)? 'Libre':'Por clase'));
            }
            if ($filtroTipo=='H')
                $condicion->condition = 'id_contrato='.$socio->id_contrato.' AND t.id_horario='.$filtroHorario;
            else
                $condicion->condition = 'id_contrato='.$socio->id_contrato;
            $condicion->with = array('idHorario');
            $listaHorarios = ContrataHorario::model()->findAll($condicion);
            
            foreach ($listaHorarios as $horario) {
                $horario_completo = $horario->idHorario->dia.' '.substr($horario->idHorario->hora_inicio, 0, 5).' a '.substr($horario->idHorario->hora_fin, 0, 5).'hs';
                $hor = array(
                    'horario'=>$horario_completo,
                );
                array_push($datos['data'][$index]['horarios'], $hor);
                if (!in_array($horario_completo, $datos['tipos'])){
                    array_push($datos['tipos'], $horario_completo);
                    array_push($datos['horarios'], $horario->idHorario->id_horario);
                }
            }
            
            $index++;
        }
        echo CJSON::encode($datos);
    }
    
    public function actionBuscar(){
		$model=new Evento('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Evento'])){
			$model->attributes=$_GET['Evento'];
		}
		$this->render('buscar',array('model'=>$model));        
    }
    
    public function actionDetalles($id_evento){
        $evento_model = Evento::model()->findByPk($id_evento);
        if(isset($_POST['Evento'])){
            $evento_model->attributes=$_POST['Evento'];
            if ($evento_model->validate() && $evento_model->save()){
                Yii::app()->end();
            } else {
                $evento_model->validate();
                foreach ($evento_model->getErrors() as $error) {
                    echo $error[0]."</br>";
                }
                Yii::app()->end();
            }
            Yii::app()->end();
        }
        $this->render('detalles', array('evento_model'=>$evento_model));
    }

    public function actionNuevosHorarios($guardado=false, $id_evento_seleccionado=0){

        $salones_model = $this->loadSalonesModel();
        $eventos_model = $this->loadEventosModel();
        $horarios = array();
        
        foreach ($salones_model as $salon) {
            $horario_salon = $this->loadHorariosPorSalonModel($salon->id_salon);
            $horarios[$salon->id_salon] = array();
            foreach ($horario_salon as $horario){
                //POR ALGO INEXPLICABLE TUVE QUE SUMARLE 5 HORAS PARA QUE ME LO MOSTRARA BIEN EN EL CALENDARIO
                //(NO ASI EN EL PRIMERO)
                // -2 PARA EL SERVIDOR ¡¡WOW!!
                //ESTO PASO POR LA ZONA HORARIA. YA LO ARREGLE PONIENDO LA ZONA CORRECTA PARA TODOS LOS
                //CONTROLADORES EN EL init() DEL COMPONENTE Controller.php
                $hf=gmdate("D M d Y H:i:s e", mktime(substr($horario->hora_fin,0,2),substr($horario->hora_fin,3,2),0,7,$this->sacarDia($horario->dia),2013));
                $hi=gmdate("D M d Y H:i:s e",  mktime(substr($horario->hora_inicio,0,2),substr($horario->hora_inicio,3,2),0,7,$this->sacarDia($horario->dia),2013));
                $horario2 = array(
                    'id'=>$horario->id_horario,
                    'title'=>$horario->nombreEvento,
                    'body'=>$horario->idEvento->nombreProfesor,
                    'start'=>$hi,
                    'end'=>$hf,
                    'disponibilidad'=>$horario->disponibilidad,
                    'salon'=>$horario->idSalon->nombre,
                    'id_salon'=>$horario->id_salon,
//                'colorSalon'=>  $this->coloresSalones($horario->id_salon),
                    'colorSalon'=>  $horario->idSalon->color,
                    'seleccionado'=>false,
                    'borrable'=>  !$this->horarioContratado($horario->id_horario),
                    'readOnly' => FALSE
                );
                array_push($horarios[$horario->id_salon], $horario2);
            }
        }
        if ($guardado)
            Yii::app()->user->setFlash('success','Se han guardado los cambios exitosamente');        
        $this->render('nuevosHorarios',array("horarios"=>$horarios, "eventos"=>$eventos_model, "salones"=>$salones_model,'idEventoSeleccionado'=>$id_evento_seleccionado));
    }
    
    private function horarioContratado($id_horario) {
        $condicion = new CDbCriteria();
        $condicion->condition='id_horario='.$id_horario;
        
        $condicion->with = array('idContrato'=>array(
            'condition'=>'idContrato.estado!=3'
        ));
        
        $contratos_horarios_model = ContrataHorario::model()->find($condicion);
        if (count($contratos_horarios_model)>0)
            return TRUE;
        else
            return FALSE;
    }
    
    
    public function actionHorariosOtrosSalones(){
        $id_salon = $_POST['id_salon'];
        $id_evento = $_POST['id_evento'];
        $id_profesor = $_POST['id_profesor'];
        $horarios = array();
        $condicion = new CDbCriteria();
        $condicion->condition='id_salon!='.$id_salon;
        $condicion->with = array('idEvento'=>array(
            'condition'=>'id_profesor='.$id_profesor,
        ));
        $horarios_model= Horario::model()->findAll($condicion);
        foreach ($horarios_model as $horario){
                //POR ALGO INEXPLICABLE TUVE QUE SUMARLE 5 HORAS PARA QUE ME LO MOSTRARA BIEN EN EL CALENDARIO
                //(NO ASI EN EL PRIMERO)
                // -2 PARA EL SERVIDOR ¡¡WOW!!
                //POR ALGO INEXPLICABLE TUVE QUE SUMARLE 5 HORAS PARA QUE ME LO MOSTRARA BIEN EN EL CALENDARIO
                //(NO ASI EN EL PRIMERO)
                // -2 PARA EL SERVIDOR ¡¡WOW!!
                //ESTO PASO POR LA ZONA HORARIA. YA LO ARREGLE PONIENDO LA ZONA CORRECTA PARA TODOS LOS
                //CONTROLADORES EN EL init() DEL COMPONENTE Controller.php
                $hf=gmdate("D M d Y H:i:s e", mktime(substr($horario->hora_fin,0,2),substr($horario->hora_fin,3,2),0,7,$this->sacarDia($horario->dia),2013));
                $hi=gmdate("D M d Y H:i:s e",  mktime(substr($horario->hora_inicio,0,2),substr($horario->hora_inicio,3,2),0,7,$this->sacarDia($horario->dia),2013));
                $horario2 = array(
                    'id'=>$horario->id_horario,
                    'title'=>$horario->nombreEvento,
                    'body'=>$horario->idEvento->nombreProfesor,
                    'start'=>$hi,
                    'end'=>$hf,
                    'disponibilidad'=>$horario->disponibilidad,
                    'id_salon'=>$horario->id_salon,
                    'salon'=>$horario->idSalon->nombre,
//                'colorSalon'=>  $this->coloresSalones($horario->id_salon),
                    'colorSalon'=>  $horario->idSalon->color,
                    'seleccionado'=>false,
                    'readOnly' => true
                );
                //echo $horario2['start'];
                array_push($horarios, $horario2);            
        }
        //print_r($horarios);
        echo CJSON::encode($horarios);
        //echo count($horarios_model);
    }
    
    public function actionGuardarCambiosHorarios(){        
        $datos = $_POST;
        //Yii::app()->user->setFlash('error', 'Ha ocurrido un error');
        
        //1ro LOS BORRADOS
        if ($datos['borrados']){
            $condicion = new CDbCriteria();
            $condicion->addInCondition('id_horario', $datos['borrados']);
            ContrataHorario::model()->deleteAll($condicion);
            Horario::model()->deleteAll($condicion);            
        }
        //2do LOS CAMBIADOS
        if ($datos['cambiados']){
            foreach ($datos['cambiados'] as $horario_cambiado) {
                $horario_model = $this->loadHorarioModel($horario_cambiado['id']);
                
                $fecha_inicio = new DateTime(trim($horario_cambiado['start'],' (Hora estándar de Argentina)'));
                $fecha_fin = new DateTime(trim($horario_cambiado['end'],' (Hora estándar de Argentina)'));

                $dia = $this->sacarNroDia(date('j',$fecha_inicio->getTimestamp()));
                $hora_inicio = date('H:i:s',$fecha_inicio->getTimestamp());
                $hora_fin = date('H:i:s',$fecha_fin->getTimestamp());

                //$horario_model->disponibilidad = $horario_cambiado['disponibilidad'];
                $horario_model->dia = $dia;
                $horario_model->orden = $this->ordenDia($dia);
                $horario_model->hora_inicio = $hora_inicio;
                $horario_model->hora_fin = $hora_fin;
                $horario_model->save();
            }
        }
        
        //3ro LOS NUEVOS
        if ($datos['nuevos']){
            foreach ($datos['nuevos'] as $nuevo_horario) {
                $nuevo_horario_model = new Horario();
                $fecha_inicio = new DateTime(trim($nuevo_horario['start'],' (Hora estándar de Argentina)'));
                $fecha_fin = new DateTime(trim($nuevo_horario['end'],' (Hora estándar de Argentina)'));

                $dia = $this->sacarNroDia(date('j',$fecha_inicio->getTimestamp()));
                $hora_inicio = date('H:i:s',$fecha_inicio->getTimestamp());
                $hora_fin = date('H:i:s',$fecha_fin->getTimestamp());

                $nuevo_horario_model->id_salon = $nuevo_horario['id_salon'];
                $nuevo_horario_model->id_evento = $nuevo_horario['id_evento'];
                $nuevo_horario_model->disponibilidad = $nuevo_horario['disponibilidad'];
                $nuevo_horario_model->dia = $dia;
                $nuevo_horario_model->orden = $this->ordenDia($dia);
                $nuevo_horario_model->hora_inicio = $hora_inicio;
                $nuevo_horario_model->hora_fin = $hora_fin;
                $nuevo_horario_model->save();

            }            
        }
        //echo "<pre>";
        //print_r($datos);
        //echo "</pre>";
    }
    
    private function ordenDia($dia){
        switch ($dia) {
            case 'lunes':
                return 1;
                break;
            case 'martes':
                return 2;
                break;
            case 'miercoles':
                return 3;
                break;
            case 'jueves':
                return 4;
                break;
            case 'viernes':
                return 5;
                break;
            case 'sabado':
                return 6;
                break;
        }
    }
    
    public function actionPrecios($id_evento_seleccionado=0) {
        $eventos_model = $this->loadEventosModel();
        $precios_model = Precio::model()->findAll();
        if (isset($_POST['precios'])){
            $datos = $_POST['precios'];
            $idEvento = $_POST['idEvento'];
            foreach ($datos as $tipo => $valores) {
                if ($tipo!=2){
                    if ($valores['modificacion']!='ninguna' && $valores['modificacion']!=null){
                        if ($valores['existe']=='true'){//modificar o eliminar precio existente
                            $precio_model = Precio::model()->findByPk($valores['idPrecio']);
                            if ($valores['modificacion']=='modificado'){
                                $precio_model->idEvento = $idEvento;
                                $precio_model->tipo = $tipo;
                                $precio_model->precio = intval($valores['valor']);
                                $precio_model->save();
                            } else{//si esta para borrarse
                                $precio_model->delete();
                            }
                        } else {//nuevo precio
                            $precio_model = new Precio();
                            $precio_model->id_evento = $idEvento;
                            $precio_model->tipo = $tipo;
                            $precio_model->precio = intval($valores['valor']);
                            $precio_model->save();
                        }
                    }
                } else {//si es de tipo 2 (clases por semana)
                    foreach ($valores as $clases => $valores_dias) {
                        if ($valores_dias['modificacion']!='ninguna' && $valores_dias['modificacion']!=null){
                            if ($valores_dias['existe']=='true'){//modificar o eliminar precio existente
                                $precio_model = Precio::model()->findByPk($valores_dias['idPrecio']);
                                if ($valores_dias['modificacion']=='modificado'){
                                    $precio_model->tipo = 2;
                                    $precio_model->clases_x_semana = $clases;
                                    $precio_model->precio = intval($valores_dias['valor']);
                                    $precio_model->save();
                                } else{//si esta para borrarse
                                    $precio_model->delete();
                                }
                            } else {//nuevo precio
                                $precio_model = new Precio();
                                $precio_model->id_evento = $idEvento;
                                $precio_model->tipo = 2;
                                $precio_model->clases_x_semana = $clases;
                                $precio_model->precio = intval($valores_dias['valor']);
                                $precio_model->save();
                            }
                        }
                    }
                }
                
            }
            Yii::app()->end();
        }
        if (isset($_GET['idEvento']))
            $id_evento_seleccionado = $_GET['idEvento'];
        $this->render('precios', array('eventos'=>$eventos_model, 'precios'=>$precios_model,'idEventoSeleccionado'=>$id_evento_seleccionado));
        //$this->render('precios', array('eventos'=>$eventos_model, 'precios'=>$precios_model,'idEventoSeleccionado'=>(isset($_GET['idEvento']))?$_GET['idEvento']:0));
    }
    
    public function actionGuardadoCorrecto() {
        $eventos_model = $this->loadEventosModel();
        $precios_model = Precio::model()->findAll();
        Yii::app()->user->setFlash('success','Se han guardado los cambios exitosamente');
        $this->render('precios', array('eventos'=>$eventos_model, 'precios'=>$precios_model,'idEventoSeleccionado'=>$_GET['idEvento']));
    }

    private function sacarDia($dia){
    switch ($dia){
            case 'lunes':
                return 1;
                break;
            case 'martes':
                return 2;
                break;
            case 'miercoles':
                return 3;
                break;
            case 'jueves':
                return 4;
                break;
            case 'viernes':
                return 5;
                break;
            case 'sabado':
                return 6;
                break;
            case 'domingo':
                return 7;
                break;
            default:
                return 7;
        }        
    }

private function sacarNroDia($nro){
    switch ($nro){
            case 1:
                return 'lunes';
                break;
            case 2:
                return 'martes';
                break;
            case 3:
                return 'miercoles';
                break;
            case 4:
                return 'jueves';
                break;
            case 5:
                return 'viernes';
                break;
            case 6:
                return 'sabado';
                break;
            case 7:
                return 'domingo';
                break;
            default:
                return 'domingo';
        }        
    }

    private function coloresSalones($id) {
        switch ($id) {
            case 1:
                return '#129';
                break;
            case 2:
                return '#A28';
                break;
            case 3:
                return '#347';
                break;
            default:
                return '#999';
                break;
        }
    }

    public function loadEventosModel()
    {
            $model=Evento::model()->findAll(array('order'=>'nombre'));
            if($model===null)
                    throw new CHttpException(404,'The requested page does not exist.');
            return $model;
    }
    
    public function loadSalonesModel()
    {
            $model=Salon::model()->findAll();
            if($model===null)
                    throw new CHttpException(404,'The requested page does not exist.');
            return $model;
    }

    public function loadHorariosPorSalonModel($id_salon)
    {
            $condicion = new CDbCriteria();
            $condicion->condition='id_salon='.$id_salon;
            $model= Horario::model()->findAll($condicion);
            if($model===null)
                    throw new CHttpException(404,'The requested page does not exist.');
            return $model;
    }

    public function loadHorarioModel($id_horario)
    {
            $model= Horario::model()->findByPk($id_horario);
            if($model===null)
                    throw new CHttpException(404,'The requested page does not exist.');
            return $model;
    }
    
}
