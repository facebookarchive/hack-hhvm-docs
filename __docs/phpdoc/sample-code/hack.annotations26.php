<?hh
async function f(): Awaitable<int> {
  return 42;
}

async function g(): Awaitable<string> {
  $f = await f();
  $f++;
  return 'hi test ' . $f;
}
