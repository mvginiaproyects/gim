<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class AdministrarAsistenciasController extends Controller{


    public function actionPresente() {
        $id_socio = $_POST['id_socio'];
        $id_horario = $_POST['id_horario'];
        $fecha = $_POST['fecha'];
        $tipo_contrato = $_POST['tipo_contrato'];
        $id_contrato = $_POST['id_contrato'];
        if ($tipo_contrato==3){
            $contrato_3_model = Contrato::model()->findByPk($id_contrato);
            if ($contrato_3_model->cantidad_clases>0){
                $cantClases = $contrato_3_model->cantidad_clases;
                $contrato_3_model->cantidad_clases = $cantClases - 1;
                if ($contrato_3_model->cantidad_clases==0)
                    $contrato_3_model->estado=3;
                $contrato_3_model->save();
            } else {
                echo 'no';
                return;
            }
        }
        $condicion = new CDbCriteria();
        $condicion->condition = 'id_socio='.$id_socio.' AND id_horario='.$id_horario.' AND fecha="'.$fecha.'"';
        $asistencia_model = Asistencia::model()->find($condicion);
        if (!count($asistencia_model)){
            $new_asistencia_model = new Asistencia;
            $new_asistencia_model->id_socio = $id_socio;
            $new_asistencia_model->id_horario = $id_horario;
            $new_asistencia_model->id_contrato = $id_contrato;
            $new_asistencia_model->fecha = $fecha;
            $new_asistencia_model->save();
        }
    }
    
    public function actionAusente() {
        $id_socio = $_POST['id_socio'];
        $id_horario = $_POST['id_horario'];
        $fecha = $_POST['fecha'];
        $tipo_contrato = $_POST['tipo_contrato'];
        $id_contrato = $_POST['id_contrato'];
        $condicion = new CDbCriteria();
        $condicion->condition = 'id_socio='.$id_socio.' AND id_horario='.$id_horario.' AND fecha="'.$fecha.'"';
        Asistencia::model()->deleteAll($condicion);
        if ($tipo_contrato==3){
            $contrato_3_model = Contrato::model()->findByPk($id_contrato);
            $contrato_3_model->cantidad_clases += 1;
            $contrato_3_model->estado=1;
            $contrato_3_model->save();
        }
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
        $condicion->with = array('horarios'=>array('condition'=>'horarios.dia="'.$dia_de_la_semana.'"'),
                                //'contratos'=>array('condition'=>'tipo_contrato!=1'),
                                );
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
                        'tipo_contrato'=>$hc->tipo_contrato,
                        'id_contrato'=>$hc->id_contrato,
                        'apellido'=>$hc->idSocio->idPersona->apellido,
                        'nombre'=>$hc->idSocio->idPersona->nombre,
                        'asistio'=>$asistio                        
                    );
                    array_push($datos["data"][$index]["alumnos"],$alumno);
                }
                $condicion->condition = 'estado<3 AND tipo_contrato=3 AND id_evento='.$evento->id_evento;
                $condicion->with = array('idSocio');
                $tipo3_contratados = Contrato::model()->findAll($condicion);
                foreach ($tipo3_contratados as $tipo3) {
                    $condicion2 = new CDbCriteria();
                    $condicion2->condition = 'id_horario='.$horario_evento->id_horario.' AND fecha="'.date('Y-m-j', strtotime($_POST['fecha'])).'" AND id_socio='.$tipo3->id_socio;
                    $asistencias_model = Asistencia::model()->findAll($condicion2);
                    $asistio = count($asistencias_model);
                    $alumno = array(
                        'id_socio'=>$tipo3->id_socio,
                        'tipo_contrato'=>$tipo3->tipo_contrato,
                        'id_contrato'=>$tipo3->id_contrato,
                        'apellido'=>$tipo3->idSocio->idPersona->apellido,
                        'nombre'=>$tipo3->idSocio->idPersona->nombre,
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
    
}