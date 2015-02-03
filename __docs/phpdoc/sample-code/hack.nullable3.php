<?php
class Foo {
  protected $x = null;

  public function bar() {
    if ($this->x === null) {
      echo "Null 1";
    }

    if (is_null($this->x)) {
      echo "Null 2";
    }
  }
}
