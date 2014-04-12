<?php
namespace phpdotnet\phd;
/* $Id$ */

class Render extends ObjectStorage
{
    const CHUNK        = 0x001;
    const OPEN         = 0x002;
    const CLOSE        = 0x004;
    const STANDALONE   = 0x008;
    const INIT         = 0x010;
    const FINALIZE     = 0x020;
    const VERBOSE      = 0x040;

    private   $STACK      = array();

    public function __construct() { /* {{{ */
    } /* }}} */

    public function notXPath($tag, $depth) { /* {{{ */
        do {
            if ((--$depth >= 0) && isset($tag[$this->STACK[$depth]])) {
                $tag = $tag[$this->STACK[$depth]];
            } else {
                $tag = $tag[0];
            }
        } while (is_array($tag));
        return $tag;
    } /* }}} */

    public function attach($obj, $inf = array()) { /* {{{ */
        if (!($obj instanceof Format)) {
            throw new \InvalidArgumentException(
                'All formats *MUST* inherit ' . __NAMESPACE__ . '\\Format'
            );
        }
        $obj->notify(Render::STANDALONE, true);

        return parent::attach($obj, $inf);
    } /* }}} */

    public function execute(Reader $r) { /* {{{ */
        ReaderKeeper::setReader($r);

        foreach($this as $format) {
            $format->notify(Render::INIT, true);
        }

        $lastdepth = -1;
        $in_method_synopsis = false;
        $is_method_return_type = false;
        $method_return_type_content = "";
        while($r->read()) {
            $type = $r->nodeType;
            $data = $retval = $name = $open = false;
            switch($type) {
                case \XMLReader::ELEMENT: /* {{{ */
                $open  = true;
                    /* break intentionally omitted */
                case \XMLReader::END_ELEMENT:
                $name  = $r->name;
                $depth = $r->depth;
                $attrs = array(
                    Reader::XMLNS_DOCBOOK => array(),
                    Reader::XMLNS_XML     => array(),
                );

                if ($r->hasAttributes) {
                    $r->moveToFirstAttribute();
                    do {
                        $attrs[$r->namespaceURI ?: Reader::XMLNS_DOCBOOK][$r->localName] = $r->value;
                    } while ($r->moveToNextAttribute());
                    $r->moveToElement();
                }

                $props    = array(
                    "empty"    => $r->isEmptyElement,
                    "isChunk"  => false,
                    "lang"     => $r->xmlLang,
                    "ns"       => $r->namespaceURI,
                    "sibling"  => $lastdepth >= $depth ? $this->STACK[$depth] : "",
                    "depth"    => $depth,
                );

                $this->STACK[$depth] = $name;

                if ($name == "notatag")
                    continue;

                if ($name === "methodsynopsis" && $open === true) {
                    $in_method_synopsis = true;
                }

                if ($name === "type" && $in_method_synopsis && $open == true) {
                    $is_method_return_type = true;
                }

                foreach($this as $format) {
                    $map = $this[$format][\XMLReader::ELEMENT];

                    if (isset($map[$name]) === false) {
                        $data = $format->UNDEF($open, $name, $attrs, $props);
                        $format->appendData($data);
                        continue;
                    }

                    $tag = $map[$name];
                    if (is_array($tag)) {
                        $tag = $this->notXPath($tag, $depth);
                    }

                    if ($tag === false) {
                        $data = $format->UNDEF($open, $name, $attrs, $props);
                        $format->appendData($data);
                        continue;
                    }

                    if (strncmp($tag, "format_", 7) !== 0) {
                        $data = $format->transformFromMap($open, $tag, $name, $attrs, $props);
                    } else {
                        $data = $format->{$tag}($open, $name, $attrs, $props);
                    }
                    if ($is_method_return_type) {
                        $method_return_type_content .= $data;
                    } else {
                        $format->appendData($data);
                    }
                }

                $lastdepth = $depth;
                break;
                    /* }}} */

                case \XMLReader::TEXT: /* {{{ */
                $value = $r->value;
                $eldepth = $r->depth - 1;
                $name  = $this->STACK[$eldepth];

                foreach($this as $format) {
                    $map = $this[$format][\XMLReader::TEXT];
                    if (isset($map[$name])) {
                        $tag = $map[$name];
                        if (is_array($tag)) {
                            $tag = $this->notXPath($tag, $eldepth);
                        }
                        if ($tag !== false) {
                            if ($is_method_return_type) {
                                // ":" is added in the
                                // format_type_if_object_or_pseudo_text
                                // method
                                $data = $format->{$tag}($value, $name, true);
                            } else {
                                $data = $format->{$tag}($value, $name);
                            }
                        } else {
                            if ($is_method_return_type) {
                                $data = ": " . $format->TEXT($value);
                            } else {
                                $data = $format->TEXT($value);
                            }
                        }
                    } else {
                        $data = $format->TEXT($value);
                    }
                    if ($data === false) {
                        if ($is_method_return_type) {
                            // Probably a primitive that doesn't have a special
                            // inner <span> tag. So add the : for return.
                            $method_return_type_content .= ": " . $value;
                        } else {
                            $format->appendData($value);
                        }
                    } else {
                        if ($is_method_return_type) {
                            // The ": " will have been added in the $data field
                            $method_return_type_content .= $data;
                        } else {
                            $format->appendData($data);
                        }
                    }
                }
                break;
                    /* }}} */

                case \XMLReader::CDATA: /* {{{ */
                            /* Different formats may want to escape the CDATA sections differently */
                $value  = $r->value;
                foreach($this as $format) {
                    $retval = $format->CDATA($value);
                    if ($is_method_return_type) {
                        $method_return_type_content .= $retval;
                    } else {
                        $format->appendData($retval);
                    }
                }
                break;
                    /* }}} */

                case \XMLReader::WHITESPACE: /* {{{ */
                case \XMLReader::SIGNIFICANT_WHITESPACE:
                            /* WS is always WS */
                $retval  = $r->value;
                foreach($this as $format) {
                    if ($is_method_return_type) {
                        $method_return_type_content .= $retval;
                    } else {
                        $format->appendData($retval);
                    }
                }
                break;
                    /* }}} */

                case \XMLReader::PI:
                $target = $r->name;
                $data = $r->value;
                foreach ($this as $format) {
                    $retval = $format->parsePI($target, $data);
                    if ($retval) {
                        if ($is_method_return_type) {
                            $method_return_type_content .= $retval;
                        } else {
                            $format->appendData($retval);
                        }
                    }
                }
                break;
            }

            if ($in_method_synopsis && $name === "type" && $type === \XMLReader::END_ELEMENT) {
                $is_method_return_type = false;
                // There may be other type elements we don't want to buffer
                $in_method_synopsis = false;
            }

            // Constructors use the same rendering as methods. Return type content will be empty, but we need
            // to capture the </div>
            if (($name === "methodsynopsis" || $name == "constructorsynopsis") && $type === \XMLReader::END_ELEMENT) {
                $format->appendData($method_return_type_content);
                $format->appendData("</div>\n");
                $method_return_type_content = "";
            }
        }

        /* Closing time */
        foreach($this as $format) {
            $format->notify(Render::FINALIZE, true);
        }
        $r->close();

        ReaderKeeper::popReader();

    } /* }}} */

}


/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

