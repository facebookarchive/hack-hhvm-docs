<?php
namespace phpdotnet\phd;
/* $Id$ */

abstract class Package_Generic_XHTML extends Format_Abstract_XHTML {
    private $myelementmap = array( /* {{{ */
        'abstract'              => 'div', /* Docbook-xsl prints "abstract"... */
        'abbrev'                => 'abbr',
        'acronym'               => 'acronym',
        'affiliation'           => 'format_suppressed_tags',
        'alt'                   => 'format_suppressed_tags',
        'arg'                   => 'format_suppressed_tags',
        'article'               => 'format_container_chunk_top',
        'author'                => array(
            /* DEFAULT */          'format_author',
            'authorgroup'       => 'format_authorgroup_author',
        ),
        'authorgroup'           => 'div',
        'authorinitials'        => 'format_entry',
        'appendix'              => 'format_container_chunk_top',
        'application'           => 'span',
        'blockquote'            => 'blockquote',
        'bibliography'          => array(
            /* DEFAULT */          'format_div',
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'book'                  => 'format_container_chunk_top',
        'chapter'               => 'format_container_chunk_top',
        'citetitle'             => 'em',
        'cmdsynopsis'           => 'format_cmdsynopsis',
        'co'                    => 'format_co',
        'colophon'              => 'format_chunk',
        'copyright'             => 'format_copyright',
        'date'                  => array(
            /* DEFAULT */          'p',
           'revision'           => 'format_entry',
        ),
        'editor'                => 'format_editor',
        'edition'               => 'format_suppressed_tags',
        'email'                 => 'format_suppressed_tags',
        'errortext'             => 'code',
        'firstname'             => 'format_name',
        'footnote'              => 'format_footnote',
        'footnoteref'           => 'format_footnoteref',
        'funcdef'               => 'format_suppressed_tags',
        'funcsynopsis'          => 'div',
        'funcsynopsisinfo'      => 'pre',
        'function'              => 'span',
        'funcprototype'         => 'code',
        'surname'               => 'format_name',
        'othername'             => 'format_name',
        'optional'              => 'span',
        'honorific'             => 'span',
        'glossary'              => array(
            /* DEFAULT */          'format_div',
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'calloutlist'           => 'format_calloutlist',
        'callout'               => 'format_callout',
        'caution'               => 'format_admonition',
        'citation'              => 'format_citation',
        'citerefentry'          => 'span',
        'classname'             => array(
            /* DEFAULT */          'span',
            'ooclass'           => array(
                /* DEFAULT */      'strong',
                'classsynopsisinfo' => 'format_classsynopsisinfo_ooclass_classname',
            ),
        ),
        'classsynopsis'         => 'format_classsynopsis',
        'classsynopsisinfo'     => 'format_classsynopsisinfo',
        'code'                  => 'code',
        'collab'                => 'span',
        'collabname'            => 'span',
        'contrib'               => 'format_suppressed_tags',
        'colspec'               => 'format_colspec',
        'command'               => 'strong',
        'computeroutput'        => 'span',
        /* FIXME: This is one crazy stupid workaround for footnotes */
        'constant'              => array(
            /* DEFAULT */          'format_constant',
            'para'              => array(
                /* DEFAULT */      'format_constant',
                'footnote'      => 'format_footnote_constant',
            ),
        ),
        'constructorsynopsis'   => 'format_methodsynopsis',
        'destructorsynopsis'    => 'format_methodsynopsis',
        'emphasis'              => 'em',
        'enumname'              => 'span',
        'entry'                 => array (
            /* DEFAULT */          'format_entry',
            'row'               => array(
                /* DEFAULT */      'format_entry',
                'thead'         => 'format_th_entry',
                'tfoot'         => 'format_th_entry',
                'tbody'         => 'format_entry',
            ),
        ),
        'envar'                 => 'span',
        'errortype'             => 'span',
        'errorcode'             => 'span',
        'example'               => 'format_example',
        'formalpara'            => 'p',
        'fieldsynopsis'         => array(
            /* DEFAULT */          'format_fieldsynopsis',
            'entry'             => 'format_div',
        ),
        'figure'                => 'div',
        'filename'              => 'var',
        'glossentry'            => 'li',
        'glossdef'              => 'p',
        'glosslist'             => 'format_itemizedlist',
        'glossterm'             => 'span',
        'holder'                => 'span',
        'imageobject'           => 'format_div',
        'imagedata'             => 'format_imagedata',
        'important'             => 'format_admonition',
        'index'                 => array(
            /* DEFAULT */          'format_div',
            'article'           => 'format_chunk',
            'book'              => 'format_chunk',
            'part'              => 'format_chunk',
        ),
        'info'                  => array(
            /* DEFAULT */         'format_div',
            'note'              => 'span',
        ),
        'informalexample'       => 'format_div',
        'informaltable'         => 'format_table',
        'indexdiv'              => 'format_dl',
        'indexentry'            => 'dd',
        'initializer'           => 'format_initializer',
        'itemizedlist'          => 'format_itemizedlist',
        'legalnotice'           => 'format_chunk',
        'listitem'              => array(
            /* DEFAULT */          'li',
            'varlistentry'      => 'format_varlistentry_listitem',
        ),
        'literal'               => 'format_literal',
        'literallayout'         => 'pre',
        'link'                  => 'format_link',
        'xref'                  => 'format_xref',
        'manvolnum'             => 'format_manvolnum',
        'inlinemediaobject'     => 'format_mediaobject',
        'mediaobject'           => 'format_mediaobject',
        'methodparam'           => 'format_methodparam',
        'methodsynopsis'        => 'format_methodsynopsis',
        'methodname'            => 'format_methodname',
        'member'                => 'li',
        'modifier'              => 'span',
        'note'                  => 'format_note',
        'orgname'               => 'span',
        'othercredit'           => 'format_div',
        'ooclass'               => array(
            /* DEFAULT */          'span',
            'classsynopsis'     => 'format_div',
        ),
        'oointerface'           => array(
            /* DEFAULT */          'span',
            'classsynopsisinfo'    => 'format_classsynopsisinfo_oointerface',
        ),
        'interfacename'         => 'span',
        'exceptionname'         => 'span',
        'option'                => 'format_option',
        'orderedlist'           => 'format_orderedlist',
        'para'                  => array(
            /* DEFAULT */          'p',
            'example'           => 'format_example_content',
            'footnote'          => 'format_footnote_para',
            'refsect1'          => 'format_refsect1_para',
            'question'          => 'format_suppressed_tags',
        ),
        'paramdef'              => 'format_suppressed_tags',
        'parameter'             => array(
            /* DEFAULT */          'format_parameter',
            'methodparam'       => 'format_methodparam_parameter',
        ),
        'part'                  => 'format_container_chunk_top',
        'partintro'             => 'format_div',
        'personname'            => 'format_personname',
        'personblurb'           => 'format_div',
        'phrase'                => 'span',
        'preface'               => 'format_chunk',
        'printhistory'          => 'format_div',
        'primaryie'             => 'format_suppressed_tags',
        'procedure'             => 'format_procedure',
        'productname'           => 'span',
        'programlisting'        => 'format_programlisting',
        'prompt'                => 'span',
        'propname'              => 'span',
        'property'              => array(
            /* DEFAULT */          'span',
            'classsynopsisinfo' => 'format_varname',
        ),
        'proptype'              => 'span',
        'pubdate'               => 'format_div', /* Docbook-XSL prints "published" */
        'refentry'              => 'format_chunk',
        'refentrytitle'         => 'span',
        'refpurpose'            => 'p',
        'reference'             => 'format_container_chunk_below',
        'refsect1'              => 'format_refsect',
        'refsect2'              => 'format_refsect',
        'refsect3'              => 'format_refsect',
        'refsynopsisdiv'        => 'div',
        'refname'               => 'h1',
        'refnamediv'            => 'div',
        'releaseinfo'           => 'div',
        'replaceable'           => 'span',
        'revhistory'            => 'format_table',
        'revision'              => 'format_row',
        'revremark'             => 'format_entry',
        'row'                   => 'format_row',
        'screen'                => 'format_screen',
        'screenshot'            => 'format_div',
        'sect1'                 => 'format_chunk',
        'sect2'                 => 'div',
        'sect3'                 => 'div',
        'sect4'                 => 'div',
        'sect5'                 => 'div',
        'section'               => array(
            /* DEFAULT */          'div',
            'sect1'                => 'format_section_chunk',
            'chapter'              => 'format_section_chunk',
            'appendix'             => 'format_section_chunk',
            'article'              => 'format_section_chunk',
            'part'                 => 'format_section_chunk',
            'reference'            => 'format_section_chunk',
            'refentry'             => 'format_section_chunk',
            'index'                => 'format_section_chunk',
            'bibliography'         => 'format_section_chunk',
            'glossary'             => 'format_section_chunk',
            'colopone'             => 'format_section_chunk',
            'book'                 => 'format_section_chunk',
            'set'                  => 'format_section_chunk',
            'setindex'             => 'format_section_chunk',
            'legalnotice'          => 'format_section_chunk',
        ),
        'seg'                   => 'format_seg',
        'segmentedlist'         => 'format_segmentedlist',
        'seglistitem'           => 'format_seglistitem',
        'segtitle'              => 'format_suppressed_tags',
        'set'                   => 'format_container_chunk_top',
        'setindex'              => 'format_chunk',
        'shortaffil'            => 'format_suppressed_tags',
        'sidebar'               => 'format_note',
        'simplelist'            => 'format_itemizedlist', /* FIXME: simplelists has few attributes that need to be implemented */
        'simplesect'            => 'format_div',
        'simpara'               => array(
            /* DEFAULT */          'p',
            'note'              => 'span',
            'listitem'          => 'span',
            'entry'             => 'span',
            'example'           => 'format_example_content',
        ),
        'spanspec'              => 'format_suppressed_tags',
        'step'                  => 'format_step',
        'superscript'           => 'sup',
        'subscript'             => 'sub',
        'systemitem'            => 'format_systemitem',
        'symbol'                => 'span',
        'synopsis'              => 'pre',
        'tag'                   => 'code',
        'table'                 => 'format_table',
        'firstterm'             => 'format_term',
        'term'                  => array(
            /* DEFAULT */          'format_term',
            'varlistentry'      => 'format_varlistentry_term'
        ),
        'tfoot'                 => 'format_th',
        'tbody'                 => 'format_tbody',
        'td'                    => 'format_th',
        'th'                    => 'format_th',
        'thead'                 => 'format_th',
        'tgroup'                => 'format_tgroup',
        'tip'                   => 'format_admonition',
        'title'                 => array(
            /* DEFAULT */          'h1',
            'example'           => 'format_example_title',
            'formalpara'        => 'h5',
            'info'              => array(
                /* DEFAULT */      'h1',
                'example'       => 'format_example_title',
                'note'          => 'format_note_title',
                'table'         => 'format_table_title',
                'informaltable' => 'format_table_title',

                'article'       => 'format_container_chunk_top_title',
                'appendix'      => 'format_container_chunk_top_title',
                'book'          => 'format_container_chunk_top_title',
                'chapter'       => 'format_container_chunk_top_title',
                'part'          => 'format_container_chunk_top_title',
                'set'           => 'format_container_chunk_top_title',

            ),
            'indexdiv'          => 'dt',
            'legalnotice'       => 'h4',
            'note'              => 'format_note_title',
            'phd:toc'           => 'strong',
            'procedure'         => 'strong',
            'refsect1'          => 'h3',
            'refsect2'          => 'h4',
            'refsect3'          => 'h5',
            'section'           => 'h2',
            'sect1'             => 'h2',
            'sect2'             => 'h3',
            'sect3'             => 'h4',
            'sect4'             => 'h5',
            'segmentedlist'     => 'strong',
            'table'             => 'format_table_title',
            'variablelist'      => 'strong',
            'article'           => 'format_container_chunk_top_title',
            'appendix'          => 'format_container_chunk_top_title',
            'book'              => 'format_container_chunk_top_title',
            'chapter'           => 'format_container_chunk_top_title',
            'part'              => 'format_container_chunk_top_title',
            'set'               => 'format_container_chunk_top_title',
        ),
        'titleabbrev'           => 'format_suppressed_tags',
        'token'                 => 'code',
        'tr'                    => 'format_row',
        'trademark'             => 'format_trademark',
        'type'                  => 'span',
        'userinput'             => 'format_userinput',
        'uri'                   => 'code',
        'variablelist'          => 'format_variablelist',
        'varlistentry'          => 'format_varlistentry',
        'varname'               => array(
            /* DEFAULT */          'var',
            'fieldsynopsis'     => 'format_fieldsynopsis_varname',
        ),
        'void'                  => 'format_void',
        'warning'               => 'format_admonition',
        'xref'                  => 'format_xref',
        'year'                  => 'span',
        'quote'                 => 'format_quote',
        'qandaset'              => 'format_qandaset',
        'qandaentry'            => 'dl',
        'question'              => array(
            /* DEFAULT */          'format_question',
            'questions'         => 'format_phd_question', // From the PhD namespace
        ),
        'questions'             => 'ol', // From the PhD namespace
        'answer'                => 'dd',

        //phpdoc: implemented in the PHP Package
        'phpdoc:classref'       => 'format_suppressed_tags',
        'phpdoc:exception'      => 'format_suppressed_tags',
        'phpdoc:exceptionref'   => 'format_suppressed_tags',
        'phpdoc:varentry'       => 'format_suppressed_tags',

        //phd
        'phd:toc'               => 'format_phd_toc',

    ); /* }}} */

    private $mytextmap = array(
        'segtitle'             => 'format_segtitle_text',
        'affiliation'          => 'format_suppressed_text',
        'contrib'              => 'format_suppressed_text',
        'shortaffil'           => 'format_suppressed_text',
        'edition'              => 'format_suppressed_text',

        'programlisting'       => 'format_programlisting_text',
        'screen'               => 'format_screen_text',
        'alt'                  => 'format_alt_text',
        'modifier'             => array(
            /* DEFAULT */         false,
            'fieldsynopsis'    => 'format_fieldsynopsis_modifier_text',
        ),
        'classname'            => array(
            /* DEFAULT */         false,
            'ooclass'          => array(
                /* DEFAULT */     false,
                'classsynopsis' => 'format_classsynopsis_ooclass_classname_text',
            ),
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
        'para'                  => array(
            /* DEFAULT */          false,
            'footnote'             => 'format_footnote_para_text',
        ),
        /* FIXME: This is one crazy stupid workaround for footnotes */
        'constant'              => array(
            /* DEFAULT */          false,
            'para'              => array(
                /* DEFAULT */      false,
                'footnote'      => 'format_footnote_constant_text',
            ),
        ),
        'literal'               => 'format_literal_text',
        'email'                 => 'format_email_text',
        'titleabbrev'           => 'format_suppressed_text',
    );

     /* Current Chunk variables */
    protected $cchunk      = array();
    /* Default Chunk variables */
    private $dchunk      = array(
        "classsynopsis"            => array(
            "close"                         => false,
            "classname"                     => false,
        ),
        "classsynopsisinfo"        => array(
            "implements"                    => false,
            "ooclass"                       => false,
        ),
        "examples"                 => 0,
        "fieldsynopsis"            => array(
            "modifier"                      => "public",
        ),
        "co"                       => 0,
        "callouts"                 => 0,
        "segmentedlist"            => array(
            "seglistitem"                   => 0,
            "segtitle"                      => array(
            ),
        ),
        "table"                    => false,
        "procedure"                => false,
        "mediaobject"              => array(
            "alt"                           => false,
        ),
        "footnote"                 => array(
        ),
        "tablefootnotes"           => array(
        ),
        "chunk_id"                 => null,
        "varlistentry"             => array(
            "listitems"                     => array(),
        ),
    );

    protected $pihandlers = array(
        'dbhtml'        => 'PI_DBHTMLHandler',
        'dbtimestamp'   => 'PI_DBHTMLHandler',
    );

    protected $stylesheets = array();

    public function __construct() {
        parent::__construct();
        $this->registerPIHandlers($this->pihandlers);
        $this->setExt(Config::ext() === null ? ".html" : Config::ext());
    }

    public function getDefaultElementMap() {
        return $this->myelementmap;
    }

    public function getDefaultTextMap() {
        return $this->mytextmap;
    }

    public function getChunkInfo() {
        return $this->cchunk;
    }

    public function getDefaultChunkInfo() {
        return $this->dchunk;
    }

    protected function createTOC($id, $name, $props, $depth = 1, $header = true) {
        if (!$this->getChildren($id) || $depth == 0) {
            return "";
        }
        $toc = '';
        if ($header) {
            $toc .= $this->getTocHeader($props);
        }
        $toc .= "<ul class=\"chunklist chunklist_$name\">\n";
        foreach ($this->getChildren($id) as $child) {
            $isLDesc = null;
            $isSDesc = null;
            $long = $this->getLongDescription($child, $isLDesc);
            $short = $this->getShortDescription($child, $isSDesc);
            $link = $this->createLink($child);

            $list = "";
            if ($depth > 1 ) {
                $list = $this->createTOC($child, $name, $props, $depth -1, false);
            }
            if ($isLDesc && $isSDesc) {
                $toc .= '<li><a href="' . $link . '">' . $short . '</a> — ' . $long . $list . "</li>\n";
            } else {
                $toc .= '<li><a href="' . $link . '">' . ($long ? $long : $short) . '</a>' . $list .  "</li>\n";
            }
        }
        $toc .= "</ul>\n";
        return $toc;
    }

    protected function getTocHeader($props)
    {
        return '<strong>' . $this->autogen('toc', $props['lang']) . '</strong>';
    }

    /**
    * Handle a <phd:toc> tag.
    */
    function format_phd_toc($open, $name, $attrs, $props) {
        if ($open) {
            return '<div class="phd-toc">';
        }
        return $this->createToc(
            $attrs[Reader::XMLNS_PHD]['element'],
            'phd-toc',
            $props,
            isset($attrs[Reader::XMLNS_PHD]['toc-depth'])
                ? (int)$attrs[Reader::XMLNS_PHD]['toc-depth'] : 1,
            false
        ) . "</div>\n";
    }

    public function createLink($for, &$desc = null, $type = Format::SDESC) {
        $retval = null;
        if (isset($this->indexes[$for])) {
            $rsl = $this->indexes[$for];
            $retval = $rsl["filename"] . $this->ext;
            if ($rsl["filename"] != $rsl["docbook_id"]) {
                $retval .= '#' . $rsl["docbook_id"];
            }
            $desc = $rsl["sdesc"] ?: $rsl["ldesc"];
        }
        return $retval;
    }

    protected function createCSSLinks() {
        $cssLinks = '';
        foreach ((array)$this->stylesheets as $css) {
            if ($this->isChunked()) {
                $cssLinks .= "<link media=\"all\" rel=\"stylesheet\" type=\"text/css\" href=\"styles/".$css."\" />\n";
            } else {
                $cssLinks .= "<style type=\"text/css\">\n" . $css . "\n</style>\n";
            }
        }
        return $cssLinks;
    }

    protected function fetchStylesheet($name = null) {
        if (!$this->isChunked()) {
            foreach ((array)Config::css() as $css) {
                if ($style = file_get_contents($css)) {
                    $this->stylesheets[] = $style;
                } else {
                    v("Stylesheet %s not fetched.", $css, E_USER_WARNING);
                }
            }
            return;
        }
        $stylesDir = $this->getOutputDir();
        if (!$stylesDir) {
            $stylesDir = Config::output_dir();
        }
        $stylesDir .= 'styles/';
        if (file_exists($stylesDir)) {
            if (!is_dir($stylesDir)) {
                v("The styles/ directory is a file?", E_USER_ERROR);
            }
        } else {
            if (!mkdir($stylesDir, 0777, true)) {
                v("Can't create the styles/ directory.", E_USER_ERROR);
            }
        }
        foreach ((array)Config::css() as $css) {
            $basename = basename($css);
            $dest = md5(substr($css, 0, -strlen($basename))) . '-' . $basename;
            if (@copy($css, $stylesDir . $dest)) {
                $this->stylesheets[] = $dest;
            } else {
                v('Impossible to copy the %s file.', $css, E_USER_WARNING);
            }
        }
    }

/* Functions format_* */
    public function format_suppressed_tags($open, $name, $attrs, $props) {
        /* Ignore it */
        return "";
    }
    public function format_suppressed_text($value, $tag) {
        /* Suppress any content */
        return "";
    }

    public function format_link($open, $name, $attrs, $props) {
        $link = null;
        if ($open) {
            $link = $class = $content = "";

            if (isset($attrs[Reader::XMLNS_DOCBOOK]["linkend"])) {
                $link = $this->createLink($attrs[Reader::XMLNS_DOCBOOK]["linkend"]);
            }
            elseif (isset($attrs[Reader::XMLNS_XLINK]["href"])) {
                $link = $attrs[Reader::XMLNS_XLINK]["href"];
                $class = " external";
                $content = "&raquo;&nbsp;";
            }
            if ($props["empty"]) {
                $content .= $link ."</a>";
            }

            return '<a href="' . $link . '" class="' . $name . $class . '">' . $content;
        }
        return "</a>";
    }

    public function format_xref($open, $name, $attrs, $props) {
        if ($open) {
            $desc = "";
            $link = $this->createLink($attrs[Reader::XMLNS_DOCBOOK]["linkend"], $desc);

            $ret = '<a href="' .$link. '" class="' .$name. '">' .$desc;

            if ($props["empty"]) {
                return $ret. "</a>";
            }
            return $ret;
        }
        return "</a>";
    }

    public function format_option($open, $name, $attrs) {
        if ($open) {
            if(!isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $attrs[Reader::XMLNS_DOCBOOK]["role"] = "unknown";
            }
            $this->role = $role = $attrs[Reader::XMLNS_DOCBOOK]["role"];
            return '<strong class="' .$name.' ' .$role. '">';
        }
        $this->role = null;
        return "</strong>\n";
    }

    public function format_literal($open, $name, $attrs)
    {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $this->role = $attrs[Reader::XMLNS_DOCBOOK]["role"];
            } else {
                $this->role = false;
            }
            return '<em>';
        }
        $this->role = false;
        return '</em>';
    }

    public function format_literal_text($value, $tag) {
        switch ($this->role) {
            case 'infdec':
                $value = (float)$value;
                $p = strpos($value, '.');
                $str = substr($value, 0, $p + 1);
                $str .= '<span style="text-decoration: overline;">';
                $str .= substr($value, $p + 1);
                $str .= '</span>';
                return $str;
            default:
                return $this->TEXT($value);
        }
    }

    public function format_copyright($open, $name, $attrs) {
        if ($open) {
            return '<div class="'.$name.'">&copy; ';
        }
        return '</div>';
    }

    public function format_author($open, $name, $attrs, $props) {
        if ($open) {
            return '<div class="' .$name. ' vcard">';
        }
        return "</div>";
    }

    public function format_personname($open, $name, $attrs, $props) {
        if ($open) {
            return '<span class="' .$name. ' fn">';
        }
        return "</span>";
    }

    public function format_name($open, $name, $attrs) {
        if ($open) {
            $class = "";
            switch($name) {
            case "firstname":
                $class = " given-name";
                break;

            case "surname":
                $class = " family-name";
                break;

            case "othername":
                if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                    /* We might want to add support for other roles */
                    switch($attrs[Reader::XMLNS_DOCBOOK]["role"]) {
                    case "nickname":
                        $class = " nickname";
                        break;
                    }
                }
                break;
            }

            return ' <span class="' . $name . $class . '">';
        }
        return '</span> ';
    }

