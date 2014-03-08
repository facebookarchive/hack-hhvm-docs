<?php
namespace phpdotnet\phd;
/* $Id$ */

abstract class Format extends ObjectStorage
{
    /**
     * Represents a short description.
     * Used in createLink()
     *
     * @var    integer
     * @usedby createLink()
     */
    const SDESC = 1;

    /**
     * Represents a long description.
     * Used in createLink()
     *
     * @var    integer
     * @usedby createLink()
     */
    const LDESC = 2;

    private $elementmap = array();
    private $textmap = array();
    private $formatname = "UNKNOWN";
    protected $sqlite;

    protected $title;
    protected $fp = array();
    protected $ext;
    protected $outputdir;
    protected $chunked;
    protected $flags = 0;

    /* Processing Instructions Handlers */
    private $pihandlers = array();

    /* Indexing maps */
    protected $indexes = array();
    protected $children = array();
    protected $refs = array();
    protected $vars = array();
    protected $classes = array();
    protected $examples = array();

    private static $autogen = array();

    private static $highlighters = array();

    /* See self::parse() */
    protected $appendToBuffer = false;
    protected $buffer = "";

    public function __construct() {
        $sqlite = Config::indexcache();
        if (!$sqlite) {
            if (file_exists(Config::output_dir() . "index.sqlite")) {
                $sqlite = new \SQLite3(Config::output_dir() . 'index.sqlite');
            }
        }
        if ($sqlite) {
            $this->sqlite = $sqlite;
            $this->sortIDs();
        }
    }

    abstract public function transformFromMap($open, $tag, $name, $attrs, $props);
    abstract public function UNDEF($open, $name, $attrs, $props);
    abstract public function TEXT($value);
    abstract public function CDATA($value);

    /**
     * Create link to chunk.
     *
     * @param string  $for   Chunk ID
     * @param string  &$desc Description of link, to be filled if neccessary
     * @param integer $type  Format of description, Format::SDESC or
     *                       Format::LDESC
     *
     * @return string Relative or absolute URI to access $for
     */
    abstract public function createLink(
        $for, &$desc = null, $type = Format::SDESC
    );

    abstract public function appendData($data);

    /**
     * Called by Format::notify()
     *
     * Possible events:
     * - Render::STANDALONE
     *     Always called with true as value from Render::attach()
     *
     *
     * - Render::INIT
     *     Called from Render::execute() when rendering
     *     is being started. Value is always true
     *
     * - Render::FINALIZE (from Render::execute())
     *     Called from Render::execute() when there is
     *     nothing more to read in the XML file.
     *
     * - Render::VERBOSE
     *     Called if the user specified the --verbose option
     *     as commandline parameter. Called in render.php
     *
     * - Render::CHUNK
     *     Called when a new chunk is opened or closed.
     *     Value is either Render::OPEN or Render::CLOSE
     *
     * @param integer $event Event flag (see Render class)
     * @param mixed   $val   Additional value flag. Depends
     *                       on $event type
     *
     * @return void
     */
    abstract public function update($event, $value = null);

    public final function parsePI($target, $data) {
        if (isset($this->pihandlers[$target])) {
            return $this->pihandlers[$target]->parse($target, $data);
        }
    }

    public final function registerPIHandlers($pihandlers) {
        foreach ($pihandlers as $target => $classname) {
            $class = __NAMESPACE__ . "\\" . $classname;
            $this->pihandlers[$target] = new $class($this);
        }
    }

    public function getPIHandler($target) {
        return $this->pihandlers[$target];
    }

    public function sortIDs() {
        $this->sqlite->createAggregate("indexes", array($this, "SQLiteIndex"), array($this, "SQLiteFinal"), 9);
        $this->sqlite->createAggregate("children", array($this, "SQLiteChildren"), array($this, "SQLiteFinal"), 2);
        $this->sqlite->createAggregate("refname", array($this, "SQLiteRefname"), array($this, "SQLiteFinal"), 2);
        $this->sqlite->createAggregate("varname", array($this, "SQLiteVarname"), array($this, "SQLiteFinal"), 2);
        $this->sqlite->createAggregate("classname", array($this, "SQLiteClassname"), array($this, "SQLiteFinal"), 2);
        $this->sqlite->createAggregate("example", array($this, "SQLiteExample"), array($this, "SQLiteFinal"), 1);
        $this->sqlite->query('SELECT indexes(docbook_id, filename, parent_id, sdesc, ldesc, element, previous, next, chunk) FROM ids');
        $this->sqlite->query('SELECT children(docbook_id, parent_id) FROM ids WHERE chunk != 0');
        $this->sqlite->query('SELECT refname(docbook_id, sdesc) FROM ids WHERE element=\'refentry\'');
        $this->sqlite->query('SELECT varname(docbook_id, sdesc) FROM ids WHERE element=\'phpdoc:varentry\'');
        $this->sqlite->query('SELECT classname(docbook_id, sdesc) FROM ids WHERE element=\'phpdoc:exceptionref\' OR element=\'phpdoc:classref\'');
        $this->sqlite->query('SELECT example(docbook_id) FROM ids WHERE element=\'example\' OR element=\'informalexample\'');
    }

