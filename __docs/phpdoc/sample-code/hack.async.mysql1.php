<?hh

async function genMain() {
  $conn = await AsyncMysqlClient::connect(
    '127.0.0.1',
    3306,
    'demo',
    'demo',
    'demo',
  );
  $res = await $conn->queryf("SELECT %s, %s", 'foo', 'bar');
  var_dump($res->vectorRowsTyped());
  $res = await $conn->queryf("SELECT %s, %s", 'foo "bar"', "herp 'derp'");
  var_dump($res->vectorRowsTyped());
  $res = await $conn->queryf("SELECT * FROM %T WHERE foo = %s", 'junk', 'herp');
  var_dump($res->vectorRowsTyped());
}

genMain()->join();
