<?php

require_once 'Services/Soundcloud.php';
ini_set('max_execution_time', 0);

ini_set('display_errors', 1);
@error_reporting(E_ALL | ~E_STRICT);

session_start();
$offset = 100;

if (!$_SESSION['access_token'])
    header('Location: index.php');

function follow ($client, $follow)
{
    print "found new mate ".$follow->username.'<br>';
    try {
        $client->put('/me/followings/'.$follow->id, array());
    } catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
        echo ("but was unable to follow friend, code : ".$e->getHttpCode()."<br>".$e->getHttpBody());
    }
}

function post_comment($client, $follow, $track)
{
    global $comments;
     try {
         $track_url = $track->permalink_url;
         echo "found track <br> $track_url<br>";

         $place = mt_rand(10000, $track->duration - 10000);
         $comment_id = mt_rand(1, count($comments));

         $comment = json_decode($client->post('tracks/' . $track->id . '/comments', array(
             'comment[body]' => $comments[$comment_id - 1],
             'comment[timestamp]' => $place
             )));

         echo "commented ".$comments[$comment_id - 1].' at '.($place / 1000).'<br> url : <a href="'.$track_url.'" target="_blank">'.$track_url.'</a><br>';

         echo '<script src="http://connect.soundcloud.com/sdk.js"></script> <script>
         function refresh() {
             window.location.reload(true);
         }

         setTimeout(refresh, 60000);

         SC.initialize({
           client_id: \'cade8577a8839fa6de895b504fafecc9\'
       });

        SC.stream("/tracks/'.$track->id.'", function(sound){
         sound.setVolume(0);
         sound.play();
        });

        </script>';

    } catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
     echo ("but was unable to add comment, code : ".$e->getHttpCode()."<br>");
    }
}

$client = new Services_Soundcloud('cade8577a8839fa6de895b504fafecc9', '42270f185a5abb1da0aaf54e0ae226ff', 'http://www.nataraja-music.com/soundcloud/index.php');
$client->setAccessToken($_SESSION['access_token']);

$leeched_user = 'http://soundcloud.com/'.$_GET['artist'];
$comments = explode("\n", $_GET['comments']);

if (!isset($_SESSION[$_GET['artist']])) {
   $_SESSION[$_GET['artist']] = array();
   $_SESSION[$_GET['artist']]['last_leeched'] = 0;
}

// resolve real client url through exception leverage
try {
    $client->get('resolve', array('url' => $leeched_user));
} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e)
{
    $status = json_decode($e->getHttpBody());
    $artist = json_decode($client->get(str_replace('https://api.soundcloud.com/', '', $status->location)));
}

try {
    while ($followers = json_decode($client->get('users/'.$artist->id.'/followers',array('limit' => 1,'offset' => $_SESSION[$_GET['artist']]['last_leeched']))))
    {
        $follow = array_pop($followers);
        try {
            $client->get('me/followings/'.$follow->id);
        } catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
            // if is not already following
            if ($e->getHttpCode() == '404') {
                $track_json = $client->get('users/'.$follow->id.'/tracks', array('limit' => 1));
                $track_tab = json_decode($track_json);
                $track = array_pop($track_tab);
                if ($track && $track->commentable) {
                    echo 'found a commentable track<br>';
                    follow($client, $follow);
                    post_comment($client, $follow, $track);
                    die();
                } else {
                    echo "no track found";
                }
            }
        }
        $_SESSION[$_GET['artist']]['last_leeched']++;
    }
} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
    echo 'Soundcloud Timed Out, Refresh :) <script>window.location.reload(true);</script>';
}