    public function SQLiteIndex(&$context, $index, $id, $filename, $parent, $sdesc, $ldesc, $element, $previous, $next, $chunk) {
        $this->indexes[$id] = array(
            "docbook_id" => $id,
            "filename"   => $filename,
            "parent_id"  => $parent,
            "sdesc"      => $sdesc,
            "ldesc"      => $ldesc,
            "element"    => $element,
            "previous"   => $previous,
            "next"       => $next,
            "chunk"      => $chunk,
        );
    }

    public function SQLiteChildren(&$context, $index, $id, $parent)
    {
        if (!isset($this->children[$parent])
            || !is_array($this->children[$parent])
        ) {
            $this->children[$parent] = array();
        }
        $this->children[$parent][] = $id;
    }

    public function SQLiteRefname(&$context, $index, $id, $sdesc) {
        $ref = strtolower(str_replace(array("_", "::", "->"), array("-", "-", "-"), html_entity_decode($sdesc, ENT_QUOTES, 'UTF-8')));
        $this->refs[$ref] = $id;
    }

    public function SQLiteVarname(&$context, $index, $id, $sdesc) {
        $this->vars[$sdesc] = $id;
    }

    public function SQLiteClassname(&$context, $index, $id, $sdesc) {
        $this->classes[strtolower($sdesc)] = $id;
    }
    public function SQLiteExample(&$context, $index, $id) {
        $this->examples[] = $id;
    }

    public static function SQLiteFinal(&$context) {
        return $context;
    }

