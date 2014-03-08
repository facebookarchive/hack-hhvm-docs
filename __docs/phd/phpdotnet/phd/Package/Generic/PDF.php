<?php
namespace phpdotnet\phd;
/* $Id$ */

abstract class Package_Generic_PDF extends Format_Abstract_PDF {
    private $elementmap = array( /* {{{ */
        'abstract'              => 'format_suppressed_tags',
        'abbrev'                => 'format_suppressed_tags',
        'acronym'               => 'format_suppressed_tags',
        'alt'                   => 'format_suppressed_tags',
        'application'           => 'format_suppressed_tags',
        'author'                => array(
            /* DEFAULT */          'format_newline',
            'authorgroup'       => 'format_authorgroup_author',
        ),
        'authorgroup'           => 'format_shifted_para',
        'blockquote'            => 'format_framed_block',
        'book'                  => 'format_suppressed_tags',
        'callout'               => 'format_callout',
        'calloutlist'           => 'format_calloutlist',
        'caution'               => 'format_admonition',
        'citerefentry'          => 'format_suppressed_tags',
        'classname'             => array(
            /* DEFAULT */          'format_suppressed_tags',
            'ooclass'           => array(
                /* DEFAULT */      'format_bold',
                'classsynopsisinfo' => 'format_suppressed_tags',
            ),
        ),
        'co'                    => 'format_co',
        'command'               => 'format_italic',
        'computeroutput'        => 'format_suppressed_tags',
        'constant'              => 'format_bold',
        'code'                  => 'format_verbatim_inline',
        'copyright'             => 'format_copyright',
        'editor'                => 'format_editor',
        'example'               => 'format_example',
        'emphasis'              => 'format_italic',
        'envar'                 => 'format_suppressed_tags',
        'errortype'             => 'format_suppressed_tags',
        'figure'                => 'format_suppressed_tags',
        'filename'              => 'format_italic',
        'firstname'             => 'format_suppressed_tags',
        'formalpara'            => 'format_para',
        'footnote'              => 'format_footnote',
        'footnoteref'           => 'format_footnoteref',
        'funcdef'               => 'format_bold',
        'function'              => 'format_suppressed_tags',
        'glossterm'             => 'format_suppressed_tags',
        'holder'                => 'format_suppressed_tags',
        'imagedata'             => 'format_imagedata',
        'imageobject'           => 'format_shifted_para',
        'index'                 => 'format_para',
        'indexdiv'              => 'format_para',
        'indexentry'            => 'format_shifted_line',
        'info'                  => 'format_suppressed_tags',
        'informalexample'       => 'format_para',
        'itemizedlist'          => 'format_shifted_para',
        'legalnotice'           => 'format_suppressed_tags',
        'link'                  => 'format_link',
        'listitem'              => array(
            /* DEFAULT */          'format_listitem',
            'varlistentry'      => 'format_shifted_para',
        ),
        'literal'               => 'format_italic',
        'literallayout'         => 'format_verbatim_inline',
        'manvolnum'             => 'format_manvolnum',
        'mediaobject'           => 'format_suppressed_tags',
        'member'                => 'format_member',
        'note'                  => 'format_admonition',
        'option'                => 'format_italic',
        'optional'              => 'format_suppressed_tags',
        'orderedlist'           => 'format_shifted_para',
        'othercredit'           => 'format_newline',
        'othername'             => 'format_suppressed_tags',
        'para'                  => array(
            /* DEFAULT */          'format_para',
            'callout'           => 'format_suppressed_tags',
            'listitem'          => 'format_suppressed_tags',
            'step'              => 'format_suppressed_tags',
        ),
        'partintro'             => 'format_para',
        'personname'            => 'format_suppressed_tags',
        'preface'               => 'format_suppressed_tags',
        'primaryie'             => 'format_suppressed_tags',
        'procedure'             => 'format_procedure',
        'productname'           => 'format_suppressed_tags',
        'programlisting'        => 'format_verbatim_block',
        'property'              => array(
            /* DEFAULT */          'format_suppressed_tags',
            'classsynopsisinfo' => 'format_italic',
        ),
        'pubdate'               => 'format_para',
        'quote'                 => 'format_suppressed_tags',
        'refentrytitle'         => 'format_bold',
        'refname'               => 'format_title',
        'refnamediv'            => 'format_suppressed_tags',
        'refpurpose'            => 'format_refpurpose',
        'refsect1'              => 'format_suppressed_tags',
        'refsection'            => 'format_refsection', // DUMMY REFSECTION DELETION
        'refsynopsisdiv'        => 'format_para',
        'replaceable'           => 'format_italic',
        'screen'                => 'format_verbatim_block',
        'section'               => 'format_suppressed_tags',
        'sect1'                 => 'format_suppressed_tags',
        'sect2'                 => 'format_suppressed_tags',
        'sect3'                 => 'format_suppressed_tags',
        'sect4'                 => 'format_suppressed_tags',
        'sect5'                 => 'format_suppressed_tags',
        'seg'                   => 'format_seg',
        'segmentedlist'         => 'format_segmentedlist',
        'seglistitem'           => 'format_seglistitem',
        'segtitle'              => 'format_suppressed_tags',
        'set'                   => 'format_suppressed_tags',
        'simpara'               => array(
            /* DEFAULT */          'format_para',
            'callout'           => 'format_suppressed_tags',
            'listitem'          => 'format_suppressed_tags',
            'step'              => 'format_suppressed_tags',
        ),
        'simplelist'            => 'format_shifted_para',
        'simplesect'            => 'format_suppressed_tags',
        'step'                  => 'format_suppressed_tags',
        'subscript'             => 'format_indice',
        'superscript'           => 'format_indice',
        'surname'               => 'format_suppressed_tags',
        'synopsis'              => 'format_verbatim_block',
        'systemitem'            => 'format_verbatim_inline',
        'tag'                   => 'format_verbatim_inline',
        'term'                  => 'format_suppressed_tags',
        'title'                 => array(
            /* DEFAULT */          'format_title',
            'example'           => 'format_example_title',
            'formalpara'        => 'format_title3',
            'info'              => array(
                /* DEFAULT */      'format_title',
                'example'       => 'format_example_title',
                'note'          => 'format_title3',
                'table'         => 'format_title3',
                'informaltable' => 'format_title3',
                'warning'           => 'format_title3',
            ),
            'informaltable'     => 'format_title3',
            'legalnotice'       => 'format_title2',
            'note'              => 'format_title3',
            'preface'           => 'format_title',
            'procedure'         => 'format_bold',
            'refsect1'          => 'format_title2',
            'refsect2'          => 'format_title3',
            'refsect3'          => 'format_title3',
            'section'           => 'format_title2',
            'sect1'             => 'format_title2',
            'sect2'             => 'format_title3',
            'sect3'             => 'format_title3',
            'sect4'             => 'format_title3',
            'segmentedlist'     => 'format_bold',
            'table'             => 'format_title3',
            'variablelist'      => 'format_bold',
            'warning'           => 'format_title3',
        ),
        'tip'                   => 'format_admonition',
        'titleabbrev'           => 'format_suppressed_tags',
        'type'                  => 'format_suppressed_tags',
        'userinput'             => 'format_bold',
        'variablelist'          => 'format_suppressed_tags',
        /* hack for phpdoc:varentry */
        'phpdoc:varentry'       => 'format_suppressed_tags',
        'varlistentry'          => 'format_newline',
        'varname'               => 'format_italic',
        'warning'               => 'format_admonition',
        'xref'                  => 'format_link',
        'year'                  => 'format_suppressed_tags',
        // TABLES
        'informaltable'         => 'format_table',
        'table'                 => 'format_table',
        'tgroup'                => 'format_tgroup',
        'colspec'               => 'format_colspec',
        'spanspec'              => 'format_suppressed_tags',
        'thead'                 => 'format_thead',
        'tbody'                 => 'format_suppressed_tags',
        'row'                   => 'format_row',
        'entry'                 => array (
            /* DEFAULT */          'format_entry',
            'row'               => array(
                /* DEFAULT */      'format_entry',
                'thead'         => 'format_th_entry',
                'tfoot'         => 'format_th_entry',
                'tbody'         => 'format_entry',
            ),
        ),
        // SYNOPSISES & OO STUFF
        'void'                  => 'format_void',
        'methodname'            => 'format_bold',
        'methodparam'           => 'format_methodparam',
        'methodsynopsis'        => 'format_methodsynopsis',
        'parameter'             => array(
            /* DEFAULT */          'format_parameter',
            'methodparam'       => 'format_methodparam_parameter',
        ),
        'interfacename'         => 'format_suppressed_tags',
        'ooclass'               => array(
            /* DEFAULT */          'format_suppressed_tags',
            'classsynopsis'     => 'format_framed_para',
        ),
        'oointerface'           => array(
            /* DEFAULT */          'format_suppressed_tags',
            'classsynopsisinfo'    => 'format_classsynopsisinfo_oointerface',
        ),
        'classsynopsis'         => 'format_classsynopsis',
        'classsynopsisinfo'     => 'format_classsynopsisinfo',
        'fieldsynopsis'         => array(
            /* DEFAULT */          'format_fieldsynopsis',
            'entry'             => 'format_para',
        ),
        'modifier'              => 'format_suppressed_tags',
        'constructorsynopsis'   => 'format_methodsynopsis',
        'destructorsynopsis'    => 'format_methodsynopsis',
        'initializer'           => 'format_initializer',
        // FAQ
        'qandaset'              => 'format_para',
        'qandaentry'            => 'format_para',
        'question'              => 'format_bold',
        'answer'                => 'format_shifted_para',

    ); /* }}} */

