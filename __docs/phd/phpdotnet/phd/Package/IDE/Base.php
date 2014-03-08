<?php
namespace phpdotnet\phd;
/* $Id$ */

abstract class Package_IDE_Base extends Format {
    protected $elementmap = array(
        'caution'               => 'format_notes',
        'entry'                 => 'format_changelog_entry',
        'function'              => 'format_seealso_entry',
        'listitem'              => 'format_parameter_desc',
        'methodparam'           => 'format_methodparam',
        'methodname'            => 'format_seealso_entry',
        'member'                => 'format_member',
        'note'                  => 'format_notes',
        'refentry'              => 'format_refentry',
        'refpurpose'            => 'format_refpurpose',
        'refnamediv'            => 'format_suppressed_tags',
        'refsect1'              => 'format_refsect1',
        'row'                   => 'format_changelog_row',
        'set'                   => 'format_set',
        'tbody'                 => 'format_changelog_tbody',
        'tip'                   => 'format_notes',
        'warning'               => 'format_notes',
    );
    protected $textmap    = array(
        'function'              => 'format_seealso_entry_text',
        'initializer'           => 'format_initializer_text',
        'methodname'            => 'format_seealso_entry_text',
        'parameter'             => 'format_parameter_text',
        'refname'               => 'format_refname_text',
        'title'                 => 'format_suppressed_text',
        'type'                  => 'format_type_text',
    );
    protected $cchunk = array();
    protected $dchunk = array(
        'function'              => array(),
        'methodparam'           => false,
        'param'                 => array(
            'name'                  => false,
            'type'                  => false,
            'description'           => false,
            'opt'                   => false,
            'initializer'           => false,
        ),
        'seealso'               => array(
            'name'                  => false,
            'type'                  => false,
            'description'           => false,
        ),
        'changelog'             => array(
            'entry'                 => false,
            'version'               => false,
            'change'                => false,
        ),
    );

    protected $isFunctionRefSet;
    protected $isChangelogRow = false;
    protected $role = false;
    protected $versions;

    protected $function  = array();
    protected $dfunction = array(
        'name'                  => null,
        'purpose'               => null,
        'manualid'              => null,
        'version'               => null,
        'params'                => array(),
        'currentParam'          => null,
        'return'                => array(
            'type'              => null,
            'description'       => null,
        ),
        'errors'                => null,
        'notes'                 => array(),
        'changelog'             => array(),
        'seealso'               => array(),
    );

    public function createLink($for, &$desc = null, $type = Format::SDESC) {}
    public function UNDEF($open, $name, $attrs, $props) {}
    public function TEXT($value) {}
    public function CDATA($value) {}
    public function transformFromMap($open, $tag, $name, $attrs, $props) {}
    public function appendData($data) {}

    public abstract function parseFunction();

    function writeChunk() {
        if (!isset($this->cchunk['funcname'][0])) {
             return;
        }
        if (false !== strpos($this->cchunk['funcname'][0], ' ')) {
            return;
        }
        $this->function['name'] = $this->cchunk['funcname'][0];
        $this->function['version'] = $this->versionInfo($this->function['name']);
        $data = $this->parseFunction();

        $filename = $this->getOutputDir() . $this->function['name'] . $this->getExt();
        file_put_contents($filename, $data);

        $index = 0;
        while(isset($this->cchunk['funcname'][++$index])) {
            $filename = $this->getOutputDir() . $this->cchunk['funcname'][$index] . $this->getExt();
            // Replace the default function name by the alternative one
            $content = preg_replace('/' . $this->cchunk['funcname'][0] . '/',
                $this->cchunk['funcname'][$index], $data, 1);
            file_put_contents($filename, $content);
        }
    }

    public function renderHTML() {
        static $format = null;
        if ($format == null) {
            $format = new Package_Generic_ChunkedXHTML();
        }
        return $format->parse(trim(ReaderKeeper::getReader()->readInnerXML()));
    }

    public function CHUNK($value) {
        $this->chunkFlags = $value;
    }

    public function STANDALONE($value) {
        $this->registerElementMap($this->elementmap);
        $this->registerTextMap($this->textmap);
    }

    public function INIT($value) {
        $this->loadVersionInfo();
        $this->createOutputDirectory();
    }

    public function FINALIZE($value) {
    }

    public function VERBOSE($value) {
        v('Starting %s rendering', $this->getFormatName(), VERBOSE_FORMAT_RENDERING);
    }

    public function update($event, $value = null) {
        switch($event) {
        case Render::CHUNK:
            $this->CHUNK($value);
            break;
        case Render::STANDALONE:
            $this->STANDALONE($value);
            break;
        case Render::INIT:
            $this->INIT($value);
            break;
        case Render::FINALIZE:
            $this->FINALIZE($value);
            break;
        case Render::VERBOSE:
            $this->VERBOSE($value);
            break;
        }
    }

