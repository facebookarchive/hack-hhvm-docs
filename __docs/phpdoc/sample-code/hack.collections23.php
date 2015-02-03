<?hh
// The Iterable objects returned by view() and
// items() are 'lazy' views of the original
// collection; this means that if a value in the
// underlying collection is changed, the lazy view
// will reflect this change. Also, certain kinds
// of modifications that invalidate iterators
// (such as removing an element) will also
// invalidate lazy views.
function main() {
  $m = Map {'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4};
  $iterable = $m->items();
  $m['a'] = 100;
  $i = 0;
  foreach ($iterable as $t) {
    echo $t[0] . " => " . $t[1] . "\n";
    if ($i == 2) {
      echo "Removing key 'a'\n";
      $m->remove('a');
    }
    ++$i;
  }
}
main();

