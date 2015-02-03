<?hh
// Copyright 2004-present Facebook. All Rights Reserved.

function main_indexish(): void {
  $arr1 = array(1, 2, 3, 4, 5);
  $arr2 = array(6, 7, 8, 9, 10);
  var_dump(array_compose($arr1, $arr2)); // original
  var_dump(array_compose($arr1, $arr2)); // modified
  var_dump(map_compose($arr1, $arr2));

  $map1 = Map {0 => 1, 1 => 2, 2 => 3, 3 => 4, 4 => 5};
  $map2 = Map {0 => 6, 1 => 7, 2 => 8, 3 => 9, 4 => 10};
  var_dump(array_compose($map1, $map2)); // original
  var_dump(array_compose($map1, $map2)); // modified
  var_dump(map_compose($map1, $map2));
}

main_indexish()
