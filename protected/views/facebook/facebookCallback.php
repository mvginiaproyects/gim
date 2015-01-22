<?php
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();
    //$cs->registerCssFile($baseUrl.'/css/Facebook/base.css');    
    $cs->registerCssFile($baseUrl.'/css/Facebook/style.css');    

require_once(Yii::app()->basePath . '/components/Facebook/facebook.php');

$facebook = new Facebook();
$amigos = $facebook->api('/me/taggable_friends');

//$amigos2 = $facebook->api('/me/friends');

//echo '<pre>'; 10152973121113032
//print_r($amigos2);
//echo '</pre>';

$feeds = $facebook->api('/me/feed','GET', array(
    //'fields'=>'id, from, message, picture, link, comments',
    'limit'=>40
));

//echo '<pre>';
//print_r($feeds);
//echo '</pre>';

foreach ($feeds['data'] as $post ){
    $posts .= '<p><a href="' . $post['link'] . '">' . $post['story'] . '</a></p>';
    $posts .= '<p><a href="' . $post['link'] . '">' . $post['message'] . '</a></p>';
    $posts .= '<p>' . $post['description'] . '</p>';
    $posts .= '<br />';
}
//echo $posts;
echo '<div class="row-fluid">';
echo '<div class="span6 fb-feed" style="height: 300px; overflow-y: scroll">';
foreach($feeds['data'] as $post) {
 
    if ($post['type'] == 'status' || $post['type'] == 'link' || $post['type'] == 'photo') {
 
        if ($post['message']!=''){
            // open up an fb-update div
            echo "<div class=\"fb-update\">";

                // post the time

                // check if post type is a status
                if ($post['type'] == 'status') {
                    echo "<h2>Estado actualizado el: " . date("jS M, Y", (strtotime($post['created_time']))) . "</h2>";
                    if ($post['from']['id']!='10152973121113032')
                        echo "<p><strong>De: " . $post['from']['name'] . "</strong></p>";                        
                    echo "<p>" . $post['message'] . "</p>";
                    if ($post['with_tags'])
                        echo "<p>Con: " . $post['with_tags']['data'][0]['name'] . "</p>";
                }

                // check if post type is a link
                if ($post['type'] == 'link') {
                    echo "<h2>Vinculo posteado el: " . date("jS M, Y", (strtotime($post['created_time']))) . "</h2>";
                    if ($post['from']['id']!='10152973121113032')
                        echo "<p><strong>De: " . $post['from']['name'] . "</strong></p>";                        
                    echo "<p>" . $post['name'] . "</p>";
                    echo "<p><a href=\"" . $post['link'] . "\" target=\"_blank\">" . $post['link'] . "</a></p>";
                }

                // check if post type is a photo
                if ($post['type'] == 'photo') {
                    echo "<h2>Foto posteada en: " . date("jS M, Y", (strtotime($post['created_time']))) . "</h2>";
                    if ($post['from']['id']!='10152973121113032')
                        echo "<p><strong>De: " . $post['from']['name'] . "</strong></p>";                        
                    if (empty($post['story']) === false) {
                        echo "<p>" . $post['story'] . "</p>";
                    } elseif (empty($post['message']) === false) {
                        echo "<p>" . $post['message'] . "</p>";
                    }
                    echo "<p><a href=\"" . $post['link'] . "\" target=\"_blank\">Ver foto &rarr;</a></p>";
                }

            echo "</div>"; // close fb-update div
        }
    }
 
} // end the foreach statement

echo "</div>";
echo "</div>";

?>


<form name="posts" action="<?php echo Yii::app()->createAbsoluteUrl('facebook/facebookCallback');?>" method="POST">
    <div class="row-fluid">
        <div class="span6">
            <div class="row-fluid">
                <select name="id">
                    <?php foreach ($amigos['data'] as $amigo):?>
                    <option value="<?php echo $amigo['id'];?>"><?php echo $amigo['name']?></option>
                    <?php endforeach;?>
                </select>                
            </div>
            <div class="row-fluid">
                <textarea rows="4" cols="50" name="mensaje"></textarea>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span1">
            <input type="submit" value="Submit" class="btn btn-primary">
        </div>
    </div>
</form>
