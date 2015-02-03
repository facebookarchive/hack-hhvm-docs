<?hh // strict

function foo_strict(bool $b): int {
  if ($b) {
    return 4;
  } else {
    return bar_partial(3, 4);
  }
}
