<?php
/**
 * Syntax highlighting using GeSHi, the generic syntax highlighter.
 *
 * PHP version 5.3+
 *
 * @category PhD
 * @package  PhD_GeSHi11x
 * @author   Christian Weiske <cweiske@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD Style
 * @version  SVN: $Id$
 * @link     https://doc.php.net/phd/
 */
namespace phpdotnet\phd;

/**
 * Yes, geshi needs to be in your include path.
 */
require 'geshi11x/class.geshi.php';

/**
 * Syntax highlighting using GeSHi, the generic syntax highlighter.
 *
 * This highlighter uses geshi 1.1.x, the unstable development
 * version as of 2009.
 * It will not work with geshi 1.0.x.
 *
 * @example
 * phd -g 'phpdotnet\phd\Highlighter_GeSHi11x' -L en -P PEAR -f xhtml -o build/en -d .manual.xml
 *
 * @category PhD
 * @package  PhD_GeSHi11x
 * @author   Christian Weiske <cweiske@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD Style
 * @link     https://doc.php.net/phd/
 */
class Highlighter_GeSHi11x extends Highlighter
{
    /**
    * Create a new highlighter instance for the given format.
    *
    * We use a factory so you can return different objects/classes
    * per format.
    *
    * @param string $format Output format (pdf, xhtml, troff, ...)
    *
    * @return PhDHighlighter Highlighter object
    */
    public static function factory($format)
    {
        if ($format != 'xhtml' && $format != 'troff') {
            return parent::factory($format);
        }

        if ($format == 'troff') {
            $rendererclass = 'GeSHiRendererTroff';
        } else {
            $rendererclass = 'GeSHiRendererHTML';
        }
        require_once GESHI_CLASSES_ROOT . 'class.geshirenderer.php';
        require_once GESHI_CLASSES_ROOT
            . 'renderers/class.' . strtolower($rendererclass) . '.php';
        $rendererclass = '\\' . $rendererclass;
        $renderer = new $rendererclass;


        return new self($renderer);
    }//public static function factory(..)



    /**
     * Create a new highlighter instance.
     *
     * @param \GeSHiRenderer $renderer Renderer instance
     */
    public function __construct(\GeSHiRenderer $renderer)
    {
        $this->geshi = new \GeSHi(null, null);
        $this->geshi->setRenderer($renderer);
        //FIXME: how to enable line numbers?
    }



    /**
    * Highlight a given piece of source code.
    *
    * @param string $text   Text to highlight
    * @param string $role   Source code role to use (php, xml, html, ...)
    * @param string $format Output format (pdf, xhtml, troff, ...)
    *
    * @return string Highlighted code
    */
    public function highlight($text, $role, $format)
    {
        $this->geshi->setSource($text);
        $lang = $this->getGeSHiLanguage($role);

        if ($lang === null) {
            //GeSHi does not support this language.
            // fall back to the default highlighter
            return parent::highlight($text, $role, $format);
        }

        //FIXME: perhaps it is more efficient to
        // keep a geshi instance for each single programming language.
        $this->geshi->setLanguage($lang);

        return $this->geshi->parseCode();
    }//public function highlight(..)



    /**
     * Returns the correct GeSHi language name for the given role.
     *
     * @internal
     * GeSHi currently triggers a fatal error when detecting a language
     * is not supported. This here is the only way to circumvent this.
     *
     * @param string $role   Source code role to use (php, xml, html, ...)
     *
     * @return string Language name for GeSHi or null if not found.
     */
    protected function getGeSHiLanguage($role)
    {
        static $supp;
        $supp = $this->geshi->languagesSupportedBy('default');

        if (isset($supp[$role])) {
            return $role;
        }

        //not supported
        if ($role == 'xml') {
            return 'html';
        }
        return null;
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

