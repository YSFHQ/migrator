<?php namespace YSFHQ\Infrastructure\Helpers;

use Html2Text\Html2Text;

class BBCodeHelper
{

    public static function convertHtmlToBBCode($text = '')
    {
        //problem with existing bbcode, just strip [url] tags and we will add them back on later
        $text = str_ireplace("[url]", "", $text);
        $text = str_ireplace("[/url]", "", $text);

        // Tags to Find
        $htmltags = array(
            '/\<b\>(.*?)\<\/b\>/is',
            '/\<i\>(.*?)\<\/i\>/is',
            '/\<u\>(.*?)\<\/u\>/is',
            '/\<ul\>(.*?)\<\/ul\>/is',
            '/\<li\>(.*?)\<\/li\>/is',
            '/\<img(.*?) src=\"(.*?)\" (.*?)\>/is',
            '/\<div\>(.*?)\<\/div\>/is',
            '/\<br(.*?)\>/is',
            '/\<strong\>(.*?)\<\/strong\>/is',
            '/\<a href=\"(.*?)\"(.*?)\>(.*?)\<\/a\>/is',
            '/\<a href=\'(.*?)\'(.*?)\>(.*?)\<\/a\>/is',
        );

        // Replace with
        $bbtags = array(
            '[b]$1[/b]',
            '[i]$1[/i]',
            '[u]$1[/u]',
            '[list]$1[/list]',
            '[*]$1[/*]',
            '[img]$2[/img]',
            '$1',
            "\n",
            '[b]$1[/b]',
            '[url=$1]$3[/url]',
            '[url=$1]$3[/url]',
        );

        // Replace $htmltags in $text with $bbtags
        $text = preg_replace ($htmltags, $bbtags, $text);

        //find urls and attempt to enclose them in  tags
        $text = preg_replace('^(?#Protocol)(?:(?:ht|f)tp(?:s?)\:\/\/|~/|/)?(?#Username:Password)(?:\w+:\w+@)?(?#Subdomains)(?:(?:[-\w]+\.)+(?#TopLevel Domains)(?:com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum|travel|[a-z]{2}))(?#Port)(?::[\d]{1,5})?(?#Directories)(?:(?:(?:/(?:[-\w~!$+|.,=]|%[a-f\d]{2})+)+|/)+|\?|#)?(?#Query)(?:(?:\?(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)(?:&(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)*)*(?#Anchor)(?:#(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)?^', '[url]$0[/url]', $text);

        // Strip all other HTML tags
        $text = strip_tags($text);
        $html2text = new Html2Text($text);
        $text = $html2text->getText();

        return $text;
    }

}
