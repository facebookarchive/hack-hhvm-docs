<?hh

// A simplified serialization api:
type Serialized<T> = string;

class FooPhantom {}
function serialize_phantom<T>(T $t): Serialized<T> {
  return serialize($t);
}
function unserialize_phantom<T>(Serialized<T> $s): T {
  return unserialize($s);
}

// Using the api:
function main_phantom(): void {
  $x = new FooPhantom();
  var_dump($x);
  // $serialized is a Serialized<Foo>, aka "string"
  $serialized = serialize_phantom($x);
  var_dump($serialized);
  // we now know the type of $y must be Foo
  $y = unserialize_phantom($serialized);
  var_dump($y);
}

main_phantom();
