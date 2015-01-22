<?php

class PruebasController extends Controller{
    

    public function actionPrueba2() {
        
        $this->render('prueba3');
    }
    
    public function actionPresente() {
        $id_socio = $_POST['id_socio'];
        $id_horario = $_POST['id_horario'];
        $fecha = $_POST['fecha'];
        $condicion = new CDbCriteria();
        $condicion->condition = 'id_socio='.$id_socio.' AND id_horario='.$id_horario.' AND fecha="'.$fecha.'"';
        $asistencia_model = Asistencia::model()->find($condicion);
        if (!count($asistencia_model)){
            $new_asistencia_model = new Asistencia;
            $new_asistencia_model->id_socio = $id_socio;
            $new_asistencia_model->id_horario = $id_horario;
            $new_asistencia_model->fecha = $fecha;
            $new_asistencia_model->save();
        }
    }
    
    public function actionAusente() {
        $id_socio = $_POST['id_socio'];
        $id_horario = $_POST['id_horario'];
        $fecha = $_POST['fecha'];
        $condicion = new CDbCriteria();
        $condicion->condition = 'id_socio='.$id_socio.' AND id_horario='.$id_horario.' AND fecha="'.$fecha.'"';
        Asistencia::model()->deleteAll($condicion);
    }

    public function actionAsistencias() {
        //if (!$fecha)
        $datos = Array();
        $datos["data"] = Array();
        //$fecha = date('Y-m-j', strtotime('2014-06-16'));
        setlocale(LC_ALL,'es_ES');
        $dia_de_la_semana = $this->diaEspanol(date('l', strtotime($_POST['fecha'])));
        //echo $dia_de_la_semana;
        $condicion = new CDbCriteria();
        $condicion->with = array('horarios'=>array('condition'=>'horarios.dia="'.$dia_de_la_semana.'"'));
        $eventos_model = Evento::model()->findAll($condicion);
        $index = 0;
        foreach ($eventos_model as $evento) {
            foreach ($evento->horarios as $horario_evento) {
                array_push($datos["data"], array(
                    'id_evento'=>$evento->id_evento,
                    "evento"=> $evento->nombre,
                    "profesor"=> $evento->nombreProfesor,
                    "id_horario"=> $horario_evento->id_horario,
                    "horario"=>  substr($horario_evento->hora_inicio, 0, 5).' a '.substr($horario_evento->hora_fin, 0, 5).'hs',
                    "alumnos"=> array()
                ));
                $condicion->with = array('contrataHorarios'=>array('condition'=>'id_horario='.$horario_evento->id_horario),'idSocio');
                $condicion->condition = 'estado<3';
                $horarios_contratados = Contrato::model()->findAll($condicion);
                foreach ($horarios_contratados as $hc) {
                    $condicion2 = new CDbCriteria();
                    $condicion2->condition = 'id_horario='.$horario_evento->id_horario.' AND fecha="'.date('Y-m-j', strtotime($_POST['fecha'])).'" AND id_socio='.$hc->id_socio;
                    $asistencias_model = Asistencia::model()->findAll($condicion2);
                    $asistio = count($asistencias_model);
                    $alumno = array(
                        'id_socio'=>$hc->id_socio,
                        'apellido'=>$hc->idSocio->idPersona->apellido,
                        'nombre'=>$hc->idSocio->idPersona->nombre,
                        'asistio'=>$asistio                        
                    );
                    array_push($datos["data"][$index]["alumnos"],$alumno);
                }
                $index++;
            }
        }
        //echo '<pre>';
        //print_r($datos["data"]);
        //echo '</pre>';
        //echo CJSON::encode($_POST['fecha']);
        echo CJSON::encode($datos);
    }
    
    public function actionAsistencias2() {
        //if (!$fecha)
        $datos = Array();
        $datos["data"] = Array();
        $fecha = date('Y-m-j', strtotime('2014-06-16'));
        setlocale(LC_ALL,'es_ES');
        $dia_de_la_semana = $this->diaEspanol(date('l', strtotime('2014-06-17')));
        echo $dia_de_la_semana;
        $condicion = new CDbCriteria();
        $condicion->with = array('horarios'=>array('condition'=>'horarios.dia="'.$dia_de_la_semana.'"'));
        $eventos_model = Evento::model()->findAll($condicion);
        echo '<pre>';
        print_r($eventos_model);
        echo '</pre>';
        foreach ($eventos_model as $evento) {
            foreach ($evento->horarios as $horario_evento) {
                $condicion->with = array('contrataHorarios'=>array('condition'=>'id_horario='.$horario_evento->id_horario),'idSocio');
                $horarios_contratados = Contrato::model()->findAll($condicion);
                foreach ($horarios_contratados as $hc) {
                    $condicion2 = new CDbCriteria();
                    $condicion2->condition = 'id_horario='.$horario_evento->id_horario.' AND fecha="'.date('Y-m-j', strtotime('2014-06-17')).'" AND id_socio='.$hc->id_socio;
                    $asistencias_model = Asistencia::model()->findAll($condicion2);
                    echo $hc->idSocio->idPersona->nombre;
                        array_push($datos["data"], array(
                            'id_evento'=>$hc->id_evento,
                            "evento"=> $hc->idEvento->nombre,
                            "profesor"=> $hc->idEvento->nombreProfesor,
                            "horario"=>  substr($horario_evento->hora_inicio, 0, 5).' a '.substr($horario_evento->hora_fin, 0, 5).'hs',
                            "alumnos"=> array(
                                array(
                                    'id_socio'=>$hc->id_socio,
                                    'apellido'=>$hc->idSocio->idPersona->apellido,
                                    'nombre'=>$hc->idSocio->idPersona->nombre,
                                    'asistio'=>count($asistencias_model)
                                    )
                            )
                        ));
                    
                }
            }
        }
                    echo '<pre>';
                    print_r($datos["data"]);
                    echo '</pre>';      
    }
    
