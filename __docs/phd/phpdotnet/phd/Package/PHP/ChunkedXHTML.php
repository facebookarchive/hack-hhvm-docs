<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_PHP_ChunkedXHTML extends Package_PHP_Web {
    private $nav = "";

    public function __construct() {
        parent::__construct();
        $this->registerFormatName("PHP-Chunked-XHTML");
        $this->setExt(Config::ext() === null ? ".html" : Config::ext());
    }

    public function __destruct() {
        parent::__destruct();
    }

    public function header($id) {
        $title = Format::getLongDescription($id);
        static $cssLinks = null;
        if ($cssLinks === null) {
            $cssLinks = $this->createCSSLinks();
        }
        $header = <<<HEADER
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>$title</title>
{$cssLinks}
 </head>
 <body>
HEADER;
        $next = $prev = $up = array("href" => null, "desc" => null);
        if ($prevId = Format::getPrevious($id)) {
            $prev = array(
                "href" => $this->getFilename($prevId) . $this->getExt(),
                "desc" => $this->getShortDescription($prevId),
            );
        }
        if ($nextId = Format::getNext($id)) {
            $next = array(
                "href" => $this->getFilename($nextId) . $this->getExt(),
                "desc" => $this->getShortDescription($nextId),
            );
        }
        if ($parentId = Format::getParent($id)) {
            $up = array(
                "href" => $this->getFilename($parentId) . $this->getExt(),
                "desc" => $this->getShortDescription($parentId),
            );
        }

        $nav = <<<NAV
<div class="manualnavbar" style="text-align: center;">
 <div class="prev" style="text-align: left; float: left;"><a href="{$prev["href"]}">{$prev["desc"]}</a></div>
 <div class="next" style="text-align: right; float: right;"><a href="{$next["href"]}">{$next["desc"]}</a></div>
 <div class="up"><a href="{$up["href"]}">{$up["desc"]}</a></div>
 <div class="home"><a href="index.html">PHP Manual</a></div>
</div>
NAV;
        $header .= $nav . "<hr />";
        $this->nav = $nav;
        return $header;
    }

    public function footer($id) {
        $nav = $this->nav;
        $this->nav = "";
        return "<hr />$nav</body></html>\n";
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

