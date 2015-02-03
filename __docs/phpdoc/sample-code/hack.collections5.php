<?hh

function main() {
  $v = Vector {40, 80, 20, 60};
  $filtered_vec = $v->filter(function($x) { return $x >= 50; });
  foreach ($filtered_vec as $key => $val) {
    echo $key . " " . $val . "\n";
  }
}

main();
