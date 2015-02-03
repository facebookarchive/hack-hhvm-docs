<?hh

// Completely contrived

function f1((function(int, int): string) $x): string {
  return $x(2,3);
}

function f2(): string {
  $c = function(int $n, int $m): string {
    $r = '';
    for ($i=0; $i<$n+$m; $i++) {
      $r .= "hi";
    }
    return $r;
  };
  return f1($c);
}
