<?php
namespace phpdotnet\phd;
/* $Id$ */

/**
 * Generates Atom feed of Table of Contents for
 * each chunk.
 *
 * @category PhD
 * @package  PhD_Generic
 * @author   Christian Weiske <cweiske@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD Style
 * @link     https://doc.php.net/phd/
 */
abstract class Package_Generic_TocFeed extends Format
{
    /**
     * Mapping of docbook tags to class methods
     *
     * @var array
     */
    private $myelementmap = array(
        'article'      => 'format_chunk',
        'appendix'     => 'format_chunk',
        'bibliography' => 'format_chunk',
        'book'         => 'format_chunk',
        'chapter'      => 'format_chunk',
        'colophon'     => 'format_chunk',
        'glossary'     => 'format_chunk',
        'index'        => 'format_chunk',
        'legalnotice'  => 'format_chunk',
        'part'         => 'format_chunk',
        'preface'      => 'format_chunk',
        'refentry'     => 'format_chunk',
        'reference'    => 'format_chunk',
        'sect1'        => 'format_chunk',
        'section'      => 'format_chunk',
        'set'          => 'format_chunk',
        'setindex'     => 'format_chunk',
    );

    /**
     * We do not need any texts
     *
     * @var array
     */
    private $mytextmap = array();

    /**
     * Name of TOC feed format used by PhD internally.
     *
     * Inheriting classes should change this.
     *
     * @var string
     */
    protected $formatName = 'TocFeed';

    /**
     * File extension with leading dot for
     * links from atom feed to chunks.
     *
     * Inheriting classes should change this if neccessary.
     *
     * @var    string
     * @usedby createTargetLink()
     */
    protected $targetExt = '.htm';

    /**
     * Base URI for links from atom feed to chunks (HTML files).
     *
     * Inheriting classes should change this if neccessary.
     *
     * @var    string
     * @usedby createTargetLink()
     */
    protected $targetBaseUri = null;

    /**
     * Base URI for the feed files themselves.
     *
     * Inheriting classes should change this if neccessary.
     * If this variable is not set, __construct() sets
     * it to $targetBaseUri
     *
     * @var    string
     * @usedby createLink()
     */
    protected $feedBaseUri = null;

    /**
     * Author string used in atom feed files.
     *
     * Inheriting classes should change this.
     *
     * @var    string
     * @usedby header()
     */
    protected $author = 'PhD - The PHP Docbook Renderer';

    /**
     * Prefix for atom entry id values.
     *
     * Inheriting classes should change this.
     *
     * @internal
     * We are using tag URIs here.
     * @link http://www.faqs.org/rfcs/rfc4151.html
     * @link http://diveintomark.org/archives/2004/05/28/howto-atom-id
     *
     * And no, this date should never be changed.
     *
     * @var string
     */
    protected $idprefix = 'tag:doc.php.net,2009-10-13:/phd/FIXME/';

    /**
     * Date used in feed <updated> tags.
     * ISO 8601 format (2004-02-12T15:19:21+00:00)
     *
     * @internal
     * Set in __construct to be as fast as possible
     * and don't waste time on re-generating the same
     * data again and again.
     *
     * @var string
     */
    protected $date = null;



    /**
     * Creates a new instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->registerFormatName($this->formatName);
        $this->setTitle('Index');
        $this->setChunked(true);
        $this->setExt(Config::ext() === null ? ".atom" : Config::ext());
        $this->date = date('c');
        if ($this->feedBaseUri === null) {
            $this->feedBaseUri = $this->targetBaseUri;
        }
    }



    /**
     * Closes all open file pointers
     *
     * @return void
     */
    public function __destruct()
    {
        $this->close();
    }



    /**
     * Closes all open file streams
     *
     * @return void
     *
     * @uses getFileStream()
     */
    public function close()
    {
        foreach ($this->getFileStream() as $fp) {
            fclose($fp);
        }
    }



    /**
     * Called by Format::notify().
     *
     * Possible events:
     * - Render::STANDALONE
     *     Always called with true as value from Render::attach()
     *     Deprecated.
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
    public function update($event, $val = null)
    {
        switch($event) {
        case Render::STANDALONE:
            if ($val) {
                $this->registerElementMap(static::getDefaultElementMap());
                $this->registerTextMap(static::getDefaultTextMap());
            }
            break;

        case Render::INIT:
            $this->setOutputDir(
                Config::output_dir()
                . strtolower($this->getFormatName()) . '/'
            );
            $dir = $this->getOutputDir();
            if (file_exists($dir)) {
                if (!is_dir($dir)) {
                    v('Output directory is a file?', E_USER_ERROR);
                }
            } else {
                if (!mkdir($dir, 0777, true)) {
                    v('Cannot create output directory', E_USER_ERROR);
                }
            }
            break;

        case Render::VERBOSE:
            v(
                'Starting %s rendering',
                $this->getFormatName(), VERBOSE_FORMAT_RENDERING
            );
            break;
        }
    }



    /**
     * Appends stringular data to the current file.
     * This method is controlled by the flags
     * in $this->flags.
     *
     * @param string $data Data to write into the file
     *
     * @return void
     */
    public function appendData($data)
    {
        //we do not need this
    }



    /**
     * Writes a single chunk file.
     *
     * @param string $id      XML Id. Used to determine filename,
     *                        header and footer
     * @param string $content XML Atom content to save
     *
     * @return boolean True if the file has been written, false if not.
     *
     * @uses Format::getFilename()
     * @uses header()
     * @uses footer()
     */
    public function writeChunk($id, $content)
    {
        $filepart = $this->getFilename($id);
        if (!$filepart) {
            return false;
        }

        $filename = $this->getOutputDir()
            . $filepart
            . $this->getExt();


        file_put_contents($filename, $this->header($id));
        file_put_contents($filename, $content, FILE_APPEND);
        file_put_contents($filename, $this->footer($id), FILE_APPEND);

        return true;
    }



