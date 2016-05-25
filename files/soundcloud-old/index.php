<?php

require_once 'Services/Soundcloud.php';

session_start();

$client = new Services_Soundcloud('cade8577a8839fa6de895b504fafecc9', '42270f185a5abb1da0aaf54e0ae226ff', 'http://www.nataraja-music.com/files/soundcloud/index.php');

function login()
{
    global $client;
    // redirect user to authorize URL
    header("Location: " . $client->getAuthorizeUrl());
}

if ($_GET['code'] && !$_SESSION['access_token'])
{
    print $_GET['code'].'<br>';

    try {
        $access_token = $client->accessToken($_GET['code']);
        $_SESSION['access_token'] = $access_token['access_token'];
        $_SESSION['refresh_token'] = $access_token['refresh_token'];
    } catch (Exception $e) {
        echo "eeor in token code : ".$e->getmessage()."<br>";
	die;
	}
}

if ($_GET['error'])
    die($_GET['error'].' '.$_GET['error_description']);

if (isset($_SESSION['access_token']))
{
    try {
        $client->setAccessToken($_SESSION['access_token']);
        $current_user = json_decode($client->get('me'));

        $_SESSION['last_leeched'] = 0;

        print 'Welcome '.$current_user->username;
        echo '<br><br><a href="unfollow.php">unfollow all</a><br>
            <form action="manyfriends.php" method="get">
            <input type="text" name="artist" value="artist to leech from" />
            <textarea name="comments">';
            echo file_get_contents('./spamalot.txt');
            echo '
            </textarea>
            <input type="submit" name="submit" value="leech" />
            </form>';

    } catch (Exception $e) {
        print $_SESSION['access_token'];
        session_destroy();
        var_dump($e);
        print "Error with login";
    }
}
else
    login();

?>
