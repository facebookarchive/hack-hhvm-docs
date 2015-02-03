<?hh
function main() {
  $vec = Vector {11};
  $vec->addAll(Vector {22, 33, 44});
  var_dump($vec);
}

main();