    private $textmap = array(
        'function'              => 'format_function_text',
        'link'                  => 'format_link_text',
        'quote'                 => 'format_quote_text',
        'refname'               => 'format_refname_text',
        'titleabbrev'           => 'format_suppressed_text',
        'segtitle'              => 'format_segtitle_text',
        'modifier'             => array(
            /* DEFAULT */         false,
            'fieldsynopsis'    => 'format_fieldsynopsis_modifier_text',
        ),
        'methodname'           => array(
            /* DEFAULT */         false,
            'constructorsynopsis' => array(
                /* DEFAULT */     false,
                'classsynopsis' => 'format_classsynopsis_methodsynopsis_methodname_text',
            ),
            'methodsynopsis'    => array(
                /* DEFAULT */     false,
                'classsynopsis' => 'format_classsynopsis_methodsynopsis_methodname_text',
            ),
            'destructorsynopsis' => array(
                /* DEFAULT */     false,
                'classsynopsis' => 'format_classsynopsis_methodsynopsis_methodname_text',
            ),
        ),
    );

    protected $lang = "";

    /* Current Chunk variables */
    private $cchunk      = array();
    /* Default Chunk variables */
    private $dchunk      = array(
        "xml-base"              => "",
        "refsection"            => false,
        "examplenumber"         => 0,
        "href"                  => "",
        "is-xref"               => false,
        "linkend"               => "",
        "links-to-resolve"      => array(
            /* $id => array( $target ), */
        ),
        "refname"               => "",
        "table"                 => false,
        "verbatim-block"        => false,
        "segmentedlist"         => array(
            "seglistitem"       => 0,
            "segtitle"          => array(
            ),
        ),
        "classsynopsis"         => array(
            "close"             => false,
            "classname"         => false,
        ),
        "classsynopsisinfo"     => array(
            "implements"        => false,
            "ooclass"           => false,
        ),
        "fieldsynopsis"         => array(
            "modifier"          => "public",
        ),
        "footnote"              => array(
        ),
        "tablefootnotes"        => array(
        ),
        "footrefs"              => array(),
        "co"                    => 0,
        "corefs"                => array(),
        "callouts"              => 0,
    );

