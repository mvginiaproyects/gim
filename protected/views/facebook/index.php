<?php
require_once(Yii::app()->basePath . '/components/Facebook/facebook.php');

$facebook = new Facebook();

$loginUrl = $facebook->getLoginUrl(
    array(
        'redirect_uri'=> Yii::app()->createAbsoluteUrl('facebook/facebookCallback'),
        'scope'=>'publish_actions, user_friends, user_tagged_places'
        //'scope'=>'publish_actions, read_stream, friends_likes, user_friends, user_tagged_places'
    )
);


?>

<a href="<?php echo $loginUrl;?>">Login facebook</a>