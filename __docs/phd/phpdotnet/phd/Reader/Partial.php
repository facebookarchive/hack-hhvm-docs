<?php
namespace phpdotnet\phd;
/*  $Id$ */

class Reader_Partial extends Reader
{
    protected $partial = array();
    protected $skip    = array();
    protected $parents = array();

    public function __construct() {
        parent::__construct();

        $render_ids = Config::render_ids();
        if ($render_ids !== NULL) {
            if (is_array($render_ids)) {
                $this->partial = $render_ids;
            } else {
                $this->partial[$render_ids] = 1;
            }
            $skip_ids = Config::skip_ids();
            if ($skip_ids !== NULL) {
                if (is_array($skip_ids)) {
                    $this->skip = $skip_ids;
                } else {
                    $this->skip[$skip_ids] = 1;
                }
            }
        } else {
            throw new \Exception("Didn't get any IDs to seek");
        }
        $parents = array();
        if (file_exists(Config::output_dir() . "index.sqlite")) {
            $sqlite = new \SQLite3(Config::output_dir() . "index.sqlite");

            // Fetch all ancestors of the ids we should render
            foreach($render_ids as $p => $v) {
                do {
                    $id = $sqlite->escapeString($p);
                    $row = $sqlite->query("SELECT parent_id FROM ids WHERE docbook_id = '$id'")->fetchArray(SQLITE3_ASSOC);
                    if ($row["parent_id"]) {
                        $parents[] = $p = $row["parent_id"];
                        continue;
                    }
                    break;
                } while(1);
            }
        }

        $this->parents = $parents;
    }

    public function read() {
        static $seeked = 0;
        static $currently_reading = false;
        static $currently_skipping = false;
        static $arrayPartial = array();
        static $arraySkip = array();
        $ignore = false;

        while($ret = parent::read()) {
            $id = $this->getAttributeNs("id", self::XMLNS_XML);
            $currentPartial = end($arrayPartial);
            $currentSkip = end($arraySkip);
            if (isset($this->partial[$id])) {
                if ($currentPartial == $id) {
                    v("%s done", $id, VERBOSE_PARTIAL_READING);

                    unset($this->partial[$id]);
                    --$seeked;
                    $currently_reading = false;
                    array_pop($arrayPartial);
                } else {
                    v("Starting %s...", $id, VERBOSE_PARTIAL_READING);

                    $currently_reading = $id;
                    ++$seeked;
                    $arrayPartial[] = $id;
                }
                return $ret;
            } elseif (isset($this->skip[$id])) {
                if ($currentSkip == $id) {
                    v("%s done", $id, VERBOSE_PARTIAL_READING);

                    unset($this->skip[$id]);
                    $currently_skipping = false;
                    $ignore = false;
                    array_pop($arraySkip);
                } else {
                    v("Skipping %s...", $id, VERBOSE_PARTIAL_READING);

                    $currently_skipping = $id;
                    $ignore = true;
                    $arraySkip[] = $id;
                }
            } elseif ($currently_skipping && $this->skip[$currently_skipping]) {
                if ($currentSkip == $id) {
                    v("Skipping child of %s, %s", $currently_reading, $id, VERBOSE_PARTIAL_CHILD_READING);
                } else {
                    v("%s done", $id, VERBOSE_PARTIAL_CHILD_READING);
                }

                $ignore = true;
            } elseif ($currently_reading && $this->partial[$currently_reading]) {
                if ($currentPartial == $id) {
                    v("Rendering child of %s, %s", $currently_reading, $id, VERBOSE_PARTIAL_CHILD_READING);
                } else {
                    v("%s done", $id, VERBOSE_PARTIAL_CHILD_READING);
                }

                return $ret;
            } elseif (empty($this->partial)) {
                return false;
            } else {
                // If we are used by the indexer then we have no clue about the
                // parents :)
                if ($id && $this->parents) {
                    // If this id isn't one of our ancestors we can jump
                    // completely over it
                    if (!in_array($id, $this->parents)) {
                        parent::next();
                    }
                }
                $ignore = true;
            }
        }
        return $ret;
    }
}

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/

