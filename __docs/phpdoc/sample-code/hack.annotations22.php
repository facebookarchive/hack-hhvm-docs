<?hh

function swap(int &$x, int &$y): void {
  $x = $y;
  $y = 'boom';
}

function main(): void {
  $x = 1;
  $y = 1;

  swap($x, $y);

  var_dump($x);
  var_dump($y);

  swap($x, $y);
}

main();
