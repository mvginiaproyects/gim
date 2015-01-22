<?php

class AdministrarContratosController extends Controller{
    
    public $layout='//layouts/column1';
    
    public $id_socio=null;
    public $nombre_socio=null;
      
    public function filters()
    {

            return array(
                    'accessControl', // perform access control for CRUD operations
                    'postOnly + delete', // we only allow deletion via POST request
            );
    }
    
                     
    public function actionContratar($id,$nombre){
        //$contratos_socio_model = $this->loadContratosModel($id);
        $condicion = new CDbCriteria();
        $condicion->condition = 'id_socio='.$id.' AND estado!=3';
        $contratos_socio_model = Contrato::model()->findAll($condicion);
        $ids_contratos_socio = Array();
        foreach ($contratos_socio_model as $contrato) {
            array_push($ids_contratos_socio,$contrato->id_evento);
        }
        //Yii::trace(CVarDumper::dumpAsString($ids_contratos_socio));
        $condicion = new CDbCriteria();
        $condicion->order = 'nombre DESC';
        $condicion->addNotInCondition('t.id_evento', $ids_contratos_socio);
        $eventos= Evento::model()->findAll($condicion);

        //$eventos = $this->loadEventosModel();
        $this->id_socio = $id;
        $this->nombre_socio = $nombre;
        //if(isset($_POST['Contrato'])){
            
        //}
        $precios = Array();
        foreach ($eventos as $evento){
            $precio = CJSON::encode($evento->precios);
            array_push($precios, $precio);
        }
        //$this->render('contratar',array('eventos'=>$eventos,'precios'=>$precios));
        $this->render('contratar',array('eventos'=>$eventos));
    }
    
