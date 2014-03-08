<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_IDE_PHP extends Package_IDE_Base {

    public function __construct() {
        $this->registerFormatName('IDE-PHP');
        $this->setExt(Config::ext() === null ? ".php" : Config::ext());
    }

    public function parseFunction() {
        $str = '<?php' . PHP_EOL;
        $str .= 'return ' . var_export($this->function, true) . ';' . PHP_EOL;
        $str .= '?>';
        return $str;
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
