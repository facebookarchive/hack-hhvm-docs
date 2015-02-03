<?hh
trait T1 {
  <<__Override>> // checked on use classes
  function foo(): void {}
}

class C1 {
  use T1; // error! foo is not an override
}

class C2 {
  function foo(): void {}
}

class C3 extends C2 {
  use T1; // OK! C2's implementation is being overridden
}
