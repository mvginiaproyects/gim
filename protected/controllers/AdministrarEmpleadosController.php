<?php


class AdministrarEmpleadosController extends Controller {
    
    /*Que quiero hacer? que me lleve a la pagina buscar y mostrar ahi un parametro*/
    
    public $layout='//layouts/column1';
       
    public function actionCrear(){

       	$empleadoModel=new Empleado;  //creo un objeto empleado
        $empleadoModel->idPersona=new Persona; //agrego un objeto persona al campo idPersona
        $empleadoModel->tipo = 0;

        if(isset($_POST['Empleado']))
	  {
            //$empleadoModel->attributes=$_POST['Empleado'];
            $empleadoModel->fecha_ingreso=$_POST['Empleado']['fecha_ingreso'];
            $empleadoModel->idPersona->attributes=$_POST['Persona'];
            $empleadoModel->idPersona->dni =$_POST['Persona']['dni'];
            $empleadoModel->idPersona->fecha_nac = Yii::app()->dateFormatter->format("yyyy-MM-dd", $_POST['Empleado']['fecha_nac']);
            if($empleadoModel->idPersona->save()){
                $idpersona = $empleadoModel->idPersona->getPrimaryKey();
                $empleadoModel->id_persona = $idpersona;
                $empleadoModel->save();
                if($empleadoModel->save()){
                    $profesor = new Profesor;
                    $profesor->id_empleado=$empleadoModel->getPrimaryKey();
                    $profesor->save();
                    Yii::app()->user->setFlash('success', 'Se grabo correctamente!');
                    $empleadoModel->unsetAttributes();  // clear any default values
                    $empleadoModel->idPersona->unsetAttributes();
                    $empleadoModel->tipo = 0;
                }
            } else {
                //echo 'error';
                //Yii::app()->end();
            }
            
            //echo '<pre>';
            //print_r($_POST);
            //echo '</pre>';
            //Yii::app()->end();

          }
	$this->render('crear',array('model'=>$empleadoModel,'modelPersona'=>$empleadoModel->idPersona)); //envio a la vista 'crear' un objeto empleado (un modelo)          
//          $this->render('crear',array('model'=>$empleadoModel));
        
       }
}