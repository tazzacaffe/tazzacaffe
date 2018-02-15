<?php
    require_once 'https://github.com/facebook/php-graph-sdk/edit/5.x/src/Facebook/Facebook.php';
    try{
        $facebook = new Facebook(array(
                'appId' => $app_id,
                'secret' => $app_secret,
                'cookie' => true
        ));
        if(is_null($facebook->getUser()))
        {
                header("Location:{$facebook->getLoginUrl(array('req_perms' => 'user_status,publish_stream,user_photos'))}");
                exit;
        }
        $me = $facebook->api('/me');
    }catch(Exception $e){
        echo $e->getMessage();
        echo '<p>Please try clearing your browser cookies or <a href="https://www.facebook.com/pg/Tazzacaffe1/photos/">click here</a>.</p>';
        die;
    }
?>
<html>
    <head>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script> 
        <script type="text/javascript" src="http://cloud.github.com/downloads/malsup/cycle/jquery.cycle.all.latest.js"></script> 
        <script type="text/javascript"> 
        $(document).ready(function() {
            $('.slideshow').cycle({
                fx: 'fade'
            });
        });
        </script> 
        <title>WebSPeaks.in | Access facebook Albums on your site using PHP</title>
    </head>
    <body>
<?php
    $albums = $facebook->api('/me/albums');

    $action = $_REQUEST['action'];

    $album_id = '';
    if(isset($action) && $action=='viewalbum'){ 
        $album_id = $_REQUEST['album_id'];
        $photos = $facebook->api("/{$album_id}/photos");
        ?>
        <div class="slideshow"> 
        <?php
        foreach($photos['data'] as $photo)
        {
            echo "<img src='{$photo['source']}' />";
        }
        ?>
        </div>
        <?php
    }

    $pageURL .= 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    echo '<div class="alb">';
    if(strstr($pageURL,'.php?')){
        $and = '&';
    }else{
        $and = '?';
    }

    echo '<p class="hd">My Albums</p>';
    foreach($albums['data'] as $album)
    {
        if($album_id == $album['id']){
            $name = '<b><u>'.$album['name'].'</u></b>';
        }else{
            $name = $album['name'];
        }
        echo '<p>'."<a href=".$pageURL.$and."action=viewalbum&album_id=".$album['id'].">".$name.'</a></p>';
    }
    echo '</div>';
    ?>
    </body>
</html>