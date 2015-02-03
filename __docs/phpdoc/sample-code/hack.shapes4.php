<?hh

type Point2D = shape('x' => int, 'y' => int);

function dotProduct(Point2D $a, Point2D $b): int {
  var_dump($a);
  var_dump($b);
  return $a['x'] * $b['x'] + $a['y'] * $b['y'];
}

function main_sse(): void {
  echo dotProduct(shape('x' => 3, 'y' => 3), shape('x' => 4, 'y' => 4));
}

main_sse();
