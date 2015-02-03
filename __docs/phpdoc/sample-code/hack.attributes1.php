<?hh
class Foo {
  <<TestFunction, key("6")>>
  public function bar(string $key) {
    return baz($key);
  }
}

$rc = new ReflectionClass('Foo');
$attrs = $rc->getMethod('bar')->getAttributes();
var_dump($attrs);
$attr = $rc->getMethod('bar')->getAttribute("key");
var_dump($attr);
