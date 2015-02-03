<?hh

class Batcher {
  private static ?Awaitable<array<int, int>> $pendingWH = null;
  private static array<int, int> $ids = array();

  public static async function fetch(int $id): Awaitable<int> {
    self::$ids[$id] = $id;
    if (self::$pendingWH === null) {
      self::$pendingWH = self::fetchDispatch();
    }
    $results = await self::$pendingWH;
    return $results[$id];
  }

  private static async function fetchDispatch(): Awaitable<array<int, int>> {
    await RescheduleWaitHandle::create(0, 0);
    $ids = self::$ids;
    self::$ids = array();
    self::$pendingWH = null;

    // do expensive serial multi-fetch
    echo "Fetch ".count($ids)." ids:\n";
    $results = array_map($id ==> $id * $id, $ids);
    var_dump($results);

    return $results;
  }
}

async function gen1(): Awaitable<void> {
  $r = await Batcher::fetch(1);
  echo "Got result for id 1: $r\n";
  $r = await Batcher::fetch(2);
  echo "Got result for id 2: $r\n";
}

async function gen2(): Awaitable<void> {
  $r = await Batcher::fetch(3);
  echo "Got result for id 3: $r\n";
}

async function run(): Awaitable<void> {
  await GenArrayWaitHandle::create(array(gen1(), gen2()));
}

function main(): void {
  run()->getWaitHandle()->join();
}

// HH_FIXME[1002] ... To ignore type checker warning for top-level statements
main();
