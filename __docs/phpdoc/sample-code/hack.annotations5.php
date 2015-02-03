<?hh
function increment(int $x): int {
  return $x + 1;
}

function average(float $x, float $y): float {
  return ($x + $y) / 2;
}

function say_hello(string $name): string {
  return "Hello ".$name;
}

function invert(bool $b): bool {
  if ($b) {
    return false;
  } else {
    return true;
  }
}

function sort(array $arr): array {
  sort($arr);
  return $arr;
}

// A piece of code that computes the average of three numbers
function avg(int $n1, int $n2, int $n3): float {
  $s = $n1 + $n2 + $n3;
  return $s / 3.0;
}
