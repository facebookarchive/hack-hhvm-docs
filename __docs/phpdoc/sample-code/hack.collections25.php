function main() {
  $map = Map {'a' => 11};
  $map->setAll(Map {'b' => 22, 'c' => 33});
  var_dump($map);
}

main();
