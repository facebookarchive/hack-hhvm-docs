<?php
namespace phpdotnet\phd;
/* $Id$ */

/**
 * Renders the pear documentation visible on the PEAR website.
 *
 * @category PhD
 * @package  PhD_PEAR
 * @author   Christian Weiske <cweiske@php.net>
 * @author   Moacir de Oliveira Miranda Júnior <moacir@php.net>
 * @author   Rudy Nappée <loudi@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD Style
 * @link     https://doc.php.net/phd/
 */
class Package_PEAR_Web extends Package_PEAR_ChunkedXHTML
{
    public function __construct()
    {
        parent::__construct();
        $this->registerFormatName('PEAR-Web');
        $this->setExt(Config::ext() === null ? '.php' : Config::ext());
    }

    public function __destruct()
    {
        parent::close();
    }

    /**
     * Add the header to this file.
     *
     * @param string $id The id of this chunk
     *
     * @return string
     */
    public function header($id)
    {
        $ext = $this->ext;

        $parent = Format::getParent($id);

        //we link toc feeds if there are children
        if (count($this->getChildren($id)) > 0) {
            $extraHeader = <<<XML
 <link rel="alternate" type="application/atom+xml" title="Live Bookmarks" href="feeds/{$id}.atom" />

XML;
        } else {
            $extraHeader = '';
        }

        if (!$parent || $parent == 'ROOT') {
            return '<?php
sendManualHeaders("UTF-8","en");
setupNavigation(array(
  "home" => array("index.php", "'.addslashes($this->title).'"),
  "prev" => array("#", ""),
  "next" => array("#", ""),
  "up"   => array("#", ""),
  "toc"  => array(
    array("#", ""))));
manualHeader("index.php"'
    . ', ' . var_export($this->title, true)
    . ', ' . var_export($extraHeader, true)
    . ');
?>
';
        }

        $toc        = array();
        $siblingIDs = Format::getChildren($parent);
        foreach ($siblingIDs as $sid) {
            $sdesc = Format::getShortDescription($sid);
            $ldesc = Format::getLongDescription($sid);
            $toc[] = array(
                $sid . $ext,
                empty($sdesc) ? $ldesc : $sdesc
            );
        }

        $prev = $next = array(null, null);
        if ($prevID = Format::getPrevious($id)) {
            $prev = array(
                Format::getFilename($prevID).$ext,
                Format::getLongDescription($prevID),
            );
        }
        if ($nextID = Format::getNext($id)) {
            $next = array(
                Format::getFilename($nextID).$ext,
                Format::getLongDescription($nextID),
            );
        }
        // Build the PEAR navigation table
        $nav = array(
            'home' => array('index' . $ext, $this->title),
            'prev' => $prev,
            'next' => $next,
            'up'   => array(
                $this->getFilename($parent) . $ext,
                Format::getLongDescription($parent)
            ),
            'toc'  => $toc
        );
        return "<?php \n" .
            "sendManualHeaders(\"UTF-8\", \"{$this->lang}\");\n" .
            "setupNavigation(" . var_export($nav, true) . ");\n" .
            'manualHeader("'
                . $this->getFilename($id).$ext . '"'
                . ', ' . var_export(Format::getLongDescription($id), true)
                . ', ' . var_export($extraHeader, true)
            . ');' . "\n" .
            "?>\n";
    }

    /**
     * Create the footer for the given page id and return it.
     *
     * In this instance, we return raw php with the pearweb manual footer call.
     *
     * @param string $id Page ID
     *
     * @return string Footer code
     */
    public function footer($id)
    {
        $ext = $this->ext;
        $parent = Format::getParent($id);

        return '<?php manualFooter("'
            . $this->getFilename($id) . $ext . '", '
            . var_export(Format::getLongDescription($id), true)
            . '); ?>';
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

