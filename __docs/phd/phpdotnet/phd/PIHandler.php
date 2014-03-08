<?php
namespace phpdotnet\phd;
/* $Id$ */

abstract class PIHandler {
    protected $format;

    public function __construct($format) {
        $this->format = $format;
    }

    public abstract function parse($target, $data);

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

