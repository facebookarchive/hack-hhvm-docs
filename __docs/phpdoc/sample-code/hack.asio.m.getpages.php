<?hh

async function getPages(): Map<string> {
  $waitHandles = Map {
    'example' => 'http://www.example.com',
    'hhvm' => 'http://www.hhvm.com',
    'docs' => 'http://docs.hhvm.com',
  };
  return await HH\asio\v($waitHandles);
}

$pages = getPages()->join();
echo $pages['example'];
echo $pages['hhvm'];
echo $pages['docs'];

