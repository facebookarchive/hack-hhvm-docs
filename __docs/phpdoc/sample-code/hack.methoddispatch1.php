<?hh

class A {
  public function f1(): void {
    echo "A.f1()\n";
  }
  public static function f2(): void {
    echo "A.f2()\n";
  }
}

class B extends A {
  public function f1(): void {
    echo "B.f1()\n";
  }
  public static function f2(): void {
    echo "B.f2()\n";
  }
  public function test(): void {
    $a = new A();
    $c = new C();

    A::f1();
    $a->f1();
    $a::f1();
    A::f2();
    $a->f2();
    $a::f2();

    self::f1();
    B::f1();
    self::f2();
    B::f2();

    C::f3();
    $c->f3();
    $c::f3();
    C::f4();
    $c->f4();
    $c::f4();

    parent::f1();
    parent::f2();

    static::f1();
    static::f2();

    $this->f1();
    $this->f2();
  }
}

class C {
  public function f3(): void {
    echo "C.f3()\n";
  }
  public static function f4(): void {
    echo "C.f4()\n";
  }
}

function main() {
  $b = new B();
  $b->test();
}

main();
