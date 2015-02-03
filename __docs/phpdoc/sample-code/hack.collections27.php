<?hh
// Example showing add(), items(), and the general collection interfaces
function process_elements<T>(ConstCollection<T> $in): OutputCollection<T> {
  $out = Vector {};
  if (!($in instanceof ConstVector)) {
   return null;
  }
  foreach ($in->items() as $elm) {
    if ($elm > 1) {
      $out->add($elm);
    }
  }
  return $out;
}

function main(): void {
  $x = Vector {1, 2, 3};
  var_dump(process_elements($x));
}

main();
