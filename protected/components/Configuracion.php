<?php

class Configuracion extends CApplicationComponent {
    
    public $diaVencimiento = 10;
    
    public $diasVencimientoPorClase = 15;

    public $borrarContratosTerminados = false;
    
    public $diasTerminarContrato = 20;
    
    public function init() {
        parent::init();
        $configuraciones = Configuraciones::model()->findByPk('1');
        $this->diaVencimiento = $configuraciones->diaVencimiento;
        $this->diasVencimientoPorClase = $configuraciones->diasVencimientoPorClases;
        $this->borrarContratosTerminados = $configuraciones->borrarContratosTerminados;
        $this->diasTerminarContrato = $configuraciones->diasTerminarContrato;
        //Yii::app()->controller->redirect(array('/administrarSocio/buscar'));
        //Yii::app()->request->redirect('index.php?r=administrarSocio/buscar');
        //$this->owner->redirect('index.php?r=administrarSocio/buscar');
        //$this->verificarMorosos();
    }

    public function verificarMorosos(){
        if (isset(Yii::app()->request->cookies['fecha'])){
            if (Yii::app()->request->cookies['fecha']!=date('d-m-Y')){
                $this->verificarVencimientos();
                Yii::app()->request->cookies['fecha'] = new CHttpCookie('fecha', date('d-m-Y'));
                //echo 'Actualizado!</br>';
                return;
            } else {
                //echo 'Hoy ya actualizó</br>';
                return;
            }
        } else {
            //echo 'Fecha no seteada</br>';
            $this->verificarVencimientos();
            Yii::app()->request->cookies['fecha'] = new CHttpCookie('fecha', date('11-11-2013'));
            return;
        }     
    }
    
    private function verificarVencimientos() {
        
    }
}