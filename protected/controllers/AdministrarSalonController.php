<?php
//ESTO ES UNA PRUEBA
class AdministrarSalonController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';
        
        private $error = '';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
            
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array();
	/*
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
		*/
	}
        
        public function actionBorrarTodo($idSalon) {
            //ContrataHorario::model()->deleteAll();
            //CuentaSocio::model()->deleteAll();
            //Contrato::model()->deleteAll();
            $this->actionDetalles($idSalon, true);
        }
        
        

	public function actionBuscar()
	{
		$model=new Salon('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Salon'])){
			$model->attributes=$_GET['Salon'];

		}
                
		$this->render('buscar',array('model'=>$model));
	}
        
	public function actionDetalles($id,$nuevo=false){
		$model=$this->loadModel($id);
		//Yii::trace(CVarDumper::dumpAsString($model));
		if(isset($_POST['Salon']))
		{
			$model->attributes=$_POST['Salon'];
                        if ($model->validate() && $model->save() ){
                              Yii::app()->end();
                        } else {
                            //$error = CActiveForm::validate($model);
                            $model->validate();
                            foreach ($model->getErrors() as $error) {
                                echo $error[0]."</br>";
                            }
                            Yii::app()->end();
                        }
                        Yii::app()->end();
                        //$this->renderPartial('_formModificarSocio',array('model'=>$model));
		}
		$this->render('detalles',array('model'=>$model,'nuevo'=>$nuevo));
	}
        
        
        public function actionErrorGuardarSalon($id) {
		$model=$this->loadModel($id);
                Yii::app()->user->setFlash('error',  $this->error);
		$this->render('detalles',array('model'=>$model,'nuevo'=>false));
        }
        
        
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCrear()
	{
                $model=new Salon;

                $condicion = new CDbCriteria();
                $condicion->select='color';
                $salones_model = Salon::model()->findAll($condicion);

                $colores = [
                   "#1abc9c","#2ecc71","#3498db","#9b59b6","#34495e",
                   "#16a085","#27ae60","#2980b9","#8e44ad","#2c3e50",
                   "#f1c40f","#e67e22","#e74c3c","#ecf0f1","#95a5a6",
                   "#f39c12","#d35400","#c0392b","#bdc3c7","#7f8c8d"
                ];
                
                foreach ($salones_model as $salon) {
                    $colores = array_diff($colores, array($salon->color));
                }
                //var_dump($salones_model);
                //print_r($salones_model);

		if(isset($_POST['Salon']))
		{
			$model->attributes=$_POST['Salon'];
                        echo Yii::trace(CVarDumper::dumpAsString($model->color));

                            if($model->save()) {
                                $this->redirect(array('buscar','id'=>$model->getPrimaryKey(),'nuevo'=>true));
                            }
                        //} 
                            else {
                            echo 'error';
                            //Yii::app()->end();
                        }
				
		}

		$this->render('crear',array('model'=>$model,
                        'colores'=>$colores,
		));
	}

	public function actionEliminar()
	{
            //$salon = $this->loadModel($id);
            //$salon->delete();
            //Yii::app()->user->setFlash('success','Se ha eliminado el Salón exitosamente');
            $id_salon = $_GET['id_salon'];
            $salon = $this->loadModel($id_salon);
            $horarios = $salon->horarios;

            
            foreach ($horarios as $horario) {
                  $id_evento = $horario->id_evento;
                  Contrato::model()->findAll(array('condition'=>'id_evento='.$id_evento));
                  //Contrato::model()->deleteAll(array('condition'=>'id_evento='.$id_evento));
              //  $contrato->delete();
            }
            $salon->delete();
           
            
            $model=new Salon('search');
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['Salon'])){
                    $model->attributes=$_GET['Salon'];
            }
            $this->render('buscar',array('model'=>$model));
          
	}

        public function actionTabla($id){
            $model = $this->loadModel($id);
            $this->renderPartial('_tablaDetalleEvento',array('model'=>$model,'opcion'=>$_POST['opcion']));
            Yii::app()->end();
        }
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

        public function tipoContrato($contrato){
            switch ($contrato) {
                case 1:
                    return 'Libre';
                    break;
                case 2:
                    return 'Semanal';
                    break;
                case 3:
                    return 'Libre';
                    break;
            }
        }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Socio the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Salon::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Socio $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='salon-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function CalculaEdad( $fecha ) {
            if ($fecha){
                list($d,$m,$Y) = explode("-",$fecha);
                return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
            }
            return "-";
        }
}
