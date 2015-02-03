<?hh

class Bar{}
class Foo<Tk, Tv> implements ArrayAccess<string, Bar> {
  public function offsetExists($offset): bool { return true;}
  public function offsetGet($offset) { return new Bar();}
  public function offsetSet($offset, $value): this { return $this;}
  public function offsetUnset($offset): this { return $this;}
}

function main(): void {
  $x = new Foo();
  $x->offsetSet("Hi", new Bar());
}
