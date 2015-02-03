<?hh

function why_shapes(array $arr_as_struct): array {
  if ($arr_as_struct['id'] === '573065673A34Z') {
    $arr_as_struct['count']++;
  }
  else {
    $arr_as_struct['url'] = "http://google.com";
    $arr_as_struct['count']--;
  }
  return $arr_as_struct;
}

function main_why_shapes() {
  $my_struct = array('id' => null, 'url' => null, 'count' => 0);
  $my_struct['id'] = '573065673A34Y';
  $my_struct['url'] = 'http://facebook.com';

  var_dump($my_struct);
  $my_struct = why_shapes($my_struct);
  var_dump($my_struct);
}

main_why_shapes();
