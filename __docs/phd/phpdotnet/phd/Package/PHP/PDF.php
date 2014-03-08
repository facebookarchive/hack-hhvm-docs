<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_PHP_PDF extends Package_Generic_PDF {
    protected $elementmap = array(
        'article'               => 'format_tocnode_newpage',
        'appendix'              => 'format_tocnode_newpage',
        'book'                  => 'format_book',
        'bibliography'          => array(
            /* DEFAULT */          'format_suppressed_tags',
            'article'           => 'format_tocnode_newpage',
            'book'              => 'format_tocnode_newpage',
            'part'              => 'format_tocnode_newpage',
        ),
        'callout'               => 'format_collect_id',
        'chapter'               => 'format_tocnode_newpage',
        'co'                    => 'format_collect_id',
        'colophon'              => 'format_tocnode_newpage',
        'footnote'              => 'format_collect_id',
        'glossary'              => array(
            /* DEFAULT */          'format_suppressed_tags',
            'article'           => 'format_tocnode_newpage',
            'book'              => 'format_tocnode_newpage',
            'part'              => 'format_tocnode_newpage',
        ),
        'index'                 => array(
            /* DEFAULT */          'format_suppressed_tags',
            'article'           => 'format_tocnode_newpage',
            'book'              => 'format_tocnode_newpage',
            'part'              => 'format_tocnode_newpage',
        ),
        'legalnotice'           => 'format_tocnode_newpage',
        'part'                  => 'format_tocnode_newpage',
        'phpdoc:exceptionref'   => 'format_tocnode_exception',
        'phpdoc:classref'       => 'format_tocnode_class',
        'preface'               => 'format_tocnode_newpage',
        'refentry'              => 'format_tocnode_newpage',
        'reference'             => 'format_tocnode_newpage',
        'phpdoc:varentry'       => 'format_tocnode_newpage',
        'sect1'                 => 'format_tocnode',
        'sect2'                 => 'format_tocnode',
        'sect3'                 => 'format_tocnode',
        'sect4'                 => 'format_tocnode',
        'sect5'                 => 'format_tocnode',
        'section'               => 'format_tocnode',
        'set'                   => array(
            /* DEFAULT */          'format_root_set',
            'set'               => 'format_set',
        ),
        'setindex'              => 'format_tocnode_newpage',

    );

    protected $textmap =        array(
        'title'                 => array(
            /* DEFAULT */          false,
            'book'              => 'format_bookname',
            'set'               => array(
                /* DEFAULT */      'format_setname',
                'set'           => false,
            ),
        ),
        'type'                  => array(
            /* DEFAULT */          'format_type_text',
            'classsynopsisinfo' => false,
            'fieldsynopsis'     => 'format_type_if_object_or_pseudo_text',
            'methodparam'       => 'format_type_if_object_or_pseudo_text',
            'methodsynopsis'    => array(
                /* DEFAULT */      'format_type_if_object_or_pseudo_text',
                'classsynopsis' => false,
            ),
        ),
        'titleabbrev'           => array(
            /* DEFAULT */          'format_suppressed_tags',
            'phpdoc:classref'   => 'format_grep_classname_text',
            'phpdoc:exceptionref'  => 'format_grep_classname_text',
        ),


    );

    private   $acronyms = array();

    /* Common properties for all functions pages */
    protected $bookName = "";

    /* Current Chunk settings */
    protected $cchunk          = array();
    /* Default Chunk settings */
    protected $dchunk          = array(
        "phpdoc:classref"           => null,
        "bookname"                  => null,
        "setname"                   => null,
        "toc-root"                  => null,
        "id-to-outline"             => array(),
        "id-to-page"                => array(),
        "root-outline"              => null,
    );

    public function __construct() {
        parent::__construct();
        $this->registerFormatName("PHP-PDF");
        $this->setTitle("PHP Manual");
    }

    public function __destruct() {}

    public function update($event, $val = null) {
        switch($event) {
        case Render::STANDALONE:
            if ($val) {
                $this->registerElementMap($this->getDefaultElementMap());
                $this->registerTextMap($this->getDefaultTextMap());
            } else {
                $this->registerElementMap($this->elementmap);
                $this->registerTextMap($this->elementmap);
            }
            break;

        case Render::INIT:
            if (!class_exists("HaruDoc")) die ("PDF output needs libharu & haru/pecl extensions... Please install them and start PhD again.\n");
            $this->setOutputDir(Config::output_dir() . strtolower($this->getFormatName()) . DIRECTORY_SEPARATOR);
            if(!file_exists($this->getOutputDir()) || is_file($this->getOutputDir())) mkdir($this->getOutputDir(), 0777, true) or die("Can't create the cache directory.\n");
            break;
        case Render::VERBOSE:
        	v("Starting %s rendering", $this->getFormatName(), VERBOSE_FORMAT_RENDERING);
        	break;
        }
    }

    public function getDefaultElementMap() {
        return array_merge(parent::getDefaultElementMap(), $this->elementmap);
    }

    public function getDefaultTextMap() {
        return array_merge(parent::getDefaultTextMap(), $this->textmap);
    }

    // Do nothing
    public function appendData($data) {}

    // To override
    public function format_root_set($open, $name, $attrs, $props) {}
    public function format_set($open, $name, $attrs, $props) {}

    public function format_book($open, $name, $attrs, $props) {
        if ($open) {
            parent::newChunk();
            $this->cchunk = $this->dchunk;
            $pdfDoc = new PdfWriter();
            try {
                $pdfDoc->setCompressionMode(\HaruDoc::COMP_ALL);
            } catch (\HaruException $e) {
                v("PDF Compression failed, you need to compile libharu with Zlib...", E_USER_WARNING);
            }
            parent::setPdfDoc($pdfDoc);
            if (isset($attrs[Reader::XMLNS_XML]["base"]) && $base = $attrs[Reader::XMLNS_XML]["base"])
                parent::setChunkInfo("xml-base", $base);

            $id = $attrs[Reader::XMLNS_XML]["id"];
            $this->cchunk["root-outline"] = $this->cchunk["id-to-outline"][$id] =
                $pdfDoc->createOutline(Format::getShortDescription($id), null, true);
            $this->setIdToPage($id);
        } else {
            $this->resolveLinks($this->cchunk["bookname"]);
            $pdfDoc = parent::getPdfDoc();
            v("Writing PDF Manual (%s)", $this->cchunk["bookname"], VERBOSE_TOC_WRITING);
            $pdfDoc->saveToFile($this->getOutputDir() . $this->toValidName($this->cchunk["bookname"]) . $this->getExt());
            unset($pdfDoc);
        }
        return false;
    }

    public function format_bookname($value, $tag) {
        $this->cchunk["bookname"] = trim($value);
        parent::getPdfDoc()->setCurrentBookName($this->cchunk["bookname"]);
        parent::getPdfDoc()->appendText($this->cchunk["bookname"]);
        return false;
    }

    public function format_setname($value, $tag) {
        $this->cchunk["setname"] = trim($value);
        parent::getPdfDoc()->appendText($this->cchunk["setname"]);
        return false;
    }

    public function format_tocnode($open, $name, $attrs, $props, $newpage = false) {
        if ($open) {
            if ($newpage) {
                parent::getPdfDoc()->add(PdfWriter::PAGE);
            } else {
                parent::getPdfDoc()->add(PdfWriter::LINE_JUMP);
            }
            if (isset($attrs[Reader::XMLNS_XML]["base"]) && $base = $attrs[Reader::XMLNS_XML]["base"]) {
                parent::setChunkInfo("xml-base", $base);
            }

            if (isset($attrs[Reader::XMLNS_XML]["id"]) && $id = $attrs[Reader::XMLNS_XML]["id"]) {
                $parentId = Format::getParent($id);
                if (isset($this->cchunk["id-to-outline"][$parentId])) {
                    $this->cchunk["id-to-outline"][$id] = parent::getPdfDoc()->createOutline
                        (Format::getShortDescription($id), $this->cchunk["id-to-outline"][$parentId], false);
                } else {
                    $this->cchunk["id-to-outline"][$id] = parent::getPdfDoc()->createOutline
                        (Format::getShortDescription($id), $this->cchunk["root-outline"], false);
                }
                $this->setIdToPage($id);
            }
            parent::setChunkInfo("examplenumber", 0);
        }
        return "";
    }

    public function format_tocnode_newpage($open, $name, $attrs, $props) {
        return $this->format_tocnode($open, $name, $attrs, $props, true);
    }

    public function format_tocnode_exception($open, $name, $attrs, $props) {
        return $this->format_tocnode($open, "reference", $attrs, $props, true);
    }

    public function format_tocnode_class($open, $name, $attrs, $props) {
        return $this->format_tocnode($open, "reference", $attrs, $props, true);
    }

    public function format_grep_classname_text($value, $tag) {
        $this->cchunk["phpdoc:classref"] = strtolower($value);
    }

    // Convert the book name to a Unix valid filename
    protected function toValidName($functionName) {
        return str_replace(array(":", "::", "->", "/", "\\", " "), array(".", ".", ".", "-", "-", "-"), $functionName);
    }

    protected function setIdToPage($id) {
        if (isset($this->cchunk["id-to-page"]) && is_array($this->cchunk["id-to-page"])) {
            $this->cchunk["id-to-page"][$id] = parent::getPdfDoc()->getCurrentPage();
        }
    }

    protected function resolveLinks($name) {
        v("Resolving Internal Links... (%s)", $name, VERBOSE_TOC_WRITING);
        $linksToResolve = parent::getChunkInfo("links-to-resolve");
        foreach ($linksToResolve as $link => $targets) {
            if (isset($this->cchunk["id-to-page"][$link]) && $destPage = $this->cchunk["id-to-page"][$link]) {
                foreach ($targets as $target) {
                    $page = $target[0];
                    $rectangle = array($target[1], $target[2], $target[3], $target[4]);
                    parent::getPdfDoc()->resolveInternalLink($page, $rectangle, $destPage);

                }
            }
        }
    }

    public function format_type_text($type, $tagname) {
        $type = trim(preg_replace('/[ \n\t]+/', ' ', $type));
        $t = strtolower($type);
        $href = $fragment = "";

        switch($t) {
        case "bool":
            $href = "language.types.boolean";
            break;
        case "int":
        case "long":
            $href = "language.types.integer";
            break;
        case "double":
            $href = "language.types.float";
            break;
        case "void":
        case "boolean":
        case "integer":
        case "float":
        case "string":
        case "array":
        case "object":
        case "resource":
        case "null":
        case "callable":
            $href = "language.types.$t";
            break;
        case "mixed":
        case "number":
        case "callback": // old name for callable
            $href = "language.pseudo-types";
            $fragment = "language.types.$t";
            break;
        default:
            /* Check if its a classname. */
            $t = strtolower(str_replace(array("_", "::", "->"), array("-", "-", "-"), $t));
            $href = Format::getFilename("class.$t");
        }

        parent::getPdfDoc()->setFont(PdfWriter::FONT_NORMAL, 12, array(0, 0, 1)); // blue
        $linkAreas = parent::getPdfDoc()->add(PdfWriter::LINK_ANNOTATION, $type);
        $linksToResolve = parent::getChunkInfo("links-to-resolve");
        if (!isset($linksToResolve[$href]))
            $linksToResolve[$href] = array();
        foreach ($linkAreas as $area)
            $linksToResolve[$href][] = $area;
        parent::setChunkInfo("links-to-resolve", $linksToResolve);
        parent::getPdfDoc()->revertFont();
        return '';
    }

    public function format_type_if_object_or_pseudo_text($type, $tagname) {
        if (in_array(strtolower($type), array("bool", "int", "double", "boolean", "integer", "float", "string", "array", "object", "resource", "null"))) {
            parent::getPdfDoc()->appendText(" " . $type);
            return false;
        }
        return self::format_type_text($type, $tagname);
    }

    public function format_collect_id($open, $name, $attrs, $props, $newpage = false) {
        if ($open && isset($attrs[Reader::XMLNS_XML]["id"]) && $id = $attrs[Reader::XMLNS_XML]["id"]) {
                $this->setIdToPage($id);
        }
        return false;
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

