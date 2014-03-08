<?php
namespace phpdotnet\phd;
/* $Id$ */

class PI_PHPDOCHandler extends PIHandler {
    protected $_changelogSince = null;

    public function __construct($format) {
        parent::__construct($format);
    }

    public function parse($target, $data) {
        $pattern = "/(?<attr>[\w]+[\w\-\.]*)[\s]*=[\s]*\"(?<value>[^\"]*)\"/";

        preg_match($pattern, $data, $matches);
        switch($matches["attr"]) {
            case "print-version-for":
                // FIXME: Figureout a way to detect the current language (for unknownversion)
                if (!$this->format instanceof Index) {
                    return $this->format->autogenVersionInfo($matches["value"], "en");
                }
                return;
            case "generate-index-for":
                if ($this->format instanceof Index) {
                    return;
                }
                switch($matches["value"]) {
                    case "function":
                    case "refentry":
                        $tmp = $this->format->getRefs();
                        $ret   = "";
                        $refs = array();
                        $info = array();
                        foreach($tmp as $id) {
                            $filename = $this->format->createLink($id, $desc);
                            $refs[$filename] = $desc;
                            $info[$filename] = array($this->format->getLongDescription($id, $islong), $islong);
                        }

                        natcasesort($refs);

                        // Nav bar to jump directly to a character
                        $chars = array_count_values(array_map(function($ref){ return strtolower($ref[0]); }, $refs));
                        $ret = '<p class="gen-index index-for-'.$matches["value"].'-toc">'."\n";
                        foreach ($chars as $char => $count) {
                            $ret .= '<a href="#'.$matches["value"].'-index-for-'.$char.'">'.$char.'</a>'."\n";
                        }
                        $ret .= "</p>\n";

                        // Workaround for 5.3 that doesn't allow func()[index]
                        $current = current($refs);
                        $char = $current[0];

                        $ret .= "<ul class='gen-index index-for-{$matches["value"]}'>";
                        $ret .= "<li class='gen-index index-for-{$char}'>$char<ul id='{$matches["value"]}-index-for-{$char}'>\n";
                        foreach($refs as $filename => $data) {
                            if ($data[0] != $char && strtolower($data[0]) != $char) {
                                $char = strtolower($data[0]);
                                $ret .= "</ul></li>\n";
                                $ret .= "<li class='gen-index index-for-{$char}'>$char<ul id='{$matches["value"]}-index-for-{$char}'>\n";
                            }
                            $longdesc = $info[$filename][1] ? " - {$info[$filename][0]}" : "";
                            $ret .= '<li><a href="'.$filename. '" class="index">' .$data. '</a>' . $longdesc . '</li>'."\n";
                        }
                        $ret .= "</ul></li></ul>\n\n";
                        return $ret;
                        break;

                    case "examples":
                        $ret = "<ul class='gen-index index-for-{$matches["value"]}'>";
                        foreach($this->format->getExamples() as $idx => $id) {
                            $link = $this->format->createLink($id, $desc);
                            $ret .= '<li><a href="'.$link.'" class="index">Example#' .$idx. ' - ' .$desc. '</a></li>'."\n";
                        }
                        $ret .= "</ul>";
                        return $ret;
                        break;
                    default:
                        trigger_error("Don't know how to handle {$matches["value"]} for {$matches["attr"]}", E_USER_WARNING);
                        break;
                }
                break;

            case "extension-membership":
                if ($this->format instanceof Index) {
                    return $this->format->setMembership($matches["value"]);
                }
                return;

            case "changelog-config-since":
                $this->_changelogSince = $matches["value"];
                break;

            case "generate-changelog-for-membership":
                if ($this->format instanceof Index) {
                    return;
                }

                $members = explode(" ", $matches["value"]);
                $changelogs = $this->format->getChangelogsForMembershipOf($members);
                return $this->generateChangelogMarkup($changelogs);
                break;

            case "generate-changelog-for":
                if ($this->format instanceof Index) {
                    return;
                }
                $parents = explode(" ", $matches["value"]);
                $changelogs = $this->format->getChangelogsForChildrenOf($parents);
                return $this->generateChangelogMarkup($changelogs);
                break;

            default:
                trigger_error("Don't know how to handle {$matches["attr"]}", E_USER_WARNING);
                break;
        }

    }

    // usort() callback function used in generate-changelog-for, higest (newest) version first
    // 1.2.11 comes before 1.2.2, then function name (actually its id.. but close enough :))
    protected static function _sortByVersion($a, $b) {
        $retval = -1 * strnatcasecmp($a["version"], $b["version"]);

        if ($retval === 0) {
            return strnatcasecmp($a["docbook_id"], $b["docbook_id"]);
        }
        return $retval;
    }

    protected function generateChangelogMarkup($changelogs) {

        usort($changelogs, array(__CLASS__, "_sortByVersion"));

        $ret = "<table class='doctable table' rules='groups'><thead><tr>";
        $ret .= "<th>" . $this->format->autogen("Version", "en") . "</th>";
        $ret .= "<th>" . $this->format->autogen("Function", "en") . "</th>";
        $ret .= "<th>" . $this->format->autogen("Description", "en") . "</th>";
        $ret .= "</tr></thead>";

        $version = "";
        foreach($changelogs as $entry) {
            if (!$this->_changelogSince || strnatcasecmp($entry["version"], $this->_changelogSince) >= 0) {
                $link = $this->format->createLink($entry["docbook_id"], $desc);
                if ($version == $entry["version"]) {
                    $v = "&nbsp;";
                }
                else {
                    if ($version) {
                        $ret .= "</tbody>";
                    }
                    $ret .= '<tbody class="gen-changelog v' . str_replace(".", "-", $version) . '">';
                    $version = $entry["version"];
                    $v = $version;
                }
                $ret .= sprintf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td></tr>", $v, $link, $desc, $entry["description"]);
            }
        }
        $this->_changelogSince= null;

        return $ret . "</tbody></table>";
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/