    /**
     * Calls update().
     *
     * @param integer $event Event flag. See Render class for constants
     *                       like Render::INIT and Render::CHUNK
     * @param mixed   $val   Value; depends on $event flag
     *
     * @return void
     */
    final public function notify($event, $val = null)
    {
        $this->update($event, $val);
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getTitle() {
        return $this->title;
    }

    /**
     * Set file extension used when chunking and writing
     * out files.
     *
     * @param string $ext File extension without dot
     *
     * @return void
     *
     * @see getExt()
     */
    public function setExt($ext)
    {
        $this->ext = $ext;
    }

    /**
     * Returns file extension without
     * leading dot.
     *
     * @return string File extension.
     *
     * @see setExt()
     */
    public function getExt() {
        return $this->ext;
    }

    public function setOutputDir($outputdir) {
        $this->outputdir = $outputdir;
    }

    public function getOutputDir() {
        return $this->outputdir;
    }

    public function setChunked($chunked) {
        $this->chunked = $chunked;
    }

    public function isChunked() {
        return $this->chunked;
    }

    public function setFileStream($stream) {
        $this->fp = $stream;
    }

    public function getFileStream() {
        return $this->fp;
    }

    public function pushFileStream($stream) {
        $this->fp[] = $stream;
    }

    public function popFileStream() {
        return array_pop($this->fp);
    }

    public function addRefname($id, $ref) {
        $this->refs[$ref] = $id;
    }
    public function addClassname($id, $class) {
        $this->classes[$class] = $id;
    }
    public function addVarname($id, $var) {
        $this->vars[$var] = $id;
    }
    public function getChangelogsForChildrenOf($bookids) {
        $ids = array();
        foreach((array)$bookids as $bookid) {
            $ids[] = "'" . $this->sqlite->escapeString($bookid) . "'";
        }
        $results = $this->sqlite->query("SELECT * FROM changelogs WHERE parent_id IN (" . join(", ", $ids) . ")");
        return $this->_returnChangelog($results);
    }
    public function getChangelogsForMembershipOf($memberships) {
        $ids = array();
        foreach((array)$memberships as $membership) {
            $ids[] = "'" . $this->sqlite->escapeString($membership) . "'";
        }
        $results = $this->sqlite->query("SELECT * FROM changelogs WHERE membership IN (" . join(", ", $ids) . ")");
        return $this->_returnChangelog($results);
    }
    public function _returnChangelog($results) {
        if (!$results) {
            return array();
        }

        $changelogs = array();
        while ($row = $results->fetchArray()) {
            $changelogs[] = $row;
        }
        return $changelogs;
    }
    public function getRefs() {
        return $this->refs;
    }
    public function getExamples() {
        return $this->examples;
    }
    public function getRefnameLink($ref) {
        return isset($this->refs[$ref]) ? $this->refs[$ref] : null;
    }
    public function getClassnameLink($class) {
        return isset($this->classes[$class]) ? $this->classes[$class] : null;
    }
    public function getVarnameLink($var) {
        return isset($this->vars[$var]) ? $this->vars[$var] : null;
    }
    public function getGeneratedExampleID($index) {
        return $this->examples[$index];
    }
    final public function registerElementMap(array $map) {
        $this->elementmap = $map;
    }
    final public function registerTextMap(array $map) {
        $this->textmap = $map;
    }
    final public function attach($obj, $inf = array()) {
        if (!($obj instanceof $this) && get_class($obj) != get_class($this)) {
            throw new \InvalidArgumentException(get_class($this) . " themes *MUST* _inherit_ " .get_class($this). ", got " . get_class($obj));
        }
        $obj->notify(Render::STANDALONE, false);
        return parent::attach($obj, $inf);
    }
    final public function getElementMap() {
        return $this->elementmap;
    }
    final public function getTextMap() {
        return $this->textmap;
    }
    final public function registerFormatName($name) {
        $this->formatname = $name;
    }
    public function getFormatName() {
        return $this->formatname;
    }

    /* Buffer where append data instead of the standard stream (see format's appendData()) */
    final public function parse($xml) {
        $parsed = "";
        $reader = new Reader();
        $render = new Render();

        $reader->XML("<notatag>" . $xml . "</notatag>");

        $this->appendToBuffer = true;
        $render->attach($this);
        $render->execute($reader);
        $this->appendToBuffer = false;
        $parsed = $this->buffer;
        $this->buffer = "";

        return $parsed;
    }

    final public static function autogen($text, $lang) {
        if ($lang == NULL) {
            $lang = Config::language();
        }
        if (isset(self::$autogen[$lang])) {
            if (isset(self::$autogen[$lang][$text])) {
                return self::$autogen[$lang][$text];
            }
            if ($lang == Config::fallback_language()) {
                throw new \InvalidArgumentException("Cannot autogenerate text for '$text'");
            }
            return self::autogen($text, Config::fallback_language());
        }

        $filename = Config::lang_dir() . $lang . ".xml";

        $r = new \XMLReader;
        if (!file_exists($filename) || !$r->open($filename)) {
            if ($lang == Config::fallback_language()) {
                throw new \Exception("Cannot open $filename");
            }
            return self::autogen($text, Config::fallback_language());
        }
        $autogen = array();
        while ($r->read()) {
            if ($r->nodeType != \XMLReader::ELEMENT) {
                continue;
            }
            if ($r->name == "term") {
                $r->read();
                $k = $r->value;
                $autogen[$k] = "";
            } else if ($r->name == "simpara") {
                $r->read();
                $autogen[$k] = $r->value;
            }
        }
        self::$autogen[$lang] = $autogen;
        return self::autogen($text, $lang);
    }

/* {{{ TOC helper functions */

    /**
     * Returns the filename for the given id, without the file extension
     *
     * @param string $id XML Id
     *
     * @return mixed Stringular filename or false if no filename
     *               can be detected.
     */
    final public function getFilename($id)
    {
        return isset($this->indexes[$id]['filename'])
            ? $this->indexes[$id]['filename']
            : false;
    }

    final public function getPrevious($id) {
        return $this->indexes[$id]["previous"];
    }
    final public function getNext($id) {
        return $this->indexes[$id]["next"];
    }
    final public function getParent($id) {
        return $this->indexes[$id]["parent_id"];
    }
    final public function getLongDescription($id, &$isLDesc = null) {
        if ($this->indexes[$id]["ldesc"]) {
            $isLDesc = true;
            return $this->indexes[$id]["ldesc"];
        } else {
            $isLDesc = false;
            return $this->indexes[$id]["sdesc"];
        }
    }
    final public function getShortDescription($id, &$isSDesc = null) {
        if ($this->indexes[$id]["sdesc"]) {
            $isSDesc = true;
            return $this->indexes[$id]["sdesc"];
        } else {
            $isSDesc = false;
            return $this->indexes[$id]["ldesc"];
        }
    }

    /**
     * Returns an array of children IDs of given ID.
     *
     * @param string $id XML ID to retrieve children for.
     *
     * @return array Array of XML IDs
     */
    final public function getChildren($id)
    {
        if (!isset($this->children[$id])
            || !is_array($this->children[$id])
            || count($this->children[$id]) == 0
        ) {
            return null;
        }
        return $this->children[$id];
    }

    /**
     * Tells you if the given ID is to be chunked or not.
     *
     * @param string $id XML ID to get chunk status for
     *
     * @return boolean True if it is to be chunked
     */
    final public function isChunkID($id)
    {
        return isset($this->indexes[$id]['chunk'])
            ? $this->indexes[$id]['chunk']
            : false;
    }

    final public function getRootIndex() {
        static $root = null;
        if ($root == null) {
            $root = $this->sqlite->querySingle('SELECT * FROM ids WHERE parent_id=""', true);
        }
        return $root;
    }
/* }}} */

/* {{{ Table helper functions */
    public function tgroup($attrs) {
        if (isset($attrs["cols"])) {
            $this->TABLE["cols"] = $attrs["cols"];
            unset($attrs["cols"]);
        }

        $this->TABLE["defaults"] = $attrs;
        $this->TABLE["colspec"] = array();
    }
    public function colspec(array $attrs) {
        $colspec = self::getColSpec($attrs);
        $this->TABLE["colspec"][$colspec["colnum"]] = $colspec;
        return $colspec;
    }
    public function getColspec(array $attrs) {
/* defaults */
        $defaults["colname"] = count($this->TABLE["colspec"])+1;
        $defaults["colnum"]  = count($this->TABLE["colspec"])+1;

        return array_merge($defaults, $this->TABLE["defaults"], $attrs);
    }
    public function getColCount() {
        return $this->TABLE["cols"];
    }
    public function initRow() {
        $this->TABLE["next_colnum"] = 1;
    }
    public function getEntryOffset(array $attrs) {
        $curr = $this->TABLE["next_colnum"];
        foreach($this->TABLE["colspec"] as $col => $spec) {
            if ($spec["colname"] == $attrs["colname"]) {
                $colnum = $spec["colnum"];
                $this->TABLE["next_colnum"] += $colnum-$curr;
                return $colnum-$curr;
            }
        }
        return -1;
    }
    public function colspan(array $attrs) {
        if (isset($attrs["namest"])) {
            foreach($this->TABLE["colspec"] as $colnum => $spec) {
                if ($spec["colname"] == $attrs["namest"]) {
                    $from = $spec["colnum"];
                    continue;
                }
                if ($spec["colname"] == $attrs["nameend"]) {
                    $to = $spec["colnum"];
                    continue;
                }
            }
            $colspan = $to-$from+1;
            $this->TABLE["next_colnum"] += $colspan;
            return $colspan;
        }
        $this->TABLE["next_colnum"]++;
        return 1;
    }
    public function rowspan($attrs) {
        if (isset($attrs["morerows"])) {
            return $attrs["morerows"]+1;
        }
        return 1;
    }
/* }}} */

    /**
    * Highlight (color) the given piece of source code
    *
    * @param string $text   Text to highlight
    * @param string $role   Source code role to use (php, xml, html, ...)
    * @param string $format Format to highlight (pdf, xhtml, troff, ...)
    *
    * @return string Highlighted code
    */
    public function highlight($text, $role = 'php', $format = 'xhtml')
    {
        if (!isset(self::$highlighters[$format])) {
            $class = Config::highlighter();
            self::$highlighters[$format] = $class::factory($format);
        }

        return self::$highlighters[$format]->highlight(
            $text, $role, $format
        );
    }

    /**
    * Provide a nested list of IDs from the document root to the CURRENT_ID.
    *
    * @param string  $name  The name of the current element.
    * @param mixed[] $props Properties relating to the current element.
    *
    * @return string A nested list of IDs from the root to the CURRENT_ID.
    */
    public function getDebugTree($name, $props)
    {
        /* Build the list of IDs from the CURRENT_ID to the root. */
        $ids = array();
        $id = $this->CURRENT_ID;
        while($id != '')
            {
            $ids[] = '<' . $this->indexes[$id]['element'] . ' id="' . $id . '">';
            $id = $this->indexes[$id]['parent_id'];
            }

        /* Reverse the list so that it goes form the root to the CURRENT_ID. */
        $ids = array_reverse($ids);

        /* Build an indented tree view of the ids. */
        $tree = '';
        $indent = 0;
        array_walk($ids, function($value, $key) use(&$tree, &$indent)
        {
            $tree .= str_repeat('    ', $indent++) . $value . PHP_EOL;
        });

        /* Add the open and closed sibling and the current element. */
        $tree .=
            str_repeat('    ', $indent) . '<' . $props['sibling'] . '>' . PHP_EOL .
            str_repeat('    ', $indent) . '...' . PHP_EOL .
            str_repeat('    ', $indent) . '</' . $props['sibling'] . '>' . PHP_EOL .
            str_repeat('    ', $indent) . '<' . $name . '>' . PHP_EOL;

        return $tree;
    }
}

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/

