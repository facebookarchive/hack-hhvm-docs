<?hh

require_once "File1.php";

function main_tap(): void {
  $p1 = create_point(3, 4);
  $p2 = create_point(5, 6);
  echo distance($p1, $p2);
}

main_tap();
