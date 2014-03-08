<?php
namespace phpdotnet\phd;
/* $Id$ */

class PI_DBHTMLHandler extends PIHandler {
    private $attrs = array(
        "background-color"              => "",
        "bgcolor"                       => "",
        "cellpadding"                   => "",
        "cellspacing"                   => "",
        "class"                         => "",
        "dir"                           => "",
        "filename"                      => "",
        "funcsynopsis-style"            => "",
        "img.src.path"                  => "",
        "label-width"                   => "",
        "linenumbering.everyNth"        => "",
        "linenumbering.separator"       => "",
        "linenumbering.width"           => "",
        "list-presentation"             => "",
        "list-width"                    => "",
        "row-height"                    => "",
        "start"                         => "",
        "stop-chunking"                 => "",
        "table-summary"                 => "",
        "table-width"                   => "",
        "term-presentation"             => "",
        "term-separator"                => "",
        "term-width"                    => "",
        "toc"                           => "",

        //Attributes for <?dbtimestamp
        "format"                        => "",
        "padding"                       => "",
    );

    public function __construct($format) {
        parent::__construct($format);
    }

    public function parse($target, $data) {
        $pattern = "/(?<attr>[\w]+[\w\-\.]*)[\s]*=[\s]*\"(?<value>[^\"]*)\"/";
        preg_match_all($pattern, $data, $matches);
        for ($i = 0; $i < count($matches["attr"]); $i++) {
            $attr = trim($matches["attr"][$i]);
            $value = trim($matches["value"][$i]);
            $this->setAttribute($attr, $value);
        }
        //Hack for stop-chunking
        if ($data == "stop-chunking") {
            $this->setAttribute("stop-chunking", true);
        }
        //Parse <?dbtimestamp
        if ($target == 'dbtimestamp') {
            $this->parseDBTimestamp();
            $this->setAttribute("padding", "");
            $this->setAttribute("format", "");
        }
    }

    public function setAttribute($attr, $value) {
        if (isset($this->attrs[$attr])) {
            $this->attrs[$attr] = $value;
        }
    }

    public function getAttribute($attr) {
        return isset($this->attrs[$attr]) ? $this->attrs[$attr] : false;
    }

    /**
     * Function to parse dbtimestamp processing instructions
     * Reference: http://www.sagehill.net/docbookxsl/Datetime.html
     */
    public function parseDBTimestamp() {
        // Array to parse formats from dbtimestamp to date()
        $parseArray = array(
            "a"         => "D",         // Day abbreviation
            "A"         => "l",         // Day name
            "b"         => "M",         // Month abbreviation
            "c"         => "c",         // ISO date and time
            "B"         => "F",         // Month name
            "d"         => "d",         // Day in month
            "H"         => "H",         // Hour in day
            "j"         => "z",         // Day in year
            "m"         => "m",         // Month in year
            "M"         => "i",         // Minute in hour
            "S"         => "s",         // Second in minute
            "U"         => "W",         // Week in year
            "w"         => "w",         // Day in week
            "x"         => "Y-m-dP",    // ISO date
            "X"         => "H:i:sP",    // ISO time
            "Y"         => "Y",         // Year
        );
        if ($this->getAttribute("padding") == "0") {
            $parseArray["d"] = "j";
            $parseArray["H"] = "G";
            $parseArray["m"] = "n";
        }
        $format = $this->getAttribute("format");
        if (!$format) {
            return $this->format->appendData(date('c'));
        }
        $dateFormat = "";
        $len = strlen($format);
        for ($i = 0; $i < $len; $i++) {
            $dateFormat .= isset($parseArray[$format[$i]])
                           ? $parseArray[$format[$i]]
                           : $format[$i];
        }
        return $this->format->appendData(date($dateFormat));
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

