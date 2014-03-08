<?php
namespace phpdotnet\phd;
/* $Id$ */

class TestBigXHTML extends Package_PHP_BigXHTML {
    public function update($event, $val = null) {
        switch($event) {
        case Render::CHUNK:
            parent::update($event, $val);
            break;
        case Render::STANDALONE:
            parent::update($event, $val);
            break;
        case Render::INIT:
            echo "Filename: " . $this->createFileName() . "\n";
            echo "Content:\n" . $this->header();
            break;
        //No verbose
        }
    }

    public function close() {
        echo $this->footer(true);
    }

    public function appendData($data) {
        if ($this->appendToBuffer) {
            $this->buffer .= $data;
            return;
        }
        if ($this->flags & Render::CLOSE) {
            echo $data;
            echo $this->footer();
            $this->flags ^= Render::CLOSE;
        } elseif ($this->flags & Render::OPEN) {
            echo $data."<hr />";
            $this->flags ^= Render::OPEN;
        } else {
            echo $data;
        }
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
