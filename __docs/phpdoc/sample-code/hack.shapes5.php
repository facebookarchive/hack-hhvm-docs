<?hh

type RandomData = shape('id' => string, 'url' => string, 'count' => int);

function foo_saf(RandomData $rd): RandomData {
  if ($rd['id'] === '573065673A34Z') {
    $rd['count']++;
  }
  else {
    $rd['url'] = "http://google.com";
    $rd['count']--;
  }
  return $rd;
}