    public function actionConfirmarContrato(){
        $datos = $_POST;
        $idcontrato = null;
        $contrato = new Contrato;
        $cuenta_contrato = new CuentaSocio;
        $cuenta_pago = new CuentaSocio;
        $contrato->id_evento = $datos['contrato']['id_evento'];
        $contrato->id_socio = $datos['contrato']['id_socio'];
        $contrato->estado = 1;
        $contrato->fecha_contrata = date('y-m-j');
        $contrato->tipo_contrato = $datos['contrato']['tipoContrato'];
        //$fecha = date('Y-m-'.$this->diaVencimiento);
        $fecha = date('Y-m-'.Yii::app()->configuracion->diaVencimiento);
        $fecha_vencimiento = strtotime('+1 month', strtotime($fecha));
        $contrato->vencimiento = date('Y-m-j', $fecha_vencimiento);
        $cuenta_contrato->movimiento = "debe";
        $cuenta_contrato->concepto = "Contrato de servicio";
        $cuenta_contrato->monto = $datos['cuenta_socio']['precioParcial'];
        $cuenta_contrato->fecha = date('Y-m-d');
        $cuenta_pago->movimiento = "haber";
        $cuenta_pago->concepto = "Pago de servicio";
        $cuenta_pago->monto = $datos['cuenta_socio']['precioParcial'] - $datos['cuenta_socio']['descuento'] - $datos['cuenta_socio']['valor_prorrateo'];
        $cuenta_pago->fecha = date('Y-m-d');

        //print_r($datos);
        switch ($datos['contrato']['tipoContrato']) {
            case 1://mensual, libre     
                $contrato->save();
                $idcontrato = $contrato->getPrimaryKey();
                break;
            case 2://mensual, clases por semana
                if ($datos['precioNuevoHorarios']['nuevo']=='true'){
                    $precio_nuevo = new Precio;
                    $precio_nuevo->id_evento = $datos['contrato']['id_evento'];
                    $precio_nuevo->tipo = 2;
                    $precio_nuevo->clases_x_semana = $datos['precioNuevoHorarios']['clasesXSemana'];
                    $precio_nuevo->precio = $datos['precioNuevoHorarios']['precio'];
                    echo "PRECIO NUEVO</br>";
                    print_r($precio_nuevo);
                    echo "</br>";
                    $precio_nuevo->save();
                }
                $contrato->save();
                $idcontrato = $contrato->getPrimaryKey();
                foreach ($datos['horarios_contratados'] as $id_horario) {
                    $horario_contratado = new ContrataHorario;
                    $horario_disponibilidad = Horario::model()->findByPk($id_horario);
                    $horario_disponibilidad->disponibilidad -= 1;
                    $horario_contratado->id_contrato = $idcontrato;
                    $horario_contratado->id_horario = $id_horario;
                    echo "Horario contratado {$id_horario} </br>";
                    print_r($horario_contratado);
                    echo '</br>';
                    $horario_contratado->save();
                    $horario_disponibilidad->save();
                }
                echo "</br>";                
                break;
            case 3://por clases
                $contrato->cantidad_clases = $datos['contrato']['cantidadClases'];                
                $fecha = date('Y-m-j');
                $fecha_vencimiento = strtotime("+ ".Yii::app()->configuracion->diasVencimientoPorClase." day", strtotime($fecha));
                $contrato->vencimiento = date('Y-m-j', $fecha_vencimiento);
                $contrato->save();
                $idcontrato = $contrato->getPrimaryKey();
                break;
        }
        if ($datos['cuenta_socio']['inscripcion']=='true'){
            $cuenta_cobro_inscripcion = new CuentaSocio;
            $cuenta_cobro_inscripcion->movimiento = "debe";
            $cuenta_cobro_inscripcion->concepto = "Inscripcion";
            $cuenta_cobro_inscripcion->monto = $datos['cuenta_socio']['valor_inscripcion'];
            $cuenta_cobro_inscripcion->id_contrato = $idcontrato;
            $cuenta_cobro_inscripcion->fecha = date('Y-m-d');
            $cuenta_pago_inscripcion = new CuentaSocio;
            $cuenta_pago_inscripcion->movimiento = "haber";
            $cuenta_pago_inscripcion->concepto = "Pago de inscripcion";
            $cuenta_pago_inscripcion->monto = $datos['cuenta_socio']['valor_inscripcion'];
            $cuenta_pago_inscripcion->id_contrato = $idcontrato;
            $cuenta_pago_inscripcion->fecha = date('Y-m-d');
            $cuenta_cobro_inscripcion->save();
            $cuenta_pago_inscripcion->save();
        }
        if ($datos['cuenta_socio']['descuento']>0){
            $cuenta_descuento = new CuentaSocio;
            $cuenta_descuento->movimiento = "haber";
            $cuenta_descuento->concepto = "Descuento";
            $cuenta_descuento->monto = $datos['cuenta_socio']['descuento'];
            $cuenta_descuento->id_descuento_recargo = ($datos['cuenta_socio']['id_descuento']==0)? null:$datos['cuenta_socio']['id_descuento'];
            $cuenta_descuento->fecha = date('Y-m-d');
            echo "CUENTA DESCUENTO</br>";
            print_r($cuenta_descuento);
            echo "</br>";
            $cuenta_descuento->id_contrato = $idcontrato;
            $cuenta_descuento->save();
        }
        if ($datos['cuenta_socio']['prorrateo']){
            if ($datos['cuenta_socio']['valor_prorrateo']>0){
                $cuenta_prorrateo = new CuentaSocio;
                $cuenta_prorrateo->movimiento = "haber";
                $cuenta_prorrateo->concepto = "Prorrateo";
                $cuenta_prorrateo->monto = $datos['cuenta_socio']['valor_prorrateo'];
                $cuenta_prorrateo->fecha = date('Y-m-d');
                echo "CUENTA PRORRATEO</br>";
                print_r($cuenta_prorrateo);
                echo "</br>";
                $cuenta_prorrateo->id_contrato = $idcontrato;
                $cuenta_prorrateo->save();                
            }            
        }
        echo "CONTRATO</br>";
        print_r($contrato);
        echo "</br>";
        echo "CUENTA CONTRATO</br>";
        print_r($cuenta_contrato);
        echo "</br>";
        echo "CUENTA PAGO</br>";
        print_r($cuenta_pago);
        echo "</br>";
        $cuenta_contrato->id_contrato = $idcontrato;
        $cuenta_contrato->save();
        $cuenta_pago->id_contrato = $idcontrato;
        $cuenta_pago->save();
        Yii::app()->end();
    }

    public function actionGuardadoCorrecto() {
        if ($_GET['modo']=='edicion')
            Yii::app()->user->setFlash('success','Se han guardado los cambios exitosamente');
        $this->redirect(array('administrarSocio/detalles','id'=>$_GET['idSocio'], 'nuevo'=>true));
    }

