<?hh

// Definitions of class C and interface I
class C { ... }
interface I {
  public function foo();
}

// Trait T requires that any using class is a descendent of class C and implements
// interface I
trait T {
  require extends C;
  require implements I;
  // ... trait functionality that potentially relies on $this extending C and implementing I ...
  // ... this functionality can be safely type checked even if there are no classes that use T !
}
