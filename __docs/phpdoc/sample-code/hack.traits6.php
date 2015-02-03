<?hh

// Classes X1, X2, X3, and X4 all satisfy T's requirements:
class X1 extends C implements I {
  use T;
  public function foo() { .. } // I satisfied via inline implementation
}

trait U {
  public function foo() { .. }
}
class X2 extends C implements I {
  use T;
  use U; // I satisfied via another trait
}

interface J extends I {}
class X3 extends C implements J { // interface inheritance is fine
  use T;
  public function foo() { .. } // with an inline implementation in this case
}

class D extends C implements I {
  public function foo() { .. }
}
class X4 extends D { // number of inheritance hops wouldn't matter to instanceof ...
  use T; // ... so they don't matter to trait requirements
}

// Classes X5, X6, and X7 do not satisfy all of T's requirements
class X5 { // neither extends C nor implements I
  use T;
}

class X6 extends C { // doesn't implement I
  use T;
}

class X7 implements I { // doesn't extend C
  use T;
  public function foo() { .. }
}

// While class X8 provides a compatible implementation for all of interface I's methods,
// it is not considered to satisfy T's requirements because it does not formally implement
// interface I
class X8 extends C {
  use T;
  use U;
}

// Class X9 would technically satisfy T's requirements if it were a valid declaration
// ... but it's not! It will fail to load at run time because it doesn't provide an
// implementation for I::foo()
class X9 extends C implements I {
  use T;
}