    public function actionBorrarTerminado() {
        $contrato_model = Contrato::model()->findByPk($_GET['id_contrato']);
        $id_socio = $contrato_model->id_socio;
        try {
            ContrataHorario::model()->deleteAll(array('condition'=>'id_contrato='.$_GET['id_contrato']));
            CuentaSocio::model()->deleteAll(array('condition'=>'id_contrato='.$_GET['id_contrato']));
            Asistencia::model()->deleteAll(array('condition'=>'id_contrato='.$_GET['id_contrato']));
            $contrato_model->delete();                
            Yii::app()->user->setFlash('success','El contrato se ha eliminado correctamente');
            $this->redirect(array('administrarSocio/detalles','id'=>$id_socio, 'nuevo'=>true));
        } catch (Exception $exc) {
            Yii::app()->user->setFlash('warning','Se produjo un error');
            $this->redirect(array('administrarSocio/detalles','id'=>$id_socio, 'nuevo'=>true));
        }
    }
    
    public function actionTerminar() {
        $contrato_model = Contrato::model()->findByPk($_GET['id_contrato']);
        $id_socio = $contrato_model->id_socio;
        $contrata_horario_model = ContrataHorario::model()->findAll(array('condition'=>'id_contrato='.$_GET['id_contrato']));
        foreach ($contrata_horario_model as $horario_contratado) {
            $horario_model = Horario::model()->findByPk($horario_contratado->id_horario);
            $horario_model->disponibilidad += 1;
            $horario_model->save();
        }
        if (Yii::app()->configuracion->borrarContratosTerminados){
            try {
                ContrataHorario::model()->deleteAll(array('condition'=>'id_contrato='.$_GET['id_contrato']));
                CuentaSocio::model()->deleteAll(array('condition'=>'id_contrato='.$_GET['id_contrato']));
                Asistencia::model()->deleteAll(array('condition'=>'id_contrato='.$_GET['id_contrato']));
                $contrato_model->delete();                
                Yii::app()->user->setFlash('success','El contrato se ha anulado correctamente');
                $this->redirect(array('administrarSocio/detalles','id'=>$id_socio, 'nuevo'=>true));
            } catch (Exception $exc) {
                Yii::app()->user->setFlash('warning','Se produjo un error');
                $this->redirect(array('administrarSocio/detalles','id'=>$id_socio, 'nuevo'=>true));
            }
        } else {
            $contrato_model->estado=3;
            $contrato_model->save();       
                Yii::app()->user->setFlash('success','El contrato se ha anulado correctamente');
                $this->redirect(array('administrarSocio/detalles','id'=>$id_socio, 'nuevo'=>true));
        }
    }

    public function actionConfirmarEdicion() {
        $datos = $_POST;
        $idcontrato = $datos['contrato']['id_contrato'];
        //echo '<pre>';
        //print_r($_POST);
        //echo '</pre>';
        foreach ($datos['horarios_contratados'] as $id_horario) {
            if (in_array($id_horario, $datos['horarios_originales'])){
                $datos['horarios_originales'] = array_diff($datos['horarios_originales'], array($id_horario));
                $datos['horarios_contratados'] = array_diff($datos['horarios_contratados'], array($id_horario));
            } else {//agrego el horario id horario
                $horario_contratado = new ContrataHorario;
                $horario_disponibilidad = Horario::model()->findByPk($id_horario);
                $horario_disponibilidad->disponibilidad -= 1;
                $horario_contratado->id_contrato = $idcontrato;
                $horario_contratado->id_horario = $id_horario;
                $horario_contratado->save();
                $horario_disponibilidad->save();
            }
        }
        foreach ($datos['horarios_originales'] as $id_horario) {
            if (in_array($id_horario, $datos['horarios_contratados'])){
                $datos['horarios_originales'] = array_diff($datos['horarios_originales'], array($id_horario));
                $datos['horarios_contratados'] = array_diff($datos['horarios_contratados'], array($id_horario));
            } else {//quitar el horario id_horario
                $condicion = new CDbCriteria();
                $condicion->condition = 'id_contrato='.$idcontrato.' AND id_horario='.$id_horario;
                $horario_contratado = ContrataHorario::model()->find($condicion);
                $horario_disponibilidad = Horario::model()->findByPk($id_horario);
                $horario_disponibilidad->disponibilidad += 1;
                $horario_contratado->delete();
                $horario_disponibilidad->save();
            }
        }
        Yii::app()->end();
    }
    
