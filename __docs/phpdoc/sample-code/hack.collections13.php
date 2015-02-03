<?hh
class C {
  public static $bar = Map {'a' => 1, 'b' => 2};

  // Each instance of class C will get its own copy
  // of Vector {1, 2, 3} in property $foo
  public $foo = Vector {1, 2, 3};

  // Each invocation of h() with no parameters will
  // return a distinct empty Vector
  function h($x = Vector {}) {
    return $x;
  }

  function j() {
    static $y = Map {1 => 'a', 2 => 'b'};
    return $y;
  }
}
  