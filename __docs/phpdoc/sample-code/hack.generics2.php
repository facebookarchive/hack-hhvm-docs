<?hh

function main_gen() {
  $gi = new Box(3);
  $gs = new Box("Hi");
  $ga = new Box(array());
  echo $gi->getData()."\n";
  echo $gs->getData()."\n";
  echo $ga->getData()."\n";
}

main_gen();