    public static function generateVersionInfo($filename) {
        static $info;
        if ($info) {
            return $info;
        }
        $r = new \XMLReader;
        if (!$r->open($filename)) {
            throw new \Exception;
        }
        $versions = array();
        while($r->read()) {
            if (
                $r->moveToAttribute('name')
                && ($funcname = str_replace(
                    array('::', '->', '__', '_', '$'),
                    array('-',  '-',  '-',  '-', ''),
                    $r->value))
                && $r->moveToAttribute('from')
                && ($from = $r->value)
            ) {
                $versions[strtolower($funcname)] = $from;
                $r->moveToElement();
            }
        }
        $r->close();
        $info = $versions;
        return $versions;
    }

    public function versionInfo($funcname) {
        $funcname = str_replace(
                array('.', '::', '-&gt;', '->', '__', '_', '$', '()'),
                array('-', '-',  '-',     '-',  '-',  '-', '',  ''),
                strtolower($funcname));
        if(isset($this->versions[$funcname])) {
           return $this->versions[$funcname];
        }
        v('No version info for %s', $funcname, VERBOSE_NOVERSION);
        return false;
    }

    public function loadVersionInfo() {
        if (file_exists(Config::phpweb_version_filename())) {
            $this->versions = self::generateVersionInfo(Config::phpweb_version_filename());
        } else {
            trigger_error("Can't load the versions file", E_USER_ERROR);
        }
    }

    public function createOutputDirectory() {
        $this->setOutputDir(Config::output_dir() . strtolower($this->getFormatName()) . '/');
        if (file_exists($this->getOutputDir())) {
            if (!is_dir($this->getOutputDir())) {
                v('Output directory is a file?', E_USER_ERROR);
            }
        } else {
            if (!mkdir($this->getOutputDir(), 0777, true)) {
                v("Can't create output directory", E_USER_ERROR);
            }
        }
    }

    public function format_suppressed_tags($open, $name, $attrs, $props) {
        return '';
    }

    public function format_suppressed_text($value, $tag) {
        return '';
    }

    public function format_set($open, $name, $attrs, $props) {
        if (isset($attrs[Reader::XMLNS_XML]['id']) && $attrs[Reader::XMLNS_XML]['id'] == 'funcref') {
            $this->isFunctionRefSet = $open;
        }
    }

    public function format_refentry($open, $name, $attrs, $props) {
        if (!$this->isFunctionRefSet) {
            return;
        }
        if ($open) {
            $this->function = $this->dfunction;
            $this->cchunk = $this->dchunk;

            $this->function['manualid'] =  $attrs[Reader::XMLNS_XML]['id'];
            return;
        }
        $this->writeChunk();
    }

    public function format_refname_text($value, $tag) {
        if ($this->isFunctionRefSet) {
            $this->cchunk['funcname'][] = $this->toValidName(trim($value));
        }
    }

    public function format_refpurpose($open, $name, $attrs, $props) {
        if ($this->isFunctionRefSet && $open) {
            $this->function['purpose'] = str_replace("\n", '', trim(ReaderKeeper::getReader()->readString()));
        }
    }

    public function format_refsect1($open, $name, $attrs, $props) {
        if (!$this->isFunctionRefSet) {
            return;
        }
        if ($open) {
            if (isset($attrs[Reader::XMLNS_DOCBOOK]['role']) && $attrs[Reader::XMLNS_DOCBOOK]['role']) {
                $this->role = $attrs[Reader::XMLNS_DOCBOOK]['role'];
            } else {
                $this->role = false;
            }
        } else {
            $this->role = false;
        }
        if ($this->role == 'errors') {
            return $this->format_errors($open, $name, $attrs, $props);
        } elseif ($this->role == 'returnvalues') {
            return $this->format_return($open, $name, $attrs, $props);
        }
    }

    public function format_type_text($value, $tag) {
        if (!$this->isFunctionRefSet) {
            return;
        }
        if ($this->role == 'description') {
            if (isset($this->cchunk['methodparam']) && !$this->cchunk['methodparam']) {
                $this->function['return']['type'] = $value;
                return;
            }
            $this->cchunk['param']['type'] = $value;
        }
    }

    public function format_methodparam($open, $name, $attrs, $props) {
        if ($this->role != 'description' || !$this->isFunctionRefSet ) {
            return;
        }
        if ($open) {
            $this->cchunk['methodparam'] = true;
            if (isset($attrs[Reader::XMLNS_DOCBOOK]['choice']) && $attrs[Reader::XMLNS_DOCBOOK]['choice'] == 'opt') {
                $this->cchunk['param']['opt'] = 'true';
            } else {
                $this->cchunk['param']['opt'] = 'false';
            }
            return;
        }
        $param['name'] = $this->cchunk['param']['name'];
        $param['type'] = $this->cchunk['param']['type'];
        $param['optional'] = $this->cchunk['param']['opt'];
        if (!empty($this->cchunk['param']['initializer'])) {
            $param['initializer'] = $this->cchunk['param']['initializer'];
        }
        $this->cchunk['methodparam'] = $this->dchunk['methodparam'];
        $this->cchunk['param'] = $this->dchunk['param'];
        $this->function['params'][$param['name']] = $param;
    }

