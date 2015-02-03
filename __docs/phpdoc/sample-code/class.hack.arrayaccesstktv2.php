<?hh

class Bar{}
class Foo<Tk, Tv> implements ArrayAccess<string, Bar> {
  public function offsetExists(Tk $offset): bool { return true;}
  public function offsetGet(Tk $offset): Tv { return new Bar();}
  public function offsetSet(Tk $offset, Tv $value): this { return $this;}
  public function offsetUnset(Tk $offset): this { return $this;}
}

function main(): void {
  $x = new Foo();
  $x->offsetSet("Hi", new Bar());
}
