<?php

require_once 'Services/Soundcloud.php';
ini_set('max_execution_time', 0);

session_start();
$offset = 100;

if (!$_SESSION['access_token'])
    header('Location: index.php');

$client = new Services_Soundcloud('cade8577a8839fa6de895b504fafecc9', '42270f185a5abb1da0aaf54e0ae226ff', 'http://www.nataraja-music.com/soundcloud/index.php');
$client->setAccessToken($_SESSION['access_token']);

$i = 1;
do {
    $following = json_decode($client->get('me/followings', array('limit' => $offset, 'offset' => $offset * $i)));
    foreach ($following as $follow)
    {
        try {
            $uri = 'me/followings/'.$follow->id;
            $client->delete($uri);
            echo "unfollowing ".$follow->username.'<br>';
        } catch (Exception $e) {
            echo "can not delete ".$follow->username.' '.$uri.'<br>';
            var_dump($e);
            die();
        }
    }
    $i++;
} while (count($following))
?>
