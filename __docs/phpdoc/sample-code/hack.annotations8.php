<?hh

// The mixed type should be used for function parameters where the behavior depends on the type.
// The code is forced to check the type of the parameter before using it
function encode(mixed $x): string {
  if (is_int($x)) {
    return "i:".($x + 1);
  } else if (is_string($x)) {
    return "s:".$x;
  } else {
    ...
  }
}
