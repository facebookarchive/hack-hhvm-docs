<?hh

class Bar{}
class Foo implements ArrayAccess<string, Bar> {
  public function offsetExists(string $offset): bool { return true;}
  public function offsetGet(string $offset): Bar { return new Bar();}
  public function offsetSet(string $offset, Bar $value): this { return $this;}
  public function offsetUnset(string $offset): this { return $this;}
}

function main(): void {
  $x = new Foo();
  $x->offsetSet("Hi", new Bar());
}
