<?php

class ConfiguracionController extends Controller{
    
                      
    public function actionConfiguracion(){

        $fecha_actual = date('Y-m-j');
        
        $condicion = new CDbCriteria();
        $condicion->condition = 'vencimiento < "'.$fecha_actual.'"';
        
        $contratosVencidosModel = Contrato::model()->findAll($condicion);        
        
        foreach ($contratosVencidosModel as $contrato) {
            $contrato->estado=2;
            $contrato->save();
        }
        
        $this->render('configuracion', array('contratosModel'=>$contratosVencidosModel));
    }
    
}
