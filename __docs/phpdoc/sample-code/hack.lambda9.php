<?hh
$dump_map = ($name, $x) ==> {
  echo "Map $name has:\n";
  foreach ($x as $k => $v) {
    echo "  $k => $v\n";
  }
};
$dump_map(
  "My Map",
  Map {'a' => 'b', 'c' => 'd'},
);
