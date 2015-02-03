<?hh
class FooG {}
class FooGChild extends FooG {}

class AAG {
  protected function bar(): Vector<FooG> {
    $x = new Vector();
    $x->add(new FooG());
    return $x;
  }
}

class BBG extends AAG {
  protected function bar(): Vector<FooGChild> {
    $x = new Vector();
    $x->add(new FooGChild());
    return $x;
  }
}