    public function __construct() {
        parent::__construct();
        $this->setExt(Config::ext() === null ? ".pdf" : Config::ext());
        $this->pdfDoc = new PdfWriter();
    }

    public function __destruct() {
        unset($this->pdfDoc);
    }

    public function getChunkInfo($info) {
        if (isset($this->cchunk[$info]))
            return $this->cchunk[$info];
        else return null;
    }

    public function setChunkInfo($info, $value) {
        $this->cchunk[$info] = $value;
    }

    public function getDefaultChunkInfo() {
        return $this->dchunk;
    }

    public function getDefaultElementmap() {
        return $this->elementmap;
    }

    public function getDefaultTextmap() {
        return $this->textmap;
    }

    public function TEXT($str) {
        if (isset($this->cchunk["refsection"]) && $this->cchunk["refsection"]) // DUMMY REFSECTION DELETION
            return "";

        if (isset($this->cchunk["verbatim-block"]) && $this->cchunk["verbatim-block"]) {
            $this->pdfDoc->appendText(utf8_decode($str));
            return "";
        }

        $ret = utf8_decode(trim(preg_replace('/[ \n\t]+/', ' ', $str)));
        // No whitespace if current text value begins with ',', ';', ':', '.'
        if (strncmp($ret, ",", 1) && strncmp($ret, ";", 1) && strncmp($ret, ":", 1) && strncmp($ret, ".", 1))
            $this->pdfDoc->appendText(" " . $ret);
        else $this->pdfDoc->appendText($ret);
        return "";
    }

    public function format_suppressed_tags($open, $name) {
        /* Ignore it */
        return "";
    }

    public function format_suppressed_text($value, $tag) {
        /* Suppress any content */
        return "";
    }

    public function newChunk() {
        $this->cchunk = $this->dchunk;
    }