    /**
     * Format a chunked element
     *
     * @param boolean $open  If the tag is opened or closed
     * @param string  $name  Name of the tag
     * @param array   $attrs XML tag attributes
     * @param array   $props FIXME
     *
     * @return string Atom feed entries representing the TOC
     *
     * @uses createAtomToc()
     */
    public function format_chunk($open, $name, $attrs, $props)
    {
        if (!$open) {
            //$this->notify(Render::CHUNK, Render::CLOSE);
            return '';
        }

        if (isset($attrs[Reader::XMLNS_PHD]['chunk'])
            && $attrs[Reader::XMLNS_PHD]['chunk'] == 'false'
        ) {
            //not chunked? no feed!
            return '';
        }

        if (!isset($attrs[Reader::XMLNS_XML]['id'])) {
            //if we do not have a hard id, then
            // we cannot link to the file.
            // So without an id, there is no atom file
            return '';
        }
        $id = $attrs[Reader::XMLNS_XML]['id'];
        $this->writeChunk(
            $id,
            $this->createAtomToc($id)
        );
        //$this->notify(Render::CHUNK, Render::OPEN);
        return '';
    }



    /**
     * Create a list of atom <entry> entries containing
     * the table of contents of the given $id.
     *
     * @param string $id Chunk ID to generate TOC for
     *
     * @return string String with XML data
     */
    protected function createAtomToc($id)
    {
        $chunks = static::getChildren($id);
        if (count($chunks) == 0) {
            return '';
        }

        $toc  = '';
        $date = $this->date;
        foreach ($chunks as $junk => $chunkid) {
            $long  = $this->getLongDescription($chunkid);
            $short = $this->getShortDescription($chunkid);
            $id    = $this->idprefix . $chunkid;
            if ($long && $short && $long != $short) {
                $title = $short . ' -- ' . $long;
            } else {
                $title = ($long ?: $short);
            }
            $link = $this->createTargetLink($chunkid);
            $toc .= <<<ATM
 <entry>
  <title>{$title}</title>
  <link href="{$link}" />
  <updated>{$date}</updated>
  <id>{$id}</id>
 </entry>

ATM;
        }
        return $toc;
    }



    /**
     * Create the file header.
     *
     * @param string $id XML ID of node being rendered.
     *
     * @return string Generated file header
     *
     * @usedby writeChunk()
     */
    public function header($id)
    {
        $title    = htmlspecialchars($this->getLongDescription($id));
        $date     = $this->date;
        $lang     = Config::language();
        $selflink = $this->createLink($id);
        $htmllink = $this->createTargetLink($id);
        $author   = htmlspecialchars($this->author);
        $atomid   = $this->idprefix . 'file/' . $id;

        return <<<XML
<?xml version="1.0" encoding="utf-8" ?>
<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="{$lang}">
 <title>{$title}</title>
 <updated>{$date}</updated>
 <id>{$atomid}</id>
 <link rel="self" type="application/atom+xml" href="{$selflink}" />
 <link rel="alternate" type="text/html" href="{$htmllink}" />
 <generator uri="https://doc.php.net/phd/">PhD</generator>
 <author>
  <name>{$author}</name>
 </author>


XML;
    }



    /**
     * Create the file footer.
     *
     * @param string $id XML ID of node being rendered.
     *
     * @return string Generated file footer
     *
     * @usedby writeChunk()
     */
    public function footer($id)
    {
        return "</feed>\n";
    }



    /**
     * Returns the element mapping array.
     * We use this method so child classes can easily overwrite it.
     *
     * @return array
     */
    public function getDefaultElementMap()
    {
        return $this->myelementmap;
    }



    /**
     * Returns the text mapping array.
     * We use this method so child classes can easily overwrite it.
     *
     * @return array
     */
    public function getDefaultTextMap()
    {
        return $this->mytextmap;
    }


    /*
     * Abstract methods inherited from Format
     */

    public function transformFromMap($open, $tag, $name, $attrs, $props)
    {
    }

    public function UNDEF($open, $name, $attrs, $props)
    {
    }

    public function TEXT($value)
    {
    }

    public function CDATA($value)
    {
    }

    /**
     * Create full URL for Atom feed.
     *
     * Every class inheriting from this one should
     * to overwrite this method to return full absolute URIs,
     * or modify $targetBaseUri.
     *
     * @param string  $id    Chunk ID
     * @param string  &$desc Description of link, to be filled if neccessary.
     *                       Not used here.
     * @param integer $type  Format of description, Format::SDESC or
     *                       Format::LDESC.
     *                       Not used here.
     *
     * @return string Relative or absolute URI to access $for
     *
     * @uses $targetBaseUri
     */
    public function createLink($id, &$desc = null, $type = Format::SDESC)
    {
        return $this->feedBaseUri . $id . $this->ext;
    }

    /**
     * Create external link from Atom feed to given chunk ID (HTML).
     *
     * Every class inheriting from this one should
     * to overwrite this method to return full absolute URIs,
     * or modify $targetBaseUri and $targetExt.
     *
     * @param string $id Chunk ID
     *
     * @return string Absolute URI to chunk
     *
     * @uses $targetBaseUri
     * @uses $targetExt
     */
    public function createTargetLink($id)
    {
        return $this->targetBaseUri. $id . $this->targetExt;
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