    public function actionEditar($id_contrato){
        $contrato_model = $this->loadContratoModel($id_contrato);
        $this->id_socio = $contrato_model->id_socio;
        $this->nombre_socio = $contrato_model->idSocio->apellido.', '.$contrato_model->idSocio->nombre;
        
        $condicion = new CDbCriteria();        
        $condicion->condition = 'id_contrato='.$id_contrato;
        $horarios_contratados_model = ContrataHorario::model()->findAll($condicion);
        $ids_horarios_contratados = Array();
        foreach ($horarios_contratados_model as $horario)
            array_push($ids_horarios_contratados, $horario->id_horario);
        
        $condicion->condition = 'id_evento='.$contrato_model->id_evento;
        $horarios_model = Horario::model()->findAll($condicion);
               
        $datos_evento['events'] = Array();
        $datos_evento['html'] = Array();
        foreach ($horarios_model as $horario){
            $hf=gmdate("D M d Y H:i:s e", mktime(substr($horario->hora_fin,0,2),substr($horario->hora_fin,3,2),0,7,$this->sacarDia($horario->dia),2013));
            $hi=gmdate("D M d Y H:i:s e",  mktime(substr($horario->hora_inicio,0,2),substr($horario->hora_inicio,3,2),0,7,$this->sacarDia($horario->dia),2013));
            $horario2 = array(
              'id'=>$horario->id_horario,
                'title'=>$contrato_model->idEvento->nombre,
                'body'=>$contrato_model->idEvento->nombreProfesor,
                'start'=>$hi,
                'end'=>$hf,
                'disponibilidad'=>$horario->disponibilidad,
                'id_salon'=>$horario->id_salon,
                'salon'=>$horario->idSalon->nombre,
//                'colorSalon'=>  $this->coloresSalones($horario->id_salon),
                'colorSalon'=>  $horario->idSalon->color,
                'seleccionado'=> in_array($horario->id_horario, $ids_horarios_contratados)? true:false,
                'readOnly' => true
            );
            array_push($datos_evento['events'], $horario2);            
        }
        $html_horarios = $this->renderPartial('_horarios', array('editar'=>'Edicion'), true);
        array_push($datos_evento['html'], $html_horarios);
        $datos_evento['horarios_seleccionados'] = $ids_horarios_contratados;
        $datos_evento['otros_datos'] = 
                array(
                        'id_contrato'=>$id_contrato,
                        'id_evento'=>$contrato_model->id_evento,
                        'evento'=>$contrato_model->idEvento->nombre,
                        'profesor'=>$contrato_model->idEvento->nombreProfesor,
                        'vencido'=>($contrato_model->estado==2)? true:false,
                        'vencimiento'=>$contrato_model->vencimiento,
                    );
        //echo '<pre>';
        //print_r($contrato_model);
        //echo '</pre>';
        $this->render('editar', array('datos'=>$datos_evento, 'otros_datos'=>$datos_evento['otros_datos']));
    }
    
