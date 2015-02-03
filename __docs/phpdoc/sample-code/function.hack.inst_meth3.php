<?hh

class C {
  public function isOdd(int $i): bool {
    return $i % 2 == 1;
  }

  public function filter(Vector<int> $data): Vector<int> {
    $callback = inst_meth($this, 'isOdd');
    return $data->filter($callback);
  }
}

$c = new C();
$v = Vector { 1, 2, 3 };
$c->filter($v); // Returns Vector { 1, 3 }
