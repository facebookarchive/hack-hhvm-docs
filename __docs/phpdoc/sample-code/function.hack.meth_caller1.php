<?hh

class C {
  function f(): string {
    return 'hi test';
  }
}

function test(): void {
  $c = new C();

  // meth_caller will invoke method 'f' on objects of type 'C'.
  $f = meth_caller('C', 'f');
  echo $f($c); // Prints "hi test"
}
