<?hh

// If no key provided, it is an integer implicitly
function genWithoutKey(): Generator<int, string, void> {
  yield "hello";
  yield "world";
}

function genWithKey(): Generator<string, int, void> {
  yield "key0" => 0;
  yield "key1" => 1;
  yield "key2" => 2;
}

function genSend(): Generator<bool, int, string> {
  $string = yield true => 1;
  var_dump($string);
}

function main(): void {
  $gen = genSend(); // yields true => 1
  $gen->next();
  $gen->send("foo"); // sends "foo" $string inside of genSend() 
                     // and var_dump()s it
}