    public function actionRenovar($id_contrato){
        $contrato_model = $this->loadContratoModel($id_contrato);
        $this->id_socio = $contrato_model->id_socio;
        $this->nombre_socio = $contrato_model->idSocio->apellido.', '.$contrato_model->idSocio->nombre;
        
        $condicion = new CDbCriteria();        
        $condicion->condition = 'id_contrato='.$id_contrato;
        $horarios_contratados_model = ContrataHorario::model()->findAll($condicion);
        $ids_horarios_contratados = Array();
        foreach ($horarios_contratados_model as $horario)
            array_push($ids_horarios_contratados, $horario->id_horario);
        
        $condicion->condition = 'id_evento='.$contrato_model->id_evento.' AND tipo='.$contrato_model->tipo_contrato;
        $precios_model = Precio::model()->findAll($condicion);
        
        $condicion->condition = 'id_evento='.$contrato_model->id_evento;
        $horarios_model = Horario::model()->findAll($condicion);
               
        $datos_evento['precios'] = Array();
        $datos_evento['events'] = Array();
        $datos_evento['html'] = Array();
        $tipo_0 = false;
        $tipo_1 = false;
        $tipo_2 = false;
        $tipo_3 = false;
        $tipos = 1;
        foreach ($precios_model as $precio){
            array_push($datos_evento['precios'], $precio);
            if ($precio->tipo == 1) {
                $datos_evento['otros_datos'] = array('tipoContrato'=>'Libre');
                $tipo_1 = true;
            }
            if ($precio->tipo == 2) {
                $datos_evento['otros_datos'] = array('tipoContrato'=>'Horario');
                $tipo_2 = true;
            }
            if ($precio->tipo == 3) {
                $datos_evento['otros_datos'] = array('tipoContrato'=>'Clase');
                $tipo_3 = true;
            }
        }
        foreach ($horarios_model as $horario){
            $hf=gmdate("D M d Y H:i:s e", mktime(substr($horario->hora_fin,0,2),substr($horario->hora_fin,3,2),0,7,$this->sacarDia($horario->dia),2013));
            $hi=gmdate("D M d Y H:i:s e",  mktime(substr($horario->hora_inicio,0,2),substr($horario->hora_inicio,3,2),0,7,$this->sacarDia($horario->dia),2013));
            $horario2 = array(
              'id'=>$horario->id_horario,
                'title'=>$contrato_model->idEvento->nombre,
                'body'=>$contrato_model->idEvento->nombreProfesor,
                'start'=>$hi,
                'end'=>$hf,
                'disponibilidad'=>$horario->disponibilidad,
                'id_salon'=>$horario->id_salon,
                'salon'=>$horario->idSalon->nombre,
//                'colorSalon'=>  $this->coloresSalones($horario->id_salon),
                'colorSalon'=>  $horario->idSalon->color,
                'seleccionado'=> in_array($horario->id_horario, $ids_horarios_contratados)? true:false,
                'readOnly' => true
            );
            array_push($datos_evento['events'], $horario2);            
        }
        if ($tipo_2){
            $html_horarios = $this->renderPartial('_horarios', null, true);
            array_push($datos_evento['html'], $html_horarios);
        }
        $datos_evento['tipo_contratos'] = array('tipos'=>$tipos,'tipo_0'=>$tipo_0,'tipo_1'=>$tipo_1,'tipo_2'=>$tipo_2,'tipo_3'=>$tipo_3);
        $datos_evento['horarios_seleccionados'] = $ids_horarios_contratados;
        $datos_evento['otros_datos'] = array_merge($datos_evento['otros_datos'],
                array(
                        'id_contrato'=>$id_contrato,
                        'id_evento'=>$contrato_model->id_evento,
                        'evento'=>$contrato_model->idEvento->nombre,
                        'profesor'=>$contrato_model->idEvento->nombreProfesor,
                        'vencido'=>($contrato_model->estado==2)? true:false,
                        'vencimiento'=>$contrato_model->vencimiento,
                    ));
        //echo '<pre>';
        //print_r($contrato_model);
        //echo '</pre>';
        $this->render('renovar', array('datos'=>$datos_evento, 'otros_datos'=>$datos_evento['otros_datos']));
    }
    