    public function format_parameter_text($value, $tag) {
        if (!$this->isFunctionRefSet) {
            return;
        }
        if (!empty($this->cchunk['methodparam'])) {
            $this->cchunk['param']['name'] = $value;
        }
        if ($this->role == 'parameters') {
            $this->cchunk['currentParam'] = trim($value);
        }
    }

    public function format_parameter_desc($open, $name, $attrs, $props) {
        if ($this->role != 'parameters') {
            return;
        }
        if ($open) {
            //Read the description
            $content = $this->renderHTML();
            if (isset($this->cchunk['currentParam']) && isset($this->function['params'][$this->cchunk['currentParam']])) {
                $this->function['params'][$this->cchunk['currentParam']]['description'] = $content;
            }
        }
    }

    public function format_initializer_text($value, $tag) {
        if (!$this->isFunctionRefSet) {
            return;
        }
        if (isset($this->cchunk['methodparam']) && !$this->cchunk['methodparam']) {
            return;
        }
        $this->cchunk['param']['initializer'] = $value;
    }

    public function format_return($open, $name, $attrs, $props) {
        if ($open) {
            //Read the description
            $content = $this->renderHTML();
            //Remove default title
            $content = str_replace('<h1 class="title">Return Values</h1>', '', $content);
            $this->function['return']['description'] = trim($content);
        }
    }

    public function format_notes($open, $name, $attrs, $props) {
        if ($this->role != 'notes' || !$this->isFunctionRefSet) {
            return;
        }
        if ($open) {
            //Read the description
            $content = $this->renderHTML();
            $note = array();
            $note['type'] = $name;
            $note['description'] = $content;
            $this->function['notes'][] = $note;
        }
    }

    public function format_errors($open, $name, $attrs, $props) {
        if ($open) {
            //Read the description
            $content = $this->renderHTML();
            //Remove default title
            $content = str_replace('<h1 class="title">Errors/Exceptions</h1>', '', $content);
            $this->function['errors'] = trim($content);
        }
    }

    public function format_changelog_tbody($open, $name, $attrs, $props) {
        if ($this->role == 'changelog' && $this->isFunctionRefSet) {
            $this->isChangelogRow = $open;
        }
    }

    public function format_changelog_row($open, $name, $attrs, $props) {
        if ($this->isChangelogRow) {
            if ($open) {
                $this->cchunk['changelog'] = $this->dchunk['changelog'];
            } else {
                $change['version'] = $this->cchunk['changelog']['version'];
                $change['change'] = $this->cchunk['changelog']['change'];
                $this->function['changelog'][] = $change;
            }
        }
    }

    public function format_changelog_entry($open, $name, $attrs, $props) {
        if ($this->isChangelogRow && $open) {
            $entryType = ($this->cchunk['changelog']['entry'] == 'version')
                    ? 'change'
                    : 'version';
            $this->cchunk['changelog'][$entryType] = trim(ReaderKeeper::getReader()->readString());
            $this->cchunk['changelog']['entry'] = $entryType;
        }
    }

    public function format_changelog_entry_text($value, $tag) {
        if ($this->isChangelogRow) {
            $this->cchunk['changelog'][$this->cchunk['changelog']['entry']] = $value;
        }
    }

    public function format_member($open, $name, $attrs, $props) {
        if ($this->role == 'seealso' && $this->isFunctionRefSet) {
            if ($open) {
                $this->cchunk['seealso'] = $this->dchunk['seealso'];
            } else {
                $seealso = array();
                $seealso['type'] = $this->cchunk['seealso']['type'];
                $seealso['name'] = $this->cchunk['seealso']['name'];
                $this->cchunk['seealso'] = $this->dchunk['seealso'];
                if ($seealso['name'] == '') {
                    return false;
                }
                return $this->function['seealso'][] = $seealso;
            }
        }
    }

    public function format_seealso_entry($open, $name, $attrs, $props) {
        if ($this->role == 'seealso' && $this->isFunctionRefSet) {
            $this->cchunk['seealso']['type'] = $name;
        }
    }

    public function format_seealso_entry_text($value, $tag) {
        if ($this->role == 'seealso' && $this->isFunctionRefSet) {
            $this->cchunk['seealso']['name'] = $value;
        }
    }

    public function toValidName($functionName) {
        return str_replace(array('::', '->', '()'), array('.', '.', ''), $functionName);
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
