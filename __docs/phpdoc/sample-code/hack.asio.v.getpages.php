<?hh

async function getPages(...$urls): Vector<string> {
  $waitHandles = Vector {};
  foreach ($urls as $url) {
    $waitHandles[] = HH\asio\curl_exec($url);
  }
  return await HH\asio\v($waitHandles);
}

$pages = getPages($url0, $url1, $url2)->join();
echo $pages[0], $pages[1], $pages[2];

