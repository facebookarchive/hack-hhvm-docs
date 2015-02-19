<?hh

async function gen_something(int $id): Awaitable<bool> {
  static $cache = array();
  if (!array_key_exists($id, $cache)) {
    $fun = async () ==> {
      $step1 = await StepOne::gen($id);
      return await StepTwo::genReturnsBool($step1, $id);
    }
    $cache[$id] = $fun();
  }
  return await $cache[$id];
}
