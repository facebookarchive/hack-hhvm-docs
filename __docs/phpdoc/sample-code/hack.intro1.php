<?php
/**
 * Wraps a name in a box
 *
 * @param raw-str $name
 * @return html-str
 */
function say_hello($name) {
  return "<div class=\"box\">Hello ".htmlize($name)."</div>";
}
