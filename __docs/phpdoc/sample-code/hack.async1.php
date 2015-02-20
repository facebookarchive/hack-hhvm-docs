<?hh
/*
   **********************************************************************
   ***** IN MANY CASES USING THIS WILL MAKE YOUR APPLICATION SLOWER *****
   **********************************************************************
   *
   * You should pretty much only use this if your multiget is blocking, slow,
   * and you get a _significant_ improvement from batching. MEASURE THE EFFECTS.
   *
   * For example:
   * - convertng blocking memcache gets to multigets would probably be a bad
   *   idea (consider using the MCRouter extension to make these async instead)
   * - converting async memcache gets to async multigets would be a _very_ bad
   *   idea.
   * - converting multiple 'WHERE x = %d' to 'WHERE x in (%d, %d, %d)' /may/ be
   *   a good idea depending on your load. Measure it and see.
   * - if you depend on an external API and have a rate limit, this can help you
   *   meet it
*/
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
