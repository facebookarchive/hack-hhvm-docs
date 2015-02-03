<?hh
function nullthrows<T>(?T $x, ?string $message = null): T {
  if ($x === null) {
    throw new Exception($message ?: 'Unexpected null');
  }

  return $x;
}
