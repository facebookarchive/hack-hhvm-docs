<?php
class Foo {
  public static function bar() {
    echo "Hello";
  }
}

// Instance level
$f = new Foo();
$f::bar();

// Class level
Foo::bar();
