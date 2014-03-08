<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_Generic_Manpage extends Format_Abstract_Manpage {
    const OPEN_CHUNK    = 0x01;
    const CLOSE_CHUNK   = 0x02;

     private $elementmap = array( /* {{{ */
        'acronym'               => 'format_suppressed_tags',
        'article'               => 'format_suppressed_tags',
        'abbrev'                => 'format_suppressed_tags',
        'abstract'              => 'format_suppressed_tags',
        'alt'                   => 'format_suppressed_tags',
        'appendix'              => 'format_suppressed_tags',
        'application'           => 'format_suppressed_tags',
        'author'                => 'format_suppressed_tags',
        'authorgroup'           => 'format_suppressed_tags',
        'book'                  => 'format_suppressed_tags',
        'blockquote'            => 'format_suppressed_tags',
        'calloutlist'           => 'format_suppressed_tags',
        'callout'               => 'format_suppressed_tags',
        'caution'               => 'format_admonition',
        'chapter'               => 'format_suppressed_tags',
        'citerefentry'          => 'format_suppressed_tags',
        'classname'             => 'format_suppressed_tags',
        'classsynopsis'         => '.PP',
        'classsynopsisinfo'     => '.PP',
        'co'                    => 'format_suppressed_tags',
        'code'                  => 'format_suppressed_tags',
        'command'               => '\\fI',
        'computeroutput'        => 'format_suppressed_tags',
        'constant'              => '\\fB',
        'constructorsynopsis'   => 'format_methodsynopsis',
        'copyright'             => 'format_suppressed_tags',
        'destructorsynopsis'    => 'format_methodsynopsis',
        'editor'                => 'format_suppressed_tags',
        'emphasis'              => '\\fI',
        'envar'                 => 'format_suppressed_tags',
        'errortype'             => 'format_suppressed_tags',
        'example'               => 'format_example',
        'fieldsynopsis'         => 'format_suppressed_tags',
        'figure'                => 'format_suppressed_tags',
        'firstname'             => 'format_suppressed_tags',
        'footnote'              => 'format_suppressed_tags',
        'footnoteref'           => 'format_suppressed_tags',
        'filename'              => '\\fI',
        'formalpara'            => 'format_indent',
        'funcdef'               => '.B',
        'function'              => array(
            /* DEFAULT */          'format_suppressed_tags',
            'member'            => 'format_suppressed_tags',
        ),
        'glossterm'             => 'format_suppressed_tags',
        'holder'                => 'format_suppressed_tags',
        'imagedata'             => 'format_suppressed_tags',
        'indexentry'            => 'format_suppressed_tags',
        'imageobject'           => 'format_suppressed_tags',
        'info'                  => 'format_suppressed_tags',
        'indexdiv'              => 'format_suppressed_tags',
        'index'              => 'format_suppressed_tags',
        'informalexample'       => '.PP',
        'initializer'           => array(
            /* DEFAULT */          'format_suppressed_tags',
            'methodparam'       => 'format_suppressed_tags',
        ),
        'interfacename'         => 'format_suppressed_tags',
        'itemizedlist'          => 'format_itemizedlist',
        'legalnotice'           => 'format_suppressed_tags',
        'link'                  => 'format_suppressed_tags',
        'listitem'              => array(
            /* DEFAULT */          false,
            'varlistentry'      => 'format_suppressed_tags',
            'itemizedlist'      => ".TP 0.2i\n\\(bu",
            'orderedlist'       => ".TP 0.2i\n\\(bu",
        ),
        'literal'               => '\\fI',
        'literallayout'         => 'format_verbatim',
        'manvolnum'             => 'format_manvolnum',
        'mediaobject'           => 'format_mediaobject',
        'member'                => 'format_member',
        'methodname'            => '\\fB',
        'methodparam'           => 'format_methodparam',
        'methodsynopsis'        => 'format_methodsynopsis',
        'modifier'              => 'format_suppressed_tags',
        'note'                  => 'format_admonition',
        'ooclass'               => 'format_suppressed_tags',
        'oointerface'           => 'format_suppressed_tags',
        'option'                => '\\fI',
        'optional'              => 'format_suppressed_tags',
        'orderedlist'           => 'format_itemizedlist',
        'othername'             => 'format_suppressed_tags',
        'othercredit'           => 'format_suppressed_tags',
        'partintro'             => 'format_suppressed_tags',
        'personname'            => 'format_suppressed_tags',
        'para'                  => array(
            /* DEFAULT */          '.PP',
            'listitem'          => 'format_suppressed_tags',
            'entry'             => 'format_suppressed_tags',
        ),
        'paramdef'              => 'format_suppressed_tags',
        'parameter'             => array(
            /* DEFAULT */          'format_suppressed_tags',
            'methodparam'       => 'format_parameter_method',
            'code'              => '\\fI',
        ),
        'productname'           => 'format_suppressed_tags',
        'preface'               => 'format_suppressed_tags',
        'part'                  => 'format_suppressed_tags',
        'programlisting'        => 'format_verbatim',
        'property'              => 'format_suppressed_tags',
        'procedure'             => 'format_suppressed_tags',
        'primaryie'             => 'format_suppressed_tags',
        'pubdate'               => 'format_suppressed_tags',
        'qandaset'              => 'format_suppressed_tags',
        'qandaentry'            => 'format_suppressed_tags',
        'question'              => 'format_suppressed_tags',
        'answer'              => 'format_suppressed_tags',
        'quote'                 => 'format_suppressed_tags',
        'refentry'              => 'format_chunk',
        'refentrytitle'         => '\\fB',
        'reference'             => 'format_suppressed_tags',
        'refname'               => 'format_refname',
        'refnamediv'            => 'format_suppressed_tags',
        'refpurpose'            => 'format_refpurpose',
        'refsect1'              => 'format_refsect',
        'refsect2'              => 'format_suppressed_tags',
        'refsection'            => 'format_refsect',
        'refsynopsisdiv'        => 'format_refsynopsisdiv',
        'replaceable'           => '\\fI',
        'set'                   => 'format_bookname',
        'screen'                => 'format_verbatim',
        'section'               => 'format_suppressed_tags',
        'sect1'                 => 'format_suppressed_tags',
        'sect2'                 => 'format_suppressed_tags',
        'sect3'                 => 'format_suppressed_tags',
        'sect4'                 => 'format_suppressed_tags',
        'sect5'                 => 'format_suppressed_tags',
        'seg'                   => 'format_seg',
        'segindex'              => 'format_suppressed_tags',
        'seglistitem'           => 'format_seglistitem',
        'segmentedlist'         => 'format_segmentedlist',
        'segtitle'              => 'format_suppressed_tags',
        'simpara'               => array(
            /* DEFAULT */          '.PP',
            'listitem'          => '',
        ),
        'simplelist'            => 'format_simplelist',
        'simplesect'            => 'format_suppressed_tags',
        'subscript'             => 'format_suppressed_tags',
        'surname'               => 'format_suppressed_tags',
        'step'                  => 'format_suppressed_tags',
        'synopsis'              => 'format_suppressed_tags',
        'systemitem'            => '\\fB',
        'tag'                   => 'format_suppressed_tags',
        'term'                  => 'format_term',
        'title'                 => array(
            /* DEFAULT */          '.B',
            'segmentedlist'     => '.B',
            'refsect1'          => 'format_refsect_title',
            'refsection'        => 'format_refsect_title',
            'section'           => 'format_refsect_title',
        ),
        'tip'                   => 'format_admonition',
        'titleabbrev'           => 'format_suppressed_tags',
        'type'                  => array(
            /* DEFAULT */          '\\fR',
            'methodparam'       => 'format_suppressed_tags'
        ),
        'userinput'             => '\\fB',
        'variablelist'          => 'format_indent',
        'varlistentry'          => ".TP 0.2i\n\\(bu ",
        'varname'               => 'format_suppressed_tags',
        'void'                  => 'format_void',
        'warning'               => 'format_admonition',
        'year'                  => 'format_suppressed_tags',
        'xref'                  => 'format_xref',
        // GROFF (tbl) ARRAYS
        'informaltable'         => '.P',
        'table'                 => '.P',
        'tgroup'                => 'format_tgroup',
        'colspec'               => 'format_suppressed_tags',
        'thead'                 => 'format_thead',
        'tbody'                 => 'format_suppressed_tags',
        'row'                   => 'format_row',
        'entry'                 => 'format_entry',

        'phpdoc:classref'       => 'format_suppressed_tags',
        'phpdoc:exceptionref'   => 'format_suppressed_tags',
        'phpdoc:varentry'       => 'format_suppressed_tags',

    ); /* }}} */

    private $textmap = array(
        'classname'             => array(
            /* DEFAULT */          false,
            'ooclass'           => 'format_ooclass_name_text',
        ),
        'function'              => 'format_function_text',
        'initializer'           => array(
            /* DEFAULT */          false,
            'methodparam'       => 'format_initializer_method_text',
        ),
        'literallayout'         => 'format_verbatim_text',
        'manvolnum'             => 'format_text',
        'parameter'             => array(
            /* DEFAULT */          'format_parameter_text',
            'code'              => false,
            'methodparam'       => 'format_parameter_method_text',
        ),
        'programlisting'        => 'format_verbatim_text',
        'pubdate'               => 'format_pubdate_text',
        'refname'               => 'format_refname_text',
        'refpurpose'            => 'format_text',
        'screen'                => 'format_verbatim_text',
        'segtitle'              => 'format_segtitle_text',
        'title'                 => array(
            /* DEFAULT */          false,
            'refsect'           => 'format_refsect_text',
            'refsect1'          => 'format_refsect_text',
            'section'           => 'format_refsect_text',
        ),
        'tag'                   => 'format_tag_text',
        'type'                  => array(
            /* DEFAULT */          false,
            'methodparam'       => 'format_type_method_text',
        ),
        'varname'               => 'format_parameter_text',
    );

    /* If a chunk is being processed */
    protected $chunkOpen = false;

    /* Common properties for all functions pages */
    protected $bookName = "";
    protected $date = "";

    /* Current Chunk variables */
    protected $cchunk      = array();
    /* Default Chunk variables */
    private $dchunk      = array(
        "appendlater"           => false,
        "firstitem"             => false,
        "buffer"                => array(),
        "examplenumber"         => 0,
        "methodsynopsis"        => array(
            "params"            => array(),
            "firstsynopsis"     => true,
        ),
        "open"                  => false,
        "ooclass"               => null,
        "role"                  => null,
        "segtitle"              => array(),
        "segindex"              => 0,
        "funcname"              => array(),
        "firstrefname"          => true,
    );

    public function __construct() {
        parent::__construct();

        $this->registerFormatName("Generic Unix Manual Pages");
        $this->setExt(Config::ext() === null ? ".3.gz" : Config::ext());
        $this->setChunked(true);
        $this->cchunk = $this->dchunk;
    }

    public function update($event, $val = null) {
        switch($event) {
        case Render::CHUNK:
            switch($val) {
            case self::OPEN_CHUNK:
                if ($this->getFileStream()) {
                    /* I have an already open stream, back it up */
                    $this->pChunk = $this->cchunk;
                }
                $this->pushFileStream(fopen("php://temp/maxmemory", "r+"));
                $this->cchunk    = $this->dchunk;
                $this->chunkOpen = true;
                break;

            case self::CLOSE_CHUNK:
                $stream = $this->popFileStream();
                $this->writeChunk($stream);
                fclose($stream);
                /* Do I have a parent stream I need to resume? */
                if ($this->getFileStream()) {
                    $this->cchunk    = $this->pChunk;
                    $this->chunkOpen = true;
                } else {
                    $this->cchunk    = array();
                    $this->chunkOpen = false;
                }
                break;

            default:
                var_dump("Unknown action");
            }
            break;

        case Render::STANDALONE:
            if ($val) {
                $this->registerElementMap(self::getDefaultElementMap());
                $this->registerTextMap(self::getDefaultTextMap());
            } else {
                $this->registerElementMap(static::getDefaultElementMap());
                $this->registerTextMap(static::getDefaultTextMap());
            }
            break;

        case Render::INIT:
            $this->setOutputDir(Config::output_dir() . strtolower($this->toValidName($this->getFormatName())) . '/');
            if (file_exists($this->getOutputDir())) {
                if (!is_dir($this->getOutputDir())) {
                    v("Output directory is a file?", E_USER_ERROR);
                }
            } else {
                if (!mkdir($this->getOutputDir(), 0777, true)) {
                    v("Can't create output directory", E_USER_ERROR);
                }
            }
            break;
        case Render::VERBOSE:
        	v("Starting %s rendering", $this->getFormatName(), VERBOSE_FORMAT_RENDERING);
        	break;
        }
    }

    public function appendData($data) {
        if ($this->chunkOpen) {
            if (trim($data) === "") {
                return 0;
            }

            $streams = $this->getFileStream();
            $stream = end($streams);
            return fwrite($stream, $data);
        }

        return 0;
    }

    public function writeChunk($stream) {
        if (!isset($this->cchunk["funcname"][0])) {
            return;
        }

        $index = 0;
        rewind($stream);

        $filename = $this->cchunk["funcname"][$index] . $this->getExt();
        $gzfile = gzopen($this->getOutputDir() . $filename, "w9");

        gzwrite($gzfile, $this->header($index));
        gzwrite($gzfile, stream_get_contents($stream));
        gzclose($gzfile);
        v("Wrote %s", $this->getOutputDir() . $filename, VERBOSE_CHUNK_WRITING);

        /* methods/functions with the same name */
        while(isset($this->cchunk["funcname"][++$index])) {
            $filename = $this->cchunk["funcname"][$index] . $this->getExt();
            rewind($stream);
            // Replace the default function name by the alternative one
            $content = preg_replace('/'.$this->cchunk["funcname"][0].'/',
                $this->cchunk["funcname"][$index], stream_get_contents($stream), 1);

            $gzfile = gzopen($this->getOutputDir() . $filename, "w9");

            gzwrite($gzfile, $this->header($index));
            gzwrite($gzfile, $content);
            gzclose($gzfile);

            v("Wrote %s", $this->getOutputDir() . $filename, VERBOSE_CHUNK_WRITING);
        }
    }

    public function header($index) {
        return ".TH " . strtoupper($this->cchunk["funcname"][$index]) . " 3 \"" . $this->date . "\" \"PhD manpage\" \"" . $this->bookName . "\"" . "\n";
    }


    public function getChunkInfo() {
        return $this->cchunk;
    }

    public function getDefaultChunkInfo() {
        return $this->dchunk;
    }

    public function getDefaultElementMap() {
        return $this->elementmap;
    }

    public function getDefaultTextMap() {
        return $this->textmap;
    }


    public function format_chunk($open, $name, $attrs, $props) {
        if ($open) {
            $this->notify(Render::CHUNK, self::OPEN_CHUNK);
        } else {
            $this->notify(Render::CHUNK, self::CLOSE_CHUNK);
        }

        return false;
    }

    public function format_bookname($value, $tag) {
        $this->bookName = $value;
        return false;
    }

    public function format_suppressed_tags($open, $name, $attrs) {
        /* Ignore it */
        return "";
    }

    public function format_suppressed_text($value, $tag) {
        /* Suppress any content */
        return "";
    }

    public function format_refsect_text($value, $tag) {
        if (isset($this->cchunk["appendlater"]) && $this->cchunk["appendlater"] && isset($this->cchunk["buffer"]))
            array_push($this->cchunk["buffer"], strtoupper('"'.$value.'"'));
        else
            return strtoupper('"'.$value.'"');
    }

    public function format_refsect_title($open, $name, $attrs, $props) {
        if ($open) {
            if (isset($this->cchunk["appendlater"]) && $this->cchunk["appendlater"] && isset($this->cchunk["buffer"]))
                array_push($this->cchunk["buffer"], "\n.SH ");
            else return "\n.SH ";
        }
        return "";
    }

    public function format_refname($open, $name, $attrs, $props) {
        if ($open) {
            return (isset($this->cchunk["firstrefname"]) && $this->cchunk["firstrefname"]) ? false : "";
        }

        if (isset($this->cchunk["firstrefname"]) && $this->cchunk["firstrefname"]) {
            $this->cchunk["firstrefname"] = false;
            return false;
        }

        return "";
    }
    /*
    public function format_refname($open, $name, $attrs, $props) {
        if ($open) {
            return "\n.SH " . $this->autogen($name, $props["lang"]) . "\n";
        }
        return "";
    }
    */
    public function format_refname_text($value, $tag) {
        $this->cchunk["funcname"][] = $this->toValidName(trim($value));

        if (isset($this->cchunk["firstrefname"]) && $this->cchunk["firstrefname"]) {
            return false;
        }
        return "";
    }

    public function format_refpurpose($open, $name, $attrs, $props) {
        if ($open) {
            return " \- ";
        }
    }

    public function format_function_text($value, $tag) {
        return "\\fB" . $this->toValidName($value) . "\\fP(3)";
    }

    public function format_parameter_text($value, $tag) {
        return "\\fI" . ((isset($value[0]) && $value[0] == "$") ? "" : "$") . $value . "\\fP";
    }

    public function format_parameter_term_text($value, $tag) {
        return "\\fI$" . $value . "\\fP\n\-";
    }

    public function format_term($open, $name, $attrs, $props) {
        if ($open)
            return "";
        return "\n\-";
    }

    public function format_simplelist($open, $name, $attrs, $props) {
        if (isset($this->cchunk["role"]) && $this->cchunk["role"] == "seealso") {
            if ($open) {
                $this->cchunk["firstitem"] = true;
                return "";
            }
            return ".";
        } else {
            if ($open) {
                $this->cchunk["firstitem"] = true;
                return "\n.PP\n.RS\n";
            }
            return "\n.RE\n.PP\n";
        }
    }

    public function format_member($open, $name, $attrs, $props) {
        if ($open) {
            if (isset($this->cchunk["role"]) && $this->cchunk["role"] == "seealso") {
                if ($this->cchunk["firstitem"]) {
                    $ret = "";
                    $this->cchunk["firstitem"] = false;
                } else {
                    $ret = ", ";
                }
                return $ret;
            }

            if ($this->cchunk["firstitem"]) {
                $ret = "\n.TP 0.2i\n\\(bu";
                $this->cchunk["firstitem"] = false;
            } else {
                $ret = "\n.TP 0.2i\n\\(bu";
            }
            return $ret;
        }
        return "";
    }

    public function format_admonition($open, $name, $attrs, $props) {
        if ($open) {
            return "\n.PP\n\\fB" . $this->autogen($name, $props["lang"]) . "\\fR\n.RS\n";
        }
        return "\n.RE\n.PP\n";
    }

    public function format_example($open, $name, $attrs, $props) {
        if ($open && isset($this->cchunk["examplenumber"])) {
            return "\n.PP\n\\fB" . $this->autogen($name, $props["lang"]) . ++$this->cchunk["examplenumber"] . "\\fR\n.RS\n";
        }
        return "\n.RE\n";
    }

    public function format_itemizedlist($open, $name, $attrs, $props) {
        if ($open) {
            return "\n.PP\n.RS\n";
        }
        return "\n.RE\n.PP\n";
    }

    public function format_methodparam($open, $name, $attrs, $props) {
        if ($open) {
            $opt = isset($attrs[Reader::XMLNS_DOCBOOK]["choice"]) &&
                $attrs[Reader::XMLNS_DOCBOOK]["choice"] == "opt";
            $this->cchunk["methodsynopsis"]["params"][] = array(
                "optional" => $opt,
                "type" => "",
                "name" => "",
                "initializer" => "",
                "reference" => false,
            );
        }
        return "";
    }

    public function format_void($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["methodsynopsis"]["params"][] = array(
                "optional" => false,
                "type" => "void",
                "name" => "",
                "initializer" => "",
                "reference" => false,
            );
        }
        return "";
    }

    public function format_type_method_text($value, $tag) {
        $this->cchunk['methodsynopsis']['params'][count($this->cchunk['methodsynopsis']['params'])-1]['type'] = $value;
        return "";
    }

    public function format_parameter_method($open, $name, $attrs, $props) {
        if ($open && isset($attrs[Reader::XMLNS_DOCBOOK]["role"]) && $attrs[Reader::XMLNS_DOCBOOK]["role"] == "reference") {
            $this->cchunk['methodsynopsis']['params'][count($this->cchunk['methodsynopsis']['params'])-1]['reference'] = true;
        }
        return "";
    }

    public function format_parameter_method_text($value, $tag) {
        $this->cchunk['methodsynopsis']['params'][count($this->cchunk['methodsynopsis']['params'])-1]['name'] = $value;
        return "";
    }

    public function format_initializer_method_text($value, $tag) {
        $this->cchunk['methodsynopsis']['params'][count($this->cchunk['methodsynopsis']['params'])-1]['initializer'] = $value;
        return "";
    }

    public function format_refsynopsisdiv($open, $name, $attrs, $props) {
        if ($open && isset($this->cchunk["methodsynopsis"]["firstsynopsis"])
            && $this->cchunk["methodsynopsis"]["firstsynopsis"]) {
            return "\n.SH " . $this->autogen("refsynopsis", $props["lang"]) . "\n";
        }
        if (!$open && isset($this->cchunk["methodsynopsis"]["firstsynopsis"]))
            $this->cchunk["methodsynopsis"]["firstsynopsis"] = false;
        return "";
    }

    public function format_methodsynopsis($open, $name, $attrs, $props) {
        if ($open && isset($this->cchunk["methodsynopsis"]["firstsynopsis"])
            && $this->cchunk["methodsynopsis"]["firstsynopsis"] && $this->cchunk["appendlater"]) {
            $this->cchunk["appendlater"] = false;
            return "\n.SH " . $this->autogen("refsynopsis", $props["lang"]) . "\n";
        }
        if ($open)
            return "\n.br";
        $params = array();
        // write the formatted synopsis
        foreach ($this->cchunk['methodsynopsis']['params'] as $parameter) {
            array_push($params, ($parameter['optional'] ? "[" : "")
                        . $parameter['type'] . " "
                        . ($parameter['reference'] ? " \\fI&\\fP" : " ")
                        . ($parameter['name'] ? "\\fI$" . $parameter['name'] . "\\fP" : "")
                        . ($parameter['initializer'] ? " = " . $parameter['initializer'] : "")
                        . ($parameter['optional'] ? "]" : "") );
        }
        $ret = "\n(" . join($params, ", ") . ")";
        $this->cchunk['methodsynopsis']['params'] = array();

        // finally write what is in the buffer
        if (isset($this->cchunk["buffer"])) {
            $ret .= implode("", $this->cchunk["buffer"]);
            $this->cchunk["buffer"] = array();
        }
        if (isset($this->cchunk["methodsynopsis"]["firstsynopsis"]))
            $this->cchunk["methodsynopsis"]["firstsynopsis"] = false;
        return $ret;
    }

    public function format_xref($open, $name, $attrs, $props) {
        if ($props['empty'])
            return "\"" . Format::getShortDescription($attrs[Reader::XMLNS_DOCBOOK]["linkend"]) . "\"";
        return "";
    }

    public function format_verbatim($open, $name, $attrs, $props) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $this->role = $attrs[Reader::XMLNS_DOCBOOK]["role"];
            } else {
                $this->role = false;
            }

            return "\n.PP\n.nf";
        }
        return "\n.fi";
    }

    public function format_verbatim_text($value, $tag) {
        return trim($value);
    }

    public function format_refsect($open, $name, $attrs, $props) {
        if ($open && isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
            $this->cchunk["role"] = $attrs[Reader::XMLNS_DOCBOOK]["role"];
            if ($this->cchunk["role"] == "description") {
                $this->cchunk["appendlater"] = true;
            }
        }
        if (!$open)
            $this->cchunk["role"] = null;
        return "";
    }

    // Returns the unformatted value without whitespaces (nor new lines)
    public function format_text($value, $tag) {
        return trim(preg_replace('/[ \n\t]+/', ' ', $value));
    }

    public function format_tgroup($open, $name, $attrs, $props) {
        if ($open) {
            $nbCols = $attrs[Reader::XMLNS_DOCBOOK]["cols"];
            $ret = "\n.TS\nbox, tab (|);\n";
            for ($i = 0; $i < $nbCols; $i++)
                $ret .= "c | ";
            $ret .= ".";
            return $ret;
        }
        return "\n.TE\n.PP\n";
    }

    public function format_thead($open, $name, $attrs, $props) {
        if ($open) {
            return "";
        }
        return "\n=";
    }

    public function format_row($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["firstitem"] = true;
            return "\n";
        }
        return "";
    }

    public function format_entry($open, $name, $attrs, $props) {
        if ($open) {
            if ($this->cchunk["firstitem"]) {
                $this->cchunk["firstitem"] = false;
                return "\nT{\n";
            }
            return "\n|T{\n";
        }
        return "\n\nT}";
    }

    public function format_segmentedlist($open, $name, $attrs, $props) {
        if ($open) {
            return "\n.P\n";
        }
        $this->cchunk["segtitle"] = array();
        return "\n";
    }

    public function format_seglistitem($open, $name, $attrs, $props) {
        if ($open && isset($this->cchunk["segindex"]))
            $this->cchunk["segindex"] = 0;
        return "";
    }

    public function format_seg($open, $name, $attrs, $props) {
        if (! (isset($this->cchunk["segtitle"]) && isset($this->cchunk["segtitle"][$this->cchunk["segindex"]])) )
            return "";
        if ($open) {
            return "\n.br\n\\fB" . $this->cchunk["segtitle"][$this->cchunk["segindex"]] . ":\\fR";
        }
        $this->cchunk["segindex"]++;
        return "";
    }

    public function format_segtitle_text($value, $tag) {
        if (isset($this->cchunk["segtitle"]))
            $this->cchunk["segtitle"][] = $value;
        return "";
    }

    public function format_manvolnum($open, $name, $attrs, $props) {
        if ($open) {
            return "(";
        }
        return ")";
    }

    public function format_ooclass_name_text($value, $tag) {
        return "\n.br\n\\fI" . $value . "\\fP";
    }

    public function format_indent($open, $name, $attrs, $props) {
        if ($open) {
            return "\n.PP\n.RS\n";
        }
        return "\n.RE\n.PP\n";
    }

    public function format_tag_text($value, $tag) {
        return "<" . $value . ">";
    }

    // Convert the function name to a Unix valid filename
    public function toValidName($functionName) {
        return str_replace(array("::", "->", "()", " ", '$', '/', '\\'), array(".", ".", "", "-", "", "", ""), $functionName);
    }

    public function format_mediaobject($open, $name, $attrs, $props) {
        if ($open) {
            return "\\fB" . $this->autogen($name, $props["lang"]) . "\\fP";
        }
        return "";
    }

    public function format_pubdate_text($value, $tag) {
        $this->date = $value;
        return false;
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

