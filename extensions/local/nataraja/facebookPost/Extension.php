<?php

namespace Bolt\Extension\YourName\ExtensionName;

use Bolt\Application;
use Bolt\BaseExtension;

class Extension extends BaseExtension
{
    public function initialize()
    {
        $this->addTwigFunction('facebookposts', 'twigFacebookposts');
    }

    public function getName()
    {
        return "facebookPost";
    }

    /**
     * Twig function {{ facebookposts() }} in Facebook posts extension.
     */
    function twigFacebookposts()
    {
        $html = '';
        ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');

        $sxe = json_decode(file_get_contents('https://graph.facebook.com/276832629031522/feed?access_token=345462428914161|b62e40826e080640869a7fbc9680e607'));
        $i = 0;
        foreach ($sxe->data as $item)
        {
            $date = date_create($item->created_time);
            $d = date_format($date, 'Y-m-d');
            if ($i < 6)
                $html .= '<a href="http://facebook.com/natarajamusic">@natarajamusic</a>, '.$d.'<br><blockquote><p>'.mb_strimwidth($item->message, 0, 120, "...").'</p><footer><a href="\'.$item->link.\'">Read the full story</a></footer></blockquote>';
            $i++;
        }
        return new \Twig_Markup($html, 'UTF-8');
    }
}
