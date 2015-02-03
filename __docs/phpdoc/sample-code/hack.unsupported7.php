<?php
class StaticFoo {
  public static function staticMethod() {
    return "Hello";
  }
}

class StaticBar extends StaticFoo {
  public function fooStatic() {
    return parent::staticMethod();
  }
}
