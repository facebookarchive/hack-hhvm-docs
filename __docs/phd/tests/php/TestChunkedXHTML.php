<?php
namespace phpdotnet\phd;
/* $Id$ */

class TestChunkedXHTML extends Package_PHP_ChunkedXHTML {
    public function update($event, $val = null) {
        switch($event) {
        case Render::CHUNK:
            parent::update($event, $val);
            break;
        case Render::STANDALONE:
            parent::update($event, $val);
            break;
        case Render::INIT:
            $this->setOutputDir(Config::output_dir() . strtolower($this->getFormatName()) . '/');
            break;
        //No verbose
        }
    }

    public function writeChunk($id, $fp) {
        $filename = $this->getOutputDir() . $id . $this->getExt();

        rewind($fp);
        $content = "\n";
        $content .= stream_get_contents($fp);

        echo "Filename: " . basename($filename) . "\n";
        echo "Content:" . $content . "\n";
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
