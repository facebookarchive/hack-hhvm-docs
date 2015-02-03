<?hh

function main() {
  $p = Pair {7, 'a'};
  echo $p[0] . "\n";
  echo $p[1] . "\n";
  echo "\n";
  foreach ($p as $val) {
    echo $val . "\n";
  }
}

main();
