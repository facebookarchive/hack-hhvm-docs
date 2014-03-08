<?php
namespace phpdotnet\phd;
/* $Id$ */

class TestRender {
    protected $format;

    public function __construct($formatclass, $opts, $extra = array()) {
        foreach ($opts as $k => $v) {
            $method = "set_$k";
            Config::$method($v);
        }
        if (count($extra) != 0) {
            Config::init($extra);
        }
        $classname = __NAMESPACE__ . "\\" . $formatclass;
        $this->format = new $classname();
    }

    public function run() {
        $reader = new Reader();
        $render = new Render();
        if (Index::requireIndexing()) {
           $format = $render->attach(new Index);
           $reader->open(Config::xml_file());
           $render->execute($reader);
           $render->detach($format);
        }
        $render->attach($this->format);
        $reader->open(Config::xml_file());
        $render->execute($reader);
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
