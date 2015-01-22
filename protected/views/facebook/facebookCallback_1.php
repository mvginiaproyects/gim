<?php
require_once(Yii::app()->basePath . '/components/Facebook/facebook.php');

$facebook = new Facebook();
/*
$feed = $facebook->api('/me/feed','POST',array(
    "message"=>'Hola!'
));

echo 'Feed: '.$feed;
 * 
 */
$urlLogOut = $facebook->getLogoutUrl(array('next'=>Yii::app()->createAbsoluteUrl('facebook/index')));
$amigos = $facebook->api('/me/friends');

echo '<pre>';
print_r($amigos);
echo '<pre>';
?>
<p><a href="<?php echo $urlLogOut;?>">Logout</a></p>