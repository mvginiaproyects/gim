<?php

class FacebookController extends Controller
{
    
    function actionIndex() {

        $this->render('index');
        
    }
    
    function actionFacebookCallback(){
        
        if ($_POST['id']){
            require_once(Yii::app()->basePath . '/components/Facebook/facebook.php');
            $facebook = new Facebook();
            $params=array();       
            $params['message'] = $_POST['mensaje'];
            $params['tags']=$_POST['id']; //comma separated friends ID's
            $params['place']='155021662189';          
            //echo 'Token: '.$access_token;
            $post = $facebook->api('/me/feed','POST',$params);
        }
        
        $this->render('facebookCallback');
        
    }
    
}