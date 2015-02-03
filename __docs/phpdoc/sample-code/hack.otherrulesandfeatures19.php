<?hh

function fun1(string $x): string {
  return $x.$x;
}

function fun2(): (function(string): string) {
  return fun('f1');
}

class FunFunFun {
  public function testFun1() {
    $x = fun('fun1');
    var_dump(call_user_func($x, "blah"));
  }

  public function testFun2() {
    $x = fun2();
    var_dump($x('blah'));
  }
}

function main_fff() {
  $f = new FunFunFun();
  $f->testFun1();
  $f->testFun2();
}

main_fff();