    //BORRAR LAS VARIABLES DE CONTRATOS DE DATOSAENVIAR (MENOS ID CONTRATO) SI NO LAS USO
    public function actionConfirmarRenovacion() {
        //echo '<pre>';
        //print_r($_POST);
        //echo '</pre>';
        $datos = $_POST;
        $idcontrato = $datos['contrato']['id_contrato'];
        $contrato_model = $this->loadContratoModel($idcontrato);

        $cuenta_contrato = new CuentaSocio;
        $cuenta_pago = new CuentaSocio;
        $contrato_model->estado = 1;
        $fecha = date(date('Y',strtotime($contrato_model->vencimiento)).'-'.date('m',strtotime($contrato_model->vencimiento)).'-'.Yii::app()->configuracion->diaVencimiento);
        $fecha_vencimiento = strtotime('+1 month', strtotime($fecha));
        $contrato_model->vencimiento = date('Y-m-j', $fecha_vencimiento);
        $cuenta_contrato->movimiento = "debe";
        $cuenta_contrato->concepto = "Renovacion de servicio";
        $cuenta_contrato->monto = $datos['cuenta_socio']['precioParcial'];
        $cuenta_contrato->fecha = date('Y-m-d');
        $cuenta_contrato->id_contrato = $idcontrato;
        $cuenta_pago->movimiento = "haber";
        $cuenta_pago->concepto = "Pago de renovacion";
        $cuenta_pago->monto = $datos['cuenta_socio']['precioParcial'] - $datos['cuenta_socio']['descuento'] - $datos['cuenta_socio']['valor_prorrateo'] + $datos['cuenta_socio']['recargo'];
        $cuenta_pago->fecha = date('Y-m-d');
        $cuenta_pago->id_contrato = $idcontrato;
        switch ($datos['contrato']['tipoContrato']) {
            case 1://mensual, libre     
                break;
            case 2://mensual, clases por semana
                if ($datos['precioNuevoHorarios']['nuevo']=='true'){
                    $precio_nuevo = new Precio;
                    $precio_nuevo->id_evento = $contrato_model->id_evento;
                    $precio_nuevo->tipo = 2;
                    $precio_nuevo->clases_x_semana = $datos['precioNuevoHorarios']['clasesXSemana'];
                    $precio_nuevo->precio = $datos['precioNuevoHorarios']['precio'];
                    $precio_nuevo->save();
                }
                foreach ($datos['horarios_contratados'] as $id_horario) {
                    if (in_array($id_horario, $datos['horarios_originales'])){
                        $datos['horarios_originales'] = array_diff($datos['horarios_originales'], array($id_horario));
                        $datos['horarios_contratados'] = array_diff($datos['horarios_contratados'], array($id_horario));
                    } else {//agrego el horario id horario
                        $horario_contratado = new ContrataHorario;
                        $horario_disponibilidad = Horario::model()->findByPk($id_horario);
                        $horario_disponibilidad->disponibilidad -= 1;
                        $horario_contratado->id_contrato = $idcontrato;
                        $horario_contratado->id_horario = $id_horario;
                        $horario_contratado->save();
                        $horario_disponibilidad->save();
                    }
                }
                foreach ($datos['horarios_originales'] as $id_horario) {
                    if (in_array($id_horario, $datos['horarios_contratados'])){
                        $datos['horarios_originales'] = array_diff($datos['horarios_originales'], array($id_horario));
                        $datos['horarios_contratados'] = array_diff($datos['horarios_contratados'], array($id_horario));
                    } else {//quitar el horario id_horario
                        $condicion = new CDbCriteria();
                        $condicion->condition = 'id_contrato='.$idcontrato.' AND id_horario='.$id_horario;
                        $horario_contratado = ContrataHorario::model()->find($condicion);
                        $horario_disponibilidad = Horario::model()->findByPk($id_horario);
                        $horario_disponibilidad->disponibilidad += 1;
                        $horario_contratado->delete();
                        $horario_disponibilidad->save();
                    }
                }
                break;
            case 3://por clases
                $contrato_model->cantidad_clases = $datos['contrato']['cantidadClases'];                
                $fecha = date('Y-m-j');
                $fecha_vencimiento = strtotime("+ ".Yii::app()->configuracion->diasVencimientoPorClase." day", strtotime($fecha));
                $contrato_model->vencimiento = date('Y-m-j', $fecha_vencimiento);
                break;
        }
        if ($datos['cuenta_socio']['recargo']>0){
            $cuenta_recargo = new CuentaSocio;
            $cuenta_recargo->movimiento = "debe";
            $cuenta_recargo->concepto = "Recargo";
            $cuenta_recargo->monto = $datos['cuenta_socio']['recargo'];
            $cuenta_recargo->id_descuento_recargo = ($datos['cuenta_socio']['id_recargo']==0)? null:$datos['cuenta_socio']['id_recargo'];
            $cuenta_recargo->fecha = date('Y-m-d');
            $cuenta_recargo->id_contrato = $idcontrato;
            $cuenta_recargo->save();
        }
        if ($datos['cuenta_socio']['descuento']>0){
            $cuenta_descuento = new CuentaSocio;
            $cuenta_descuento->movimiento = "haber";
            $cuenta_descuento->concepto = "Descuento";
            $cuenta_descuento->monto = $datos['cuenta_socio']['descuento'];
            $cuenta_descuento->id_descuento_recargo = ($datos['cuenta_socio']['id_descuento']==0)? null:$datos['cuenta_socio']['id_descuento'];
            $cuenta_descuento->fecha = date('Y-m-d');
            $cuenta_descuento->id_contrato = $idcontrato;
            $cuenta_descuento->save();
        }
        if ($datos['cuenta_socio']['prorrateo']){
            if ($datos['cuenta_socio']['valor_prorrateo']>0){
                $cuenta_prorrateo = new CuentaSocio;
                $cuenta_prorrateo->movimiento = "haber";
                $cuenta_prorrateo->concepto = "Prorrateo";
                $cuenta_prorrateo->monto = $datos['cuenta_socio']['valor_prorrateo'];
                $cuenta_prorrateo->fecha = date('Y-m-d');
                $cuenta_prorrateo->id_contrato = $idcontrato;
                $cuenta_prorrateo->save();                
            }            
        }
        $cuenta_contrato->save();
        $cuenta_pago->save();
        $contrato_model->save();
    }

