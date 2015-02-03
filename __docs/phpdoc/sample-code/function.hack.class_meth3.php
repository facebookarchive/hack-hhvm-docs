<?hh

class C {
  public static function isOdd(int $i): bool {
    return $i % 2 == 1;
  }
}

$v = Vector { 1, 2, 3 };
$v->filter(class_meth('C', 'isOdd')); // returns Vector { 1, 3 }
