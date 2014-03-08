<?php
namespace phpdotnet\phd;
/* $Id$ */

abstract class Format_Abstract_XHTML extends Format {
    public $role = false;

    /* XHTMLPhDFormat */
    protected $openPara = 0;
    protected $escapedPara = array();

    /* PhDThemeXhtml */
    protected $mediamanager = null;
    protected $lang = 'en';

    public function __construct() {
        parent::__construct();
    }

    public function transformFromMap($open, $tag, $name, $attrs, $props) {
        if ($open) {
            $idstr = "";
            if (isset($attrs[Reader::XMLNS_XML]["id"])) {
                $id = $attrs[Reader::XMLNS_XML]["id"];
                $idstr = ' id="' .$id. '"';
            }
            return '<' .$tag. ' class="' .$name. '"' . $idstr . ($props["empty"] ? '/' : "") . '>';
        }
        return '</' .$tag. '>';
    }

    public function TEXT($str) {
        return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
    }

    public function UNDEF($open, $name, $attrs, $props) {
        if ($open) {
            trigger_error("No mapper found for '{$name}'", E_USER_WARNING);
        }
    }

    public function CDATA($str) {
        switch($this->role) {
        case '':
            return '<div class="cdata"><pre>'
                . htmlspecialchars($str, ENT_QUOTES, "UTF-8")
                . '</pre></div>';
        default:
            return '<div class="' . $this->role . 'code">'
                . $this->highlight(trim($str), $this->role, 'xhtml')
                . '</div>';
        }
    }

    /* Functions from PhDThemeXhtml */

    /**
    * Called after the constructor finished.
    * This is needed since themes set their outputdir and outputfile
    * in the constructor. That file/dir is used for mediamanager.
    * That means we cannot instantiate and complete the manager in our
    * constructor centrally.
    *
    * Each theme needs its own media manager, since the manager contains
    * the output path.
    *
    * @return void
    */
    public function postConstruct() {
        $this->mediamanager = new MediaManager(Config::xml_root());
        $outputdir = $this->getOutputDir();
        if (isset($outputdir) && $outputdir) {
            $this->mediamanager->output_dir = $outputdir;
        } else {
            $this->mediamanager->output_dir        = Config::output_dir() . '/' . strtolower($this->getFormatName()) . '-data/';
            $this->mediamanager->relative_ref_path = basename($this->mediamanager->output_dir) . '/';
        }
    }


    /* Functions from XHTMLPhDFormat */

    /**
    * Closes a para tag when we are already in a paragraph.
    *
    * @return string HTML code
    *
    * @see $openPara
    * @see restorePara()
    */
    public function escapePara() {
        if (!$this->openPara) {
            return '';
        }

        if (!isset($this->escapedPara[$this->openPara])) {
            $this->escapedPara[$this->openPara] = 1;
            return '</p>';
        } else {
            ++$this->escapedPara[$this->openPara];
            return '';
        }
    }

    /**
    * Opens a para tag again when we escaped one before.
    *
    * @return string HTML code
    *
    * @see $openPara
    * @see escapePara()
    */
    public function restorePara() {
        if (!$this->openPara || !isset($this->escapedPara[$this->openPara])) {
            return '';
        }

        if ($this->escapedPara[$this->openPara] == 1) {
            unset($this->escapedPara[$this->openPara]);
            return '<p>';
        } else {
            --$this->escapedPara[$this->openPara];
            return '';
        }
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

