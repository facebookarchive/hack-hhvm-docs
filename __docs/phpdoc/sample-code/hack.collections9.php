<?hh
function foo(): void {
  $s = Set {2, 3, 4};
  $v = Set {2, 3, 5};
  $s->add(6);
  $z = $s->removeAll($v); //difference between $v and $s
  var_dump($s, $v, $z);
}

foo();
