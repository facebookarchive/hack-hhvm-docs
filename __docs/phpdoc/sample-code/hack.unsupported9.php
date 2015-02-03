<?hh // strict
class Foo {
  public static function bar() {
    echo "Hello";
  }
}

function main_csm() {
  // Instance level
  $f = new Foo();
  // Hack will balk here
  $f::bar();

  // Class level
  Foo::bar();
}

main_csm();
