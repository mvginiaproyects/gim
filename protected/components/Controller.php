<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
        
        public $diaVencimiento = 10;
        
        public $saldoVencido = 0;
        
        public $diasVencimientoPorClase = 15;
        
        public function init() {
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            
            //aqui cargaria de la BD las configuraciones de las variables
        }

        protected function beforeAction($action) {
            if (!Yii::app()->user->isGuest){
                if (isset(Yii::app()->request->cookies['fecha'])){
                    if (Yii::app()->request->cookies['fecha']!=date('d-m-Y')){
                        Yii::app()->request->cookies['fecha'] = new CHttpCookie('fecha', date('d-m-Y'));
                        //echo 'Actualizado!</br>';
                        $this->redirect(array('site/proceso'), true, 301);
                    } else {
                        //echo 'Hoy ya actualizó</br>';
                    }
                } else {
                    //echo 'Fecha no seteada</br>';
                    Yii::app()->request->cookies['fecha'] = new CHttpCookie('fecha', date('11-11-2013'));
                    $this->redirect(array('site/proceso'), true, 301);
                }    
             }
            return parent::beforeAction($action);
        } 

        public function accessRules(){
            return array(
                array('allow',  // permite a todos los usuarios ejecutar las acciones 'login'
                    'actions'=>array('login'),
                    'users'=>array('*'),
                ),
                array('allow', // permite a los usuarios logueados ejecutar todas las acciones
                    //'actions'=>array('*'),
                    'users'=>array('@'),
                ),
                array('allow', // permite a los usuarios logueados ejecutar todas las acciones
                    //'actions'=>array('*'),
                    'users'=>array('admin'),
                ),
                array('deny',  // niega cualquier otra acción para cualquier usuario
                     'users'=>array('*'),
                ),
            );
        }        
}