<?php

/**
 * Copyright (c) Thomas Potaire
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   Teapot
 * @package    ForumBundle
 * @author     Thomas Potaire
 */

namespace Teapot\ForumBundle\Service;

use Teapot\ForumBundle\Entity\Message;
use Teapot\Base\ForumBundle\Service\MessageService as BaseMessageService;

class MessageService extends BaseMessageService
{
    public function createMessage()
    {
        return new Message();
    }

    public function parseBody(Message $message)
    {
        $message->setBody(html_entity_decode(htmlspecialchars_decode($message->getBody()), ENT_QUOTES));

        $regex = array("#<!--QuoteBegin-(?:[^>]*)?-->#", "#<!--quoteo\(post=([0-9]+):date=.+:name=.+\)-->#");
        $message->setBody(preg_replace($regex, '', $message->getBody()));

        // $regex = "#<div class='quotetop'>(?:[\n\s]+)?QUOTE\((.+)\s+\@(?:[^\)]+)?\)(?:[\n\s]+)?</div>(?:[\n\s]+)?<div class='quotemain'>(?:[\n\s]+)?<!--QuoteEBegin-->(?:[\n\s]+)?(.+)(?:[\n\s]+)?<!--QuoteEnd-->(?:[\n\s]+)?</div>(?:[\n\s]+)?<!--QuoteEEnd-->#";
        $regex = "#<div class='quotetop'>(?:[\n\s]+)?QUOTE\(([^)]+)\)(?:[\n\s]+)?</div>(?:[\n\s]+)?<div class='quotemain'>(?:[\n\s]+)?<!--QuoteEBegin-->(?:[\n\s]+)?(.+)(?:[\n\s]+)?<!--QuoteEnd-->(?:[\n\s]+)?</div>(?:[\n\s]+)?<!--QuoteEEnd-->#";
        $message->setBody(preg_replace($regex, '<blockquote><header>$1</header>$2</blockquote>', $message->getBody()));
        $message->setBody(preg_replace('#\[right\](?:.*)?\[\/right\]#', '', $message->getBody()));

        $regex = "#<div class='quotetop'>(?:[\n\s]+)?Citation \((.+)\s+\@(?:[^\)]+)?\)(?:\s+)?<a href=\"index.php\?act=findpost\&pid=\d+\"><\{POST_SNAPBACK\}></a></div><div class='quotemain'>(?:[\n\s]+)?<!--quotec-->(?:[\n\s]+)?(.+)(?:[\n\s]+)?<!--QuoteEnd-->(?:[\n\s]+)?</div>(?:[\n\s]+)?<!--QuoteEEnd-->#";
        $message->setBody(preg_replace($regex, '<blockquote><header>$1</header>$2</blockquote>', $message->getBody()));

// <!--quoteo(post=14796:date=31 Aug 2012, 00:07:name=Alba)-->
// <div class='quotetop'>Citation (Alba @ 31 Aug 2012, 00:07)
//   <a href="index.php?act=findpost&pid=14796"><{POST_SNAPBACK}></a>
// </div>
// <div class='quotemain'>
//   <!--quotec-->Quel rabat-joie <img src="style_emoticons/<#EMO_DIR#>/laugh.gif" style="vertical-align:middle" emoid=":lol:" border="0" alt="laugh.gif" />
//   <!--QuoteEnd-->
// </div>
// <!--QuoteEEnd-->

        $regex = "#<\!--emo&([^\!]+)--><img src='style_emoticons/<\#EMO_DIR\#>/\w+.\w+' border='0' style='vertical-align:middle' alt='\w+\.\w+' /><!--endemo-->#";
        $message->setBody(preg_replace($regex, '<span class="emo">$1</span>', $message->getBody()));

        $regex = '#<img src="style_emoticons/<\#EMO_DIR\#>/\w+.\w+" style="vertical-align:middle" emoid="([^>]+)" border="0" alt="\w+\.\w+" />#';
        $message->setBody(preg_replace($regex, '<span class="emo">$1</span>', $message->getBody()));

        $message->setBody(nl2br($message->getBody()));

        $regex = '#\[img:(?:.+)\](.+)\[/img:(?:.+)\]#s';
        $message->setBody(preg_replace($regex, '<br /><img src="$1" />', $message->getBody()));

        $regex = '#\[size=(?:.+)\](.+)\[/size:(?:.+)\]#sU';
        $message->setBody(preg_replace($regex, '$1', $message->getBody()));

        $regex = '#\[color=(?:.+)\](.+)\[/color:(?:.+)\]#sU';
        $message->setBody(preg_replace($regex, '$1', $message->getBody()));

        $regex = '#\[code:(?:.+)\]#sU';
        $message->setBody(preg_replace($regex, '<blockquote>', $message->getBody()));

        $regex = '#\[/code:(?:.+)\]#sU';
        $message->setBody(preg_replace($regex, '</blockquote>', $message->getBody()));

        // $regex = '#\[quote:(?:.+)="(.+)"\]#sU';
        // $message->setBody(preg_replace($regex, '<blockquote><header>$1</header>', $message->getBody()));

        // $regex = '#\[/quote:(?:.+)\]#sU';
        // $message->setBody(preg_replace($regex, '</blockquote>', $message->getBody()));

        // $regex = '#\[quote:(?:.+)\]#sU';
        // $message->setBody(preg_replace($regex, '<blockquote>', $message->getBody()));

        $regex = '#\[u:(?:[a-zA-Z0-9]+)\](.+)\[/u:(?:[a-zA-Z0-9]+)\]#sU';
        $message->setBody(preg_replace($regex, '<u>$1</u>', $message->getBody()));

        $regex = '#\[b:(?:[a-zA-Z0-9]+)\](.+)\[/b:(?:[a-zA-Z0-9]+)\]#sU';
        $message->setBody(preg_replace($regex, '<b>$1</b>', $message->getBody()));

        $regex = '#\[i:(?:[a-zA-Z0-9]+)\](.+)\[/i:(?:[a-zA-Z0-9]+)\]#sU';
        $message->setBody(preg_replace($regex, '<i>$1</i>', $message->getBody()));

        $matches = array();
        $regex = '#\[url=(?:.+)webheberg\.com/forum/viewtopic\.php\?t=(.+)\](.+)\[/url\]#sU';
        preg_match_all($regex, $message->getBody(), $matches);
        if (isset($matches[1][0])) {
            $topic = $this->container->get('doctrine')->getManager()
                          ->getRepository('TeapotForumBundle:Topic')->findOneByLegacyId($matches[1]);
            $url = $this->container->get('teapot.forum')->forumPath('ForumListMessagesByTopic', $topic);
            $message->setBody(preg_replace($regex, '<a href="'. $url .'">$2</a>', $message->getBody()));
        }

        $regex = '#\[url\](.+)\[/url\]#sU';
        $message->setBody(preg_replace($regex, '<a href="$1">$1</a>', $message->getBody()));

        $regex = '#\[url=(.+)\](.+)\[/url\]#sU';
        $message->setBody(preg_replace($regex, '<a href="$1">$2</a>', $message->getBody()));

        return $message;
    }
}