    private function diaEspanol($dia) {
        switch ($dia) {
            case 'Monday':
                return 'lunes';
                break;
            case 'Tuesday':
                return 'martes';
                break;
            case 'Wednesday':
                return 'miercoles';
                break;
            case 'Thursday':
                return 'jueves';
                break;
            case 'Friday':
                return 'viernes';
                break;
            case 'Saturday':
                return 'sabado';
                break;
        }
    }
    
    public function actionData() {
        $datos = Array();
        $datos["data"] = Array();
        array_push($datos["data"], array(
            'id_evento'=>'1',
            "evento"=> "TaiShao",
            "profesor"=> "Eduardo",
            "horario"=>"18:00hs a 19:00hs",
            "alumnos"=> array(
                array(
                    'id_socio'=>'1',
                    'apellido'=>'Splendore',
                    'nombre'=>'Gabriel',
                    'asistio'=>1
                    ),
                array(
                    'id_socio'=>'2',
                    'apellido'=>'Cohon',
                    'nombre'=>'Sebastian',
                    'asistio'=>0
                    ),
            )
        ));
        array_push($datos["data"], array(
            'id_evento'=>'2',
            "evento"=> "Pilates",
            "profesor"=> "Lia",
            "horario"=>"16:00hs a 17:00hs",
            "alumnos"=> array(
                array(
                    'id_socio'=>'3',
                    'apellido'=>'Bersano',
                    'nombre'=>'Paula',
                    'asistio'=>1
                    ),
                array(
                    'id_socio'=>'1',
                    'apellido'=>'Splendore',
                    'nombre'=>'Gabriel',
                    'asistio'=>0
                    ),
            )
        ));
        //echo '<pre>';
        //print_r($datos["data"]);
        //echo '</pre>';
        echo CJSON::encode($datos);
    }
    
    public function actionPrueba1(){
        //Yii::app()->user->setFlash('success','Cargado!');
        //Yii::app()->session['fecha'] = date('Y-m-j');
        //Yii::trace(CVarDumper::dumpAsString(Yii::app()->session['sleep']));
        
            $condicion = new CDbCriteria();
            $condicion->condition = '(estado = 2) AND (DATEDIFF(CURDATE(),vencimiento)>16)';

            $contratosVencidosModel = Contrato::model()->findAll($condicion);
            
            echo '<pre>';
            print_r($contratosVencidosModel);
            echo '</pre>';
        
        //$this->render('prueba1');
    }
    
    public function actionWorker(){

        $fecha_actual = date('Y-m-j');
        
        $condicion = new CDbCriteria();
        $condicion->condition = '(estado = 1) AND ((vencimiento < "'.$fecha_actual.'") OR (cantidad_clases = 0))';
        
        $contratosVencidosModel = Contrato::model()->findAll($condicion);
        
        //SI ES LA PRIMERA VEZ QUE PASA ESTABLECE EL TAMAÑO INICIAL,
        //PARA EL RESTO DE LAS VECES UTILIZA EL PRIMER TAMAÑO
        if ($_POST['tamano']==0)
            $tamano = count($contratosVencidosModel);
        else
            $tamano = $_POST['tamano'];
        //
        
        if ($tamano<10)
            $multiplo = 0.1;
        if ($tamano<500)
            $multiplo = 0.4;
        else if ($tamano<5000)
            $multiplo = 0.25;
        else
            $multiplo = 0.1;

        $step = round($tamano*$multiplo, 0, PHP_ROUND_HALF_UP);
        $posInferior=$_POST['posicion'];
        $posSuperior=$posInferior+(($step==0)? 1:$step);
        if ($posSuperior>$tamano) $posSuperior = $tamano;
        $index = 0;
        while ($posInferior<$posSuperior){
            $contratosVencidosModel[$index]->estado=2;
            $contratosVencidosModel[$index]->save();
            $index++;
            $posInferior++;
        }
        //sleep(1);
        $progreso = ((($posInferior==0)? 1:$posInferior) / (($tamano==0)? 1:$tamano)) * 100;
        //$progreso = $posInferior / $tamano * 100;
        echo json_encode(array('posicion'=>$posInferior,'progreso'=>round($progreso),'tamano'=>$tamano));        

    }
    
    function actionError() {
        Yii::app()->user->setFlash('error','Error prueba');
        throw new CHttpException(403, 'You are not authorized to perform this action.');
    }
}
