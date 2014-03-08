<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_PHP_HowTo extends Package_PHP_Web {
    private $nav = "";

    public function __construct() {
        parent::__construct();
        $this->registerFormatName("PHP-HowTo");
    }

    public function __destruct() {
        parent::__destruct();
    }

    public function update($event, $val = null) {
        switch($event) {
        case Render::CHUNK:
        case Render::STANDALONE:
        case Render::VERBOSE:
            parent::update($event, $val);
            break;
        case Render::INIT:
            $this->setOutputDir(Config::output_dir() . strtolower($this->getFormatName()) . '/');
            $this->postConstruct();
            if (file_exists($this->getOutputDir())) {
                if (!is_dir($this->getOutputDir())) {
                    v("Output directory is a file?", E_USER_ERROR);
                }
            } else {
                if (!mkdir($this->getOutputDir(), 0777, true)) {
                    v("Can't create output directory", E_USER_ERROR);
                }
            }
            if (Config::css()) {
                $this->fetchStylesheet();
            }
            break;
        }
    }

    public function header($id) {
        $title = Format::getShortDescription($id);
        $parent = Format::getParent($id);
        $next = $prev = $up = array(null, null);
        if ($parent && $parent != "ROOT") {
            $siblings = Format::getChildren($parent);
            if ($nextId = Format::getNext($id)) {
                $next = array(
                    Format::getFilename($nextId) . $this->getExt(),
                    Format::getShortDescription($nextId),
                );
            }
            if ($prevId = Format::getPrevious($id)) {
                $prev = array(
                    Format::getFilename($prevId) . $this->getExt(),
                    Format::getShortDescription($prevId),
                );
            }
            $up = array($parent . $this->getExt(), Format::getShortDescription($parent));
        }

        $this->nav = <<<NAV
<div style="text-align: center;">
 <div class="prev" style="text-align: left; float: left;"><a href="{$prev[0]}">{$prev[1]}</a></div>
 <div class="next" style="text-align: right; float: right;"><a href="{$next[0]}">{$next[1]}</a></div>
 <div class="up"><a href="{$up[0]}">{$up[1]}</a></div>
</div>
NAV;

        return "<?php include_once '../include/init.inc.php'; echo site_header('$title');?>\n" . $this->nav . "<hr />\n";
    }

    public function footer($id) {
        return "<hr />\n" . $this->nav . "<br />\n<?php echo site_footer(); ?>\n";
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

