<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
        
        public function actionInfoVisto() {
            Yii::app()->session['infoVisto'] = true;
            echo Yii::app()->session['infoVisto'];
        }
        
        public function actionContraidos() {
            $nombre = $_POST['seccion'];
            $condicion = new CDbCriteria();
            $condicion->condition = 'nombre="'.$nombre.'"';
            $seccion = TablasInicio::model()->find($condicion);
            $seccion->expandido = $_POST['accion'];
            $seccion->save();
        }

        public function actionReordenarTablas() {
            $filaIni = $_POST['filaIni'];
            $columnaIni = $_POST['columnaIni'];
            $filaFin = $_POST['filaFin'];
            $columnaFin = $_POST['columnaFin'];
            
            $orden_ini_model = TablasInicio::model()->find(array('condition'=>'fila='.$filaIni.' AND columna='.$columnaIni));
            
            if ($columnaFin==$columnaIni){
                $orden_fin_model = TablasInicio::model()->find(array('condition'=>'fila='.$filaFin.' AND columna='.$columnaFin));

                $orden_ini_model->fila = $filaFin;
                //$orden_ini_model->columna = $columnaFin;
                $orden_fin_model->fila = $filaIni;
                //$orden_fin_model->columna = $columnaIni;

                $orden_fin_model->save();
                $orden_ini_model->save();  
                //echo '<pre>';
                //print_r($orden_ini_model);
                //echo '</pre>';
            } else {
                //se suman
                $reorden_fin_model = TablasInicio::model()->findAll(array('condition'=>'fila>='.$filaFin.' AND columna='.$columnaFin));
                //se restan
                $reorden_ini_model = TablasInicio::model()->findAll(array('condition'=>'fila>='.$filaIni.' AND columna='.$columnaIni));
                
                foreach ($reorden_fin_model as $reorden) {
                    $reorden->fila += 1;
                    $reorden->save();
                };
                foreach ($reorden_ini_model as $reorden) {
                    $reorden->fila -= 1;
                    $reorden->save();
                };
                $orden_ini_model->fila = $filaFin;
                $orden_ini_model->columna = $columnaFin;
                $orden_ini_model->save();                
            }
            
            
        }
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
            if(Yii::app()->user->isGuest)
		$this->render('indexGuest');
            else{
                         /*
                 $dbC = Yii::app()->db->createCommand();
                 $dbC->selectDistinct(array('p.nombre', 'p.apellido', 'p.dni'))
                         ->from(array('contrato c', 'socio s', 'persona p'))
                         ->where(array('and','c.id_socio=s.id_socio','s.id_persona=p.id_persona','c.estado=2'))
                         ->queryAll();
                          * */
                $condicion = new CDbCriteria();
                //$condicion->condition = 't.estado = 2';
                //$condicion->distinct = true;  
                //$condicion->with = array('idSocio' => array("select"=>"idSocio.id_persona"), 
                                         //'idSocio.idPersona' => array("select"=>"idPersona.nombre, idPersona.apellido, idPersona.dni"));
                $condicion->with = array('contratos' => array(
                                            "select"=>"estado",
                                            "condition"=>"estado=2",
                                        ));
                //$condicion->select = 't.id_socio';                        
                $socios_vencidos_model = Socio::model()->findAll($condicion);
                $socios_model = Socio::model()->findAll();
                //Yii::trace(CVarDumper::dumpAsString());
                $fecha_actual = date('Y-m-j');
                $fecha = date('Y-m-'.Yii::app()->configuracion->diaVencimiento);
                $fecha_vencimiento = strtotime('+'.(Yii::app()->configuracion->diasTerminarContrato - 1).' day', strtotime($fecha));
                if ($fecha_actual==date('Y-m-j',$fecha_vencimiento) && !Yii::app()->session['infoVisto'])
                    Yii::app()->user->setFlash('info', 'Atencion! mañana se cancelarán los contratos vencidos');
		$this->render('index',array('socios_vencidos_model'=>$socios_vencidos_model, 'socios_model'=>$socios_model));                
            }
	}
        
        public function actionProceso(){
            $this->render('proceso');
        }
              
        public function actionProcesar(){
            
            $fecha_actual = date('Y-m-j');
            //EN ESTE MOMENTO SI PASAN MAS DE 'diasTerminarContrato' SIN ENTRAR AL SISTEMA, LOS CONTRATOS QUE
            //DEBERIAN PASAR A TERMINADO PASAN PRIMERO POR VENCIDO Y RECIEN AL DIA SIGUIENTE, O LA PROX VEZ
            //QUE HAGA EL PROCESO, PASARAN A VENCIDOS. SE SOLUCIONA AMPLIANDO LA CONDICION EN EL SIGUINTE QUERY
            $condicion = new CDbCriteria();
            $condicion->condition = '(estado = 1) AND ((vencimiento < "'.$fecha_actual.'") OR (cantidad_clases = 0))';

            $contratosVencidosModel = Contrato::model()->findAll($condicion);
            
            $condicion->condition = '(estado = 2) AND (DATEDIFF(CURDATE(),vencimiento)>'.Yii::app()->configuracion->diasTerminarContrato.')';

            $contratosTerminadosModel = Contrato::model()->findAll($condicion);
            

            //SI ES LA PRIMERA VEZ QUE PASA ESTABLECE EL TAMAÑO INICIAL,
            //PARA EL RESTO DE LAS VECES UTILIZA EL PRIMER TAMAÑO
            if ($_POST['tamano1']==0)
                $tamano1 = count($contratosVencidosModel);
            else
                $tamano1 = $_POST['tamano1'];

            if ($_POST['tamano']==0)
                $tamano = count($contratosVencidosModel) + count($contratosTerminadosModel);
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
            //$step = 1;
            $posInferior=$_POST['posicion'];
            $posSuperior=$posInferior+(($step==0)? 1:$step);
            
            if ($posInferior<$tamano1){
                if ($posSuperior>$tamano1) $posSuperior = $tamano1;
                $index = 0;
                while ($posInferior<$posSuperior){
                    if ($contratosVencidosModel[$index]->tipo_contrato==3){
                        $contratosVencidosModel[$index]->estado=3;
                        $contratosVencidosModel[$index]->save();                                                
                    } else {
                        $contratosVencidosModel[$index]->estado=2;
                        $contratosVencidosModel[$index]->save();                        
                    }
                    $index++;
                    $posInferior++;
                }
            } else if ($posInferior<$tamano){
                if ($posSuperior>$tamano) $posSuperior = $tamano;
                $index = 0;
                while ($posInferior<$posSuperior){
                    if (Yii::app()->configuracion->borrarContratosTerminados==1){
                        if ($contratosTerminadosModel[$index]->tipo_contrato==2){
                            $contrata_horario_model = ContrataHorario::model()->findAll(array('condition'=>'id_contrato='.$contratosTerminadosModel[$index]->id_contrato));
                            foreach ($contrata_horario_model as $horario_contratado) {
                                $horario_model = Horario::model()->findByPk($horario_contratado->id_horario);
                                $horario_model->disponibilidad += 1;
                                $horario_model->save();
                            }
                            ContrataHorario::model()->deleteAll(array('condition'=>'id_contrato='.$contratosTerminadosModel[$index]->id_contrato));
                        }
                        CuentaSocio::model()->deleteAll(array('condition'=>'id_contrato='.$contratosTerminadosModel[$index]->id_contrato));
                        Asistencia::model()->deleteAll(array('condition'=>'id_contrato='.$contratosTerminadosModel[$index]->id_contrato));
                        $contratosTerminadosModel[$index]->delete();                
                    }else {
                        if ($contratosTerminadosModel[$index]->tipo_contrato==2){
                            $contrata_horario_model = ContrataHorario::model()->findAll(array('condition'=>'id_contrato='.$contratosTerminadosModel[$index]->id_contrato));
                            foreach ($contrata_horario_model as $horario_contratado) {
                                $horario_model = Horario::model()->findByPk($horario_contratado->id_horario);
                                $horario_model->disponibilidad += 1;
                                $horario_model->save();
                            }

                        }
                        $contratosTerminadosModel[$index]->estado=3;
                        $contratosTerminadosModel[$index]->save();
                    }
                    /*
                    if ($contratosTerminadosModel[$index]->tipo_contrato==2){
                        $contrata_horario_model = ContrataHorario::model()->findAll(array('condition'=>'id_contrato='.$contratosTerminadosModel[$index]->id_contrato));
                        foreach ($contrata_horario_model as $horario_contratado) {
                            $horario_model = Horario::model()->findByPk($horario_contratado->id_horario);
                            $horario_model->disponibilidad += 1;
                            $horario_model->save();
                        }
                        
                    }
                    $contratosTerminadosModel[$index]->estado=3;
                    $contratosTerminadosModel[$index]->save();
                     * 
                     */
                    $index++;
                    $posInferior++;
                }
            }
            
            sleep(1);
            $progreso = ((($posInferior==0)? 1:$posInferior) / (($tamano==0)? 1:$tamano)) * 100;
            //$progreso = $posInferior / $tamano * 100;
            echo json_encode(array('posicion'=>$posInferior,'progreso'=>round($progreso),'tamano'=>$tamano,'tamano1'=> $tamano1));        

        }

        public function actionProcesar2(){

            $fecha_actual = date('Y-m-j');

            $condicion = new CDbCriteria();
            $condicion->condition = '(estado = 1) AND ((vencimiento < "'.$fecha_actual.'") OR (cantidad_clases = 0))';

            $contratosVencidosModel = Contrato::model()->findAll($condicion);
            
            $condicion->condition = '(estado = 2) AND (DATEDIFF(CURDATE(),vencimiento)>'.Yii::app()->configuracion->diasTerminarContrato.')';

            $contratosTerminadosModel = Contrato::model()->findAll($condicion);
            

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
            sleep(1);
            $progreso = ((($posInferior==0)? 1:$posInferior) / (($tamano==0)? 1:$tamano)) * 100;
            //$progreso = $posInferior / $tamano * 100;
            echo json_encode(array('posicion'=>$posInferior,'progreso'=>round($progreso),'tamano'=>$tamano));        

        }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}