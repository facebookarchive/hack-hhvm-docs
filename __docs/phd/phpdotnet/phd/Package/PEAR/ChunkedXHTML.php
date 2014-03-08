<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_PEAR_ChunkedXHTML extends Package_PEAR_XHTML {
    public function __construct() {
        parent::__construct();
        $this->registerFormatName("PEAR-Chunked-XHTML");
        $this->setTitle("PEAR Manual");
        $this->setExt(Config::ext() === null ? ".html" : Config::ext());
        $this->setChunked(true);
    }

    public function __destruct() {
        $this->close();
    }

    public function appendData($data) {
        $data = $this->mangleParagraphs($data);
    	if ($this->appendToBuffer) {
    		$this->buffer .= $data;

    		return;
    	} elseif ($this->flags & Render::CLOSE) {
            $fp = $this->popFileStream();
            fwrite($fp, $data);
            $this->writeChunk($this->CURRENT_CHUNK, $fp);
            fclose($fp);

            $this->flags ^= Render::CLOSE;
        } elseif ($this->flags & Render::OPEN) {
            $fp = fopen("php://temp/maxmemory", "r+");
            fwrite($fp, $data);
            $this->pushFileStream($fp);

            $this->flags ^= Render::OPEN;
        } else {
            $fp = $this->getFileStream();
            fwrite(end($fp), $data);
        }
    }

    public function writeChunk($id, $fp) {
        $filename = $this->getOutputDir() . $id . $this->getExt();

        rewind($fp);
        file_put_contents($filename, $this->header($id));
        file_put_contents($filename, $fp, FILE_APPEND);
        file_put_contents($filename, $this->footer($id), FILE_APPEND);
    }

    public function close() {
        foreach ($this->getFileStream() as $fp) {
            fclose($fp);
        }
    }

    public function update($event, $val = null) {
        switch($event) {
        case Render::CHUNK:
            $this->flags = $val;
            break;

        case Render::STANDALONE:
            if ($val) {
                $this->registerElementMap(static::getDefaultElementMap());
                $this->registerTextMap(static::getDefaultTextMap());
            }
            break;

        case Render::INIT:
            $this->setOutputDir(Config::output_dir() . strtolower($this->getFormatName()) . '/');
            $this->postConstruct();
            if (file_exists($this->getOutputDir())) {
                if (!is_dir($this->getOutputDir())) {
                    v("Output directory is a file?", E_USER_ERROR);
                }
            } else {
                if (!mkdir($this->getOutputDir(), 0777, true)) {
                    v("Can't create output directory", E_USER_ERROR);
                }
            }
            if (Config::css()) {
                $this->fetchStylesheet();
            }
            break;
        case Render::VERBOSE:
        	v("Starting %s rendering", $this->getFormatName(), VERBOSE_FORMAT_RENDERING);
        	break;
        }
    }


    /**
    * Generates the header HTML for the given ID.
    * Full doctype, html head, begin of body tag and top navigation.
    *
    * @param string $id Page ID
    *
    * @return string Header HTML
    */
    public function header($id) {
        $title = $this->getShortDescription($id);
        $lang = Config::language();
        static $cssLinks = null;
        if ($cssLinks === null) {
            $cssLinks = $this->createCSSLinks();
        }
        $this->prev = $this->next = $this->up = array("href" => null, "desc" => null);
        $strPrev = $strNext = '';

        if ($parentId = $this->getParent($id)) {
            $this->up = array("href" => $this->getFilename($parentId) . $this->ext,
                "desc" => $this->getShortDescription($parentId));
        }
        if ($prevId = Format::getPrevious($id)) {
            $this->prev = array("href" => Format::getFilename($prevId) . $this->ext,
                "desc" => $this->getShortDescription($prevId));
        }
        if ($nextId = Format::getNext($id)) {
            $this->next = array("href" => Format::getFilename($nextId) . $this->ext,
                "desc" => $this->getShortDescription($nextId));
        }
        $header = '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
 <head>
  <title>' . $title . '</title>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
  <meta name="generator" content="PhD" />
'.$cssLinks.'
  <link rel="start" href="index.html" title="'.$this->title.'" />
';
        if ($this->up["href"]) {
            $header .= '  <link rel="up" href="'.$this->up["href"].'" title="'.$this->up["desc"].'" />
';
        }
        if ($this->prev["href"]) {
            $strPrev = '<a href="' . $this->prev["href"] . '" title="' . $this->prev["desc"] . '">Prev</a>';
            $header .= '  <link rel="prev" href="' . $this->prev["href"] . '" title="' . $this->prev["desc"] . '" />
';
        }
        if ($this->next["href"]) {
            $strNext = '<a href="' . $this->next["href"] . '" title="' . $this->next["desc"] . '">Next</a>';
            $header .= '  <link rel="next" href="' . $this->next["href"] . '" title="' . $this->next["desc"] . '" />
';
        }

        $header .= ' </head>
 <body>
';

        $nav = <<<NAV
<div class="navheader">
 <table style="width: 100%;">
  <tr><th colspan="3" style="text-align: center">{$title}</th></tr>
  <tr>
   <td style="width: 40%; text-align: left;">{$strPrev}</td>
   <td style="width: 20%;"></td>
   <td style="width: 40%; text-align: right;">{$strNext}</td>
  </tr>
 </table>
 <hr/>
</div>

NAV;
        $header .= $nav . "<div id=\"body\">\n";
        return $header;
    }

    public function footer($id) {
        $strPrev = $strNext = $strUp = '';
        if ($this->up["href"]) {
            $strUp = '<a accesskey="h" href="' . $this->up["href"] . '">' . $this->up["desc"] . '</a>';
        }
        if ($this->prev["href"]) {
            $strPrev = '<a accesskey="p" href="' . $this->prev["href"] . '">Prev</a>';
        }
        if ($this->next["href"]) {
            $strNext = '<a accesskey="n" href="' . $this->next["href"] . '">Next</a>';
        }
        $strHome = $id !== 'index' ? '<a accesskey="h" href="index.html">'.$this->title.'</a>' : '';

        $nav = <<<NAV
<div class="navfooter">
 <hr />
 <table style="width: 100%;">
  <tr>
   <td style="width: 40%; text-align: left;">{$strPrev}</td>
   <td style="width: 20%; text-align: center;">{$strUp}</td>
   <td style="width: 40%; text-align: right;">{$strNext}</td>
  </tr>
  <tr>
   <td style="text-align: left; vertical-align: top;">{$this->prev["desc"]}</td>
   <td style="text-align: center;">{$strHome}</td>
   <td style="text-align: right; vertical-align: top;">{$this->next["desc"]}</td>
  </tr>
 </table>
</div>

NAV;
        return "</div>\n$nav</body></html>\n";
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