    public function actionHorarios(){
        date_default_timezone_set('Etc/GMT+3');
        if(isset($_POST['id_evento'])){
            $evento = $this->loadEventoModel($_POST['id_evento']);
            $datos_evento = Array();
            $datos_evento['precios'] = Array();
            $datos_evento['events'] = Array();
            $datos_evento['html'] = Array();
            $tipo_0 = false;
            $tipo_1 = false;
            $tipo_2 = false;
            $tipo_3 = false;
            $tipos = 0;
            foreach ($evento->precios as $precio){
                array_push($datos_evento['precios'], $precio);
                if ($precio->tipo == 0) {
                    $tipo_0 = true;
                }
                if ($precio->tipo == 1) {
                    $tipo_1 = true;
                    $tipos++;
                }
                if ($precio->tipo == 2) {
                    if (!$tipo_2) $tipos++;
                    $tipo_2 = true;
                }
                if ($precio->tipo == 3) {
                    $tipo_3 = true;
                    $tipos++;
                }
            }
            foreach ($evento->horarios as $horario){
                $hf=gmdate("D M d Y H:i:s e", mktime(substr($horario->hora_fin,0,2),substr($horario->hora_fin,3,2),0,7,$this->sacarDia($horario->dia),2013));
                $hi=gmdate("D M d Y H:i:s e",  mktime(substr($horario->hora_inicio,0,2),substr($horario->hora_inicio,3,2),0,7,$this->sacarDia($horario->dia),2013));
                $horario2 = array(
                  'id'=>$horario->id_horario,
                    'title'=>$evento->nombre,
                    'body'=>$evento->nombreProfesor,
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
                array_push($datos_evento['events'], $horario2);
            }
            if ($tipo_2){
                $html_horarios = $this->renderPartial('_horarios', null, true);
                array_push($datos_evento['html'], $html_horarios);
            }
            $datos_evento['tipo_contratos'] = array('tipos'=>$tipos,'tipo_0'=>$tipo_0,'tipo_1'=>$tipo_1,'tipo_2'=>$tipo_2,'tipo_3'=>$tipo_3);
            //array_push($datos_evento['tipo_2'], $tipo_2);
            echo CJSON::encode($datos_evento);
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
    
    public function actionCalcularVencimientoDias($id){
        Yii::app()->miscomponentes->calcularVencimientoDias();
        $this->redirect(array('administrarSocio/detalles','id'=>$id,'nuevo'=>true));
    }

    public function loadContratosModel($id_socio)
    {
            $condition = new CDbCriteria;
            $condition->condition='id_socio='.$id_socio;
            //$condition->params=array(':id_socio'=>$id_socio);
            $model=Contrato::model()->findAll($condition);
            if($model===null)
                    throw new CHttpException(404,'The requested page does not exist.');
            return $model;
    }
    
    public function loadContratoModel($id)
    {
            $model=Contrato::model()->findByPk($id);
            if($model===null)
                    throw new CHttpException(404,'The requested page does not exist.');
            return $model;
    }

    public function loadHorarioModel($id_evento)
    {
            $condicion = new CDbCriteria();
            $condicion->condition = 'id_evento='.$id_evento;
            $model=Horario::model()->findAll($condicion);
            if($model===null)
                    throw new CHttpException(404,'The requested page does not exist.');
            return $model;
    }

    public function loadEventoModel($id)
    {
            $model=Evento::model()->findByPk($id);
            if($model===null)
                    throw new CHttpException(404,'The requested page does not exist.');
            return $model;
    }

    public function loadEventosModel()
    {
            $model=Evento::model()->findAll(array('order'=>'nombre'));
            if($model===null)
                    throw new CHttpException(404,'The requested page does not exist.');
            return $model;
    }
}

?>