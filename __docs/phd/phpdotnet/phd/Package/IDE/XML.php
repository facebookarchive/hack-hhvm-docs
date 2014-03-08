<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_IDE_XML extends Package_IDE_Base {

    public function __construct() {
        $this->registerFormatName('IDE-XML');
        $this->setExt(Config::ext() === null ? ".xml" : Config::ext());
    }

    public function parseFunction() {
        $str = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $str .= '<function>' . PHP_EOL;
        $str .= '<name>' . $this->function['name'] . '</name>' . PHP_EOL;
        $str .= '<purpose>' . $this->e($this->function['purpose']) . '</purpose>'  . PHP_EOL;
        $str .= '<manualid>' . $this->function['manualid'] . '</manualid>' . PHP_EOL;
        $str .= '<version>' . $this->e($this->function['version']) . '</version>'  . PHP_EOL;

        //Parameters
        $str .= '<params>' . PHP_EOL;
        foreach ((array)$this->function['params'] as $param) {
            $str .= '  <param>' . PHP_EOL;
            $str .= '    <name>' . $param['name'] . '</name>'  . PHP_EOL;
            $str .= '    <type>' . $param['type'] . '</type>'  . PHP_EOL;
            $str .= '    <optional>' . $param['optional'] . '</optional>'  . PHP_EOL;
            if (isset($param['initializer'])) {
                $str .= '    <initializer>' . $param['initializer'] . '</initializer>'  . PHP_EOL;
            }
            $str .= '    <description>' . (isset($param['description']) ? $this->cdata_str($param['description']) : '') . '</description>' . PHP_EOL;
            $str .= '  </param>' . PHP_EOL;
        }
        $str .= '</params>' . PHP_EOL;

        //Return
        $str .= '<return>' . PHP_EOL;
        $str .= '  <type>' . $this->function['return']['type'] . '</type>' . PHP_EOL;
        $str .= '  <description>' . $this->cdata_str($this->function['return']['description']) . '</description>' . PHP_EOL;
        $str .= '</return>' . PHP_EOL;

        //Errors
        $str .= '<errors>' . PHP_EOL;
        $str .= '  <description>' . $this->cdata_str($this->function['errors']) . '</description>' . PHP_EOL;
        $str .= '</errors>' . PHP_EOL;

        //Notes
        $str .= '<notes>' . PHP_EOL;
        foreach ((array)$this->function['notes'] as $note) {
            $str .= '  <note>' . PHP_EOL;
            $str .= '    <type>' . $note['type'] . '</type>'  . PHP_EOL;
            $str .= '    <description>' . $this->cdata_str($note['description']) . '</description>'  . PHP_EOL;
            $str .= '  </note>' . PHP_EOL;
        }
        $str .= '</notes>' . PHP_EOL;

        //Changelog
        $str .= '<changelog>' . PHP_EOL;
        foreach ((array)$this->function['changelog'] as $entry) {
            $str .= '  <entry>' . PHP_EOL;
            $str .= '    <version>' . $this->e($entry['version']) . '</version>'  . PHP_EOL;
            $str .= '    <change>' . $this->e($entry['change']) . '</change>'  . PHP_EOL;
            $str .= '  </entry>' . PHP_EOL;
        }
        $str .= '</changelog>' . PHP_EOL;

        //See also
        $str .= '<seealso>' . PHP_EOL;
        foreach ((array)$this->function['seealso'] as $entry) {
            $str .= '  <entry>' . PHP_EOL;
            $str .= '    <name>' . $this->e($entry['name']) . '</name>'  . PHP_EOL;
            $str .= '    <type>' . $entry['type'] . '</type>'  . PHP_EOL;
            $str .= '  </entry>' . PHP_EOL;
        }
        $str .= '</seealso>' . PHP_EOL;

        $str .= '</function>';
        return $str;
    }

    protected function e($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    protected function cdata_str($data) {
        return "<![CDATA[$data]]>";
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