    // DUMMY REFSECTION DELETION
    public function format_refsection($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["refsection"] = true;
        } else {
            $this->cchunk["refsection"] = false;
        }
        return "";
    }

    public function format_para($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::PARA);
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
        }
        return "";
    }

    public function format_shifted_para($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->shift();
            $this->pdfDoc->add(PdfWriter::PARA);
        } else {
            $this->pdfDoc->unshift();
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
        }
        return "";
    }

    public function format_shifted_line($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->shift();
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
        } else {
            $this->pdfDoc->unshift();
        }
        return "";
    }

    public function format_title($open, $name, $attrs, $props) {
        if ($props["empty"]) return '';
        if ($open) {
            $this->pdfDoc->add(PdfWriter::TITLE);
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->revertFont();
        }
        return "";
    }

    public function format_title2($open, $name, $attrs, $props) {
        if ($props["empty"]) return '';
        if ($open) {
            $this->pdfDoc->add(PdfWriter::TITLE2);
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->revertFont();
        }
        return "";
    }

    public function format_title3($open, $name, $attrs, $props) {
        if ($props["empty"]) return '';
        if ($open) {
            $this->pdfDoc->add(PdfWriter::TITLE3);
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->revertFont();
        }
        return "";
    }

    public function format_bold($open, $name, $attrs, $props) {
        if ($props["empty"]) return '';
        if ($open) {
            $this->pdfDoc->setFont(PdfWriter::FONT_BOLD);
        } else {
            $this->pdfDoc->revertFont();
        }
        return "";
    }

    public function format_italic($open, $name, $attrs, $props) {
        if ($props["empty"]) return '';
        if ($open) {
            $this->pdfDoc->setFont(PdfWriter::FONT_ITALIC);
        } else {
            $this->pdfDoc->revertFont();
        }
        return "";
    }

    public function format_admonition($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::ADMONITION);
            $this->pdfDoc->appendText($this->autogen($name, $props["lang"]));
            $this->pdfDoc->add(PdfWriter::ADMONITION_CONTENT);
        } else {
            $this->pdfDoc->add(PdfWriter::END_ADMONITION);
        }
        return "";
    }

    public function format_example($open, $name, $attrs, $props) {
        if ($open) {
            $this->lang = $props["lang"];
            $this->cchunk["examplenumber"]++;
            $this->pdfDoc->add(PdfWriter::ADMONITION);

        } else {
            $this->pdfDoc->add(PdfWriter::END_ADMONITION);
        }
        return "";
    }

    public function format_example_title($open, $name, $attrs, $props) {
        if ($props["empty"]) {
            $this->pdfDoc->appendText($this->autogen("example", $this->lang) .
                $this->cchunk["examplenumber"]);
            $this->pdfDoc->add(PdfWriter::ADMONITION_CONTENT);
        } elseif ($open) {
            $this->pdfDoc->appendText($this->autogen("example", $this->lang) .
                $this->cchunk["examplenumber"] . " -");
        } else {
            $this->pdfDoc->add(PdfWriter::ADMONITION_CONTENT);
        }
        return "";
    }

    public function format_newpage($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::PAGE);
        }
        return "";
    }

    public function format_verbatim_block($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["verbatim-block"] = true;
            $this->pdfDoc->add(PdfWriter::VERBATIM_BLOCK);
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->revertFont();
            $this->cchunk["verbatim-block"] = false;
        }
        return "";
    }

    public function format_verbatim_inline($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->setFont(PdfWriter::FONT_VERBATIM, 10);
        } else {
            $this->pdfDoc->revertFont();
        }
        return "";
    }

    public function format_framed_block($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::FRAMED_BLOCK);
        } else {
            $this->pdfDoc->add(PdfWriter::END_FRAMED_BLOCK);
        }
        return "";
    }

    public function format_framed_para($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::FRAMED_BLOCK);
            $this->format_para($open, $name, $attrs, $props);
        } else {
            $this->format_para($open, $name, $attrs, $props);
            $this->pdfDoc->add(PdfWriter::END_FRAMED_BLOCK);
        }
        return "";
    }

    public function format_newline($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
        }
    }

    public function format_link($open, $name, $attrs, $props) {
        if ($open && ! $props["empty"]) {
            $this->pdfDoc->setFont(PdfWriter::FONT_NORMAL, 12, array(0, 0, 1)); // blue
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["linkend"])) {
                $this->cchunk["linkend"] = $attrs[Reader::XMLNS_DOCBOOK]["linkend"];
            } elseif(isset($attrs[Reader::XMLNS_XLINK]["href"])) {
                $this->cchunk["href"] = $attrs[Reader::XMLNS_XLINK]["href"];
            }
        } elseif ($open && $name == "xref" && isset($attrs[Reader::XMLNS_DOCBOOK]["linkend"])
            && $linkend = $attrs[Reader::XMLNS_DOCBOOK]["linkend"]) {
            $this->cchunk["linkend"] = $linkend;
            $this->pdfDoc->setFont(PdfWriter::FONT_NORMAL, 12, array(0, 0, 1)); // blue
            $this->format_link_text(Format::getShortDescription($linkend), $name);
            $this->pdfDoc->revertFont();
            $this->cchunk["linkend"] = "";
        } elseif (!$open) {
            $this->cchunk["href"] = "";
            $this->cchunk["linkend"] = "";
            $this->pdfDoc->revertFont();
        }
        return "";
    }

    public function format_link_text($value, $tag) {
        $value = trim(preg_replace('/[ \n\t]+/', ' ', $value));
        if (isset($this->cchunk["href"]) && $this->cchunk["href"]) {
            $this->pdfDoc->add(PdfWriter::URL_ANNOTATION, array(chr(187) . chr(160) . $value, $this->cchunk["href"])); // links with >> symbol
        } elseif (isset($this->cchunk["linkend"]) && $linkend = $this->cchunk["linkend"]) {
            $linkAreas = $this->pdfDoc->add(PdfWriter::LINK_ANNOTATION, $value);
            if (!isset($this->cchunk["links-to-resolve"][$linkend]))
                $this->cchunk["links-to-resolve"][$linkend] = array();
            foreach ($linkAreas as $area)
                $this->cchunk["links-to-resolve"][$linkend][] = $area;
        }
        return "";
    }

    public function format_function_text($value, $tag, $display_value = null) {
        $value = trim(preg_replace('/[ \n\t]+/', ' ', $value));
        if ($display_value === null) {
            $display_value = $value;
        }

        $ref = strtolower(str_replace(array("_", "::", "->"), array("-", "-", "-"), $value));
        if (($linkend = $this->getRefnameLink($ref)) !== null) {
            $this->pdfDoc->setFont(PdfWriter::FONT_NORMAL, 12, array(0, 0, 1)); // blue
            $linkAreas = $this->pdfDoc->add(PdfWriter::LINK_ANNOTATION, $display_value.($tag == "function" ? "()" : ""));
            if (!isset($this->cchunk["links-to-resolve"][$linkend]))
                $this->cchunk["links-to-resolve"][$linkend] = array();
            foreach ($linkAreas as $area)
                $this->cchunk["links-to-resolve"][$linkend][] = $area;
        } else {
            $this->pdfDoc->setFont(PdfWriter::FONT_BOLD);
            $this->pdfDoc->appendText(" " . $display_value.($tag == "function" ? "()" : ""));
        }
        $this->pdfDoc->revertFont();
        return "";
    }

    public function format_authorgroup_author($open, $name, $attrs, $props) {
        if ($open) {
            if ($props["sibling"] !== $name) {
                $this->pdfDoc->setFont(PdfWriter::FONT_BOLD);
                $this->pdfDoc->appendText($this->autogen("by", $props["lang"]));
                $this->pdfDoc->revertFont();
            }
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
        }
        return "";
    }

    public function format_editor($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->setFont(PdfWriter::FONT_BOLD);
            $this->pdfDoc->appendText($this->autogen("editedby", $props["lang"]));
            $this->pdfDoc->revertFont();
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
        }
        return "";
    }

    public function format_copyright($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::PARA);
            $this->pdfDoc->appendText(utf8_decode("©"));
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
        }
        return "";
    }

    // Lists {{{
    public function format_listitem($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->add(PdfWriter::ADD_BULLET);
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP, 0.5);
        }
        return "";
    }

    public function format_procedure($open, $name, $attrs, $props) {
        $this->cchunk["step"] = 0;
        return $this->format_shifted_para($open, $name, $attrs, $props);
    }

    public function format_step($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->add(PdfWriter::ADD_NUMBER_ITEM, (++$this->cchunk["step"]).".");
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP, 0.5);
        }
        return "";
    }

    public function format_member($open, $name, $attrs, $props) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->add(PdfWriter::ADD_BULLET);
        }
        return "";
    }

    public function format_segmentedlist($open, $name, $attrs, $props) {
        $this->cchunk["segmentedlist"] = $this->dchunk["segmentedlist"];
        return $this->format_para($open, $name, $attrs, $props);
    }

    public function format_segtitle_text($value, $tag) {
        $this->cchunk["segmentedlist"]["segtitle"][count($this->cchunk["segmentedlist"]["segtitle"])] = $value;
        return '';
    }
    public function format_seglistitem($open, $name, $attrs) {
        if ($open) {
            $this->cchunk["segmentedlist"]["seglistitem"] = 0;
        }
        return '';
    }
    public function format_seg($open, $name, $attrs) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->pdfDoc->setFont(PdfWriter::FONT_BOLD);
            $this->pdfDoc->appendText($this->cchunk["segmentedlist"]["segtitle"][$this->cchunk["segmentedlist"]["seglistitem"]++].":");
            $this->pdfDoc->revertFont();
        }
        return '';
    }
    // }}} Lists

    // Tables {{{
    public function format_table($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["table"] = true;
            $this->pdfDoc->add(PdfWriter::PARA);
        } else {
            $this->cchunk["table"] = false;
            $this->pdfDoc->add(PdfWriter::END_TABLE);

            if ($this->cchunk["tablefootnotes"]) {
                $this->pdfDoc->add(PdfWriter::FRAMED_BLOCK);
                $this->pdfDoc->add(PdfWriter::LINE_JUMP);
                $this->pdfDoc->appendBufferNow();
                foreach ($this->cchunk["footrefs"] as $ref)
                    foreach ($ref as $area)
                        $this->pdfDoc->resolveInternalLink($area[0], array($area[1], $area[2], $area[3], $area[4]), $this->pdfDoc->getCurrentPage());
                $this->pdfDoc->add(PdfWriter::END_FRAMED_BLOCK, array(2)); // With Dash line
                $this->cchunk["tablefootnotes"] = $this->dchunk["tablefootnotes"];
            }
        }
        return "";
    }

    public function format_colspec($open, $name, $attrs) {
        if ($open) {
            Format::colspec($attrs[Reader::XMLNS_DOCBOOK]);
        }
        return "";
    }
    public function format_thead($open, $name, $attrs) {
        if ($open) {
            $this->pdfDoc->setFont(PdfWriter::FONT_BOLD);
        } else {
            $this->pdfDoc->revertFont();
        }
        return "";
    }

    public function format_row($open, $name, $attrs) {
        if ($open) {
            Format::initRow();
            $valign = isset($attrs[Reader::XMLNS_DOCBOOK]['valign'])
                      ? $attrs[Reader::XMLNS_DOCBOOK]['valign'] : 'middle';
            $colCount = Format::getColCount();
            $this->pdfDoc->add(PdfWriter::TABLE_ROW, array($colCount, $valign));
        } else {
            $this->pdfDoc->add(PdfWriter::TABLE_END_ROW);
        }
        return "";
    }
    public function format_th_entry($open, $name, $attrs, $props) {
        $align = (isset($attrs["align"]) ? $attrs["align"] : "center");
        if ($props["empty"]) {
            $this->pdfDoc->add(PdfWriter::TABLE_ENTRY, array(1, 1, $align));
            $this->pdfDoc->add(PdfWriter::TABLE_END_ENTRY);
        }
        if ($open) {
            $dbattrs = Format::getColspec($attrs[Reader::XMLNS_DOCBOOK]);
            $align = (isset($dbattrs["align"]) ? $dbattrs["align"] : $align);
            $colspan = Format::colspan($attrs[Reader::XMLNS_DOCBOOK]);
            $this->pdfDoc->add(PdfWriter::TABLE_ENTRY, array($colspan, 1, $align));
        } else {
            $this->pdfDoc->add(PdfWriter::TABLE_END_ENTRY);
        }
        return "";
    }
    public function format_entry($open, $name, $attrs, $props) {
        $align = (isset($attrs["align"]) ? $attrs["align"] : "left");
        if ($props["empty"]) {
            $this->pdfDoc->add(PdfWriter::TABLE_ENTRY, array(1, 1, $align));
            $this->pdfDoc->add(PdfWriter::TABLE_END_ENTRY);
            return;
        }
        if ($open) {
            $dbattrs = Format::getColspec($attrs[Reader::XMLNS_DOCBOOK]);
            $align = (isset($dbattrs["align"]) ? $dbattrs["align"] : $align);
            $retval = "";
            if (isset($dbattrs["colname"])) {
                for($i=Format::getEntryOffset($dbattrs); $i>0; --$i) {
                    $this->pdfDoc->add(PdfWriter::TABLE_ENTRY, array(1, 1, $align));
                    $this->pdfDoc->add(PdfWriter::TABLE_END_ENTRY);
                }
            }

            /*
             * "colspan" is *not* an standard prop, only used to overwrite the
             * colspan for <footnote>s in tables
             */
            if (isset($props["colspan"])) {
                $colspan = $props["colspan"];
            } else {
                $colspan = Format::colspan($dbattrs);
            }

            $rowspan = Format::rowspan($dbattrs);
            $this->pdfDoc->add(PdfWriter::TABLE_ENTRY, array($colspan, $rowspan, $align));
        } else {
            $this->pdfDoc->add(PdfWriter::TABLE_END_ENTRY);
        }
        return "";
    }

    public function format_tgroup($open, $name, $attrs, $props) {
        if ($open) {
            Format::tgroup($attrs[Reader::XMLNS_DOCBOOK]);
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["cols"]))
                $this->pdfDoc->add(PdfWriter::TABLE, $attrs[Reader::XMLNS_DOCBOOK]["cols"]);
        } else {
        }
        return "";
    }
    // }}} Tables

    // Synopsises {{{
    public function format_methodsynopsis($open, $name, $attrs, $props) {
        if ($open) {
            $this->params = array("count" => 0, "opt" => 0, "content" => "");
            return $this->format_para($open, $name, $attrs, $props);
        }
        $content = "";
        if ($this->params["opt"]) {
            $content = str_repeat(" ]", $this->params["opt"]);
        }
        $content .= " )";

        $this->pdfDoc->appendText($content);
        return $this->format_para($open, $name, $attrs, $props);
    }

    public function format_classsynopsis_methodsynopsis_methodname_text($value, $tag) {
        $value = $this->TEXT($value);
        if ($this->cchunk["classsynopsis"]["classname"] === false) {
            $this->pdfDoc->appendText($value);
            return '';
        }
        if (strpos($value, '::')) {
            $explode = '::';
        } elseif (strpos($value, '->')) {
            $explode = '->';
        } else {
            $this->pdfDoc->appendText($value);
            return '';
        }

        list($class, $method) = explode($explode, $value);
        if ($class !== $this->cchunk["classsynopsis"]["classname"]) {
            $this->pdfDoc->appendText($value);
            return '';
        }
        $this->pdfDoc->appendText($method);
        return '';
    }

    public function format_methodparam_parameter($open, $name, $attrs, $props) {
        if ($props["empty"]) return '';
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $this->pdfDoc->setFont(PdfWriter::FONT_VERBATIM, 10);
                $this->pdfDoc->appendText(" &$");
                return '';
            }
            $this->pdfDoc->setFont(PdfWriter::FONT_VERBATIM, 10);
            $this->pdfDoc->appendText(" $");
            return '';
        }
        $this->pdfDoc->revertFont();
        return '';
    }

    public function format_parameter($open, $name, $attrs, $props) {
        if ($props["empty"]) return '';
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $this->pdfDoc->setFont(PdfWriter::FONT_VERBATIM_ITALIC, 10);
                $this->pdfDoc->appendText(" &");
                return '';
            }
            $this->pdfDoc->setFont(PdfWriter::FONT_VERBATIM_ITALIC, 10);
            return '';
        }
        $this->pdfDoc->revertFont();
        return '';
    }

    public function format_methodparam($open, $name, $attrs) {
        if ($open) {
            $content = '';
                if ($this->params["count"] == 0) {
                    $content .= " (";
                }
                if (isset($attrs[Reader::XMLNS_DOCBOOK]["choice"]) && $attrs[Reader::XMLNS_DOCBOOK]["choice"] == "opt") {
                    $this->params["opt"]++;
                    $content .= " [";
                } else if($this->params["opt"]) {
                    $content .= str_repeat(" ]", $this->params["opt"]);
                    $this->params["opt"] = 0;
                }
                if ($this->params["count"]) {
                    $content .= ",";
                }
                $content .= '';
                ++$this->params["count"];
                $this->pdfDoc->appendText($content);
                return '';
        }
        return '';
    }

    public function format_void($open, $name, $attrs) {
        $this->pdfDoc->appendText(" ( void");
        return '';
    }

    public function format_classsynopsisinfo($open, $name, $attrs, $props) {
        $this->cchunk["classsynopsisinfo"] = $this->dchunk["classsynopsisinfo"];
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"]) && $attrs[Reader::XMLNS_DOCBOOK]["role"] == "comment") {
                $this->format_para($open, $name, $attrs, $props);
                $this->pdfDoc->appendText("/* ");
                return '';
            }
            return $this->format_para($open, $name, $attrs, $props);
        }

        if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"]) && $attrs[Reader::XMLNS_DOCBOOK]["role"] == "comment") {
            $this->pdfDoc->appendText(" */");
            return $this->format_para($open, $name, $attrs, $props);
        }
        $this->cchunk["classsynopsis"]["close"] = true;
        $this->pdfDoc->appendText(" {");
        $this->pdfDoc->shift();
        return $this->format_para($open, $name, $attrs, $props);
    }

    public function format_classsynopsisinfo_oointerface($open, $name, $attrs) {
        if ($open) {
            if ($this->cchunk["classsynopsisinfo"]["implements"] === false) {
                $this->cchunk["classsynopsisinfo"]["implements"] = true;
                $this->pdfDoc->appendText(" implements");
                return '';
            }
            $this->pdfDoc->appendText(",");
            return '';
        }
        return '';
    }

    public function format_classsynopsis($open, $name, $attrs, $props) {
        if ($open) {
            return $this->format_para($open, $name, $attrs, $props);
        }

        if ($this->cchunk["classsynopsis"]["close"] === true) {
            $this->cchunk["classsynopsis"]["close"] = false;
            $this->pdfDoc->unshift();
            $this->pdfDoc->appendText("}");
        }
        return $this->format_para($open, $name, $attrs, $props);
    }

    public function format_fieldsynopsis_modifier_text($value, $tag) {
        $this->cchunk["fieldsynopsis"]["modifier"] = trim($value);
        $this->pdfDoc->appendText($this->TEXT($value));
        return '';
    }

    public function format_fieldsynopsis($open, $name, $attrs, $props) {
        $this->cchunk["fieldsynopsis"] = $this->dchunk["fieldsynopsis"];
        if ($open) {
            return $this->format_para($open, $name, $attrs, $props);
        }
        $this->pdfDoc->appendText(";");
        return $this->format_para($open, $name, $attrs, $props);
    }

    public function format_initializer($open, $name, $attrs) {
        if ($open) {
            $this->pdfDoc->appendText(" = ");
        }
        return '';
    }
    // }}} Synopsises


    // Footnotes & Callouts {{{
    public function format_footnoteref($open, $name, $attrs, $props) {
        if ($open) {
            $linkend = $attrs[Reader::XMLNS_DOCBOOK]["linkend"];
            $found = false;
            foreach($this->cchunk["footnote"] as $k => $note) {
                if ($note["id"] === $linkend) {
                    $this->pdfDoc->setFont(PdfWriter::FONT_NORMAL, 12, array(0,0,1));
                    $this->cchunk["footrefs"][] = $this->pdfDoc->add(PdfWriter::LINK_ANNOTATION, "[".($k + 1)."]");
                    $this->pdfDoc->revertFont();
                }
            }
            return '';
        }
    }

    public function format_footnote($open, $name, $attrs, $props) {
        if ($open) {
            $count = count($this->cchunk["footnote"]);
            $noteid = isset($attrs[Reader::XMLNS_XML]["id"]) ? $attrs[Reader::XMLNS_XML]["id"] : $count + 1;
            $note = array("id" => $noteid, "str" => "");
            $this->cchunk["footnote"][$count] = $note;
            if ($this->cchunk["table"]) {
                $this->cchunk["tablefootnotes"][$count] = $noteid;
            }
            $this->pdfDoc->setFont(PdfWriter::FONT_NORMAL, 12, array(0,0,1));
            $this->cchunk["footrefs"][] = $this->pdfDoc->add(PdfWriter::LINK_ANNOTATION, "[".($count + 1)."]");
            $this->pdfDoc->revertFont();
            $this->pdfDoc->setAppendToBuffer(true);
            $this->pdfDoc->setFont(PdfWriter::FONT_BOLD, 12, array(0,0,1));
            $this->pdfDoc->appendText("[".($count + 1)."]");
            $this->pdfDoc->revertFont();
            return "";
        }
        $this->pdfDoc->appendText("\n");
        $this->pdfDoc->setAppendToBuffer(false);
        return "";
    }

    public function format_co($open, $name, $attrs, $props) {
        if (($open || $props["empty"]) && isset($attrs[Reader::XMLNS_XML]["id"]) && $id = $attrs[Reader::XMLNS_XML]["id"]) {
            $co = ++$this->cchunk["co"];
            $this->pdfDoc->setFont(PdfWriter::FONT_NORMAL, 12, array(0,0,1));
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["linkends"]) && $linkends = $attrs[Reader::XMLNS_DOCBOOK]["linkends"]) {
                $linkAreas = $this->pdfDoc->add(PdfWriter::LINK_ANNOTATION, "[{$co}]");
                if (!isset($this->cchunk["links-to-resolve"][$linkends]))
                    $this->cchunk["links-to-resolve"][$linkends] = array();
                foreach ($linkAreas as $area)
                    $this->cchunk["links-to-resolve"][$linkends][] = $area;
            }
            $this->pdfDoc->revertFont();
        }
        return "";
    }

    public function format_calloutlist($open, $name, $attrs) {
        if ($open) {
            $this->pdfDoc->add(PdfWriter::FRAMED_BLOCK);
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->cchunk["co"] = 0;
        } else {
            $this->pdfDoc->add(PdfWriter::END_FRAMED_BLOCK, array(2)); // With Dash line
            $this->cchunk["co"] = 0;
        }
        return '';
    }

    public function format_callout($open, $name, $attrs) {
        if ($open) {
            $co = ++$this->cchunk["co"];
            $this->pdfDoc->setFont(PdfWriter::FONT_BOLD, 12, array(0,0,1));
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["arearefs"]) && $ref = $attrs[Reader::XMLNS_DOCBOOK]["arearefs"]) {
                $linkAreas = $this->pdfDoc->add(PdfWriter::LINK_ANNOTATION, "[{$co}]");
                if (!isset($this->cchunk["links-to-resolve"][$ref]))
                    $this->cchunk["links-to-resolve"][$ref] = array();
                foreach ($linkAreas as $area)
                    $this->cchunk["links-to-resolve"][$ref][] = $area;
            }
            $this->pdfDoc->revertFont();
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
        }
        return '';
    }
    // }}} Footnotes & Callouts

    public function format_quote_text($value, $tag) {
        $value = trim(preg_replace('/[ \n\t]+/', ' ', $value));
        $this->pdfDoc->appendText(' "'.$value.'"');
        return "";
    }

    public function format_refname_text($value, $tag) {
        $this->cchunk["refname"][] = $value;
        $this->pdfDoc->appendText(trim(preg_replace('/[ \n\t]+/', ' ', $value)));
        return "";
    }

    public function format_refpurpose($open, $tag, $attrs, $props) {
        if ($props["empty"]) {
            $this->pdfDoc->add(PdfWriter::PARA);
            foreach($this->cchunk["refname"] as $refname) {
                $this->pdfDoc->appendText(" " . $refname . " --");
            }
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->cchunk["refname"] = array();
        } elseif ($open) {
            $this->pdfDoc->add(PdfWriter::PARA);
            foreach($this->cchunk["refname"] as $refname) {
                $this->pdfDoc->appendText(" " . $refname . " --");
            }
        } else {
            $this->pdfDoc->add(PdfWriter::LINE_JUMP);
            $this->cchunk["refname"] = array();
        }
        return "";
    }

    public function format_manvolnum($open, $name, $attrs) {
        if ($open) {
            $this->pdfDoc->appendText(")");
            return '';
        }
        $this->pdfDoc->appendText(")");
        return '';
        return ")</span>";
    }

    public function format_indice($open, $name, $attrs) {
        if (($open && $name == "subscript") || (!$open && $name == "superscript")) {
            $this->pdfDoc->vOffset("-4");
            return '';
        }
        $this->pdfDoc->vOffset("4");
        return '';
    }

    public function format_imagedata($open, $name, $attrs, $props) {
        if ($props["empty"] && isset($this->cchunk["xml-base"]) && ($base = $this->cchunk["xml-base"]) &&
            isset($attrs[Reader::XMLNS_DOCBOOK]["fileref"]) && ($fileref = $attrs[Reader::XMLNS_DOCBOOK]["fileref"])) {
            $imagePath = Config::xml_root() . DIRECTORY_SEPARATOR . $base . $fileref;
            if (file_exists($imagePath))
                $this->pdfDoc->add(PdfWriter::IMAGE, $imagePath);

        }
        return '';
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

