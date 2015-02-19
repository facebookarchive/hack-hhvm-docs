<?hh
async function (int $x, string $y): Awaitable<Foo> use ($z) {
  return await $x->genFoo($y, $z);
}
