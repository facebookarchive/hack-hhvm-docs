<?php
namespace phpdotnet\phd;
/* $Id$ */

/**
 * Generates Atom feed of Table of Contents for
 * each chunk.
 *
 * @category PhD
 * @package  PhD_PHP
 * @author   Christian Weiske <cweiske@php.net>
 * @author   Moacir de Oliveira <moacir@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD Style
 * @link     https://doc.php.net/phd/
 */
class Package_PHP_TocFeed extends Package_Generic_TocFeed
{
    /**
     * Mapping of docbook tags to class methods.
     * php.net especific chunks.
     *
     * @var array
     */
    private $myelementmap = array(
        'phpdoc:classref'       => 'format_chunk',
        'phpdoc:exceptionref'   => 'format_chunk',
        'phpdoc:varentry'       => 'format_chunk',
        'section'               => array(
            /* DEFAULT */          false,
            'sect1'                => 'format_chunk',
            'chapter'              => 'format_chunk',
            'appendix'             => 'format_chunk',
            'article'              => 'format_chunk',
            'part'                 => 'format_chunk',
            'reference'            => 'format_chunk',
            'refentry'             => 'format_chunk',
            'index'                => 'format_chunk',
            'bibliography'         => 'format_chunk',
            'glossary'             => 'format_chunk',
            'colopone'             => 'format_chunk',
            'book'                 => 'format_chunk',
            'set'                  => 'format_chunk',
            'setindex'             => 'format_chunk',
            'legalnotice'          => 'format_chunk',
        ),
    );

    /**
     * Name of TOC feed format used by PhD internally.
     *
     * Inheriting classes should change this.
     *
     * @var string
     */
    protected $formatName = 'PHP-TocFeed';

    /**
     * File extension with leading dot for
     * links from atom feed to chunks.
     *
     * Inheriting classes should change this if neccessary.
     *
     * @var    string
     * @usedby createTargetLink()
     */
    protected $targetExt = '.php';

    /**
     * Base URI for links from atom feed to chunks.
     *
     * Inheriting classes should change this if neccessary.
     *
     * @var    string
     * @usedby createTargetLink()
     */
    protected $targetBaseUri = 'http://php.net/manual/{language}/';

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
    protected $feedBaseUri = 'http://php.net/manual/{language}/feeds/';

    /**
     * Author string used in atom feed files.
     *
     * Inheriting classes should change this.
     *
     * @var    string
     * @usedby header()
     */
    protected $author = 'PHP Documentation Group';

    /**
     * Prefix for atom entry id values.
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
    protected $idprefix = 'tag:php.net,2009-10-13:/manual/{language}/';

    /**
     * Overriding the update() method to extend the parent element map.
     * Only the Render::STANDALONE event is overrided.
     */
    public function update($event, $val = null) {
        switch($event) {
        case Render::STANDALONE:
            if ($val) {
                $this->registerElementMap(array_merge(
                    parent::getDefaultElementMap(),
                    $this->myelementmap));
            }
            break;
        default:
            parent::update($event, $val);
        }
    }

    /**
     * Create new instance.
     */
    public function __construct()
    {
        parent::__construct();

        $language = Config::language();
        $variables = array('targetBaseUri', 'feedBaseUri', 'idprefix');
        foreach ($variables as $varname) {
            $this->$varname = str_replace(
                '{language}', $language, $this->$varname
            );
        }
    }

}
