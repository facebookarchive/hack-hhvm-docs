<?php
function foo() {
  $arr = array(1,2,3,4);
  foreach ($arr as &$k) {
    echo $k;
  }
  echo "\n";
  $k = 'foo';
  var_dump($arr);
}

foo();