    public function format_container_chunk_top($open, $name, $attrs, $props) {
        $this->cchunk = $this->dchunk;
        $this->cchunk["name"] = $name;
        if(isset($attrs[Reader::XMLNS_XML]["id"])) {
            $id = $attrs[Reader::XMLNS_XML]["id"];
        } else {
            $id = uniqid("phd");
        }

        if ($open) {
            $this->CURRENT_CHUNK = $id;
            $this->notify(Render::CHUNK, Render::OPEN);

            return '<div id="' .$id. '" class="' .$name. '">';
        }

        $this->CURRENT_CHUNK = $id;
        $this->notify(Render::CHUNK, Render::CLOSE);
        $toc = "";
        if (!in_array($id, $this->TOC_WRITTEN)) {
            $toc = $this->createTOC($id, $name, $props);
        }

        return $toc."</div>";
    }
    public function format_container_chunk_top_title($open, $name, $attrs, $props) {
        if ($open) {
            return '<h1>';
        }

        $id = $this->CURRENT_CHUNK;

        $toc = $this->createTOC($id, $name, $props, 2);

        $this->TOC_WRITTEN[] = $id;
        return '</h1>'.$toc;
    }
    public function format_container_chunk_below($open, $name, $attrs, $props) {
        $this->cchunk = $this->dchunk;
        $this->cchunk["name"] = $name;
        if(isset($attrs[Reader::XMLNS_XML]["id"])) {
            $id = $attrs[Reader::XMLNS_XML]["id"];
        } else {
            /* FIXME: This will obviously not exist in the db.. */
            $id = uniqid("phd");
        }

        if ($open) {
            $this->CURRENT_CHUNK = $id;
            $this->notify(Render::CHUNK, Render::OPEN);

            return '<div id="' .$attrs[Reader::XMLNS_XML]["id"]. '" class="' .$name. '">';
        }

        $toc = '<ol>';
        $desc = "";
        if (!in_array($id, $this->TOC_WRITTEN)) {
            $toc = $this->createTOC($id, $name, $props);
        }
        $toc .= "</ol>\n";

        $this->CURRENT_CHUNK = $id;
        $this->notify(Render::CHUNK, Render::CLOSE);
        return $toc . '</div>';
    }
    public function format_exception_chunk($open, $name, $attrs, $props) {
        return $this->format_container_chunk_below($open, "reference", $attrs, $props);
    }
    public function format_section_chunk($open, $name, $attrs, $props) {
        if ($open) {
            if (!isset($attrs[Reader::XMLNS_XML]["id"])) {
                $this->isSectionChunk[] = false;
                return $this->transformFromMap($open, "div", $name, $attrs, $props);
            }
            $this->isSectionChunk[] = true;
            return $this->format_chunk($open, $name, $attrs, $props);
        }
        if (array_pop($this->isSectionChunk)) {
            return $this->format_chunk($open, $name, $attrs, $props);
        }
        return $this->transformFromMap($open, "div", $name, $attrs, $props);
    }
    public function format_chunk($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk = $this->dchunk;
            if(isset($attrs[Reader::XMLNS_XML]["id"])) {
                $id = $attrs[Reader::XMLNS_XML]["id"];
            } else {
                $id = uniqid("phd");
            }

            $class = $name;
            if ($name === "refentry") {
                //$class .= " -rel-posting";
            }

            $this->CURRENT_CHUNK = $id;
            $this->CURRENT_LANG  = $props["lang"];

            $this->notify(Render::CHUNK, Render::OPEN);
            return '<div id="' .$id. '" class="' .$class. '">';
        }
        $this->notify(Render::CHUNK, Render::CLOSE);

