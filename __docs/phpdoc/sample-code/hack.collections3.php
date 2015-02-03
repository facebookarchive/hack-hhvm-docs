<?hh

function main_col() {

  $vector = Vector {5, 10};

  $vector->add(15);
  $vector->add(20);

  $vector[] = 25;

  $vector->removeKey(2);

  foreach ($vector as $item) {
    echo $item . "\n";
  }
}

main_col();
