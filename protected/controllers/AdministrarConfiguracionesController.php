<?php

class AdministrarConfiguracionesController extends Controller{
    
                      
    public function actionConfiguracion(){
        $configuraciones_model = Configuraciones::model()->findByPk(1);
        if (isset($_POST['Configuraciones'])){
            $configuraciones_model->attributes = $_POST['Configuraciones'];
            if ($configuraciones_model->validate()){
                Yii::app()->user->setFlash('success','Se han guardado las configuraciones con éxito');
                Yii::app()->configuracion->diaVencimiento = $configuraciones_model->diaVencimiento;
                $configuraciones_model->save();
            } else {
                Yii::app()->user->setFlash('danger','Se ha producido un error');                
            }
        }
        $this->render('configuracion', array('configuraciones_model'=>$configuraciones_model));
    }
    
    
}
