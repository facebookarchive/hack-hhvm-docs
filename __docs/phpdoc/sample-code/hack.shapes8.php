<?hh

type Point<T> = shape ('x' => T, 'y' => T);

function gen_shape_add<T>(Point<T> $pt1, Point<T> $pt2): Point<T> {
  $sumx = $pt1['x'] + $pt2['x'];
  $sumy = $pt1['y'] + $pt2['y'];
  return shape ('x' => $sumx, 'y' => $sumy);
}

function main_gs() {
  // Float based shape
  $pt1 = shape('x' => 1.0, 'y' => 2.0);
  $pt2 = shape('x' => 3.0, 'y' => 4.0);
  var_dump(gen_shape_add($pt1, $pt2));

  // Int based shape
  $pt3 = shape('x' => 1, 'y' => 2);
  $pt4 = shape('x' => 3, 'y' => 4);
  var_dump(gen_shape_add($pt3, $pt4));
}

main_gs();