        $str = "";
        foreach ($this->cchunk["footnote"] as $k => $note) {
            $str .= '<div class="footnote">';
            $str .= '<a name="fnid' .$note["id"]. '" href="#fn' .$note["id"]. '"><sup>[' .($k + 1). ']</sup></a>';
            $str .= $note["str"];
            $str .= "</div>\n";
        }
        $this->cchunk = $this->dchunk;

        return $str. "</div>";
    }
    public function format_refsect1_para($open, $name, $attrs, $props) {
        if ($open) {
            switch ($props["sibling"]) {
            case "methodsynopsis":
            case "constructorsynopsis":
            case "destructorsynopsis":
                return '<p class="'.$name.' rdfs-comment">';
                break;

            default:
                return '<p class="'.$name.'">';
            }

        }
        return '</p>';
    }
    public function format_refsect($open, $name, $attrs) {
        static $role = 0;

        if ($open) {
            if(!isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $attrs[Reader::XMLNS_DOCBOOK]["role"] = "unknown-" . ++$role;
            }
            $this->role = $role = $attrs[Reader::XMLNS_DOCBOOK]["role"];

            if (isset($attrs[Reader::XMLNS_XML]["id"])) {
                $id = $attrs[Reader::XMLNS_XML]["id"];
            }
            else {
                $id = $name. "-" . $this->CURRENT_CHUNK . "-" . $role;
            }

            return '<div class="' .$name.' ' .$role. '" id="' . $id . '">';
        }
        $this->role = null;
        return "</div>\n";
    }

    public function format_classsynopsisinfo_oointerface($open, $name, $attrs) {
        if ($open) {
            if ($this->cchunk["classsynopsisinfo"]["implements"] === false) {
                $this->cchunk["classsynopsisinfo"]["implements"] = true;
                return '<span class="'.$name.'">implements ';
            }
            return '<span class="'.$name.'">, ';
        }

        return "</span>";
    }
    public function format_classsynopsisinfo_ooclass_classname($open, $name, $attrs)
    {
        if ($open) {
            if ($this->cchunk["classsynopsisinfo"]["ooclass"] === false) {
                $this->cchunk["classsynopsisinfo"]["ooclass"] = true;
                return ' class <strong class="'.$name.'">';
            }
            return '<strong class="'.$name.'"> ';
        }
        return "</strong>";
    }
    public function format_classsynopsisinfo($open, $name, $attrs) {
        $this->cchunk["classsynopsisinfo"] = $this->dchunk["classsynopsisinfo"];
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"]) && $attrs[Reader::XMLNS_DOCBOOK]["role"] == "comment") {
                return '<div class="'.$name.' classsynopsisinfo_comment">/* ';
            }
            return '<div class="'.$name.'">';
        }

        if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"]) && $attrs[Reader::XMLNS_DOCBOOK]["role"] == "comment") {
            return ' */</div>';
        }
        $this->cchunk["classsynopsis"]["close"] = true;
        return ' {</div>';
    }

    public function format_classsynopsis($open, $name, $attrs) {
        if ($open) {
            return '<div class="'.$name.'">';
        }

        if ($this->cchunk["classsynopsis"]["close"] === true) {
            $this->cchunk["classsynopsis"]["close"] = false;
            return "}</div>";
        }
        return "</div>";
    }

    public function format_classsynopsis_methodsynopsis_methodname_text($value, $tag) {
        // For HHVM - Handle generics
        if ($this->cchunk["classsynopsis"]["classname"] === false) {
            $value = $this->TEXT($value);
            return $value;
        }
        if (strpos($value, '::')) {
            $explode = '::';
        } elseif (strpos($value, '->')) {
            $explode = '->';
        } elseif (strpos($value, '-&gt;')) {
            $explode = '-&gt;';
        } else {
            return $value;
        }

        list($class, $method) = explode($explode, $value);
        if ($class !== $this->cchunk["classsynopsis"]["classname"]) {
            $value = $this->TEXT($value);
            return $value;
        }
        return $this->TEXT($method);
    }

    public function format_classsynopsis_ooclass_classname_text($value, $tag) {
        $this->cchunk["classsynopsis"]["classname"] = $value;
        return $this->TEXT($value);
    }

    public function format_fieldsynopsis($open, $name, $attrs) {
        $this->cchunk["fieldsynopsis"] = $this->dchunk["fieldsynopsis"];
        if ($open) {
            return '<div class="'.$name.'">';
        }
        return ";</div>\n";
    }

    public function format_fieldsynopsis_modifier_text($value, $tag) {
        $this->cchunk["fieldsynopsis"]["modifier"] = trim($value);
        return $this->TEXT($value);
    }

    public function format_methodsynopsis($open, $name, $attrs) {
        if ($open) {
            $this->params = array("count" => 0, "opt" => 0, "content" => "");
            $id = (isset($attrs[Reader::XMLNS_XML]["id"]) ? ' id="'.$attrs[Reader::XMLNS_XML]["id"].'"' : '');
            return '<div class="'.$name.' dc-description"'.$id.'>';
        }
        $content = "";
        if ($this->params["opt"]) {
            $content = str_repeat("]", $this->params["opt"]);
        }
        $content .= " )";

        $content .= "</div>\n";

        return $content;
    }

    public function format_methodparam_parameter($open, $name, $attrs, $props)
    {
        if ($props["empty"]) {
            return '';
        }
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                return ' <code class="parameter reference">&$';
            }
            return ' <code class="parameter">$';
        }
        return "</code>";
    }

    public function format_initializer($open, $name, $attrs) {
        if ($open) {
            return '<span class="'.$name.'"> = ';
        }
        return '</span>';
    }
    public function format_parameter($open, $name, $attrs, $props)
    {
        if ($props["empty"]) {
            return '';
        }
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                return '<em><code class="parameter reference">&';
            }
            return '<em><code class="parameter">';
        }
        return "</code></em>";
    }

    public function format_void($open, $name, $attrs, $props) {
        if ($props['sibling'] == 'methodname') {
            return ' ( <span class="methodparam">void</span>';
        } else {
            return '<span class="type"><span class="type void">void</span></span>';
        }
    }

    public function format_methodparam($open, $name, $attrs) {
        if ($open) {
            $content = '';
                if ($this->params["count"] == 0) {
                    $content .= " (";
                }
                if (isset($attrs[Reader::XMLNS_DOCBOOK]["choice"]) && $attrs[Reader::XMLNS_DOCBOOK]["choice"] == "opt") {
                    $this->params["opt"]++;
                    $content .= "[";
                } else if($this->params["opt"]) {
                    $content .= str_repeat("]", $this->params["opt"]);
                    $this->params["opt"] = 0;
                }
                if ($this->params["count"]) {
                    $content .= ",";
                }
                $content .= ' <span class="methodparam">';
                ++$this->params["count"];
                return $content;
        }
        return "</span>";
    }

    public function format_methodname($open, $name, $attr) {
        if ($open) {
            return ' <span class="' .$name. '">';
        }
        return '</span>';
    }

    public function format_varname($open, $name, $attrs) {
        if ($open) {
            return '<var class="'.$name.'">$';
        }
        return "</var>";
    }
    public function format_fieldsynopsis_varname($open, $name, $attrs) {
        if ($open) {
            if ($this->cchunk["fieldsynopsis"]["modifier"] === "const") {
                return '<var class="fieldsynopsis_varname">';
            }
            return '<var class="'.$name.'">$';
        }
        return '</var>';
    }

    public function format_footnoteref($open, $name, $attrs, $props) {
        if ($open) {
            $linkend = $attrs[Reader::XMLNS_DOCBOOK]["linkend"];
            $found = false;
            foreach($this->cchunk["footnote"] as $k => $note) {
                if ($note["id"] === $linkend) {
                    return '<a href="#fnid' .$note["id"]. '"><sup>[' .($k + 1). ']</sup></a>';
                }
            }
            trigger_error("footnoteref ID '$linkend' not found", E_USER_WARNING);
            return "";
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
            return '<a href="#fnid' .$noteid. '" name="fn'.$noteid.'"><sup>[' .($count + 1). ']</sup></a>';
        }
        return "";
    }

    /* {{{ FIXME: These are crazy workarounds :( */
    public function format_footnote_constant($open, $name, $attrs, $props) {
        $k = count($this->cchunk["footnote"]) - 1;
        $this->cchunk["footnote"][$k]["str"] .= self::format_constant($open, $name, $attrs, $props);
        return "";
    }
    public function format_footnote_constant_text($value, $tag) {
        $k = count($this->cchunk["footnote"]) - 1;
        $this->cchunk["footnote"][$k]["str"] .= $value;
        return "";
    }
    public function format_footnote_para($open, $name, $attrs, $props) {
        $k = count($this->cchunk["footnote"]) - 1;
        if ($open) {
            $this->cchunk["footnote"][$k]["str"] .= '<span class="para footnote">';
            return "";
        }

        $this->cchunk["footnote"][$k]["str"] .= "</span>";
        return "";
    }
    public function format_footnote_para_text($value, $tag) {
        $k = count($this->cchunk["footnote"]) - 1;
        $this->cchunk["footnote"][$k]["str"] .= $value;
        return "";
    }

    /* }}} */

    public function format_co($open, $name, $attrs, $props) {
        if (($open || $props["empty"]) && isset($attrs[Reader::XMLNS_XML]["id"])) {
            $co = ++$this->cchunk["co"];
            return '<a name="'.$attrs[Reader::XMLNS_XML]["id"].'" id="'.$attrs[Reader::XMLNS_XML]["id"].'">' .str_repeat("*", $co) .'</a>';
        }
        /* Suppress closing tag if any */
        return "";
    }
    public function format_calloutlist($open, $name, $attrs) {
        if ($open) {
            $this->cchunk["callouts"] = 0;
            return '<table>';
        }
        return '</table>';
    }
    public function format_callout($open, $name, $attrs) {
        if ($open) {
            return '<tr><td><a href="#'.$attrs[Reader::XMLNS_DOCBOOK]["arearefs"].'">' .str_repeat("*", ++$this->cchunk["callouts"]). '</a></td><td>';
        }
        return "</td></tr>\n";
    }

    public function format_quote($open, $name, $attrs) {
        if ($open) {
            return '"<span class="'.$name.'">';
        }
        return '</span>"';
    }
    public function format_manvolnum($open, $name, $attrs) {
        if ($open) {
            return '<span class="'.$name.'">(';
        }
        return ")</span>";
    }
    public function format_segmentedlist($open, $name, $attrs) {
        $this->cchunk["segmentedlist"] = $this->dchunk["segmentedlist"];
        if ($open) {
            return '<div class="'.$name.'">';
        }
        return '</div>';
    }
    public function format_segtitle_text($value, $tag) {
        $this->cchunk["segmentedlist"]["segtitle"][count($this->cchunk["segmentedlist"]["segtitle"])] = $value;
        /* Suppress the text */
        return "";
    }
    public function format_seglistitem($open, $name, $attrs) {
        if ($open) {
            $this->cchunk["segmentedlist"]["seglistitem"] = 0;
            return '<div class="'.$name.'">';
        }
        return '</div>';
    }
    public function format_seg($open, $name, $attrs) {
        if ($open) {
            return '<div class="seg"><strong><span class="segtitle">' .$this->cchunk["segmentedlist"]["segtitle"][$this->cchunk["segmentedlist"]["seglistitem"]++]. ':</span></strong>';
        }
        return '</div>';
    }
    public function format_procedure($open, $name, $attrs) {
        $this->cchunk["procedure"] = false;
        if ($open) {
            return '<div class="'.$name.'">';
        }
        return '</ol></div>';
    }
    public function format_step($open, $name, $attrs) {
        if ($open) {
            $ret = "";
            if ($this->cchunk["procedure"] === false) {
                $this->cchunk["procedure"] = true;
                $ret = '<ol type="1">';
            }
            return $ret . "<li>";
        }
        return '</li>';
    }
    public function format_variablelist($open, $name, $attrs) {
        if ($open) {
            return "<dl>\n";
        }
        return "</dl>\n";
    }
    public function format_varlistentry($open, $name, $attrs) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_XML]["id"])) {
                $this->cchunk['varlistentry']['id'] = $attrs[Reader::XMLNS_XML]["id"];
            } else {
                unset($this->cchunk['varlistentry']['id']);
            }
        }
        return '';
    }
    public function format_varlistentry_term($open, $name, $attrs, $props) {
        if ($open) {
            if (isset($this->cchunk['varlistentry']['id'])) {
                $id = $this->cchunk['varlistentry']['id'];
                unset($this->cchunk['varlistentry']['id']);
                return '<dt id="'.$id.'">';
            } else {
                return "<dt>\n";
            }
        }
        return "</dt>\n";
    }
    public function format_varlistentry_listitem($open, $name, $attrs) {
        if ($open) {
            return "<dd>\n";
        }
        return "</dd>\n";
    }
    public function format_term($open, $name, $attrs, $props) {
        if ($open) {
            if ($props["sibling"] == $name) {
                return '<br /><span class="' .$name. '">';
            }
            return '<span class="' .$name. '">';
        }
        return "</span>";
    }
    public function format_trademark($open, $name, $attrs, $props) {
        if ($open) {
            return '<span class=' .$name. '">';
        }
        return '®</span>';
    }
    public function format_userinput($open, $name, $attrs) {
        if ($open) {
            return '<strong class="' .$name. '"><code>';
        }
        return "</code></strong>";
    }
    public function format_systemitem($open, $name, $attrs) {
        if ($open) {
            $val = isset($attrs[Reader::XMLNS_DOCBOOK]["role"]) ? $attrs[Reader::XMLNS_DOCBOOK]["role"] : null;
            switch($val) {
            case "directive":
            /* FIXME: Different roles should probably be handled differently */
            default:
                return '<code class="systemitem ' .$name. '">';
            }
        }
        return "</code>";
    }
    public function format_example_content($open, $name, $attrs) {
        if ($open) {
            return '<div class="example-contents"><p>';
        }
        return "</p></div>";
    }
    public function format_programlisting($open, $name, $attrs) {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["role"])) {
                $this->role = $attrs[Reader::XMLNS_DOCBOOK]["role"];
            } else {
                $this->role = false;
            }

            return '<div class="example-contents">';
        }
        $this->role = false;
        return "</div>\n";
    }
    public function format_programlisting_text($value, $tag) {
        return $this->CDATA($value);
    }
    public function format_screen($open, $name, $attrs) {
        if ($open) {
            return '<div class="example-contents ' .$name. '">';
        }
        return '</div>';
    }
    public function format_constant($open, $name, $attrs)
    {
        if ($open) {
            return "<strong><code>";
        }
        return "</code></strong>";
    }
    public function admonition_title($title, $lang)
    {
        return '<strong class="' .(strtolower($title)). '">' .($this->autogen($title, $lang)). '</strong>';
    }
    public function format_admonition($open, $name, $attrs, $props) {
        if ($open) {
            return '<div class="'. $name. '">' .$this->admonition_title($name, $props["lang"]);
        }
        return "</div>";
    }
    public function format_authorgroup_author($open, $name, $attrs, $props) {
        if ($open) {
            if ($props["sibling"] !== $name) {
                return '<div class="'.$name.' vcard">' .$this->admonition_title("by", $props["lang"]). ':<br />';
            }
            return '<div class="'.$name.' vcard">';
        }
        return "</div>\n";
    }
    public function format_editor($open, $name, $attrs, $props) {
        if ($open) {
            return '<div class="editor vcard">' .$this->admonition_title("editedby", $props["lang"]). ': ';
        }
        return "</div>\n";
    }
    public function format_note($open, $name, $attrs, $props) {
        if ($open) {
            return '<blockquote class="note"><p>'.$this->admonition_title("note", $props["lang"]). ': ';
        }
        return "</p></blockquote>";
    }
    public function format_note_title($open, $name, $attrs)
    {
        if ($open) {
            return '<strong>';
        }
        return '</strong><br />';
    }
    public function format_example($open, $name, $attrs, $props) {
        static $n = 0;
        if ($open) {
            ++$n;
            if (isset($props["id"])) {
                return '<div class="' . $name . '" id="' . $props["id"] . '">';
            }
            return '<div class="' . $name . '" id="' . $this->getGeneratedExampleId($n-1) . '">';
        }
        return '</div>';
    }
    public function format_example_title($open, $name, $attrs, $props)
    {
        if ($props["empty"]) {
            return "";
        }
        if ($open) {
            return "<p><strong>" . ($this->autogen('example', $props['lang'])
                . (isset($this->cchunk["examples"]) ? ++$this->cchunk["examples"] : "")) . " ";
        }
        return "</strong></p>";
    }
    public function format_table_title($open, $name, $attrs, $props)
    {
        if ($props["empty"]) {
            return "";
        }
        if ($open) {
            return "<caption><strong>";
        }
        return "</strong></caption>";
    }

    public function format_mediaobject($open, $name, $attrs) {
        $this->cchunk["mediaobject"] = $this->dchunk["mediaobject"];
        if ($open) {
            return '<div class="'.$name.'">';
        }
        return '</div>';
    }
    public function format_alt_text($value, $tag) {
        $this->cchunk["mediaobject"]["alt"] = $value;
    }
    public function format_imagedata($open, $name, $attrs) {
        $file    = $attrs[Reader::XMLNS_DOCBOOK]["fileref"];
        if ($newpath = $this->mediamanager->handleFile($file)) {
            $curfile = $this->mediamanager->findFile($file);
            $width   = isset($attrs[Reader::XMLNS_DOCBOOK]["width"]) ? 'width="' . $attrs[Reader::XMLNS_DOCBOOK]["width"] . '"' : '';
            $height  = isset($attrs[Reader::XMLNS_DOCBOOK]["depth"]) ? 'height="' . $attrs[Reader::XMLNS_DOCBOOK]["depth"] . '"' : '';
            $alt     = 'alt="' . ($this->cchunk["mediaobject"]["alt"] !== false ? $this->cchunk["mediaobject"]["alt"] : basename($file)) . '"';

            // Generate height and width when none are supplied.
            if ($curfile && '' === $width . $height) {
                list(,,,$dimensions,,,,) = getimagesize($curfile);
            } else {
                $dimensions = $width . ' ' . $height;
            }

            // Generate warnings when only 1 dimension supplied or alt is not supplied.
            if (!$width xor !$height) {
                v('Missing %s attribute for %s', (!$width ? 'width' : 'height'), $file, VERBOSE_MISSING_ATTRIBUTES);
            }
            if (false === $this->cchunk["mediaobject"]["alt"]) {
                v('Missing alt attribute for %s', $file, VERBOSE_MISSING_ATTRIBUTES);
            }

            return '<img src="' . $newpath . '" ' . $alt . ' ' . $dimensions . ' />';
        } else {
            return '';
        }
    }

    public function format_table($open, $name, $attrs, $props) {
        if ($open) {
            $this->cchunk["table"] = true;
            // Initialize an empty tgroup in case we never process such element
            Format::tgroup(array());
            $idstr = '';
            if (isset($attrs[Reader::XMLNS_XML]["id"])) {
                $idstr = ' id="' . $attrs[Reader::XMLNS_XML]["id"] . '"';
            }
            return '<table' . $idstr . ' class="doctable ' .$name. '">';
        }
        $this->cchunk["table"] = false;
        $str = "";
        if (isset($this->cchunk["tablefootnotes"]) && $this->cchunk["tablefootnotes"]) {
            $opts = array(Reader::XMLNS_DOCBOOK => array());

            $str =  $this->format_tbody(true, "footnote", $opts, $props);
            $str .= $this->format_row(true, "footnote", $opts, $props);
            $str .= $this->format_entry(true, "footnote", $opts, $props+array("colspan" => $this->getColCount()));

            foreach ($this->cchunk["tablefootnotes"] as $k => $noteid) {
                $str .= '<div class="footnote">';
                $str .= '<a name="fnid' .$noteid. '" href="#fn' .$noteid .'"><sup>[' .($k + 1). ']</sup></a>' .$this->cchunk["footnote"][$k]["str"];
                unset($this->cchunk["footnote"][$k]);
                $str .= "</div>\n";

            }
            $str .= $this->format_entry(false, "footnote", $opts, $props);
            $str .= $this->format_row(false, "footnote", $opts, $props);
            $str .= $this->format_tbody(false, "footnote", $opts, $props);

            $this->cchunk["tablefootnotes"] = $this->dchunk["tablefootnotes"];
        }
        return "$str</table>\n";
    }
    public function format_tgroup($open, $name, $attrs) {
        if ($open) {
            Format::tgroup($attrs[Reader::XMLNS_DOCBOOK]);
            return '';
        }
        return '';
    }

    private static function parse_table_entry_attributes($attrs)
    {
        $style  = array();
        $retval = '';
        if (!empty($attrs['align'])) {
            if ('char' != $attrs['align']) {
                $style[] = 'text-align: ' . $attrs['align'];
            } elseif (isset($attrs['char'])) {
                // There's no analogue in CSS, but as this stuff isn't supported
                // in any browser, it is unlikely to appear in DocBook anyway
                $retval .= ' align="char" char="'
                           . htmlspecialchars($attrs["char"], ENT_QUOTES) . '"';
                if (isset($attrs['charoff'])) {
                    $retval .= ' charoff="'
                               . htmlspecialchars($attrs["charoff"], ENT_QUOTES) . '"';
                }
            }
        }
        if (isset($attrs["valign"])) {
            $style[] = 'vertical-align: ' . $attrs["valign"];
        }
        if (isset($attrs["colwidth"])) {
            if (preg_match('/^\\d+\\*$/', $attrs['colwidth'])) {
                // relative_length measure has no analogue in CSS and is
                // unsupported in browsers, leave as is
                $retval .= ' width="' . $attrs['colwidth'] . '"';
            } else {
                // probably fixed width, use inline styles
                $style[] = 'width: ' . $attrs['colwidth'];
            }
        }
        return $retval . (empty($style) ? '' : ' style="' . implode('; ', $style) . ';"');
    }

    public function format_colspec($open, $name, $attrs)
    {
        if ($open) {
            $str = self::parse_table_entry_attributes(Format::colspec($attrs[Reader::XMLNS_DOCBOOK]));
            return '<col' . $str . ' />';
        }
        /* noop */
    }

    public function format_th($open, $name, $attrs)
    {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]['valign'])) {
                return '<' . $name . ' style="vertical-align: '
                       . $attrs[Reader::XMLNS_DOCBOOK]['valign'] . ';">';
            } else {
                return '<' . $name . '>';
            }
        }
        return "</$name>\n";
    }

    public function format_tbody($open, $name, $attrs)
    {
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]['valign'])) {
                return '<tbody class="' . $name . '" style="vertical-align: '
                       . $attrs[Reader::XMLNS_DOCBOOK]['valign'] . ';">';
            } else {
                return '<tbody class="' . $name . '">';
            }
        }
        return "</tbody>";
    }

    public function format_row($open, $name, $attrs)
    {
        if ($open) {
            $idstr = '';
            if (isset($attrs[Reader::XMLNS_XML]['id'])) {
                $idstr = ' id="'. $attrs[Reader::XMLNS_XML]['id']. '"';
            }
            Format::initRow();
            if (isset($attrs[Reader::XMLNS_DOCBOOK]['valign'])) {
                return '<tr' . $idstr . ' style="vertical-align: '
                       . $attrs[Reader::XMLNS_DOCBOOK]['valign'] . ';">';
            } else {
                return '<tr' . $idstr . '>';
            }
        }
        return "</tr>\n";
    }

    public function format_th_entry($open, $name, $attrs, $props) {
        if ($props["empty"]) {
            return '<th class="empty">&nbsp;</th>';
        }
        if ($open) {
            $colspan = Format::colspan($attrs[Reader::XMLNS_DOCBOOK]);
            if ($colspan == 1) {
                return '<th>';
            } else {
                return '<th colspan="' .((int)$colspan). '">';
            }
        }
        return '</th>';
    }
    public function format_entry($open, $name, $attrs, $props) {
        if ($props["empty"]) {
            return '<td class="empty">&nbsp;</td>';
        }
        if ($open) {
            $dbattrs = (array)Format::getColspec($attrs[Reader::XMLNS_DOCBOOK]);

            $retval = "";
            if (isset($dbattrs["colname"])) {
                for($i=Format::getEntryOffset($dbattrs); $i>0; --$i) {
                    $retval .= '<td class="empty">&nbsp;</td>';
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
            $moreattrs = self::parse_table_entry_attributes($dbattrs);

            $sColspan = $colspan == 1 ? '' : ' colspan="' .((int)$colspan) . '"';
            $sRowspan = $rowspan == 1 ? '' : ' rowspan="' .((int)$rowspan). '"';
            return $retval. '<td' . $sColspan . $sRowspan . $moreattrs. '>';
        }
        return "</td>";
    }
    public function format_qandaset($open, $name, $attrs, $props) {
        if ($open) {
            $node = ReaderKeeper::getReader()->expand();
            $doc = new \DOMDocument;
            $doc->appendChild($node);

            $xp = new \DOMXPath($doc);
            $xp->registerNamespace("db", Reader::XMLNS_DOCBOOK);

            $questions = $xp->query("//db:qandaentry/db:question");

            $retval = '<div class="qandaset"><ol class="qandaset_questions">';
            foreach($questions as $node) {
                $id = $xp->evaluate("ancestor::db:qandaentry", $node)->item(0)->getAttributeNs(Reader::XMLNS_XML, "id");

                /* FIXME: No ID? How can we create an anchor for it then? */
                if (!$id) {
                    $id = uniqid("phd");
                }

                $retval .= '<li><a href="#'.$id.'">'.htmlentities($node->textContent, ENT_QUOTES, "UTF-8").'</a></li>';
            }
            $retval .= "</ol></div>";
            return $retval;
        }
    }
    public function format_question($open, $name, $attrs, $props) {
        if ($open) {
            return '<dt><strong>';
        }
        return '</strong></dt>';
    }
    public function format_phd_question($open, $name, $attrs, $props) {
        if ($open) {
            $href = $this->createLink($attrs[Reader::XMLNS_XML]["id"]);
            return '<li><a href="' .$href. '">';
        }
        return '</a></li>';
    }

    public function format_citation($open, $name, $attrs, $props) {
        if ($open) {
            return '[<span class="citation">';
        }
        return '</span>]';
    }

    public function format_email_text($value) {
        return '&lt;<a href="mailto:' . $value . '">' . $value . '</a>&gt;';
    }

    public function format_bold_paragraph($open, $name, $attrs, $props)
    {
        if ($props["empty"]) {
            return "";
        }
        if ($open) {
            return "<p><strong>";
        }
        return "</strong></p>";
    }

   /**
    * Functions from the old XHTMLPhDFormat
    */
    public function format_legalnotice_chunk($open, $name, $attrs) {
        if ($open) {
            return '<div id="legalnotice">';
        }
        return "</div>\n";
    }

    public function format_div($open, $name, $attrs, $props) {
        if ($open) {
            return '<div class="' . $name . '">';
        }
        return '</div>';
    }

    public function format_screen_text($value, $tag) {
        return nl2br($this->TEXT($value));
    }

    /**
    * Renders  a <tag class=""> tag.
    *
    * @return string HTML code
    */
    public function format_tag($open, $name, $attrs, $props) {
        static $arFixes = array(
            'attribute'     => array('', ''),
            'attvalue'      => array('"', '"'),
            'comment'       => array('&lt;!--', '--&gt;'),
            'element'       => array('', ''),
            'emptytag'      => array('&lt;', '/&gt;'),
            'endtag'        => array('&lt;/', '&gt;'),
            'genentity'     => array('&amp;', ';'),
            'localname'     => array('', ''),
            'namespace'     => array('', ''),
            'numcharref'    => array('&amp;#', ';'),
            'paramentity'   => array('%', ';'),
            'pi'            => array('&lt;?', '?&gt;'),
            'prefix'        => array('', ''),
            'starttag'      => array('&lt;', '&gt;'),
            'xmlpi'         => array('&lt;?', '?&gt;'),
        );
        if ($props['empty']) {
            return '';
        }

        $class = 'starttag';
        if (isset($attrs['class'])) {
            $class = $attrs['class'];
        }

        if (!isset($arFixes[$class])) {
            trigger_error('Unknown tag class "' . $class . '"', E_USER_WARNING);
            $class = 'starttag';
        }
        if (!$open) {
            return $arFixes[$class][1] . '</code>';
        }

        return '<code>' . $arFixes[$class][0];
    }

    public function format_dl($open, $name, $attrs, $props) {
        if ($open) {
            return '<dl class="' . $name . '">';
        }
        return '</dl>';
    }

    public function format_itemizedlist($open, $name, $attrs, $props) {
        if ($open) {
            return '<ul class="' . $name . '">';
        }
        return '</ul>';
    }

    public function format_orderedlist($open, $name, $attrs, $props) {
        if ($open) {
            $numeration = "1";
            if (isset($attrs[Reader::XMLNS_DOCBOOK]["numeration"])) {
                switch($attrs[Reader::XMLNS_DOCBOOK]["numeration"]) {
                case "upperalpha":
                    $numeration = "A";
                    break;
                case "loweralpha":
                    $numeration = "a";
                    break;
                case "upperroman":
                    $numeration = "I";
                    break;
                case "lowerroman":
                    $numeration = "i";
                    break;
                }
            }
            return '<ol type="' .$numeration. '">';
        }
        return '</ol>';
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

