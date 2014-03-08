<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_PHP_KDevelop extends Format {
    const DEFAULT_HREF = "http://www.php.net/manual/en/";

    protected $elementmap = array(
        'book'                  => 'format_tocsect1',
        'bibliography'          => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'article'               => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'appendix'              => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'colophon'              => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'chapter'               => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'glossary'              => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'index'                 => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'part'                  => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'preface'               => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'reference'             => array(
            /* DEFAULT */          false,
            'book'              => 'format_tocsect2',
        ),
        'refentry'              => 'format_refentry',
        'refname'               => 'format_suppressed_tags',
        'refpurpose'            => 'format_suppressed_tags',
        'refsection'            => 'format_suppressed_tags',
    );
    protected $textmap =        array(
        'refname'               => 'format_refname_text',
    );

    protected $currentEntryName;
    protected $index = array();

    // CHM Table of contents
    protected $currentTocDepth = 0;
    protected $lastContent = null;
    protected $toc;
    // CHM Index Map
    protected $hhkStream;

    public function __construct() {
        parent::__construct();
        $this->registerFormatName("PHP-KDevelop");
        $this->setTitle("PHP Manual");
        $this->setExt(Config::ext() === null ? ".php" : Config::ext());
    }

    public function __destruct() {
        self::footerToc();
        fclose($this->getFileStream());
    }

    public function transformFromMap($open, $tag, $name, $attrs, $props) {}
    public function UNDEF($open, $name, $attrs, $props) {}
    public function TEXT($value) {}
    public function CDATA($value) {}
    public function createLink($for, &$desc = null, $type = Format::SDESC) {}
    public function appendData($data) {}

    public function update($event, $val = null) {
        switch($event) {
        case Render::STANDALONE:
            if ($val) {
                $this->registerElementMap($this->elementmap);
                $this->registerTextMap($this->textmap);
            }
            break;
        case Render::INIT:
            if ($val) {
                $this->setOutputDir(Config::output_dir());
                $this->setFileStream(fopen($this->getOutputDir() . strtolower($this->getFormatName()), "w"));
                self::headerToc();
            }
            break;
        case Render::VERBOSE:
            v("Starting %s rendering", $this->getFormatName(), VERBOSE_FORMAT_RENDERING);
            break;
        }
    }

    public function format_suppressed_tags($open, $name, $attrs) {
        return "";
    }

    protected function headerToc() {
        fwrite($this->getFileStream(), "<!DOCTYPE kdeveloptoc>\n<kdeveloptoc>\n<title>" . $this->getTitle() . "</title>\n" .
          "<base href=\"" . self::DEFAULT_HREF . "\"/>\n");
    }

    protected function footerToc() {
        fwrite($this->getFileStream(), "<index>\n");
        foreach ($this->index as $name => $url)
            fwrite($this->getFileStream(), "<entry name=\"{$name}\" url=\"{$url}\"/>\n");
        fwrite($this->getFileStream(), "</index>\n</kdeveloptoc>\n");
    }

    public function format_tocsect1($open, $name, $attrs) {
        if (!isset($attrs[Reader::XMLNS_XML]["id"])) return "";
        $id = $attrs[Reader::XMLNS_XML]["id"];
        $hasChild = (count(Format::getChildren($id)) > 0);
        if ($open) {
            $name = htmlspecialchars(Format::getShortDescription($id), ENT_QUOTES, 'UTF-8');
            $url = (Format::getFilename($id) ? Format::getFilename($id) : $id) . $this->getExt();
            fwrite($this->getFileStream(), "<tocsect1 name=\"{$name}\" url=\"{$url}\"" . ($hasChild ? "" : "/") . ">\n");
        } else {
            if ($hasChild)
                fwrite($this->getFileStream(), "</tocsect1>\n");
        }
        return "";
    }

    public function format_tocsect2($open, $name, $attrs) {
        if (!isset($attrs[Reader::XMLNS_XML]["id"])) return "";
        $id = $attrs[Reader::XMLNS_XML]["id"];
        $hasChild = (count(Format::getChildren($id)) > 0);
        if ($open) {
            $name = htmlspecialchars(Format::getShortDescription($id), ENT_QUOTES, 'UTF-8');
            $url = (Format::getFilename($id) ? Format::getFilename($id) : $id) . $this->getExt();
            fwrite($this->getFileStream(), "    <tocsect2 name=\"{$name}\" url=\"{$url}\"/>\n");
        }
        return "";
    }

    public function format_refentry($open, $name, $attrs) {
        if (!isset($attrs[Reader::XMLNS_XML]["id"])) return "";
        $id = $attrs[Reader::XMLNS_XML]["id"];
        if ($open) {
            $this->currentEntryName = null;
        }
        if (!$open && $this->currentEntryName) {
            $url = (Format::getFilename($id) ? Format::getFilename($id) : $id) . $this->getExt();
            $this->index[$this->currentEntryName] = $url;
        }
        return "";
    }

    public function format_refname_text($value, $tag) {
        $this->currentEntryName = $value;
        return "";
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

