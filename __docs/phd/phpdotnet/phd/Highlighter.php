<?php
/**
 * Source code highlighting class for phd.
 *
 * PHP version 5.3+
 *
 * @category PhD
 * @package  PhD
 * @author   Christian Weiske <cweiske@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD Style
 * @version  SVN: $Id$
 * @link     https://doc.php.net/phd/
 */
namespace phpdotnet\phd;

/**
 * Source code highlighting class for phd.
 *
 * @category PhD
 * @package  PhD
 * @author   Christian Weiske <cweiske@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD Style
 * @link     https://doc.php.net/phd/
 */
class Highlighter
{
    /**
    * Create a new highlighter instance for the given format.
    *
    * We use a factory so you can return different objects/classes
    * per format.
    *
    * @param string $format Output format (pdf, xhtml, troff, ...)
    *
    * @return PhDHighlighter Highlighter object
    */
    public static function factory($format)
    {
        return new self();
    }//public static function factory(..)



    /**
    * Highlight a given piece of source code.
    * Dead simple version that only works for xhtml+php. Returns text as
    *  it was in all other cases.
    *
    * @param string $text   Text to highlight
    * @param string $role   Source code role to use (php, xml, html, ...)
    * @param string $format Output format (pdf, xhtml, troff, ...)
    *
    * @return string Highlighted code
    */
    public function highlight($text, $role, $format)
    {
        if ($format == 'troff') {
            return "\n.PP\n.nf\n"
                . str_replace("\\", "\\\\", trim($text))
                . "\n.fi";
        } else if ($format != 'xhtml') {
            return $text;
        }

        if ($role == 'php') {
            return $this->highlight_string($text, 1);
        } else {
            return '<pre class="'. ($role ? $role . 'code' : 'programlisting') .'">'
                . htmlspecialchars($text, ENT_QUOTES, 'UTF-8')
                . "</pre>\n";
        }

        return $retval;
    }//public function highlight(..)

    private function highlight_file($filename, $return = false) {
      $data = file_get_contents($filename);
      if ($data === false) {
        return '';
      }

      return highlight_string($data, $return);
    }

    private function highlight_string($data, $return = false) {
      $colors = array(
        'html' => '#000000',
        'comment' => '#FF8000',
        'default' => '#0000BB',
        'string' => '#DD0000',
        'keyword' => '#007700',
      );

      $output = '';
      $last_color = $colors['html'];
      $next_color = null;

      $output .= '<code><span style="color: '.$last_color."\">\n";
      $hh_replace = false;
      if (strpos($data, "<?hh") !== false) {
        $data = str_replace("<?hh", "<?php", $data);
        $hh_replace = true;
      }
      foreach (token_get_all($data) as $token) {
        if (is_array($token)) {
          list($type, $string, $_) = $token;
          if ($type === T_WHITESPACE) {
            $output .= $this->__HPHP_highlight_html_puts($string);
            continue;
          }

          $next_color = $this->__HPHP_highlight_get_color($colors, $type);
        } else {
          $string = $token;
          $next_color = $colors['keyword'];
        }

        if ($last_color != $next_color) {
          if ($last_color != $colors['html']) {
            $output .= '</span>';
          }
          $last_color = $next_color;
          if ($last_color != $colors['html']) {
            $output .= '<span style="color: '.$last_color.'">';
          }
        }

        $output .= $this->__HPHP_highlight_html_puts($string);
      }

      if ($last_color != $colors['html']) {
        $output .= "</span>\n";
      }
      $output .= "</span>\n</code>";

      if ($hh_replace) {
        $output = str_replace("&lt;?php", "&lt;?hh", $output);
      }
      if ($return) {
        return $output;
      } else {
        echo $output;
      }
    }

    function __HPHP_highlight_get_color($colors, $type) {
      switch ($type) {
        case T_INLINE_HTML:
          return $colors['html'];

        case T_COMMENT:
        case T_DOC_COMMENT:
          return $colors['comment'];

        case T_OPEN_TAG:
        case T_OPEN_TAG_WITH_ECHO:
          return $colors['default'];

        case T_CLOSE_TAG:
          return $colors['default'];

        case T_ENCAPSED_AND_WHITESPACE:
        case T_CONSTANT_ENCAPSED_STRING:
          return $colors['string'];

        case T_STRING:
        case T_VARIABLE:
        case T_DNUMBER:
        case T_LNUMBER:
          return $colors['default'];

        default:
          return $colors['keyword'];
      }
    }

    private function __HPHP_highlight_html_puts($s) {
      $len = strlen($s);
      $out = '';
      for ($i = 0; $i < $len; $i++) {
        switch ($s[$i]) {
          case "\n":
            $out .= '<br />';
            break;
          case '<':
            $out .= '&lt;';
            break;
          case '>':
            $out .= '&gt;';
            break;
          case '&':
            $out .= '&amp;';
            break;
          case ' ':
            $out .= '&nbsp;';
            break;
          case "\t":
            $out .= '&nbsp;&nbsp;&nbsp;&nbsp;';
            break;
          default:
            $out .= $s[$i];
            break;
        }
      }
      return $out;
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/