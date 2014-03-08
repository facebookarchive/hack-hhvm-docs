<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_Generic_ChunkedXHTML extends Package_Generic_XHTML {
    public function __construct() {
        parent::__construct();
        $this->registerFormatName("Chunked-XHTML");
        $this->setTitle("Index");
        $this->setChunked(true);
    }

    public function __destruct() {
        $this->close();
    }

    public function appendData($data) {
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
        $filename = $this->getOutputDir() . Format::getFilename($id) . $this->getExt();

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
            if ($this->appendToBuffer) {
                return; //Don't create output dir when rendering to buffer
            }

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

    public function header($id) {
        $title = $this->getLongDescription($id);
        $lang = Config::language();
        $root = Format::getRootIndex();
        static $cssLinks = null;
        if ($cssLinks === null) {
            $cssLinks = $this->createCSSLinks();
        }
        $prev = $next = $parent = array("href" => null, "desc" => null);

        if ($parentId = $this->getParent($id)) {
            $parent = array("href" => $this->getFilename($parentId) . $this->getExt(),
                "desc" => $this->getShortDescription($parentId));
        }
        if ($prevId = Format::getPrevious($id)) {
            $prev = array("href" => Format::getFilename($prevId) . $this->getExt(),
                "desc" => $this->getShortDescription($prevId));
        }
        if ($nextId = Format::getNext($id)) {
            $next = array("href" => Format::getFilename($nextId) . $this->getExt(),
                "desc" => $this->getShortDescription($nextId));
        }
        $navBar = $this->createNavBar($id);
        return
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' .$lang. '" lang="' .$lang. '">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>'.(($title != $root["ldesc"]) ? $root["ldesc"].': ' : "").$title.'</title>
'.$cssLinks.'
</head>
<body>
<table width="100%">
    <tr valign="top">
        <td style="font-size: smaller;" width="15%">
'.$navBar.'
        </td>
        <td width="85%">
            <div style="text-align: center;">
                '.($prevId ? '<div class="prev" style="text-align: left; float: left;"><a href="' .$prev["href"]. '">' .$prev["desc"]. '</a></div>' : '') .'
                '.($nextId ? '<div class="next" style="text-align: right; float: right;"><a href="' .$next["href"]. '">' .$next["desc"].'</a></div>' : '') .'
                '.($parentId ? '<div class="up"><a href="' .$parent["href"]. '">' .$parent["desc"]. '</a></div>' : '') .'
                <div class="home"><a href="'.$root["filename"].$this->getExt().'">'.$root["ldesc"].'</a></div>
            </div><hr/>
';
    }

    public function footer($id) {
        return "\n        </td>\n    </tr>\n</table>\n</body>\n</html>\n";
    }

    protected function createNavBar($id) {
        $root = Format::getRootIndex();
        $navBar =  '<style type="text/css">
#leftbar {
	float: left;
	width: 186px;
	padding: 5px;
	font-size: smaller;
}
ul.toc {
	margin: 0px 5px 5px 5px;
	padding: 0px;
}
ul.toc li {
	font-size: 85%;
	margin: 1px 0 1px 1px;
	padding: 1px 0 1px 11px;
	list-style-type: none;
	background-repeat: no-repeat;
	background-position: center left;
}
ul.toc li.header {
	font-size: 115%;
	padding: 5px 0px 5px 11px;
	border-bottom: 1px solid #cccccc;
	margin-bottom: 5px;
}
ul.toc li.active {
	font-weight: bold;
}
ul.toc li a {
	text-decoration: none;
}
ul.toc li a:hover {
	text-decoration: underline;
}
</style>
 <ul class="toc">
  <li class="header home"><a href="'.$root["filename"].$this->getExt().'">'.$root["ldesc"].'</a></li>
';
        // Fetch ancestors of the current node
        $ancestors = array();
        $currentId = $id;
        while (($currentId = $this->getParent($currentId)) && $currentId != "index") {
            $desc = "";
            $link = $this->createLink($currentId, $desc);
            $ancestors[] = array("desc" => $desc, "link" => $link);
        }
        // Show them from the root to the closest parent
        foreach (array_reverse($ancestors) as $ancestor) {
        	$navBar .= "  <li class=\"header up\"><a href=\"{$ancestor["link"]}\">{$ancestor["desc"]}</a></li>\n";
        }
        // Fetch siblings of the current node
        $parent = $this->getParent($id);
        foreach ($this->getChildren($parent) as $child) {
            $desc = "";
            $link = $this->createLink($child, $desc);
            $active = ($id === $child);
            $navBar .= "  <li" .($active ? " class=\"active\"" : ""). "><a href=\"$link\">$desc</a></li>\n";
        }
        return $navBar . " </ul>\n";
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

