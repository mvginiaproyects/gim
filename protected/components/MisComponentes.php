<?php
class MisComponentes extends CApplicationComponent {
    
    public $fechaVencimiento = 99;
    
    public function init() {
        parent::init();
        $configuraciones = Configuraciones::model()->findByPk('1');
        $this->fechaVencimiento = $configuraciones->fecha_vencimiento;
        //$this->verificarMorosos();
    }

    public function verificarMorosos(){
        if (isset(Yii::app()->request->cookies['fecha'])){
            if (Yii::app()->request->cookies['fecha']!=date('d-m-Y')){
                $this->calcularVencimientoDias();
                Yii::app()->request->cookies['fecha'] = new CHttpCookie('fecha', date('d-m-Y'));
                //echo 'Actualizado!</br>';
                return;
            } else {
                //echo 'Hoy ya actualizó</br>';
                return;
            }
        } else {
            //echo 'Fecha no seteada</br>';
            $this->calcularVencimientoDias();
            Yii::app()->request->cookies['fecha'] = new CHttpCookie('fecha', date('11-11-2013'));
            return;
        }      
    }
    
    public function calcularVencimientoDias(){
        $contratos = ContrataEvento::model()->findAll();
        if ($contratos){
            foreach ($contratos as $contrato){
                //$contrato->setScenario('actualizarContrato');
                switch ($contrato->tipo_contrato){
                    case 1:
                        $mes = date('m',strtotime($contrato->fecha_contrata)) + 1;
                        $vencimiento = strtotime($this->fechaVencimiento."-".$mes."-".date('Y'));
                        //$vencimiento = strtotime("1-2-2015");
                        if (strtotime(date("Y-m-d"))>$vencimiento){
                            //vencio
                            $contrato->estado = 0;
                            $contrato->saveAttributes(array('estado'));
                            //echo "Vencido actualizado</br>";
                        } else {
                            //no vencio
                            $contrato->estado = 1;
                            $contrato->saveAttributes(array('estado'));
                            //echo "No vencido actualizado</br>";
                            //echo "Quedan: ".ceil(($vencimiento-strtotime(date("Y-m-d")))/86400)." dias</br>";
                        }
                        break;
                }
            }
        }
    }

    
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

