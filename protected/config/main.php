
<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Gimnasios Conectados',
        //'theme'=>'bootstrap',
	// preloading 'log' component
	//'preload'=>array('log','bootstrap','miscomponentes'),
	//'defaultController' => 'myController/myAction',
        'preload'=>array('log','bootstrap','EJSUrlManager','configuracion'),
        'language'=>'es',
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
                'ext.ECompositeUniqueValidator',
                //'application.components.Facebook.*',
                // --- ADMINISTRADOR DE USUARIOS ----
                'application.modules.cruge.components.*',
                'application.modules.cruge.extensions.crugemailer.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'grupete2014',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','*.*.*.*','::1'),
		),
                // --- ADMINISTRADOR DE USUARIOS ----
                'cruge'=>array(
                        'tableprefix'=>'cruge_',

                        // para que utilice a protected.modules.cruge.models.auth.CrugeAuthDefault.php
                        //
                        // en vez de 'default' pon 'authdemo' para que utilice el demo de autenticacion alterna
                        // para saber mas lee documentacion de la clase modules/cruge/models/auth/AlternateAuthDemo.php
                        //
                        'availableAuthMethods'=>array('default'),

                        'availableAuthModes'=>array('username','email'),

                        // url base para los links de activacion de cuenta de usuario
                        'baseUrl'=>'http://coco.com/',

                         // NO OLVIDES PONER EN FALSE TRAS INSTALAR
                         'debug'=>false,
                         'rbacSetupEnabled'=>false,
                         'allowUserAlways'=>false,

                        // MIENTRAS INSTALAS..PONLO EN: false
                        // lee mas abajo respecto a 'Encriptando las claves'
                        //
                        'useEncryptedPassword' => false,

                        // Algoritmo de la función hash que deseas usar
                        // Los valores admitidos están en: http://www.php.net/manual/en/function.hash-algos.php
                        'hash' => 'md5',

                        // a donde enviar al usuario tras iniciar sesion, cerrar sesion o al expirar la sesion.
                        //
                        // esto va a forzar a Yii::app()->user->returnUrl cambiando el comportamiento estandar de Yii
                        // en los casos en que se usa CAccessControl como controlador
                        //
                        // ejemplo:
                        //		'afterLoginUrl'=>array('/site/welcome'),  ( !!! no olvidar el slash inicial / )
                        //		'afterLogoutUrl'=>array('/site/page','view'=>'about'),
                        //
                        'afterLoginUrl'=>null,
                        'afterLogoutUrl'=>null,
                        'afterSessionExpiredUrl'=>null,

                        // manejo del layout con cruge.
                        //
                        'loginLayout'=>'//layouts/column2',
                        'registrationLayout'=>'//layouts/column2',
                        'activateAccountLayout'=>'//layouts/column2',
                        'editProfileLayout'=>'//layouts/column2',
                        // en la siguiente puedes especificar el valor "ui" o "column2" para que use el layout
                        // de fabrica, es basico pero funcional.  si pones otro valor considera que cruge
                        // requerirá de un portlet para desplegar un menu con las opciones de administrador.
                        //
                        'generalUserManagementLayout'=>'ui',

                        // permite indicar un array con los nombres de campos personalizados, 
                        // incluyendo username y/o email para personalizar la respuesta de una consulta a: 
                        // $usuario->getUserDescription(); 
                        'userDescriptionFieldsArray'=>array('email'), 

                ),
              
		
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
                    'allowAutoLogin'=>true,
                    //'class'=>'CWebUser',
                    'autoUpdateFlash' => false, // add this line to disable the flash counter
                    // --- ADMINISTRADOR DE USUARIOS ----
                    'class' => 'application.modules.cruge.components.CrugeWebUser',
                    'loginUrl' => array('/cruge/ui/login'),
                    ),
                    'authManager' => array(
                    'class' => 'application.modules.cruge.components.CrugeAuthManager',
                    ),
                    'crugemailer'=>array(
                    'class' => 'application.modules.cruge.components.CrugeMailer',
                    'mailfrom' => 'email-desde-donde-quieres-enviar-los-mensajes@xxxx.com',
                    'subjectprefix' => 'Tu Encabezado del asunto - ',
                    'debug' => true,
                    ),
                    'format' => array(
                    'datetimeFormat'=>"d M, Y h:m:s a",          
                    ),
                //'behaviors' => array(
                    //'onBeginRequest' => array(
                        //'class' => 'application.components.RequireLogin'
                    //)
                //),            
		'bootstrap'=>array(
                        'class'=>'bootstrap.components.Bootstrap',
                ),
                //'miscomponentes'=>array(
                    //'class'=>'application.components.MisComponentes',
                //),
                'configuracion'=>array(
                    'class'=>'application.components.Configuracion',
                ),
                'EJSUrlManager' => array(
                    'class' => 'ext.JSUrlManager.EJSUrlManager'
                ),
                // uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=gabriele_proyecto2014',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
                        'enableProfiling' => true,
		),
		
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		*/
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'trace, info, error, warning, vardump',
				),
				// uncomment the following to show log messages on web pages
				array(
					'class' => 'CWebLogRoute',
					'enabled' => YII_DEBUG,
					'levels' => 'error, warning, trace, notice',
					'categories' => 'application',
					'showInFireBug' => false,
				),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
            /*
                'Facebook'=>array(  
                    'appId' => '736937486366843',
                    'secret' => '',
                    'cookie' => true,
                ),
             * 
             */
	),
);