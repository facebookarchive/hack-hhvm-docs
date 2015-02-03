<?hh

class Foo{}
class Bar {
  public function getFoo(): Foo {
    return new Foo();
  }
}

async function gen_foo(int $a): Awaitable<?Foo> {
  if ($a === 0) {
    return null;
  }

  $bar = await gen_bar($a);
  if ($bar !== null) {
    return $bar->getFoo();
  }

  return null;
}

async function gen_bar(int $a): Awaitable<?Bar> {
  if ($a === 0) {
    return null;
  }

  return new Bar();
}


gen_foo(4);
