<?php
//ESTO ES UNA PRUEBA
class AdministrarSocioController extends Controller
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
	public function filters() {
            
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	public function accessRules(){
		return array();
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
	}
		*/

    public function actionBorrarTodo($idSocio) {
        ContrataHorario::model()->deleteAll();
        CuentaSocio::model()->deleteAll();
        Contrato::model()->deleteAll();
        $this->actionDetalles($idSocio, true);
    }
        
	public function actionBuscar()
	{
		//$a = array(1,2,3);
		//Yii::trace(CVarDumper::dumpAsString($a));
		$model=new Socio('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Socio'])){
			$model->attributes=$_GET['Socio'];
		}
		$this->render('buscar',array('model'=>$model));
	}
        
	public function actionDetalles($id,$nuevo=false){
		$model=$this->loadModel($id);
		if(isset($_POST['Socio']))
		{
			$model->attributes=$_POST['Socio'];
			$model->idPersona->attributes=$_POST['Socio'];
			if ($model->validate() && $model->save() && $model->idPersona->save()){
                            //$error = CActiveForm::validate($model);
                            //echo $model->getScenario();
                            Yii::app()->end();
                        } else {
                            //$this->error = CActiveForm::validate($model);
                            $model->idPersona->validate();
                            //print_r($model->getErrors());
                            foreach ($model->idPersona->getErrors() as $error) {
                                echo $error[0]."</br>";
                            }
                            
                            //Yii::app()->user->setFlash('error','$error');
                            Yii::app()->end();
                        }
                        Yii::app()->end();
                        //$this->renderPartial('_formModificarSocio',array('model'=>$model));
		}
		//Yii::trace(CVarDumper::dumpAsString(Yii::app()->user->getFlashes()));
		$this->render('detalles',array('model'=>$model,'nuevo'=>$nuevo));
	}
        
        public function actionErrorGuardarSocio($id) {
		$model=$this->loadModel($id);
                Yii::app()->user->setFlash('error',  $this->error);
		$this->render('detalles',array('model'=>$model,'nuevo'=>false));
        }
        
	public function actionCrear()
	{
		$model=new Socio;
                $model->idPersona = new Persona;
		// Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($model);
		//$this->performAjaxValidation($model->idPersona);

		if(isset($_POST['Socio']))
		{
                    //$model->idPersona->nombre = $_POST['Persona']['nombre'];
			$model->attributes=$_POST['Socio'];
			$model->attributes=$_POST['Persona'];
                        $model->idPersona->attributes = $_POST['Persona'];
                        $model->idPersona->dni = $_POST['Persona']['dni'];
			$model->idPersona->fecha_nac = $_POST['Socio']['fecha_nac'];
			//$model->idPersona->fecha_nac = Yii::app()->dateFormatter->format("yyyy-MM-dd", $_POST['Socio']['fecha_nac']);
                        //echo Yii::trace(CVarDumper::dumpAsString($model->idPersona->attributes));
                        //Yii::app()->end();
                        if($model->idPersona->save()){
                            $idpersona = $model->idPersona->getPrimaryKey();
                            $model->id_persona = $idpersona;
                            if($model->save()){
                                $nombre_socio = $model->apellido.', '.$model->nombre;
                                //$this->redirect(array('detalles','id'=>$model->getPrimaryKey(),'nuevo'=>true));
                                $this->redirect(array('administrarContratos/contratar','id'=>$model->getPrimaryKey(),'nombre'=>$nombre_socio));
                            }
                        } else {
                            //echo 'error';
                            //Yii::app()->end();
                        }
				
		}

		$this->render('crear',array(
			'model'=>$model,
                        'persona'=>$model->idPersona,
		));
	}

	public function actionEliminar()
	{
            $id_socio = $_GET['id_socio'];
            $socio = $this->loadModel($id_socio);
            $contratos = $socio->contratos;
            
            Asistencia::model()->deleteAll(array('condition'=>'id_socio='.$id_socio));
            
            foreach ($contratos as $contrato) {
                $id_contrato = $contrato->id_contrato;
                ContrataHorario::model()->deleteAll(array('condition'=>'id_contrato='.$id_contrato));
                CuentaSocio::model()->deleteAll(array('condition'=>'id_contrato='.$id_contrato));
                $contrato->delete();
            }
            $id_persona = $socio->idPersona;
            $socio->delete();
            $id_persona->delete();
            
            
            $model=new Socio('search');
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['Socio'])){
                    $model->attributes=$_GET['Socio'];
            }
            $this->render('buscar',array('model'=>$model));
            //$persona->delete();
            //$this->redirect(array('buscar'));
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
                    return 'Por Clase';
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
		$model=Socio::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='socio-form')
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
