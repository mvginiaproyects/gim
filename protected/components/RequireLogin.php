<?php
class RequireLogin extends CBehavior
{
    public function attach($owner)
    {
        $owner->attachEventHandler('onBeginRequest', array($this, 'handleBeginRequest'));
    }    

    public function handleBeginRequest($event)
    {
            $app = Yii::app();
            $user = $app->user;
            $recovery = trim(is_array(Yii::app()->getModule('user')->recoveryUrl) ? Yii::app()->getModule('user')->recoveryUrl[0] : Yii::app()->getModule('user')->recoveryUrl, '/'); //gets recovery url for yii-user
            $registration = trim(is_array(Yii::app()->getModule('user')->registrationUrl) ? Yii::app()->getModule('user')->registrationUrl[0] : Yii::app()->getModule('user')->registrationUrl, '/'); //gets reistraion url for yii-user
            $captchUrl = trim(is_array(Yii::app()->getModule('user')->captchaUrl) ? Yii::app()->getModule('user')->captchaUrl[0] : Yii::app()->getModule('user')->captchaUrl, '/'); //gets captcha url for yii-user..dosen't work?!?
            $request = trim($app->urlManager->parseUrl($app->request), '/');
            $login = trim($user->loginUrl[0], '/');
            $login = trim(is_array($user->loginUrl) ? $user->loginUrl[0] : $user->loginUrl, '/'); //gets loginurl from main config file

            // Restrict guests to public pages.
            $allowed = array($login,$recovery,$registration,$captchUrl);//allows users if not logged in to view only these 4 pages you can add others like above or like array($login,'site/login'); either way works. This is easier than adding to each controller if you only want users to be able to view a few pages w/o logging in.
            if ($user->isGuest && !in_array($request, $allowed))
            $user->loginRequired();
            
            // Prevent logged in users from viewing the login page.
            $request = substr($request, 0, strlen($login));
            if (!$user->isGuest && $request == $login)
            {
            $url = $app->createUrl($app->homeUrl[0]);
            $app->request->redirect($url);
            }
    }

}
?>