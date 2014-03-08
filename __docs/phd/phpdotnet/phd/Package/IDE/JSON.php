<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_IDE_JSON extends Package_IDE_Base {

    public function __construct() {
        $this->registerFormatName('IDE-JSON');
        $this->setExt(Config::ext() === null ? ".json" : Config::ext());
    }

    public function parseFunction() {
        return json_encode($this->function);
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
