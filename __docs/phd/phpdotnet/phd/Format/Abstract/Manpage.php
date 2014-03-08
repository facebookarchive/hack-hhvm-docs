<?php
namespace phpdotnet\phd;
/* $Id$ */

abstract class Format_Abstract_Manpage extends Format {
    public $role = false;

    public function UNDEF($open, $name, $attrs, $props) {
        if ($open) {
            trigger_error("No mapper found for '{$name}'", E_USER_WARNING);
        }
        return "\n.B [NOT PROCESSED] $name [/NOT PROCESSED]";
    }

    public function CDATA($str) {
        return $this->highlight(trim($str), $this->role, 'troff');
    }

    public function TEXT($str) {
        $ret = preg_replace( '/[ \n\t]+/', ' ', $str);

        // Escape \ ' and NUL byte
        $ret = addcslashes($ret, "\\'\0");

        // No newline if current line begins with ',', ';', ':', '.'
        if (in_array($ret[0], array(",", ";", ":", "."))) {
            return $ret;
        }

        return $ret;
    }

    public function transformFromMap($open, $tag, $name, $attrs, $props) {
        if ($tag === '') {
            return $tag;
        }

        $isMacro = $tag[0] == ".";

        if ($open) {

            if ($isMacro && strpos($tag, "\n") === false) {
                return "\n" . $tag . "\n";
            }
            return "\n" . $tag;
        }

        return ($isMacro ? "" : "\\fP");
    }

    public function createLink($for, &$desc = null, $type = Format::SDESC) {
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

