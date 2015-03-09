<?hh

<<__Memoize>>
function mcrouterClient(): Awaitable<MCRouter> {
  return MCRouter::createSimple(array('127.0.0.1:11211'));
}

<<__Memoize>>
async function mysqlClient(): Awaitable<AsyncMysqlClient> {
  return await AsyncMysqlClient::connect('127.0.0.1', 3306);
}

async function getUser(int $id): Awaitable<Map<string,mixed>> {
  $mcclient = mcrouterClient();
  $mckey = 'USER:' . strval($id);

  // Get the cached value, if it exists
  try {
    $userData = await $mcclient->get($mckey);
    $user = json_decode($userData, true);
    if (is_array($user)) {
      return new Map($user);
    }
  } catch (MCRouterException $e) {
    // Catch the common case of not-found memcache key
    if ($e->getCode() !== MCRouter::mc_res_notfound) {
      throw $e;
    }
  }

  // Go to the Database otherwise
  $conn = await mysqlClient();
  $dbres = await $conn->queryf('SELECT * FROM USERS WHERE id = %d', $id);
  if ($dbres->numRows() != 1) {
    throw new Exception("No such user ".strval($id));
  }
  $user = $dbres->mapRowsTyped()[0];

  // Cache the data now that we have it
  $userData = json_encode($user->toArray());
  await $mcclient->set($mckey, $userData);

  return $user;
}

async function getFriends(Map<string,mixed> $user): Awaitable<Vector<Map<string,mixed>>> {
  $wh = Vector {};
  foreach ($user['friends'] as $friend) {
    $wh[] = getUser($friend);
  }
  return await HH\asio\v($wh);
}

async function getUserAndFriends(int $id): Awaitable<Vector<Map<string,mixed>>> {
  $user = await getUser($id);
  $ret = await getFriends($user);
  $ret->add($user);
  return $ret;
}

// Output user record for ID#4 and all his friends
var_dump(getUser(4)->join());
